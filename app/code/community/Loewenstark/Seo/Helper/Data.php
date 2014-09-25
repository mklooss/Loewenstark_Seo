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
class Loewenstark_Seo_Helper_Data
extends Mage_Core_Helper_Abstract
{

    const XML_PATH_DEFAULT_ROBOTS      = 'design/head/default_robots';
    const XML_PATH_CUSTOMER_ROBOTS     = 'customer/loewenstark_seo/robots';
    const XML_PATH_CONTACTS_ROBOTS     = 'contacts/contacts/robots';
    const XML_PATH_SITEMAP_ROBOTS      = 'catalog/sitemap/robots';
    const XML_PATH_CONTACTS_BREADCRUMB = 'contacts/contacts/breadcrumb';
    const XML_PATH_CHECKOUT_ROBOTS     = 'checkout/cart/robots';

    /**
     * get Default Robots Tag from main configuration
     *
     * @return string
     */
    public function getDefaultRobots()
    {
        return Mage::getStoreConfig(self::XML_PATH_DEFAULT_ROBOTS);;
    }

    /**
     *
     * @return string
     */
    public function getCustomerRobots()
    {
        return Mage::getStoreConfig(self::XML_PATH_CUSTOMER_ROBOTS);
    }

    /**
     *
     * @return string
     */
    public function getContactsRobots()
    {
        return Mage::getStoreConfig(self::XML_PATH_CONTACTS_ROBOTS);
    }

    /**
     *
     * @return boolean
     */
    public function getContactsBreadcrumb()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_CONTACTS_BREADCRUMB);
    }

    /**
     * Robots in catalog/seo_sitemap
     * @return string
     */
    public function getSitemapRobots()
    {
        return Mage::getStoreConfig(self::XML_PATH_SITEMAP_ROBOTS);
    }

    /**
     *
     * @return string
     */
    public function getCheckoutRobots()
    {
        return Mage::getStoreConfig(self::XML_PATH_CHECKOUT_ROBOTS);
    }

    /**
     * @return array Category phrases
     */
    public function getCategoryPhrases()
    {
        $raw = Mage::getStoreConfig('catalog/seo/category_meta_description_phrases');
        $split = '--split--';
        return array_values(array_filter(explode($split, str_replace(array("\r\n", "\n", "\r"), $split, $raw))));
    }

    /**
     * @return array Product phrases
     */
    public function getProductPhrases()
    {
        $raw = Mage::getStoreConfig('catalog/seo/product_meta_description_phrases');
        $split = '--split--';
        return array_values(array_filter(explode($split, str_replace(array("\r\n", "\n", "\r"), $split, $raw))));
    }
}
