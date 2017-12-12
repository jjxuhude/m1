<?php

class HN_Followupemail_Model_Mysql4_Mail extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('followupemail/mail', 'id');
    }
}
