<?php
class HN_Followupemail_Block_Adminhtml_Rule_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('followupemail_rule_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('followupemail')->__('Rule Information'));
    }
    
    /*
	 * (non-PHPdoc) @see Mage_Adminhtml_Block_Widget_Tabs::_beforeToHtml()
	 */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_section_general',
            array (
                'label' => Mage::helper('followupemail')->__('Rule Information'),
                'title' => Mage::helper('followupemail')->__('Rule Information'),
                'content' => $this->getLayout()->createBlock('followupemail/adminhtml_rule_edit_tab_form')->toHtml()
            )
        );
        
        $this->addTab(
            'form_section_condition',
            array (
                'label' => Mage::helper('followupemail')->__('Condition'),
                'title' => Mage::helper('followupemail')->__('Condition'),
                'content' => $this->getLayout()->createBlock('followupemail/adminhtml_rule_edit_tab_condition')->toHtml()
            )
        );
        
        $product = Mage::getModel('catalog/product')->getCollection()->getFirstItem();
        Mage::register('current_product', $product);
        
        $this->addTab(
            'form_section_category',
            array (
                'label' => Mage::helper('followupemail')->__('Exclude category'),
                'title' => Mage::helper('followupemail')->__('Exclude category'),
                'content' => $this->getLayout()->createBlock('followupemail/adminhtml_rule_edit_tab_categories')->toHtml()
            )
        );
        
        $this->addTab(
            'form_section_newsletter',
            array (
                'label' => Mage::helper('followupemail')->__('Newsletter Subscriber Only'),
                'title' => Mage::helper('followupemail')->__('Newsletter Subscriber Only'),
                'content' => $this->getLayout()->createBlock('followupemail/adminhtml_rule_edit_tab_newsletter')->toHtml()
            )
        );
        
        $this->addTab(
            'form_section_coupon',
            array (
                'label' => Mage::helper('followupemail')->__('Coupon'),
                'title' => Mage::helper('followupemail')->__('Coupon'),
                'content' => $this->getLayout()->createBlock('followupemail/adminhtml_rule_edit_tab_coupon')->toHtml()
            )
        );
        $this->addTab(
            'form_section_bcc',
            array (
                'label' => Mage::helper('followupemail')->__('BCC'),
                'title' => Mage::helper('followupemail')->__('BCC'),
                'content' => $this->getLayout()->createBlock('followupemail/adminhtml_rule_edit_tab_bcc')->toHtml()
            )
        );
        $this->addTab(
            'form_section_test',
            array (
                'label' => Mage::helper('followupemail')->__('Test'),
                'title' => Mage::helper('followupemail')->__('Test'),
                'content' => $this->getLayout()->createBlock('followupemail/adminhtml_rule_edit_tab_test')->toHtml()
            )
        );
    
// 		$this->addTab ( 'form_section_', array (
// 				'label' => Mage::helper ( 'followupemail' )->__ ( ' ' ),
// 				'title' => Mage::helper ( 'followupemail' )->__ ( '' ),
// 				'content' => $this->getLayout ()->createBlock ( 'followupemail/adminhtml_rule_edit_tab_' )->toHtml () 
// 		) );
        
        return parent::_beforeToHtml();
    }
}
