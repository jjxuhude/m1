<?php
class HN_Followupemail_Model_Rule_Condition_Customerlogout extends HN_Followupemail_Model_Rule_Condition_Customer
{
    
    public function getName()
    {
        return  Mage::helper('followupemail')->__('Customer logout');
    }
    
    /**
     *
     * @return string
     */
    public function getCode()
    {
        return 'customer_logout';
    }
    
    public function listener($observer)
    {
        $customer = $observer->getEvent()->getData('customer');
        parent::process($customer);
        return $observer;
    }
}
