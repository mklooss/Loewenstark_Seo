<?php
/**
  * Loewenstark_Seo
  *
  * @category  Loewenstark
  * @package   Loewenstark_Seo
  * @author    Mathis Klooss <m.klooss@loewenstark.com>
  * @copyright 2013 Loewenstark Web-Solution GmbH (http://www.mage-profis.de/). All rights served.
  * @license   https://github.com/mklooss/Loewenstark_Seo/blob/master/README.md
  */
class Loewenstark_Seo_Model_System_Config_Source_Cms_Robots
extends Loewenstark_Seo_Model_System_Config_Source_Design_Robots
{
    public function toOptionArray()
    {
        $items = array(
            array(
                'value' => '',
                'label' => Mage::helper('loeseo')->__('Default'),
            )
        );
        return array_merge($items, parent::toOptionArray());
    }
}
