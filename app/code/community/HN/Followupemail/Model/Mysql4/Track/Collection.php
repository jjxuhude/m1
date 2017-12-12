<?php
class HN_Followupemail_Model_Mysql4_Track_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();

        $this->_init('followupemail/track');
    }
}
