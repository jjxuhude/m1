<?php
class HN_Followupemail_Block_Adminhtml_Rule_Edit_Tab_Newsletter extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        //$form = new Varien_Data_Form(array("encrypt","multipart/form-data"));
        $form = new Varien_Data_Form(array('id' => 'addrule', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data'));
        $this->setForm($form);
    
        $fieldset = $form->addFieldset('subscribers_only_fs', array('legend'=>Mage::helper('followupemail')->__('Newsletter Subscriber')));
    
        $fieldset->addField(
            'subscriber_only',
            'select',
            array(
                'label' => $this->__('Send to subscriber only'),
                'name' => 'subscriber_only',
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            )
        );
        
        
        //end of shipping method
        if (Mage::getSingleton('adminhtml/session')->getRuleData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getRuleData());
            Mage::getSingleton('adminhtml/session')->setdripshipData(null);
        } elseif (Mage::registry('giftcert_data')) {
            Mage::registry('giftcert_data')->setData('statuss', Mage::registry('giftcert_data')->getData('status'));
            $form->setValues(Mage::registry('giftcert_data')->getData());
        }

        return parent::_prepareForm();
    }
}
