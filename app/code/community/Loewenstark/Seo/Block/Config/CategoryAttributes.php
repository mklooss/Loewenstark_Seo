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
        $this->addColumn('attrcode_name', array(
            'label' => Mage::helper('loewenstark_seo')->__('Attribut Name'),
            'style' => 'width:100px',
            'readonly' => true,
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('loewenstark_seo')->__('Add');
    }
    
    /**
     * Render array cell for prototypeJS template
     *
     * @param string $columnName
     * @return string
     */
    /*
    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }
        $column     = $this->_columns[$columnName];
        $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';

        if ($column['renderer']) {
            return $column['renderer']->setInputName($inputName)->setColumnName($columnName)->setColumn($column)
                ->toHtml();
        }

        return '<input type="text" disabled="disabled" name="' . $inputName . '" value="#{' . $columnName . '}" ' .
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
            (isset($column['class']) ? $column['class'] : 'input-text') . '"'.
            (isset($column['style']) ? ' style="'.$column['style'] . '"' : '') . '/>';
    }
    */
}