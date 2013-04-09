<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * DependencyInjection namespace
 */
namespace Heystack\Subsystem\Ecommerce\DependencyInjection;

use Heystack\Subsystem\Core\Loader\DBClosureLoader;
use Heystack\Subsystem\Ecommerce\Config\ContainerConfig;
use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface;
use Heystack\Subsystem\Shipping\Types\CountryBased\Interfaces\CountryInterface;
use Heystack\Subsystem\Ecommerce\Services;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Dependency Injection Extension
 *
 * This class is responsible for loading this Subsystem's services.yml configuration
 * as well as overriding that configuration with the relevant entry from
 * the mysite/config/services.yml configuration file
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @author Cameron Spiers <cam@heyday.co.nz>
 * @package Ecommerce-Core
 */
class ContainerExtension extends Extension
{

    /**
     * Loads a specific configuration. Additionally calls processConfig, which handles overriding
     * the subsytem level configuration with more relevant mysite/config level configuration
     *
     * @param array            $configs    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        (new YamlFileLoader(
            $container,
            new FileLocator(ECOMMERCE_CORE_BASE_PATH . '/config/')
        ))->load('services.yml');

        $config = (new Processor())->processConfiguration(
            new ContainerConfig(),
            $configs
        );
        
        if (isset($config['yml_transaction']) && $container->hasDefinition('transaction_schema')) {
            $definition = $container->getDefinition('transaction_schema');
            $definition->replaceArgument(0, $config['yml_transaction']);
        }

        // Configure currencies from DB
        if (isset($config['currency_db'])) {
            $currencyQuery = new \SQLQuery(
                $config['currency_db']['select'],
                $config['currency_db']['from'],
                $config['currency_db']['where']
            );
            (new DBClosureLoader(
                function (CurrencyInterface $record) use ($container) {
                    $definition = new Definition(
                        'Heystack\\Subsystem\\Ecommerce\\Currency\\Currency',
                        array(
                            $identifier = $record->getIdentifier(),
                            $record->getValue(),
                            (boolean) $default = $record->isDefaultCurrency(),
                            $record->getSymbol()
                        )
                    );
                    $definition->addTag(Services::CURRENCY_SERVICE . '.currency');
                    $container->setDefinition(
                        "currency.$identifier",
                        $definition
                    );
                    if ($default) {
                        $definition->addTag(Services::CURRENCY_SERVICE . '.currency_default');
                    }
                }
            ))->load($currencyQuery);
        } elseif (isset($config['currency'])) {
            foreach ($config['currency'] as $currency) {
                $container->setDefinition(
                    "currency.{$currency['code']}",
                    $definition = new Definition(
                        'Heystack\\Subsystem\\Ecommerce\\Currency\\Currency',
                        array(
                            $currency['code'],
                            $currency['value'],
                            $currency['default'],
                            $currency['symbol']
                        )
                    )
                );
                $definition->addTag(Services::CURRENCY_SERVICE . '.currency');
                if ($currency['default']) {
                    $definition->addTag(Services::CURRENCY_SERVICE . '.currency_default');
                }
            }
        }

        // Configure locale from DB
        if (isset($config['locale_db'])) {
            $localeQuery = new \SQLQuery(
                $config['locale_db']['select'],
                $config['locale_db']['from'],
                $config['locale_db']['where']
            );
            (new DBClosureLoader(
                function (CountryInterface $record) use ($container) {
                    $definition = new Definition(
                        'Heystack\\Subsystem\\Ecommerce\\Locale\\Country',
                        array(
                            $identifier = $record->getCountryCode(),
                            $record->getName(),
                            (boolean) $default = $record->isDefault()
                        )
                    );
                    $definition->addTag(Services::LOCALE_SERVICE . '.locale');
                    $container->setDefinition(
                        "locale.$identifier",
                        $definition
                    );
                    if ($default) {
                        $definition->addTag(Services::LOCALE_SERVICE . '.locale_default');
                    }
                }
            ))->load($localeQuery);
        } elseif (isset($config['locale'])) {
            foreach ($config['locale'] as $locale) {
                $container->setDefinition(
                    "locale.{$locale['code']}",
                    $definition = new Definition(
                        'Heystack\\Subsystem\\Ecommerce\\Locale\\Country',
                        array(
                            $locale['code'],
                            $locale['name'],
                            $locale['default']
                        )
                    )
                );
                $definition->addTag(Services::LOCALE_SERVICE . '.locale');
                if ($locale['default']) {
                    $definition->addTag(Services::LOCALE_SERVICE . '.locale_default');
                }
            }
        }
    }

    /**
     * Returns the namespace to be used for this extension (XML namespace).
     *
     * @return string The XML namespace
     *
     * @api
     */
    public function getNamespace()
    {
        return 'ecommerce';
    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     *
     * @api
     */
    public function getXsdValidationBasePath()
    {
        return false;
    }

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     *
     * @api
     */
    public function getAlias()
    {
        return 'ecommerce';
    }

}
