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
$installer = $this;
/* @var $installer Loewenstark_Seo_Model_Resource_Setup */
$installer->startSetup();

$where = $installer->getConnection()
        ->quoteInto('path = ?', 'catalog/seo/category_canonical_tag');
$installer->getConnection()
        ->update($installer->getTable('core/config_data'), array(
            'path' => 'catalog/seo/category_canonical_tag_seo'
        ), $where);
$installer->setConfigData('catalog/seo/category_canonical_tag', 0);

$installer->endSetup();