<?php
class HN_Followupemail_Block_Adminhtml_Mail_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'followupemail';
        $this->_controller = 'adminhtml_mail';
    }
    public function getHeaderText()
    {
        return Mage::helper('followupemail')->__("Edit Mail");
    }
}
