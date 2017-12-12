<?php
class HN_Followupemail_Block_Adminhtml_Rule_Edit_Tab_Condition extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new HN_Followupemail_Block_Data_Form(
            array (
                'id' => 'addrule',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );
        $form->setData('enctype', 'multipart/form-data');
        $form->setData('id', 'addrule');
        
        $this->setForm($form);
        
        $fieldset = $form->addFieldset(
            'customer',
            array (
                'legend' => Mage::helper('followupemail')->__('Rule Condition')
            )
        );
        
        // events which fire sending email
        $option = array ();
        
        $events = Mage::helper('followupemail/config')->getActiveEvents();
        
        foreach ($events as $event) {
            $option[] = array('label' => $event->getName(), 'value' => $event->getCode());
        }
        
        $fieldset->addField(
            'event_name',
            'select',
            array (
                'label' => Mage::helper('followupemail')->__('Event'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'event_name' ,
                'onchange'=>'reLoadCondition()',
                'values' =>$option
            )
        );
        
        /** add the conditions from each event*/
// 		foreach ($events as $event) {
// 			$conditions = $event->getCondition();
// 			if (!empty($conditions)) {
// 				foreach ($conditions as $con) {
                    

// 					$fieldset->addField ( $con['name'] ,$con['type'], $con['att']
// 					 );
// 				}
// 			}
// 		}
        
        
        if (Mage::getSingleton('adminhtml/session')->getgiftcertData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getgiftcertData());
            Mage::getSingleton('adminhtml/session')->setgiftcertData(null);
        } elseif (Mage::registry('giftcert_data')) {
            $form->setValues(Mage::registry('giftcert_data')->getData());
        }

        return parent::_prepareForm();
    }
}
