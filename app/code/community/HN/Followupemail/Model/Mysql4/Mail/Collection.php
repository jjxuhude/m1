<?php
class HN_Followupemail_Model_Mysql4_Mail_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('followupemail/mail');
    }
    

    /**
     * @author Luu Thanh Thuy
     * @return array:
     */
    public function getAvailableMailToSend()
    {


        $select = $this->getSelect()->where('send_at <=?', now())->where('status=0 or status =2');
    
        return  $this->getData();
    }

    /**
     * get the email log of abandoned cart fue rule that have been resume and complete order
     * @return $this
     */
    public function getAbandonedCartMailNeedCancel()
    {

        $resource = Mage::getSingleton('core/resource');

        $this ->getSelect()
            ->joinLeft(
                array('r'=>$resource->getTableName('followupemail/rule')),
                'main_table.rule_id = r.id',
                array()
            )

            ->joinLeft(
                array('s'=>$resource->getTableName('sales/order')),
                'main_table.event_info = s.quote_id',
                array('quote_id')
            )

            ->where('r.event=?', 'abandoned_cart')->where('s.quote_id is not null');



//echo $this->getSelect();
        return $this;
    }
}
