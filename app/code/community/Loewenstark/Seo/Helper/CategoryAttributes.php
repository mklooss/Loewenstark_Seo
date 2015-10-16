<?php
/**
  * Loewenstark_Seo
  *
  * @category  Loewenstark
  * @package   Loewenstark_Seo
  * @author    Mathis Klooss <m.klooss@loewenstark.com>
  * @copyright 2014 Loewenstark Web-Solution GmbH (http://www.mage-profis.de/). All rights served.
  * @license   https://github.com/mklooss/Loewenstark_Seo/blob/master/README.md
  */
class Loewenstark_Seo_Helper_CategoryAttributes
extends Mage_Core_Helper_Abstract
{
    
    protected $_categoryAttributes  = array();
    
    /**
     * 
     * @param string $attrcode
     * @return bool
     */
    public function checkIfAttributeExists( $attrcode )
    {
        $attrcodes = $this->getAttributeCodes();

        return in_array($attrcode, $attrcodes);
    }
    
    /**
     * 
     * @param string $attrcode
     * @return bool
     */
    public function getAttributeCodes()
    {
        $codes = array();
        
        if ( empty($this->_categoryAttributes) ) {
            $eavConfig = Mage::getModel('eav/config');

            $codes = $eavConfig->getEntityAttributeCodes(
                Mage_Catalog_Model_Category::ENTITY
            );
        }

        if ( is_array($codes) ) {
            $this->_categoryAttributes = $codes;
        }
        
        return $this->_categoryAttributes;
    }

}