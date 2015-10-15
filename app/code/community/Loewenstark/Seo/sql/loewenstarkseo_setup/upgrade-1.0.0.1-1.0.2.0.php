<?php
/**
  * Loewenstark_Seo
  *
  * @category  Loewenstark
  * @package   Loewenstark_Seo
  * @author    Mathis Klooss <m.klooss@loewenstark.com>
  * @copyright 2013 Loewenstark Web-Solution GmbH (http://www.loewenstark.de). All rights served.
  * @license   https://github.com/mklooss/Loewenstark_Seo/blob/master/README.md
  */
$installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');
/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer->startSetup();

$installer->addAttribute('catalog_category', Loewenstark_Seo_Helper_Data::ATTR_CODE_SEO_TEXT, array(
    'label'                      => 'SEO-Text',
    'group'                      => 'General Information',
    'type'                       => 'text',
    'input'                      => 'textarea',
    'sort_order'                 => 4,
    'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'required'                   => false,
    'user_defined'               => true,
    'visible_on_front'           => true,
    'wysiwyg_enabled'            => true,
    'is_html_allowed_on_front'   => true,
));

$installer->endSetup();
