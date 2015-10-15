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
    const ATTR_CODE_SEO_TEXT           = 'seo_text';

    const XML_PATH_DEFAULT_ROBOTS      = 'design/head/default_robots';
    const XML_PATH_CUSTOMER_ROBOTS     = 'customer/loewenstark_seo/robots';
    const XML_PATH_CONTACTS_ROBOTS     = 'contacts/contacts/robots';
    const XML_PATH_SITEMAP_ROBOTS      = 'catalog/sitemap/robots';
    const XML_PATH_CONTACTS_BREADCRUMB = 'contacts/contacts/breadcrumb';
    const XML_PATH_CHECKOUT_ROBOTS     = 'checkout/cart/robots';
    const XML_PATH_CATALOG_PHRASES_STATE = 'catalog/seo/disabled_phrases';

    /**
     * get Default Robots Tag from main configuration
     *
     * @return string
     */
    public function getDefaultRobots()
    {
        return Mage::getStoreConfig(self::XML_PATH_DEFAULT_ROBOTS);
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
     *
     * @return string
     */
    public function isPhraseEnabled()
    {
        return !Mage::getStoreConfigFlag(self::XML_PATH_CATALOG_PHRASES_STATE);
    }

    /**
     * @return array Category description phrases
     */
    public function getCategoryDescriptionPhrases()
    {
        $raw = Mage::getStoreConfig('catalog/seo/category_meta_description_phrases');
        $split = '--split--';

        //check if no line break is in $raw
        if ( !preg_match("/\\r\\n?|\\n/", $raw) ) return array_values(array_filter(array( $raw )));

        return array_values(array_filter(explode($split, str_replace(array("\r\n", "\n", "\r"), $split, $raw))));
    }

    /**
     * @return array Product description phrases
     */
    public function getProductDescriptionPhrases()
    {
        $raw = Mage::getStoreConfig('catalog/seo/product_meta_description_phrases');
        $split = '--split--';

        //check if no line break is in $raw
        if ( !preg_match("/\\r\\n?|\\n/", $raw) ) return array_values(array_filter(array( $raw )));

        return array_values(array_filter(explode($split, str_replace(array("\r\n", "\n", "\r"), $split, $raw))));
    }

    /**
     * @return array Category title phrases
     */
    public function getCategoryTitlePhrases()
    {
        $raw = Mage::getStoreConfig('catalog/seo/category_meta_title_phrases');
        $split = '--split--';

        //check if no line break is in $raw
        if ( !preg_match("/\\r\\n?|\\n/", $raw) ) return array_values(array_filter(array( $raw )));

        return array_values(array_filter(explode($split, str_replace(array("\r\n", "\n", "\r"), $split, $raw))));
    }

    /**
     * @return array Product title phrases
     */
    public function getProductTitlePhrases()
    {
        $raw = Mage::getStoreConfig('catalog/seo/product_meta_title_phrases');
        $split = '--split--';

        //check if no line break is in $raw
        if ( !preg_match("/\\r\\n?|\\n/", $raw) ) return array_values(array_filter(array( $raw )));

        return array_values(array_filter(explode($split, str_replace(array("\r\n", "\n", "\r"), $split, $raw))));
    }
}
