<?php
class HN_Followupemail_Block_Adminhtml_Rule_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array('id' => 'edit_form','action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),'method' => 'post',));
        $form->setUseContainer(true);
        $this->setForm($form);
        //$fieldset = $form->addFieldset('giftcert_form', array('legend'=>Mage::helper('giftcert')->__('Warehouse  Information')));
        
        return parent::_prepareForm();
    }
}
