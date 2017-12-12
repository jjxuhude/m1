<?php
class HN_Followupemail_Block_Adminhtml_Rule_Rule extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
    
        
        $this->_controller = 'adminhtml_rule';
        $this->_blockGroup = 'followupemail';
        $this->_headerText = Mage::helper('followupemail')->__('Rules management');
        $this->_addButtonLabel = Mage::helper('followupemail')->__('Add rule');
        
        
        
        parent::__construct();
    }
}
