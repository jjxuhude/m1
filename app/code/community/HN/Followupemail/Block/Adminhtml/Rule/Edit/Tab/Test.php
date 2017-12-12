<?php
class HN_Followupemail_Block_Adminhtml_Rule_Edit_Tab_Test extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        // $form = new Varien_Data_Form(array("encrypt","multipart/form-data"));
        $form = new Varien_Data_Form(
            array (
                'id' => 'addrule',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );
        $this->setForm($form);
        
        $fieldset = $form->addFieldset(
            'test_fs',
            array (
                'legend' => Mage::helper('followupemail')->__('Send Test Mail')
            )
        );
        //$note =
        $fieldset->addField(
            'test_recipient',
            'text',
            array (
                'label' => $this->__('Recipient email'),
                'name' => 'test_recipient' ,
                'note' => Mage::helper('followupemail')->__('Fill up email with the format John Doe'.htmlspecialchars('<').'john_doe@example.com '.htmlspecialchars('>'))
            )
        );
        
        $fieldset = $form->addFieldset(
            'test_object_fs',
            array (
                'legend' => Mage::helper('followupemail')->__('Test Object')
            )
        );
        
        $fieldset->addField(
            'test_customer_id',
            'text',
            array (
                'label' => $this->__('Customer Id'),
                'name' => 'test_customer_id'
            )
        );
        
        $fieldset->addField(
            'test_customer_email',
            'text',
            array (
                'label' => Mage::helper('followupemail')->__('Customer email'),
                'name' => 'test_customer_email'
            )
        );
        $fieldset->addField(
            'test_order_no',
            'text',
            array (
                'label' => Mage::helper('followupemail')->__('Order #'),
                'name' => 'test_order_no'
            )
        );
        $fieldset->addField(
            'test_wishlist_id',
            'text',
            array (
                'label' => Mage::helper('followupemail')->__('Wishlist ID'),
                'name' => 'test_wishlist_id'
            )
        );
        $fieldset->addField(
            'test_cart_id',
            'text',
            array (
                'label' => Mage::helper('followupemail')->__('Cart ID'),
                'name' => 'test_cart_id'
            )
        );
        $fieldset->addField(
            'test_product_id',
            'text',
            array (
                'label' => Mage::helper('followupemail')->__('Product ID'),
                'name' => 'test_product_id'
            )
        );
        $fieldset->addField(
            'test_resume_code',
            'text',
            array (
                'label' => Mage::helper('followupemail')->__('Resume code'),
                'name' => 'test_resume_code'
            )
        );
    }
}
