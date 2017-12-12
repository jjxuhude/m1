<?php
class HN_Followupemail_Block_Adminhtml_Rule_Edit_Tab_Coupon extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        //$form = new Varien_Data_Form(array("encrypt","multipart/form-data"));
        $form = new Varien_Data_Form(array('id' => 'addrule', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data'));
        $this->setForm($form);
    
        $fieldset = $form->addFieldset('coupon', array('legend'=>Mage::helper('followupemail')->__('Coupon')));
    
        $fieldset->addField(
            'coupon_active',
            'select',
            array(
                'label' => $this->__('Enable coupon for this rule'),
                'name' => 'coupon_active',
                'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            )
        );
        
        $fieldset->addField(
            'coupon_rule',
            'select',
            array(
                'label' => $this->__('Coupon rule'),
                'name' => 'coupon_rule',
                'values' => Mage::getSingleton('followupemail/system_config_source_coupon')->toOptionArray(),
            )
        );
        
        
        $fieldset->addField(
            'coupon_prefix',
            'text',
            array(
                'label' => Mage::helper('followupemail')->__('Coupon prefix'),
                'name' => 'coupon_prefix',
            )
        );
        $fieldset->addField(
            'coupon_sufix',
            'text',
            array(
                'label' => Mage::helper('followupemail')->__('Coupon sufix'),
                'name' => 'coupon_sufix',
            )
        );
        $fieldset->addField(
            'coupon_length',
            'text',
            array(
                'label' => Mage::helper('followupemail')->__('Coupon length'),
                'name' => 'coupon_length',
            )
        );
        
        $fieldset->addField(
            'expired_after_day',
            'text',
            array(
                'label' => Mage::helper('followupemail')->__('Expired after , days '),
                'name' => 'expired_after_day',
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
