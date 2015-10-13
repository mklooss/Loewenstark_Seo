<?php
/**
 * This is the controller for rendering the robots.txt file.
 *
 * @category  Loewenstark
 * @package   Loewenstark_Seo
 * @author    Volker Thiel <volker@mage-profis.de>
 */
class Loewenstark_Seo_IndexController extends Mage_Core_Controller_Front_Action
{
    private $_helper;

    /**
     * Render robots.txt
     *
     * This action pulls the relevant system config data and simply
     * echoes it to the browser.
     */
    public function indexAction()
    {
        Mage::app()->getResponse()->setHeader("Content-Type", $this->_helper()->getContentType(), true);
        echo $this->_helper()->getContent();
    }

    /**
     * Load helper
     *
     * @return Mage_Core_Helper_Abstract
     */
    protected function _helper($helper = 'loewenstark_seo/robotstxt')
    {
        if (is_null($this->_helper[$helper])) {
            $this->_helper[$helper] = Mage::helper($helper);
        }
        return $this->_helper[$helper];
    }
}
