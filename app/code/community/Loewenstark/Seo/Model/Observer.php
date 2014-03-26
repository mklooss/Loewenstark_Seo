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
class Loewenstark_Seo_Model_Observer
{

    /**
     * event: adminhtml_cms_page_edit_tab_meta_prepare_form
     * in: Mage_Adminhtml_Block_Cms_Page_Edit_Tab_Meta::_prepareForm()
     * 
     * @param $event Varien_Event_Observer
     * @return void
     */
    public function addFieldsToCmsMetaTagForm(Varien_Event_Observer $event)
    {
        $isElementDisabled = Mage::getSingleton('admin/session')->isAllowed('cms/page/save') ? false : true;
        
        $form = $event->getForm();
        /* @var $form Varien_Data_Form */
        $fieldset = $form->addFieldset('meta_seo', array('legend' => Mage::helper('loewenstark_seo')->__('Meta Data for SEO'), 'class' => 'fieldset-wide'));
        $fieldset->addField('meta_robots', 'select', array(
            'name' => 'meta_robots',
            'label' => Mage::helper('loewenstark_seo')->__('Robots Tag'),
            'title' => Mage::helper('loewenstark_seo')->__('Robots Tag'),
            'values'    => Mage::getSingleton('loewenstark_seo/system_config_source_cms_robots')->toOptionArray(),
            'disabled'  => $isElementDisabled
        ));
    }

    /**
     * event: controller_action_layout_render_before_ . $this->getFullActionName();
     * in: Mage_Core_Controller_Varien_Action::renderLayout()
     * 
     * @param $event Varien_Event_Observer
     * @return void
     */
    public function addRobotsTagToCmsPage(Varien_Event_Observer $event)
    {
        $page = Mage::getSingleton('cms/page');
        /* @var $page Mage_Cms_Model_Page */
        $this->_setRobotsHeader($page->getMetaRobots());
        if(Mage::getStoreConfig('web/default/front') == 'cms' && Mage::getStoreConfig('web/default/cms_home_page') == $page->getIdentifier())
        {
            $this->_setCanonicalHeader($this->_getBaseUrl());
        } else {
            $this->_setCanonicalHeader($this->getUrl($page->getIdentifier()));
        }
    }

    /**
     * event: controller_action_layout_render_before_ . $this->getFullActionName();
     * in: Mage_Core_Controller_Varien_Action::renderLayout()
     * 
     * @param $event Varien_Event_Observer
     * @return void
     */
    public function addRobotsTagToIndexPage(Varien_Event_Observer $event)
    {
        $obj = new Varien_Object();
        $obj->setIndexHandle('cms_index_index');
        Mage::dispatchEvent('loewenstark_seo_robots_tag_to_index', array(
            'object' => $obj,
            'model'  => $this
        ));
        if(in_array($obj->getIndexHandle(), $this->_getLayout()->getUpdate()->getHandles()))
        {
            $this->_setRobotsHeader($this->_helper()->getDefaultRobots());
            $this->_setCanonicalHeader($this->_getBaseUrl());
        }
    }
    
    /**
     * event: controller_action_layout_render_before_ . $this->getFullActionName();
     * in: Mage_Core_Controller_Varien_Action::renderLayout()
     * 
     * @param $event Varien_Event_Observer
     * @return void
     */
    public function addRobotsTagToContacts(Varien_Event_Observer $event)
    {
        $this->_setRobotsHeader($this->_helper()->getContactsRobots());
        $this->_setCanonicalHeader($this->getUrl('contacts'));
        
        $breadcrumbs = $this->_getLayout()->getBlock('breadcrumbs');
        if($this->_helper()->getContactsBreadcrumb() && $breadcrumbs)
        {
            $title = Mage::helper('contacts')->__('Contact Us');
            $breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('cms')->__('Go to Home Page'), 'link'=>Mage::getBaseUrl()));
            $breadcrumbs->addCrumb('cms_page', array('label'=>$title, 'title'=>$title));
        }
    }

    /**
     * event: controller_action_layout_render_before
     * in: Mage_Core_Controller_Varien_Action::renderLayout()
     * 
     * @param $event Varien_Event_Observer
     * @return void
     */
    public function addRobotsTagToCustomerAccount(Varien_Event_Observer $event)
    {
        $fullActions = array('customer_account_login' => '','customer_account_create' => '');
        $items = new Varien_Object();
        $items->setLayoutHandle('customer_account')
                ->setFullActions(new Varien_Object($fullActions));
        Mage::dispatchEvent('loewenstark_seo_robots_tag_to_customer_account', array(
            'object' => $items,
            'model'  => $this
        ));
        if(
                in_array($items->getLayoutHandle(), $this->_getLayout()->getUpdate()->getHandles())
                || 
                in_array($this->_getFullActionName(), array_keys($items->getFullActions()->getData()))
        ) {
            $this->_setRobotsHeader($this->_helper()->getCustomerRobots());
        }
    }

    /**
     * event: controller_action_layout_render_before_ . $this->getFullActionName();
     * in: Mage_Core_Controller_Varien_Action::renderLayout()
     * 
     * @param $event Varien_Event_Observer
     * @return void
     */
    public function addRobotsTagToCatalogProduct(Varien_Event_Observer $event)
    {
        $head = $this->_getLayout()->getBlock('head');
        /* @var $head Mage_Page_Block_Html_Head */
        $this->_setRobotsHeader($head->getRobots(), false);
        if ($this->helper('catalog/product')->canUseCanonicalTag())
        {
            $url = Mage::registry('product')
                    ->getUrlModel()
                    ->getUrl(Mage::registry('product'), array('_ignore_category' => true));
            $this->_setCanonicalHeader($url, false);
        }
    }
    
    /**
     * event: controller_action_layout_render_before_ . $this->getFullActionName();
     * in: Mage_Core_Controller_Varien_Action::renderLayout()
     * 
     * @param $event Varien_Event_Observer
     * @return void
     */
    public function addRobotsTagToCatalogCategory(Varien_Event_Observer $event)
    {
        $head = $this->_getLayout()->getBlock('head');
        /* @var $head Mage_Page_Block_Html_Head */
        $this->_setRobotsHeader($head->getRobots(), false);
        if ($this->helper('catalog/category')->canUseCanonicalTag())
        {
            $url = Mage::registry('current_category')->getUrl();
            $this->_setCanonicalHeader($url, false);
        }
    }
    
    /**
     * event: controller_action_layout_render_before_ . $this->getFullActionName();
     * in: Mage_Core_Controller_Varien_Action::renderLayout()
     * 
     * @param $event Varien_Event_Observer
     * @return void
     */
    public function addRobotsTagToCheckoutCart(Varien_Event_Observer $event)
    {
        $this->_setRobotsHeader($this->_helper()->getCheckoutRobots());
    }
    
    /**
     * event: controller_action_layout_render_before_ . $this->getFullActionName();
     * in: Mage_Core_Controller_Varien_Action::renderLayout()
     * 
     * @param $event Varien_Event_Observer
     * @return void
     */
    public function addRobotsTagToSitemap(Varien_Event_Observer $event)
    {
        $this->_setRobotsHeader($this->_helper()->getSitemapRobots());
    }
    
    /**
     * 
     * @return Mage_Core_Model_Layout
     */
    protected function _getLayout()
    {
        return Mage::app()->getLayout();
    }

    /**
     * set Robots Tag in Response Header (HTTP/1.1)
     * 
     * @param string $value
     */
    public function _setRobotsHeader($value, $addToHtmlHead = true)
    {
        if(empty($value))
        {
            $value = $this->_helper()->getDefaultRobots();
        }
        Mage::app()->getResponse()->setHeader('X-Robots-Tag', $value);
        if($addToHtmlHead)
        {
            $this->_getLayout()->getBlock('head')
                ->setData('robots', $value);
        }
        return $this;
    }
    
    /**
     * 
     * @param string $value url
     * @return Loewenstark_Seo_Model_Observer
     */
    public function _setCanonicalHeader($value, $addToHtmlHead = true)
    {
        if(!empty($value))
        {
            $link = '<'.$value.'>; rel="canonical"';
            Mage::app()->getResponse()->setHeader('Link', $link);
            if($addToHtmlHead)
            {
                $this->_getLayout()->getBlock('head')
                        ->addLinkRel('canonical', $value);
            }
        }
        return $this;
    }

    /**
     * get Controller FullActionName like "cms_page_view"
     * 
     * @param string $delimiter
     * @return string
     */
    public function _getFullActionName($delimiter='_')
    {
        $request = Mage::app()->getRequest();
        /* @var $request Mage_Core_Controller_Request_Http */
        return $request->getRequestedRouteName().$delimiter.
            $request->getRequestedControllerName().$delimiter.
            $request->getRequestedActionName();
    }

    /**
     * getBase Url of Store
     * 
     * @return string
     */
    public function _getBaseUrl()
    {
        $url = null;
        if($this->_getDefaultStoreId() == $this->_getStoreId())
        {
            $url = $this->getUrl('', array('_type' => 'direct_link'));
        } else {
            $url = Mage::helper('core/url')->getHomeUrl();
        }
        return $url;
    }

    /**
     * 
     * @return Loewenstark_Seo_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('loewenstark_seo');
    }

    /**
     * get Default Store Id
     * 
     * @return int
     */
    public function _getDefaultStoreId()
    {
        return Mage::app()->getWebsite()->getDefaultGroup()->getDefaultStoreId();
    }

    /**
     * get Current Store Id
     * 
     * @return int
     */
    public function _getStoreId()
    {
        return Mage::app()->getStore()->getStoreId();
    }

    /**
     * 
     * @param type $name
     * @return type
     */
    protected function helper($name)
    {
        return Mage::helper($name);
    }


    /**
     * 
     * @param string $url
     * @param array $params
     */
    public function getUrl($url, $params = array())
    {
        $params['_nosid'] = true;
        $url = Mage::getUrl($url, $params);
        $search = array('?___SID=U', '&___SID=U');
        $replace = '';
        return str_replace($search, $replace, $url);
    }
}
