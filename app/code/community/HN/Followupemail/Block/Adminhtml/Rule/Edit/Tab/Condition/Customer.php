<?php
class HN_Followupemail_Block_Adminhtml_Rule_Edit_Tab_Condition_Customer extends Mage_Core_Block_Template
{
    
    protected $_elements = null;
    protected $_form = null;
    public function _construct()
    {
        $this->setTemplate('hn/followupemail/rule/edit/tab/condition/customer.phtml');
    }
}
