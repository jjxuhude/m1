<?php
class HN_Followupemail_Block_Adminhtml_Mail_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array (
                'id' => 'edit_form',
                'action' => $this->getUrl(
                    '*/*/save',
                    array (
                        'id' => $this->getRequest()->getParam('id')
                    )
                ),
                'method' => 'post'
            )
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        
        $fieldset = $form->addFieldset(
            'general_form',
            array (
                'legend' => Mage::helper('followupemail')->__('Email detail')
            )
        );
        
        $fieldset->addField(
            'email_subject',
            'text',
            array (
                'label' => Mage::helper('followupemail')->__('Subject'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'email_subject'
            )
        );
        $fieldset->addField(
            'email_content',
            'textarea',
            array (
                'label' => Mage::helper('followupemail')->__('Content'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'email_content'
            )
        );
        $fieldset->addField(
            'id',
            'hidden',
            array (
                'label' => Mage::helper('followupemail')->__('Id'),
                'class' => 'hidden',
                'required' => true,
                'name' => 'id'
            )
        );
        
        $form->setValues(Mage::registry('data')->getData());
        return parent::_prepareForm();
    }
}
