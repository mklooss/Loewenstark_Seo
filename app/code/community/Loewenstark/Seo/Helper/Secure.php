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
class Loewenstark_Seo_Helper_Secure
extends Mage_Core_Helper_Abstract
{
    /**
     * Check whether URL for corresponding path should use https protocol
     * @see Mage_Core_Controller_Varien_Router_Standard
     * 
     * @param string $path
     * @return bool
     */
    public function shouldBeSecure($path)
    {
        return substr(Mage::getStoreConfig('web/unsecure/base_url'), 0, 5) === 'https'
            || Mage::getStoreConfigFlag('web/secure/use_in_frontend')
                && substr(Mage::getStoreConfig('web/secure/base_url'), 0, 5) == 'https'
                && Mage::getConfig()->shouldUrlBeSecure($path);
    }

    /**
     * @see Mage_Core_Controller_Varien_Router_Standard
     * @param Mage_Core_Controller_Request_Http $request
     * @return string
     */
    public function getCurrentSecureUrl($request)
    {
        if ($alias = $request->getAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS)) {
            return Mage::getBaseUrl('link', true).ltrim($alias, '/');
        }

        return Mage::getBaseUrl('link', true).ltrim($request->getPathInfo(), '/');
    }

    /**
     * 
     * @param Mage_Core_Controller_Request_Http $request
     * @return string
     */
    public function getCurrentUnSecureUrl($request)
    {
        if ($alias = $request->getAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS)) {
            return Mage::getBaseUrl('link', false).ltrim($alias, '/');
        }

        return Mage::getBaseUrl('link', false).ltrim($request->getPathInfo(), '/');
    }
}