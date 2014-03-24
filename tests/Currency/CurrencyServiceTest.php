<?php
namespace Heystack\Ecommerce\Currency;

use Heystack\Core\Identifier\Identifier;
use Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface;
use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\NZD;
use SebastianBergmann\Money\AUD;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-04-25 at 16:57:48.
 */
class CurrencyServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CurrencyService
     */
    protected $currencyService;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $nzCurrencyIdentifier = $this->getMockBuilder('Heystack\Core\Identifier\Identifier')
            ->disableOriginalConstructor()
            ->getMock();
        $nzCurrencyIdentifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('NZD'));

        $nzCurrency = $this->getMock('Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface');
        $nzCurrency->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($nzCurrencyIdentifier));
        $nzCurrency->expects($this->any())
            ->method('getCurrencyCode')
            ->will($this->returnValue('NZD'));
        $nzCurrency->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(1));
        $nzCurrency->expects($this->any())
            ->method('isDefaultCurrency')
            ->will($this->returnValue(true));


        $ozCurrencyIdentifier = $this->getMockBuilder('Heystack\Core\Identifier\Identifier')
            ->disableOriginalConstructor()
            ->getMock();
        $ozCurrencyIdentifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('AUD'));

        $ozCurrency = $this->getMock('Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface');
        $ozCurrency->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue($ozCurrencyIdentifier));
        $ozCurrency->expects($this->any())
            ->method('getCurrencyCode')
            ->will($this->returnValue('AUD'));
        $ozCurrency->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(2));


        $stateService = $this->getMockBuilder('Heystack\Core\State\State')
            ->disableOriginalConstructor()
            ->getMock();

        $eventDispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->currencyService = new CurrencyService([$nzCurrency, $ozCurrency], $nzCurrency, $stateService, $eventDispatcher);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->currencyService = null;
    }

    /**
     * @covers Heystack\Ecommerce\Currency\CurrencyService::__construct
     * @covers Heystack\Ecommerce\Currency\CurrencyService::setActiveCurrency
     */
    public function testSetActiveCurrency()
    {
        $ozCurrencyIdentifier = $this->getMockBuilder('Heystack\Core\Identifier\Identifier')
            ->disableOriginalConstructor()
            ->getMock();
        $ozCurrencyIdentifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('AUD'));

        $this->currencyService->setActiveCurrency($ozCurrencyIdentifier);

        $this->assertEquals('AUD', $this->currencyService->getActiveCurrencyCode());
    }

    /**
     * @covers Heystack\Ecommerce\Currency\CurrencyService::__construct
     * @covers Heystack\Ecommerce\Currency\CurrencyService::getActiveCurrency
     */
    public function testGetActiveCurrency()
    {
        $this->assertTrue($this->currencyService->getActiveCurrency() instanceof CurrencyInterface);
    }

    /**
     * @covers Heystack\Ecommerce\Currency\CurrencyService::__construct
     * @covers Heystack\Ecommerce\Currency\CurrencyService::getActiveCurrencyCode
     */
    public function testGetActiveCurrencyCode()
    {
        $this->assertEquals('NZD', $this->currencyService->getActiveCurrencyCode());
    }

    /**
     * @covers Heystack\Ecommerce\Currency\CurrencyService::__construct
     * @covers Heystack\Ecommerce\Currency\CurrencyService::addCurrency
     * @covers Heystack\Ecommerce\Currency\CurrencyService::setCurrencies
     * @covers Heystack\Ecommerce\Currency\CurrencyService::getCurrencies
     */
    public function testGetCurrencies()
    {
        $currencies = $this->currencyService->getCurrencies();

        $this->assertCount(2, $currencies);

        $this->assertContainsOnlyInstancesOf(
            'Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface',
            $currencies
        );
    }

    /**
     * @covers Heystack\Ecommerce\Currency\CurrencyService::__construct
     * @covers Heystack\Ecommerce\Currency\CurrencyService::convert
     */
    public function testConvert()
    {
        $this->assertEquals(
            new Money(200, $this->currencyService->getCurrency(new Identifier('AUD'))),
            $this->currencyService->convert(
                new Money(100, $this->currencyService->getCurrency(new Identifier('NZD'))),
                new Identifier('AUD')
            )
        );
        $this->assertEquals(
            new Money(50, $this->currencyService->getCurrency(new Identifier('NZD'))),
            $this->currencyService->convert(
                new Money(100, $this->currencyService->getCurrency(new Identifier('AUD'))),
                new Identifier('NZD')
            )
        );
    }

    /**
     * @covers Heystack\Ecommerce\Currency\CurrencyService::__construct
     * @covers Heystack\Ecommerce\Currency\CurrencyService::getCurrency
     */
    public function testGetCurrency()
    {
        $nzCurrencyIdentifier = $this->getMockBuilder('Heystack\Core\Identifier\Identifier')
            ->disableOriginalConstructor()
            ->getMock();
        $nzCurrencyIdentifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('NZD'));

        $nzCurrency = $this->currencyService->getCurrency($nzCurrencyIdentifier);

        $this->assertTrue($nzCurrency instanceof CurrencyInterface);

        $this->assertEquals('NZD', $nzCurrency->getCurrencyCode());
    }

    /**
     * @covers Heystack\Ecommerce\Currency\CurrencyService::__construct
     * @covers Heystack\Ecommerce\Currency\CurrencyService::getDefaultCurrency
     */
    public function testGetDefaultCurrency()
    {
        $nzCurrency = $this->currencyService->getDefaultCurrency();

        $this->assertTrue($nzCurrency instanceof CurrencyInterface);

        $this->assertEquals('NZD', $nzCurrency->getCurrencyCode());
    }

    /**
     * @covers Heystack\Ecommerce\Currency\CurrencyService::__construct
     * @covers Heystack\Ecommerce\Currency\CurrencyService::setDefaultCurrency
     */
    public function testSetDefaultCurrency()
    {
        $ozCurrencyIdentifier = $this->getMockBuilder('Heystack\Core\Identifier\Identifier')
            ->disableOriginalConstructor()
            ->getMock();
        $ozCurrencyIdentifier->expects($this->any())
            ->method('getFull')
            ->will($this->returnValue('AUD'));

        $this->currencyService->setDefaultCurrency($ozCurrencyIdentifier);

        $currency = $this->currencyService->getDefaultCurrency();

        $this->assertTrue($currency instanceof CurrencyInterface);

        $this->assertEquals('AUD', $currency->getCurrencyCode());

    }
}