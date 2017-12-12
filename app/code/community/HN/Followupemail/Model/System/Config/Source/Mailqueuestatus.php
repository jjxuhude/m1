<?php
class HN_Followupemail_Model_System_Config_Source_Mailqueuestatus
{
    public function toOptionArray()
    {
        return array(
                array('value' => 0, 'label'=>Mage::helper('followupemail')->__('queue')),
                array('value' => 1, 'label'=>Mage::helper('followupemail')->__('sent')),
                array('value' => 2, 'label'=>Mage::helper('followupemail')->__('failed')),
                array('value' => 3, 'label'=>Mage::helper('followupemail')->__('cancelled'))
        );
    }
    
    public function toArray()
    {
        return array(
                0 => Mage::helper('followupemail')->__('queue'),
                1 => Mage::helper('followupemail')->__('sent'),
                2 => Mage::helper('followupemail')->__('failed'),
                3 => Mage::helper('followupemail')->__('cancelled')
        );
    }
}
