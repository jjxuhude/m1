<?php
class HN_Followupemail_Block_Adminhtml_Rule_Edit_Tab_Condition_Order extends Mage_Core_Block_Template
{
    
    protected $_elements = null;
    protected $_form = null;
    public function _construct()
    {
        $this->setTemplate('hn/followupemail/rule/edit/tab/condition/order.phtml');
    }
// 	public function _construct() {
// 		parent::_construct();
// 		$form = new Varien_Data_Form ( array (
// 				'id' => 'addrule',
// 				'action' => $this->getData ( 'action' ),
// 				'method' => 'post',
// 				'enctype' => 'multipart/form-data' 
// 		) );
// 		$form->setData ( 'enctype', 'multipart/form-data' );
// 		$form->setData ( 'id', 'addrule' );
        
// 		$this->setForm ( $form );
        
// 		$fieldset = $form->addFieldset ( 'condition_order', array (
// 				'legend' => Mage::helper ( 'followupemail' )->__ ( 'Condition' ) 
// 		) );
        

// 		$fieldset->addField('order_status', 'text', array(
// 				'label' => Mage::helper('followupemail')->__('Order status'),
// 				'class' => 'required-entry',
// 				'required' => true,
// 				'name' => 'order_status',
// 		));

// 		$fieldset->addField('grand_total[value]', 'text', array(
// 				'label' => Mage::helper('followupemail')->__('Grand total value'),
// 				'class' => 'required-entry',
// 				'required' => true,
// 				'name' => 'grand_total[value]',
// 		));
        
// 		$fieldset->addField('grand_total[operation]', 'text', array(
// 				'label' => Mage::helper('followupemail')->__('Grand total operation'),
// 				'class' => 'required-entry',
// 				'required' => true,
// 				'name' => 'grand_total[value]',
// 		));
        
        
// 	//	$form->getElement('grand_total')->setRenderer($this->getLayout()->createBlock('followupemail/adminhtml_rule_edit_tab_saleamount'));
        
// 		$this->_form = $form;
// 		$this->_elements = $form->getElements();
        
// 	}
    
// 	public function getHtml() {
// 	echo 	$this->_form->toHtml();
// 	}
// 	public function getAddElements() {
// 		return $this->_elements;
// 	}
    
// 	public function _prepareLayout() {
// 		$this->setTemplate('hn/followupemail/rule/edit/tab/condition/order.phtml');
        
// 	}
}
