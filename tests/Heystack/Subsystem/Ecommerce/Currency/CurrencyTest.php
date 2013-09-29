<?php
namespace Heystack\Subsystem\Ecommerce\Currency;

use Heystack\Subsystem\Core\Identifier\Identifier;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-04-25 at 16:57:46.
 */
class CurrencyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Currency
     */
    protected $currency;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->currency = new Currency('NZD', 1, true);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->currency = null;
    }

    /**
     * @covers Heystack\Subsystem\Ecommerce\Currency\Currency::__construct
     * @covers Heystack\Subsystem\Ecommerce\Currency\Currency::getIdentifier
     */
    public function testGetIdentifier()
    {
        $identifier = $this->currency->getIdentifier();

        $this->assertTrue($identifier instanceof Identifier);

        $this->assertEquals('NZD', $identifier->getFull());
    }

    /**
     * @covers Heystack\Subsystem\Ecommerce\Currency\Currency::getCurrencyCode
     */
    public function testGetCurrencyCode()
    {
        $this->assertEquals('NZD', $this->currency->getCurrencyCode());
    }

    /**
     * @covers Heystack\Subsystem\Ecommerce\Currency\Currency::getSymbol
     */
    public function testGetSymbol()
    {
        $this->assertEquals('$', $this->currency->getSymbol());
    }

    /**
     * @covers Heystack\Subsystem\Ecommerce\Currency\Currency::isDefaultCurrency
     */
    public function testIsDefaultCurrency()
    {
        $this->assertTrue($this->currency->isDefaultCurrency());
    }

    /**
     * @covers Heystack\Subsystem\Ecommerce\Currency\Currency::getValue
     */
    public function testGetValue()
    {
        $this->assertEquals(1, $this->currency->getValue());
    }
}
