<?php
class HN_Followupemail_Model_Mysql4_Rule_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();

        $this->_init('followupemail/rule');
    }
    
    public function getAvailableRule($event_name)
    {
        $select = $this->getSelect()->where('event=?', $event_name) ->where('is_active=? ', 1)->where('from_date <=? or from_date is null', now()) ->where('to_date>=? or to_date is null', now());
        return  $this->getData();
    }
}
