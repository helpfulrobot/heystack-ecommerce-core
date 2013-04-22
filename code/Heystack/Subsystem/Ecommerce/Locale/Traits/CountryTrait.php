<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Traits namespace
 */
namespace Heystack\Subsystem\Ecommerce\Locale\Traits;

use Heystack\Subsystem\Core\GenerateContainerDataObjectTrait;
use Heystack\Subsystem\Core\Identifier\Identifier;

/**
 * Provides a basic implementation of the CountryInterface for dataobjects
 *
 * @copyright  Heyday
 * @author     Glenn Bautista <glenn@heyday.co.nz>
 * @author     Cam Spiers <cameron@heyday.co.nz>
 * @package    Ecommerce-Core
 */
trait CountryTrait
{
    use GenerateContainerDataObjectTrait;
    /**
     * @return \Heystack\Subsystem\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier($this->CountryCode);
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->getField('Name');
    }
    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->getField('CountryCode');
    }
    /**
     * @return mixed
     */
    public function isDefault()
    {
        return $this->getField('IsDefault');
    }
}
