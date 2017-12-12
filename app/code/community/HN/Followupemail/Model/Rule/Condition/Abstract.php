<?php
class HN_Followupemail_Model_Rule_Condition_Abstract extends Mage_Core_Model_Abstract
{
    const OPERATION_GREATER_THAN = 'gt';
    const OPERATION_LESS_THAN = 'lt';
    const OPERATION_NOT_IN = 'nin';
    const OPERATION_IN = 'in';
    const OPERATION_EQUAL = 'eq';
    const OPERATION_NOT_EQUAL = 'neq';
    const OPERATION_LIKE = 'like';
    const OPERATION_NOT_LIKE = 'nlike';
    const OPERATION_GREATER_OR_EQUAL = 'gteq';
    const OPERATION_LESS_OR_EQUAL = 'lteq';
    const QUEUE = 0;
    const SENDING = 1;
    const CANCELLED = 2;
    protected $_condition = array ();
    public function _construct()
    {
        parent::_construct();
        
        $this->_condition [] = array (
                'name' => $this->getCode(),
                'type' => 'text',
                'att' => array (
                        'label' => Mage::helper('followupemail')->__('Grand total'),
                        'class' => $this->getCode(),
                        'name' => $this->getCode()
                ),
                'operation' => array (
                        'equal',
                        'gt',
                        'lt',
                        'not equal'
                )
        );
        
        // skus
        $this->_condition [] = array (
                'name' => 'skus',
                'type' => 'text',
                'att' => array (
                        'label' => Mage::helper('followupemail')->__('Skus'),
                        'class' => $this->getCode(),
                        'name' => $this->getCode(),
                        'note' => "separate sku by commas"
                ),
                'operation' => array (
                        'equal',
                        'gt',
                        'lt',
                        'not equal'
                )
        );
        
        $this->_condition [] = array (
                'name' => 'store_ids',
                'type' => 'multiselect',
                'att' => array (
                        'label' => Mage::helper('followupemail')->__('Store view'),
                        'class' => 'store_ids',
                        'name' => 'store_ids'
                )
        );
    }
    public function getCode()
    {
        return "code";
    }
    
    /**
     * ription: validate some common criteria such as store id, customer group id
     * store id is in (array), customer_group _in _array;
     * $compare_arr['store_id'] = 1
     * $compare_arr['customer_id'] = 1
     * $criteria['store_id] = array('value' , 'in');
     */
    public function validate($compare_arr, $criteria)
    {
    }
    
    /**
     *
     * @param string $email
     */
    public function isSubscriber($email)
    {
        $isSubcriber = false;
        
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
        if ($subscriber->getId()) {
            return true;
        }

        return false;
    }
    
    /**
     *
     * @param int $customerid
     * @param int $customerGroupId
     * @param int $storeId
     * @param HN_Followupemail_Model_Rule $rule
     * @param Mage_Customer_Model_Customer $customer
     * @return boolean
     */
    public function isValidateGeneral($customerid, $customerGroupId, $storeId, $rule, $isTest = false)
    {
        $message = '';
        $customer = Mage::getModel('customer/customer')->load($customerid);
        $customer_email = $customer->getEmail();
        ; // if it ==1 means only send to subsciber, == 0 is ok for all cases
        if ($rule->getData('subscriber_only') == 0 || $this->isSubscriber($customer_email)) {
            $subscriber_check = true;
        } else {
            $subscriber_check = false;
            if ($isTest) {
                $message = Mage::helper('adminhtml')->__('The condition of being subscriber is not satisfied');
            }
        }

        $customerGroups = unserialize(base64_decode($rule->getData('customer_group')));
        //var_dump($customerGroups);
        $storeIds = unserialize(base64_decode($rule->getData('store_id')));
        $storeIds[]=0;
        $customerGroupId_check = in_array($customerGroupId, $customerGroups);
        $storeId_check = in_array($storeId, $storeIds);
        
        if (!$customerGroupId_check && $isTest) {
            $message .= Mage::helper('adminhtml')->__('The condition of customer group is not satisfied');
        }
        
        if (!$storeId_check && $isTest) {
            $message .= Mage::helper('adminhtml')->__('The condition of store id is not satisfied');
        }
        
        if ($message) {
            /* Mage_Adminhtml_Model_Session */
            Mage::getSingleton('adminhtml/session')->addNotice($message);
        }
        
        if ($subscriber_check && $customerGroupId_check && $storeId_check) {
            //echo "thoa man";
            return true;
        } else {
            //echo 'khong thoa man';
            return false;
        }
    }
    
    /**
     * {{coupon.code}} or {{coupon1.code}}
     * {{coupon.expired_day}}
     * @param HN_Followupemail_Model_Rule $rule
     */
    
    public function processCouponFromMailText($text)
    {
    }
    public function attachCoupon($rule)
    {
        $qty = '';
        $ruleId = $rule->getData('coupon_rule');
        $prefix = $rule->getData('coupon_prefix');
        $sufix = $rule->getData('coupon_sufix');
        $length = $rule->getData('coupon_length');
        $generator = new Mage_SalesRule_Model_Coupon_Massgenerator();
        $data = array (
                'format' => 'alphanum',
                'length' => $length,
                'prefix' => $prefix,
                'qty' => $qty,
                'length' => $length,
                
                'rule_id' => $ruleId,
                'suffix' => $sufix,
                'uses_per_coupon' => 0,
                'uses_per_customer' => 0
        );
        $generator->setData($data);
        $generator->generatePool();
        $generated = $generator->getGeneratedCount();
        return $generated;
    }
    /**
     * @param int $wishlist_id
     * @param int $rule_id
     * @return boolean
     */
    public function isMailExist($id, $rule_id)
    {
        $collection =Mage::getModel('followupemail/mail')->getCollection();
        $collection->getSelect()->where('rule_id = ?', $rule_id)->where('event_info = ?', $id)->where('is_test !=?', 1);
    
        if ($collection->getSize() > 0) {
            return true;
        }

        return false;
    }
}
