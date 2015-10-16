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
    const XML_PATH_CATALOG_PHRASES_ENABLED = 'catalog/seo/phrases_enabled';

    protected $_categoryTitlePhrases       = array();
    protected $_categoryDescriptionPhrases = array();
    protected $_productTitlePhrases        = array();
    protected $_productDescriptionPhrases  = array();

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
        return (bool) Mage::getStoreConfigFlag(self::XML_PATH_CATALOG_PHRASES_ENABLED);
    }

    /**
     * Get all category description phrases defined in system config
     *
     * @return array Category description phrases
     */
    public function getCategoryDescriptionPhrases()
    {
        if ($this->_categoryDescriptionPhrases) {
            return $this->_categoryDescriptionPhrases;
        }
        $raw = Mage::getStoreConfig('catalog/seo/category_meta_description_phrases');

        $this->_categoryDescriptionPhrases = array_filter(preg_split ('/\R/', $raw));

        return $this->_categoryDescriptionPhrases;
    }

    /**
     * Get all product description phrases defined in system config
     *
     * @return array Product description phrases
     */
    public function getProductDescriptionPhrases()
    {
        if ($this->_productDescriptionPhrases) {
            return $this->_productDescriptionPhrases;
        }
        $raw = Mage::getStoreConfig('catalog/seo/product_meta_description_phrases');

        $this->_productDescriptionPhrases = array_filter(preg_split ('/\R/', $raw));

        return $this->_productDescriptionPhrases;
    }

    /**
     * Get all category title phrases defined in system config
     *
     * @return array Category title phrases
     */
    public function getCategoryTitlePhrases()
    {
        if ($this->_categoryTitlePhrases) {
            return $this->_categoryTitlePhrases;
        }
        $raw = Mage::getStoreConfig('catalog/seo/category_meta_title_phrases');

        $this->_categoryTitlePhrases = array_filter(preg_split ('/\R/', $raw));

        return $this->_categoryTitlePhrases;
    }

    /**
     * Get all product title phrases defined in system config
     *
     * @return array Product title phrases
     */
    public function getProductTitlePhrases()
    {
        if ($this->_productTitlePhrases) {
            return $this->_productTitlePhrases;
        }
        $raw = Mage::getStoreConfig('catalog/seo/product_meta_title_phrases');

        $this->_productTitlePhrases = array_filter(preg_split ('/\R/', $raw));

        return $this->_productTitlePhrases;
    }
}
