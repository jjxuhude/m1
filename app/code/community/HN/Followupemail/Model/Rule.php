<?php
/**
 *
 * @author dell
 *@method int  getCouponActive()
 *@method int getCouponRule()
 *@method string getCouponPrefix()
 *@method string getCouponSufix()
 *@method string getExpiredAfterDay()
 *@method string getCouponLength()
 *@method int getExpiredAfterDay()
 */
class HN_Followupemail_Model_Rule extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('followupemail/rule');
    }
    public function getSenderName()
    {
    }
    public function getSenderEmail()
    {
    }
    /**
     * the chain will be store as text such as
     * before;1;1;20;rush_out
     *:after;2;3;6;happy_birthay:
     * @return Varien_Data_Collection
     */
    public function getEmailChain()
    {
        $send_at = '';
        $email_chain_collection = new Varien_Data_Collection();
        $chain_text = $this->getChain();
        $rows = explode(":", $chain_text);
        if ($rows) {
            foreach ($rows as $row) {
                $email_arr = explode(';', $row);
                if (count($email_arr) == 5) {
                    $email = array();
                    $email['time'] = $email_arr[0];
                    $email['day'] = $email_arr[1];
                    $email['hour'] = $email_arr[2];
                    $email['min'] = $email_arr[3];
                    $email['template'] = $email_arr[4];
                
                        $item = new Varien_Object($email);
                        $email_chain_collection->addItem($item);
                }
                    
                    // email array for example email_array('time'=> 'before' , 'hour'=>1, 'day'=>1 , 'min' , 'template');
            }
        }
    
        
        return $email_chain_collection;
    }
    
    /**
     * @param string $event_name
     */
    public function getAvailableRule($event_name)
    {
    }
    
    public function filterStopProcessing($customer_email)
    {
    }
    public function validate()
    {
    }
}
