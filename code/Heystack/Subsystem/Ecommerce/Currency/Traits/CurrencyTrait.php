<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Traits namespace
 */
namespace Heystack\Subsystem\Ecommerce\Currency\Traits;

use Heystack\Subsystem\Core\GenerateContainerDataObjectTrait;

/**
 * Provides a basic implementation of the CurrencyInterface for dataobjects
 *
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Ecommerce-Core
 */
trait CurrencyTrait
{
    use GenerateContainerDataObjectTrait;

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->getCurrencyCode();
    }
    /**
     * @return mixed
     */
    public function getCurrencyCode()
    {
        return $this->getField('CurrencyCode');
    }
    /**
     * @return mixed
     */
    public function getSymbol()
    {
        return $this->getField('Symbol');
    }
    /**
     * @return mixed
     */
    public function isDefaultCurrency()
    {
        return $this->getField('IsDefaultCurrency');
    }
    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->getField('Value');
    }
}
