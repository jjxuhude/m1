<?php
class HN_Followupemail_Model_Rule_Condition_Customerlogin extends HN_Followupemail_Model_Rule_Condition_Customer
{
    public function getName()
    {
        return  Mage::helper('followupemail')->__('Customer login');
    }
    
    /**
     *
     * @return string
     */
    public function getCode()
    {
        return 'customer_login';
    }
    
    public function listener($observer)
    {
        $customer = $observer->getEvent()->getData('customer');
        parent::process($customer);
        return $observer;
    }
}
