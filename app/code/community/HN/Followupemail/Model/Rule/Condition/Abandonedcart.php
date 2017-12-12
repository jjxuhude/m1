<?php
class HN_Followupemail_Model_Rule_Condition_Abandonedcart extends HN_Followupemail_Model_Rule_Condition_Abstract
{
    const SESSION_TIMEOUT = 3600;
    const ABANDONED_CART_PERIOD = 'followupemail/general/time_abandoned_cart';
    const LAST_ABANDONED_CART_COLLECT_EXEC = 'followupemail/time/abandonedcart_collect_lt';
//    const LAST_ABANDONED_CART_LAST_TIME_EXEC = 'followupemail/time/abandonedcart_collect_lt';

    public function _construct()
    {
        parent::_construct();
    }
    
    /**
     *
     * @return string
     */
    public function getName()
    {
        return Mage::helper('followupemail')->__('Abandoned cart');
    }
    public function getTemplateForAdditionElement()
    {
        return 'followupemail/adminhtml_rule_edit_tab_condition_customer';
    }
    /**
     *
     * @return string
     */
    public function getCode()
    {
        return 'abandoned_cart';
    }
    public function scheduleCollectAbandonedCart()
    {
         $abandoned_cart = $this->_collectAbandonedCarts();
        
        if (count($abandoned_cart) > 0) {
            $rules = Mage::getResourceModel('followupemail/rule_collection')->getAvailableRule($this->getCode());
            
            foreach ($abandoned_cart as $cart) {
                if ($cart ['customer_emai'] || $cart ['customer_id']) {
                    $this->process($cart);
                }
            }
        }
    }
    public function process($cart)
    {
        $mail_collection = new Varien_Data_Collection();
        $rules = Mage::getResourceModel('followupemail/rule_collection')->getAvailableRule($this->getCode());
        if (! empty($rules)) {
            foreach ($rules as $rule_data) {
                $rule = Mage::getModel('followupemail/rule')->load($rule_data ['id']);
                
                 $this->processEachRule($rule, $cart);
            }
        }
        
        return $mail_collection;
    }
    
    /**
     * @param HN_Followupemail_Model_Rule $rule
     * @param Mage_Sales_Model_Quote $cart
     * @param boolean $checkMailExist
     */
    public function processEachRule($rule, $cart, $checkMailExist = true, $isTest = false)
    {
        $mail_collection = new Varien_Data_Collection();
        $isRuleSatisfied = $this->isRuleSatisfied($rule, $cart, $isTest);
        $recipient_email = $cart->getCustomerEmail();
        $isMailExist = $this->isMailExist($cart->getId(), $rule->getId());
        if ($isRuleSatisfied && (!$isMailExist  || !$checkMailExist)) {
            // get Email chain
            $chains = $rule->getEmailChain();
            if (! empty($chains)) {
                foreach ($chains as $chain) {
                    $email_template_code = $chain->getData('template');
                    $email_template = Mage::getModel('core/email_template')->load($email_template_code);
                    $mail_text = $email_template->getData('template_text');
                        
                    $emailTemplateVariables = array (
                            'cart' => $cart
                    );
                    $mail_text = $email_template->getProcessedTemplate($emailTemplateVariables);
        
                    $mail_content = Mage::getModel('followupemail/mail')->preCoupon($mail_text, $rule);
                        
                        
                    $mail_subject = $email_template->getData('template_subject');

                    $recipient_name = $cart->getFirstName() . ' ' . $cart ->getLastName();
                    $recipient_email = $cart->getCustomerEmail();
                    $event_info = $cart->getId();
                    /* @var $mailBean HN_Followupemail_Model_Mail */


                    $mailBean = Mage::getModel('followupemail/mail')->prepareMail($rule, $chain, $mail_content, $mail_subject, $recipient_name, $recipient_email, $event_info, $isTest)->save();
                        
                    $mail =     $mailBean->save();
                    $mail_collection->addItem($mail);
                }
            }
        }
        
        return $mail_collection;
    }
    public function isRuleSatisfied($rule, $cart, $isTest = false)
    {
        $storeId = $cart->getStoreId();// ['store_id'];
        $customerid = $cart->getCustomerId();// ['customer_id'];
        //if ($customerid) {
            $customer = Mage::getModel('customer/customer')->load($customerid);
            $customerGroupId = $customer->getGroupId();
            return $this->isValidateGeneral($customerid, $customerGroupId, $storeId, $rule, $isTest);
        //}
        return true;
    }
    public function isMailExist($cart_id, $rule_id)
    {
         $collection =Mage::getModel('followupemail/mail')->getCollection();
         $collection->getSelect()->where('rule_id = ?', $rule_id)->where('event_info = ?', $cart_id)->where('is_test !=?', 1);
        
        if ($collection->getSize() > 0) {
            return true;
        }

        return false;
    }
    public function _collectAbandonedCarts($down_limit_mysql = '')
    {
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');

        /**
         * @var $core_helper Mage_Core_Model_Date
         */
        $core_helper = Mage::getModel('core/date');
        $current_time =date("Y-m-d H:i:s", time());

        $dateObj = new DateTime($current_time);
        //unit is minutes
        $time_to_be_considered_abandoned_cart = Mage::getStoreConfig(self::ABANDONED_CART_PERIOD);

        if (!$time_to_be_considered_abandoned_cart) {
            $time_to_be_considered_abandoned_cart = 60;
        }

        $dateObj->modify('-' . $time_to_be_considered_abandoned_cart. ' minutes');

        $upper_limit = $dateObj->getTimestamp();

        $down_limit = Mage::getStoreConfig(self::LAST_ABANDONED_CART_COLLECT_EXEC);

        if (!$down_limit) {
            $dateObj->modify('-' . $time_to_be_considered_abandoned_cart. ' minutes');

            $down_limit =$dateObj->format('Y-m-d H:i:s');
        }

        if (!$down_limit_mysql) {
            $down_limit_mysql=$down_limit;
        }

        $up_limit_mysql = date("Y-m-d H:i:s", $upper_limit);

        $collection = Mage::getModel('sales/quote')->getCollection();
        $collection ->getSelect()->joinLeft(
            array (
                'a' => $resource->getTableName('sales/quote_address')
            ),
            'main_table.entity_id=a.quote_id AND a.address_type="billing"',
            array (
                'customer_email' => new Zend_Db_Expr('IFNULL(main_table.customer_email, a.email)'),
                'customer_firstname' => new Zend_Db_Expr('IFNULL(main_table.customer_firstname, a.firstname)'),
                'customer_middlename' => new Zend_Db_Expr('IFNULL(main_table.customer_middlename, a.middlename)'),
                'customer_lastname' => new Zend_Db_Expr('IFNULL(main_table.customer_lastname, a.lastname)')
            )
        )->joinInner(
            array (
                'i' => $resource->getTableName('sales/quote_item')
                ),
            'main_table.entity_id=i.quote_id',
            array (
                'product_ids' => new Zend_Db_Expr('GROUP_CONCAT(i.product_id)'),
                'item_ids' => new Zend_Db_Expr('GROUP_CONCAT(i.item_id)')
                )
        )->where('main_table.is_active=1')->where('main_table.updated_at > ?', $down_limit_mysql)->where('main_table.updated_at < ?', $up_limit_mysql)->where('main_table.items_count>0')->where('main_table.customer_email IS NOT NULL OR a.email IS NOT NULL')->where('i.parent_item_id IS NULL')->group('main_table.entity_id')->order('updated_at');

        /** @var  $configModel Mage_Core_Model_Config */
        $configModel = Mage::getModel('core/config');

        $configModel->saveConfig(self::LAST_ABANDONED_CART_COLLECT_EXEC, $current_time);

     // echo $collection->getSelect();

        if ($collection->getSize() == 0) {
            return;
        }

        return $collection;
    }
}
