<?php
class HN_Followupemail_Model_Rule_Condition_Newsletter extends HN_Followupemail_Model_Rule_Condition_Customer
{
    public function getName()
    {
        return 'Newsletter subscription';
    }
    
    /**
     *
     * @return string
     */
    public function getCode()
    {
        return 'newsletter_subscriber_save_commit_after';
    }
    
    /**
     * @param unknown $observer
     * @return unknown
     */
    public function newsletterSubscriberList($observer)
    {
        $event = $observer->getEvent();
        $subscriber = $event->getDataObject();
        $data = $subscriber->getData();
    
        $statusChange = $subscriber->getIsStatusChanged();
    
        // Trigger if user is now subscribed and there has been a status change:
        if ($data['subscriber_status'] == 1 && $statusChange == true) {
            // Insert your code here
            $customer_id = $data['customer_id'];
            $customer = Mage::getModel('customer/customer')->load($customer_id);
                
            parent::process($customer);
            //$this->process($customer);
        }

        return $observer;
    }
}
