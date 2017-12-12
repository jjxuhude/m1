<?php

class HN_Followupemail_Block_Adminhtml_Rule_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        //$form = new Varien_Data_Form(array("encrypt","multipart/form-data"));
        $form = new Varien_Data_Form(array('id' => 'addrule', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data'));
        $form->setData('enctype', 'multipart/form-data');
        $form->setData('id', 'addrule');
        $this->setForm($form);
        $note = "Pattern examples <br/><strong>[A8] : 8 alpha chars<br>[N4] : 4 numerics<br>[AN6] : 6 alphanumeric<br>GIFT-[A4]-[AN6] : GIFT-ADFA-12NF0O</strong>";
        
        $fieldset = $form->addFieldset('general_form', array('legend'=>Mage::helper('followupemail')->__('Rule')));
        
        $fieldset->addField(
            'name',
            'text',
            array(
            'label' => Mage::helper('followupemail')->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name',
            )
        );
        
        $status = array(0=>'inactive', 1=>'active');
        $fieldset->addField(
            'is_active',
            'select',
            array(
                'name' => 'is_active',
                'class' => 'required-entry',
                'required' => true,
                'label' => Mage::helper('catalog')->__('Status'),
                'title' => Mage::helper('catalog')->__('Status'),
                'values' => $status,
            )
        );
        
        //Description of rule
        $fieldset->addField(
            'description',
            'textarea',
            array(
            'label' => Mage::helper('followupemail')->__('Description'),
            'class' => 'required-entry',
            'required' => true,
        
            'name' => 'description',
            )
        );
        
        //Rule active from
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_LONG);
         
        $attribute = array(
                'name'   => 'from_date',
                'label'  => Mage::helper('followupemail')->__('Active from'),
                'title'  => Mage::helper('followupemail')->__('Active from'),
                'image'  => $this->getSkinUrl('images/grid-cal.gif'),
                'format'       => $dateFormatIso
                );
        
        $fieldset->addField('from_date', 'date', $attribute);
        

       
        $dateFormatIso = Mage::app()->getLocale() ->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_LONG);
         $fieldset->addField(
             'to_date',
             'date',
             array(
             'name' => 'to_date',
             'label' => Mage::helper('followupemail')->__('Active to'),
             'title' => Mage::helper('followupemail')->__('Active to'),
             'image' => $this->getSkinUrl('images/grid-cal.gif'),
             'format' => $dateFormatIso,
             )
         );
         
        $allStores = Mage::app()->getStores();
        $store_option = array();
        $store_name = array();
        $store_name[] = "Al store view";
        foreach ($allStores as $_eachStoreId => $val) {
            $store_option[] = Mage::app()->getStore($_eachStoreId)->getId();
            $store_name[] = Mage::app()->getStore($_eachStoreId)->getName();
        }

        $store_source = new Mage_Adminhtml_Model_System_Config_Source_Store();
        $store_option = $store_source->toOptionArray();
         $fieldset->addField(
             'store_id',
             'multiselect',
             array(
             'name' => 'store_id',
             'label' => Mage::helper('catalog')->__('Store view'),
             'title' => Mage::helper('catalog')->__('store view'),
             'values' => $store_option,
                'class' => 'required-entry',
                'required' => true,
                
             )
         );
       
         $customer_group = new Mage_Adminhtml_Model_System_Config_Source_Customer_Group_Multiselect();
         $options = $customer_group->toOptionArray();
         $options[] =array('label' =>Mage::helper('followupemail')->__('Guest'),'value' =>'') ;
        
        $fieldset->addField(
            'customer_group',
            'multiselect',
            array(
            'name' => 'customer_group',
            'label' => Mage::helper('catalog')->__('Customer group'),
            'title' => Mage::helper('catalog')->__('Customer group'),
            'values' => $options,
                'class' => 'required-entry',
                'required' => true,
            )
        );
        
        $fieldset->addField(
            'id',
            'hidden',
            array(
            'name' => 'id',
            'label' => Mage::helper('catalog')->__('Rule Id'),
            'title' => Mage::helper('catalog')->__('Rule Id'),
            'values' => '',
            )
        );
        
       
        //Varien_Data_Form_Element_Note('text'=>"")expired_at

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
