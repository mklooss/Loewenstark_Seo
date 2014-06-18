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
class Loewenstark_Seo_Helper_Trailingslashes
extends Mage_Core_Helper_Abstract
{

    protected $_is_active = null;

    protected function _construct()
    {
        parent::_construct();
        $this->_is_active = (bool) intval(Mage::getConfig()->getModuleConfig('LimeSoda_TrailingSlashes')->is('active', 'true'));
    }

    /**
     * Returns whether the redirect is enabled for the category view page.
     *
     * @return bool
     */
    public function shouldRedirectCategoryView()
    {
        if (!$this->_is_active)
        {
            return false;
        }
        return Mage::helper('limesoda_trailingslashes')->shouldRedirectCategoryView();
    }

    /**
     * Returns whether the redirect is enabled for the product view page.
     *
     * @return bool
     */
    public function shouldRedirectProductView()
    {
        if (!$this->_is_active)
        {
            return false;
        }
        return Mage::helper('limesoda_trailingslashes')->shouldRedirectProductView();
    }

    /**
     * Returns whether the redirect is enabled for the category view page.
     *
     * @return bool
     */
    public function shouldRedirectCmsPageView()
    {
        if (!$this->_is_active)
        {
            return false;
        }
        return Mage::helper('limesoda_trailingslashes')->shouldRedirectCmsPageView();
    }

    /**
     * parse url
     *
     * @return string
     */
    public function parseUrl($url, $type)
    {
        if (!is_null($type))
        {
            switch ($type)
            {
                case 'catalog_product':
                    $url = $this->shouldRedirectProductView() ? trim($url, '/') : $url;
                break;
                case 'catalog_category':
                    $url = $this->shouldRedirectCategoryView() ? trim($url, '/') : $url;
                break;
                case 'cms':
                    $url = $this->shouldRedirectCmsPageView() ? trim($url, '/') : $url;
                break;
            }
        }
        return $url;
    }
}
