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

$sql = $installer->getConnection()
        ->select()
        ->from($installer->getTable('core_resource'), 'version')
        ->where('code = ?', 'loeseo_setup')
        ->limit(1);
$item = $installer->getConnection()->fetchOne($sql);
if(!$item)
{
    $installer->getConnection()
            ->addColumn($installer->getTable('cms/page'), 'meta_robots', array(
            'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length'    => 32,
            'nullable'  => true,
            'comment'   => 'meta robots text'
        ));
}
$installer->endSetup();