<?php
class HN_Followupemail_Block_Adminhtml_Rule_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'followupemail';
        $this->_controller = 'adminhtml_rule';
        $this->_updateButton('save', 'label', Mage::helper('followupemail')->__('Save Rule'));
        $this->_updateButton('delete', 'label', Mage::helper('followupemail')->__('Delete Rule'));
        $url = $this->getUrl('*/*/saveandsendtestmail');
        $this->_addButton(
            'saveandtest',
            array(
                'label'     => Mage::helper('followupemail')->__('Save and send test mail'),
                'onclick'   => "editForm.submit(" . "'" . $url ."'".")",
                'class'     => '',
            )
        );
        //$this->setChild('hum', '');
    }
    public function getHeaderText()
    {
        if (Mage::registry('rule_data') && Mage::registry('rule_data')->getId()) {
            return Mage::helper('followupemail')->__("Edit Rule", $this->htmlEscape(Mage::registry('rule_data')->getTitle()));
        } else {
            return Mage::helper('followupemail')->__('Add Rule');
        }
    }
}
