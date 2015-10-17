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

    protected $_page_type = null;

    const XML_PATH_CATEGORY_CANONICAL_TAG          = 'catalog/seo/category_canonical_tag_seo';
    const XML_PATH_REDIRECT_IF_DISABLED            = 'catalog/seo/redirect_if_disabled';
    const XML_PATH_REDIRECT_BLACKLIST_CATEGORY_IDS = 'catalog/seo/redirect_blacklist_category_ids';

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
            'disabled'  => $isElementDisabled,
        ));
    }
    
    /**
     * event: admin_system_config_section_save_after
     * in: Mage_Adminhtml_System_ConfigController::saveAction()
     *
     * @param $event Varien_Event_Observer
     * @return void
     */
    public function createSeoTextAttributes(Varien_Event_Observer $event)
    {
        $_helper = Mage::helper('loewenstark_seo/CategoryAttributes');
        $setup = Mage::getModel('catalog/resource_setup', 'core/resource');
        $setup->startSetup();
        
        //get attributes from config and validate
        $config = $_helper->getSeoTextAttributeCodes();
        
        //delete old attributes
        $_helper->deleteAttributesFromCategory( $setup, $config );
        
        //create not existing attributes
        $_helper->addAttributesToCategory( $setup, $config );

        $setup->endSetup();
        
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
        $this->setPageType('cms');
        if (Mage::getStoreConfig('web/default/front') == 'cms' && Mage::getStoreConfig('web/default/cms_home_page') == $page->getIdentifier())
        {
            $this->_setCanonicalHeader($this->_getBaseUrl());
        }
        else
        {
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
            'model'  => $this,
        ));
        if (in_array($obj->getIndexHandle(), $this->_getLayout()->getUpdate()->getHandles()))
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
        if ($this->_helper()->getContactsBreadcrumb() && $breadcrumbs)
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
        $fullActions = array(
            'customer_account_login'  => '',
            'customer_account_create' => '',
        );
        $items = new Varien_Object();
        $items->setLayoutHandle('customer_account')
                ->setFullActions(new Varien_Object($fullActions));
        Mage::dispatchEvent('loewenstark_seo_robots_tag_to_customer_account', array(
            'object' => $items,
            'model'  => $this,
        ));
        if (
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
            $this->setPageType('catalog_product');
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
        $category = Mage::registry('current_category');
        /* @var $category Mage_Catalog_Model_Category */
        if (Mage::getStoreConfig(self::XML_PATH_CATEGORY_CANONICAL_TAG, $category->getStoreId()))
        {
            $this->setPageType('catalog_category');
            $url = $this->cleanUrl($category->getUrl());
            if ($url != Mage::helper('core/url')->getCurrentUrl())
            {
                $this->_setRobotsHeader('NOINDEX, FOLLOW');
            }
            $this->_setCanonicalHeader($url);
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
     * redirect if non ssl page opened with ssl
     *
     * @param Varien_Event_Observer $event
     */
    public function checkNonSecureUrl(Varien_Event_Observer $event)
    {
        $controller = $event->getControllerAction();
        /* @var $controller Mage_Core_Controller_Varien_Action */
        $request = $controller->getRequest();
        /* @var $request Mage_Core_Controller_Request_Http */
        $path = '/'.$controller->getFullActionName('/');

        $_helper = Mage::helper('loewenstark_seo/secure');
        /* @var $_helper Loewenstark_Energetic_Helper_Secure */
        if ($request->isSecure() && !$_helper->shouldBeSecure($path))
        {
            $url = $_helper->getCurrentUnSecureUrl($request);
            if (Mage::app()->getUseSessionInUrl())
            {
                $url = Mage::getSingleton('core/url')->getRedirectUrl($url);
            }

            Mage::app()->getFrontController()->getResponse()
                ->setRedirect($url, 301)
                ->sendResponse();
            exit;
        }
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
     *
     * @param $type set Page Type (like: cms, catalog_product, catalog_category)
     * @return Mage_Core_Model_Layout
     */
    public function setPageType($type)
    {
        $this->_page_type = $type;
        return $this;
    }

    /**
     * set Robots Tag in Response Header (HTTP/1.1)
     *
     * @param string $value
     */
    public function _setRobotsHeader($value, $addToHtmlHead = true)
    {
        if (empty($value))
        {
            $value = $this->_helper()->getDefaultRobots();
        }
        Mage::app()->getResponse()->setHeader('X-Robots-Tag', $value);
        if ($addToHtmlHead)
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
        if (!empty($value))
        {
            $value = $this->cleanUrl($value);
            $link = '<'.$value.'>; rel="canonical"';
            Mage::app()->getResponse()->setHeader('Link', $link);
            if ($addToHtmlHead)
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
        if ($this->_getDefaultStoreId() == $this->_getStoreId())
        {
            $url = Mage::getStoreConfig('web/unsecure/base_url', $this->_getStoreId());
        }
        else
        {
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
        return $this->parseUrl($url);
    }

    /**
     *
     * @param string $url
     * @return string
     */
    public function parseUrl($url)
    {
        $url = $this->cleanUrl($url);
        return Mage::helper('loewenstark_seo/trailingslashes')->parseUrl($url, $this->_page_type);
    }

    /**
     *
     * @param string $url
     * @return string
     */
    public function cleanUrl($url)
    {
        return str_replace(array('?___SID=U', '&___SID=U'), '', $url);
    }

    /**
     * Check if the current request matches a disabled product or category. If
     * an item is found redirect to a special page (or homepage by default)
     * instead of throwing a 404 error.
     */
    public function redirectIfDisabled($observer)
    {
        $redirect_if_disabled = Mage::getStoreConfig(self::XML_PATH_REDIRECT_IF_DISABLED);
        if ($redirect_if_disabled != '')
        {
            $request  = Mage::app()->getRequest();
            $response = Mage::app()->getResponse();
            $path     = explode('/', trim($request->getPathInfo(), '/'));

            if ($request->getActionName() == 'noRoute')
            {
                // Product
                if (in_array('product', $path) && in_array($redirect_if_disabled, array('product', 'both')))
                {
                    $product = Mage::getModel('catalog/product')->load($request->getParam('id'));
                    if ($product['status'] != Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                    {
                        // Redirect to parent category or base url
                        $response->clearHeaders()->setRedirect($this->getRedirectForProduct($product), 301)->sendResponse();
                        exit;
                    }
                }

                // Category
                if (in_array('category', $path) && in_array($redirect_if_disabled, array('category', 'both')))
                {
                    $category = Mage::getModel('catalog/category')
                        ->setStoreId(Mage::app()->getStore()->getStoreId())
                        ->getCollection()
                        ->addAttributeToFilter('entity_id', $request->getParam('id'))
                        ->addAttributeToSelect(array('is_active'))
                        ->getFirstItem()
                    ;
                    if ($category['is_active'] != '1')
                    {
                        // Redirect to parent category or base url
                        $response->clearHeaders()->setRedirect($this->getRedirectForCategory($category), 301)->sendResponse();
                        exit;
                    }
                }
            }
        }
    }

    /**
     * Get a parent category for a (disabled) product
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string Redirect url
     */
    protected function getRedirectForProduct(Mage_Catalog_Model_Product $product)
    {
        $url = Mage::getBaseUrl();

        if ($product->getCategoryIds())
        {
            // Get all categories for the product
            $categories = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToFilter('entity_id', array('in' => $product->getCategoryIds()))
                ->addIsActiveFilter()
                ->addUrlRewriteToResult()
            ;

            // Don't use blacklisted categories (backend config)
            $blacklist = explode(',', Mage::getStoreConfig(self::XML_PATH_REDIRECT_BLACKLIST_CATEGORY_IDS));
            array_walk($blacklist, create_function('&$val', '$val = trim($val);'));
            $blacklist = array_filter($blacklist);
            if ($blacklist)
            {
                $categories->addAttributeToFilter('entity_id', array('nin' => $blacklist));
                foreach ($blacklist as $id)
                {
                    $categories->addAttributeToFilter('path', array('nlike' => '%/' . $id . '/%'));
                }
            }

            // Return the first result
            foreach ($categories as $category)
            {
                return $url . $category->getRequestPath();
            }
        }

        return $url;
    }

    /**
     * Get a parent category for a (disabled) category
     *
     * @param Mage_Catalog_Model_Category $category
     * @return string Redirect url
     */
    protected function getRedirectForCategory(Mage_Catalog_Model_Category $category)
    {
        $url = Mage::getBaseUrl();

        $path = array_reverse(explode('/', $category->getPath()));

        $blacklist = explode(',', Mage::getStoreConfig(self::XML_PATH_REDIRECT_BLACKLIST_CATEGORY_IDS));
        array_walk($blacklist, create_function('&$val', '$val = trim($val);'));
        $blacklist = array_filter($blacklist);

        $root_id = Mage::app()->getGroup()->getRootCategoryId();
        foreach ($path as $id)
        {
            // When category root is reached return base url
            if ($id == $root_id) return $url;

            // Skip blacklisted categories
            if (in_array($id, $blacklist)) continue;

            $category = Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToFilter('entity_id', $id)
                ->addUrlRewriteToResult()
                ->addIsActiveFilter()
            ;

            if ($category->getId())
            {
                return $url . $category->getRequestPath();
            }
        }

        return $url;
    }

    /**
     * Add a phrase to the meta description of a category
     */
    public function addPhraseToMetaCategory($event)
    {
        if ($this->_helper()->isPhraseEnabled()) {
            $category = Mage::registry('current_category');

            if (!$category->getMetaTitle()) {
                $this->setCategoryMetaTitle($category);
            }
            if (!$category->getMetaDescription()) {
                $this->setCategoryMetaDescription($category);
            }
        }
    }

    /**
     * Add a phrase to the meta description of a category
     */
    public function addPhraseToMetaProduct($event)
    {
        if ($this->_helper()->isPhraseEnabled()) {
            $product = Mage::registry('current_product');

            if (!$product->getMetaTitle()) {
                $this->setProductMetaTitle($product);
            }
            if (!$product->getMetaDescription()) {
                $this->setProductMetaDescription($product);
            }
        }
    }

    /**
     * Set meta title for category
     *
     * @param Mage_Catalog_Model_Category $category Category object
     */
    protected function setCategoryMetaTitle(Mage_Catalog_Model_Category $category)
    {
        $title = $this->getPhrase(Mage::helper('loewenstark_seo')->getCategoryTitlePhrases(), $category->getId());
        $title = str_replace(array('{{product_name}}', '{{category_name}}'), $category->getName(), $title);
        Mage::app()->getLayout()->getBlock('head')->setTitle($title);
    }

    /**
     * Set meta title for product
     *
     * @param Mage_Catalog_Model_Product $product Product object
     */
    protected function setProductMetaTitle(Mage_Catalog_Model_Product $product)
    {
        $title = $this->getPhrase(Mage::helper('loewenstark_seo')->getProductTitlePhrases(), $product->getId());
        $title = str_replace(array('{{product_name}}', '{{category_name}}'), $product->getName(), $title);
        Mage::app()->getLayout()->getBlock('head')->setTitle($title);
    }

    /**
     * Set meta description for category
     *
     * @param Mage_Catalog_Model_Category $category Category object
     */
    protected function setCategoryMetaDescription(Mage_Catalog_Model_Category $category)
    {
        $title = $this->getPhrase(Mage::helper('loewenstark_seo')->getCategoryDescriptionPhrases(), $category->getId());
        $title = str_replace(array('{{product_name}}', '{{category_name}}'), $category->getName(), $title);
        Mage::app()->getLayout()->getBlock('head')->setDescription($title);
    }

    /**
     * Set meta description for product
     *
     * @param Mage_Catalog_Model_Product $product Product object
     */
    protected function setProductMetaDescription(Mage_Catalog_Model_Product $product)
    {
        $title = $this->getPhrase(Mage::helper('loewenstark_seo')->getProductDescriptionPhrases(), $product->getId());
        $title = str_replace(array('{{product_name}}', '{{category_name}}'), $product->getName(), $title);
        Mage::app()->getLayout()->getBlock('head')->setDescription($title);
    }

    /**
     * Determine a phrase for a given id. Will always return the same
     * phrase for the same id, independent from no. of phrases.
     *
     * @param array  $phrases List with phrases
     * @param int    $id      Category/Product ID
     *
     * @return string
     */
    protected function getPhrase($phrases, $id)
    {
        $phrases = array_values($phrases);

        // Determine the phrase index for given id
        $idx = $id % count($phrases);

        if (isset($phrases[$idx])) {
            return html_entity_decode($phrases[$idx], null, 'UTF-8');
        }

        return false;
    }
}
