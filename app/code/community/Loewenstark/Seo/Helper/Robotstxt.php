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
class Loewenstark_Seo_Helper_Robotstxt extends Mage_Core_Helper_Abstract
{
    const XML_PATH_LOEWENSTARK_SEO_ROBOTSTXT = 'catalog/seo/robotstxt';

    /**
     * Get content type for robots.txt
     *
     * @return string
     */
    public function getContentType()
    {
        return "text/plain; charset=UTF-8";
    }

    /**
     *
     * Get content for robots.txt file
     *
     * @return string
     */
    public function getContent()
    {
        $content = Mage::getStoreConfig(self::XML_PATH_LOEWENSTARK_SEO_ROBOTSTXT);
        $processor = Mage::getModel('cms/template_filter');
        return $processor->filter($content);
    }
}
