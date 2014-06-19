<?php
/**
  * Loewenstark_Seo
  *
  * @category  Loewenstark
  * @package   Loewenstark_Seo
  * @author    Volker Thiel <volker.thiel@mage-profis.de>
  * @copyright 2014 Loewenstark Web-Solution GmbH (http://www.mage-profis.de/). All rights served.
  * @license   https://github.com/mklooss/Loewenstark_Seo/blob/master/README.md
  */
class Loewenstark_Seo_Model_System_Config_Source_Redirect
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => '',
                'label' => Mage::helper('loewenstark_seo')->__('Disabled'),
            ),
            array(
                'value' => 'product',
                'label' => Mage::helper('loewenstark_seo')->__('Products'),
            ),
            array(
                'value' => 'category',
                'label' => Mage::helper('loewenstark_seo')->__('Categories'),
            ),
            array(
                'value' => 'both',
                'label' => Mage::helper('loewenstark_seo')->__('Both'),
            ),
        );
    }
}
