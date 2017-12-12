<?php
class HN_Followupemail_Model_Rule_Condition_Customergroupchange extends HN_Followupemail_Model_Rule_Condition_Customer
{
    
    public function getName()
    {
        return  Mage::helper('followupemail')->__('Customer group change');
    }
    
    /**
     *
     * @return string
     */
    public function getCode()
    {
        return 'customer_save_before';
    }
    
    public function listener($observer)
    {
        
        $customer = $observer->getEvent()->getData('customer');
        
        $furture_group_id = $customer->getData('group_id');
        $current_group_id = Mage::getModel('customer/customer')->load($customer->getId());
        if ($current_group_id != $furture_group_id) {
            $this->process($customer);
        }

        /// $storeId = $this->getStoreId() ? $this->getStoreId() : Mage::app()->getStore()->getId();
        //    $groupId = Mage::getStoreConfig(Mage_Customer_Model_Group::XML_PATH_DEFAULT_ID, $storeId);
        return $observer;
    }
}
