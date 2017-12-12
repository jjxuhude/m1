<?php
class HN_Followupemail_Model_Track extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('followupemail/track');
    }
}
