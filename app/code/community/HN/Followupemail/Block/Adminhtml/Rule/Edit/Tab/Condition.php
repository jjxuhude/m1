<?php
class HN_Followupemail_Block_Adminhtml_Rule_Edit_Tab_Condition extends Mage_Core_Block_Template
{
    
    protected $_form = null;
    public function _contruct()
    {
        parent::_construct();
        
        //$this->setTemplate('hn/followupemail/rule/edit/tab/condition.phtml');
        $this->append('followupemail/adminhtml_rule_edit_tab_condition_order', 'gi.the');
        $this->setChild('order.conditional', 'followupemail/adminhtml_rule_edit_tab_condition_order');
        $this->_form = new HN_Followupemail_Block_Data_Form(
            array (
                'id' => 'addrule',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );
        $this->_form ->setData('enctype', 'multipart/form-data');
        $this->_form ->setData('id', 'addrule');
        
        $this->setForm($this->_form);
        
        $fieldset = $this->_form->addFieldset(
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
        
        //$this->_form = $form;
    }
    
    public function getForm()
    {
        $this->_form = new HN_Followupemail_Block_Data_Form(
            array (
                'id' => 'addrule',
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );
        $this->_form ->setData('enctype', 'multipart/form-data');
        $this->_form ->setData('id', 'addrule');
        
        $this->setForm($this->_form);
        
        $fieldset = $this->_form->addFieldset(
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
        
        return $this->_form;
    }
    public function _prepareLayout()
    {
        $this->setTemplate('hn/followupemail/rule/edit/tab/condition.phtml');
        $this->append('followupemail/adminhtml_rule_edit_tab_condition_order', 'gi.the');
        //$child = new HN_Followupemail_Block_Adminhtml_Rule_Edit_Tab_Condition_Order();
        //$this->setChild('order.conditional', $child);
        $block = $this->getLayout()->createBlock(
            'HN_Followupemail_Block_Adminhtml_Rule_Edit_Tab_Condition_Order',
            'order.conditional'
        );
        
        //$this->getChildHtml();
    }
}
