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
class Loewenstark_Seo_Model_System_Config_Source_Design_Robots
{
    public function toOptionArray()
    {
        $items = array();
        foreach((array)Mage::app()->getConfig()->getNode('robots') as $_row)
        {
            $items[] = array(
                'value' => $_row,
                'label' => Mage::helper('loewenstark_seo')->__(str_replace(',', ', ', $_row))
            );
        }
        return $items;
    }
}
