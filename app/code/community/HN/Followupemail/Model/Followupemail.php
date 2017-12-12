<?php
class HN_Followupemail_Model_Followupemail extends Mage_Core_Model_Abstract
{
    
    /* (non-PHPdoc)
     * @see Varien_Object::_construct()
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('ticket/ticket');
    }
}
