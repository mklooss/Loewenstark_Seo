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
    
    const XML_PATH_LOEWENSTARK_SEO_TEXT_ATTRIBUTES = 'catalog/seo/category_seo_text_attributes';
    const LOEWENSTARK_SEO_TEXT_CATEGORY_ATTRIBUTES_PREFIX = 'loe_seo_text_';
    
    protected $_categoryAttributes  = array();
    
    
    /**
     * 
     * @return array
     */
    public function getSeoTextAttributeCodes()
    {
        $raw = Mage::getStoreConfig(self::XML_PATH_LOEWENSTARK_SEO_TEXT_ATTRIBUTES);
        $updateconfig = false;
        
        if ($raw) {
            $arr = unserialize($raw);
            if (is_array($arr)) {
                //check for bad suffixes
                foreach($arr as $index=>$row) {
                    if ( $row['attrcode_suffix'] && $row['attrcode_name'] ){
                        $secure_attrcode_suffix = ereg_replace("[^A-Za-z0-9_]", "", $row['attrcode_suffix']);
                        $secure_attrcode_suffix = strtolower($secure_attrcode_suffix);
                        
                        if ( $row['attrcode_suffix']!==$secure_attrcode_suffix ) $updateconfig = true;
                        
                        $arr[ $index ]["attrcode_suffix"] = $secure_attrcode_suffix;
                    }
                }
                
                //save config
                if ($updateconfig){
                    $mageconfig = Mage::app()->getConfig();
                    $mageconfig->saveConfig('catalog/seo/category_seo_text_attributes', serialize($arr), 'default', 0);
                    
                    //refresh magento configuration cache
                    Mage::app()->getCacheInstance()->cleanType('config');
                }
                    
                return $arr;
            }
        }
        
        return false;
    }
    
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
     * @param class $setup
     * @param array $config
     * @return bool
     */
    public function addAttributesToCategory( $setup, $config )
    {
        $i = 0;
        foreach ($config as $index=>$config_row){
            $attrcode = self::LOEWENSTARK_SEO_TEXT_CATEGORY_ATTRIBUTES_PREFIX . $config_row['attrcode_suffix'];
            
            if ( !$this->checkIfAttributeExists($attrcode) ){
                $setup->addAttribute("catalog_category", $attrcode, array(
                    'label'                      => 'SEO Text "' . $config_row['attrcode_name'] . '"',
                    'group'                      => 'SEO',
                    'type'                       => 'text',
                    'input'                      => 'textarea',
                    'sort_order'                 => $i,
                    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required'                   => false,
                    'user_defined'               => true,
                    'visible_on_front'           => true,
                    'wysiwyg_enabled'            => true,
                    'is_html_allowed_on_front'   => true
                ));
                
                $i++;
            }
        }
    }
    
    /**
     * 
     * @param class $setup
     * @param array $config
     * @return bool
     */
    public function deleteAttributesFromCategory( $setup, $config )
    {
        //put suffixes in single array for in_array check
        $config_suffixes = array();
        foreach ( $config as $config_row ){
            $config_suffixes[] = $config_row['attrcode_suffix'];
        }
        
        //delete old attributes
        foreach ( $this->getAttributeCodes() as $attrcode ){
            $attrcode_suffix = substr($attrcode, 13);  //convert loe_seo_text_demo to demo
            
            if ( preg_match("/^".self::LOEWENSTARK_SEO_TEXT_CATEGORY_ATTRIBUTES_PREFIX."/", $attrcode) && !in_array($attrcode_suffix, $config_suffixes) ) {
                $setup->removeAttribute('catalog_category', $attrcode);
            }
        }
    }
    
    /**
     * 
     * @return array
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