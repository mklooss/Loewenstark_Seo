<?php
class Loewenstark_Seo_Block_Config_CategoryAttributes
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function _prepareToRender()
    {
        $this->addColumn('attrcode_suffix', array(
            'label' => Mage::helper('loewenstark_seo')->__('Attribut Code Suffix'),
            'style' => 'width:100px',
        ));
        $this->addColumn('cost', array(
            'label' => Mage::helper('loewenstark_seo')->__('Attribut Name'),
            'style' => 'width:100px',
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('loewenstark_seo')->__('Add');
    }
}