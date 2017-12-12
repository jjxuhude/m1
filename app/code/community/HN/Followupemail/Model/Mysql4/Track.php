<?php

class HN_Followupemail_Model_Mysql4_Track extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('followupemail/track', 'id');
    }
}
