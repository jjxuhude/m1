<?php
class HN_Followupemail_Model_Rule_Condition_Order extends HN_Followupemail_Model_Rule_Condition_Abstract
{
    protected $_condition = array ();
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
        return 'Order';
    }
    
    /**
     *
     * @return string
     */
    public function getCode()
    {
        return 'sales_order_save_after';
    }
    public function getTemplateForAdditionElement()
    {
        return 'followupemail/adminhtml_rule_edit_tab_condition_order';
    }
    
    /**
     *
     * @return multitype:
     */
    public function getCondition()
    {
        return $this->_condition;
    }
    public function listener($order)
    {
        $rules = Mage::getModel('followupemail/rule')->getCollection()->addFilter('event', $this->getCode())->addFilter('enable', 1)->addFilter(
            'active_from',
            array (
                'gt'
            )
        );
        
        if (! empty($rules)) {
            foreach ($rules as $rule) {
                /**
                 * @var rule HN_Followupemail_Model_Rule
                 */
                // $rule = new HN_Followupemail_Model_Rule();
                $condition_of_rule = $rule->getData('conditions_serialized');
                $condition_of_rule = unserialize(base64_decode($condition_of_rule));
                if ($condition_of_rule ['grand_total']) {
                    $grand_total = $order->getGrandTotal();
                    $validate = $this->validateCell($grand_total, $condition_of_rule);
                    if (! $validate) {
                        return false;
                    }
                }
                
                // skus
                if ($condition_of_rule ['sku']) {
                    $skus = $this->getSkusInOrder($order); // example skus= array(a,b,c);
                    foreach ($skus as $sku) { // if only one sku is in $condition_of_rule['value']
                        if (in_array($sku, $condition_of_rule ['value'])) {
                            $validate = true;
                        }

                        break;
                    }
                }
            }
        }
    }
    
    /**
     *
     * @param
     *          $compare_value
     * @param array $condition_of_rule
     *          .For example $condition_of_rule = array('operation'=>'gt', 'value' =>100);
     */
    public function validateCell($compare_value, $condition_of_rule)
    {
        $operation = $condition_of_rule ['operation'];
        switch ($operation) {
            case 'gt':
                if ($compare_value > $condition_of_rule ['value']) {
                    return true;
                }
                break;
            case 'gteq':
                if ($compare_value > $condition_of_rule ['value'] || $compare_value == $condition_of_rule ['value']) {
                    return true;
                }
                break;
            case 'lt':
                if ($compare_value < $condition_of_rule ['value']) {
                    return true;
                }
                break;
            case 'lteq':
                if ($compare_value < $condition_of_rule ['value'] || $compare_value == $condition_of_rule ['value']) {
                    return true;
                }
                break;
            case 'eq':
                if ($compare_value == $condition_of_rule ['value']) {
                    return true;
                }
                break;
            case 'neq':
                if ($compare_value != $condition_of_rule ['value']) {
                    return true;
                }
                break;
            
            default:
                return false;
                break;
        }
    }
    public function getSkusInOrder()
    {
        $skus = array ();
        $order = new Mage_Sales_Model_Order();
        $orderItem = $order->getAllItems();
        foreach ($orderItem as $item) {
            // $item = new Mage_Sales_Model_Order_Item();
            $item->getProductType();
            $productid = $item->getProductId();
            $skus [] = Mage::getModel('catalog/product')->load($productid)->getSku();
        }

        return $skus;
    }
    public function orderCommitListener($observer)
    {
        $order = $observer->getOrder();
        $this->process($order);
        
        return $observer;
    }
    
    /**
     *
     * @param Mage_Sales_Model_Order $order
     */
    public function process($order)
    {
        // $order = new Mage_Sales_Model_Order ();
        
        /**
         * var $rules array
         */
        $rules = Mage::getResourceModel('followupemail/rule_collection')->getAvailableRule('sales_order_save_after');
        //var_dump ( $rules );
        if (! empty($rules)) {
            foreach ($rules as $rule_data) {
                $rule = Mage::getModel('followupemail/rule')->load($rule_data ['id']);
                $this->processEachRule($rule, $order);
            } //foreach
        }
    }
    
    public function processEachRule($rule, $order, $checkMailExist = true, $isTest = false)
    {
        
        $mail_collection = new Varien_Data_Collection();
        
        $isRuleSatisfied = $this->isRuleSatisfied($rule, $order, $isTest);
        if (!$isRuleSatisfied) {
            Mage::getSingleton('adminhtml/session')->addWarning(Mage::helper('adminhtml')->__('The object is not validate to send follow up email'));
        }

        $isMailExist = $this->isMailExist($order->getId(), $rule->getId());
        if ($isRuleSatisfied && (!$isMailExist || !$checkMailExist)) {
            // get Email chain
            $chains = $rule->getEmailChain();
            //Mage_Core_Model_Email_Template
            if (! empty($chains)) {
                foreach ($chains as $chain) {
                    $email_template_code = $chain->getData('template');
                    $email_template = Mage::getModel('core/email_template')->load($email_template_code);
                    $mail_text = $email_template->getData('template_text');
                    //$email_template->setStoreId(1);
                    $designConfig = array('area'=>'frontend' ,'store' =>1);
                    
                    $email_template->setDesignConfig($designConfig);
                    $emailTemplateVariables = array (
                            'order' => $order,
                            'items' =>$order->getAllItems()
                    );
                    $mail_text = $email_template->getProcessedTemplate($emailTemplateVariables);
                    $mail_content = $this->replacePredefinedVariable($order, $mail_text);
                        
                    $mail_content = Mage::getModel('followupemail/mail')->preCoupon($mail_content, $rule);
                    
                        
                    $mail_subject = $email_template->getData('template_subject');
                        
                    $mailBean = Mage::getModel('followupemail/mail')->prepareMail($rule, $chain, $mail_content, $mail_subject, $order->getCustomerName(), $order->getCustomerEmail(), $order->getId(), $isTest)->save();
                    $mailBean->save();
                    $mail_collection->addItem($mailBean);
                }
            }
        }

        return  $mail_collection;
    }
    public function stopProcessingRule($order)
    {
    }
    public function replacePredefinedVariable($order, $word)
    {
        $predefined = array (
                '{{hn_customer_name}}' => $order->getCustomerName(),
                '{{hn_customer_first_name}}' => $order->getCustomerFirstName(),
                '{{hn_customer_email}}' => $order->getCustomerEmail(),
                '{{hn_order_base_grand_total}}' => $order->getBaseGrandTotal(),
                '{{hn_order_increment_id}}' => $order->getIncrementId()
        );
        $content = strtr($word, $predefined);
        
        return $content;
    }
    
    /**
     * whether to add the mail to queue
     *
     * @param HN_Followupemail_Model_Rule $rule
     * @param Mage_Sales_Model_Order $order
     */
    public function isRuleSatisfied($rule, $order, $isTest = false)
    {
        $message = '';
        $store_id = $order->getStoreId();
        $customer_id = $order->getCustomerId();
        $customer_group_id = $order->getCustomerGroupId();
        
        $ok = $this->isValidateGeneral($customer_id, $customer_group_id, $store_id, $rule);
        
        //if it is not test mode then return false immediately.  If it is test mode we run the past to show the message to explain why the rule can not applied and send follow up email
        if (!$ok  && !$isTest) {
            return $ok;
        }
        
        $data ['event'] = $this->getCode();
        $data ['status'] = self::QUEUE;
        $data ['store_id'] = $order->getStoreId();
        $data ['recipient_email'] = $order->getCustomerEmail();
        $data ['recipient_name'] = $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname();
        
        /**
         * The rule
         */
        $condition = unserialize(base64_decode($rule->getData('conditions_serialized')));
        
        $status_check = false;
        if (isset($condition ['status'])) {
            if ($condition ['status'] == $order->getStatus()) {
                $status_check = true;
            }
        }
        
        if (!$status_check && $isTest) {
            $message .= Mage::helper('adminhtml')->__('The status of order is not satisfied');
        }
        
        $grand_total_check = false;
        
        if (isset($condition ['grand_total'] ['value'])) {
            switch ($condition ['grand_total'] ['operation']) {
                case 'gt' :
                    {
                    if ($order->getGrandTotal() > $condition ['grand_total'] ['value']) {
                        $grand_total_check = true;
                    }
                }
                    ;
                    break;
                case 'lt' :
                    {
                    if ($order->getGrandTotal() < $condition ['grand_total'] ['value']) {
                        $grand_total_check = true;
                    }
                }
                    ;
                    break;
                case 'eq' :
                    {
                    if ($order->getGrandTotal() == $condition ['grand_total'] ['value']) {
                        $grand_total_check = true;
                    }
                }
                    ;
                    break;
                case 'neq' :
                    {
                    if ($order->getGrandTotal() != $condition ['grand_total'] ['value']) {
                        $grand_total_check = true;
                    }
                }
                    ;
                    break;
                case 'gteq' :
                    {
                    if ($order->getGrandTotal() == $condition ['grand_total'] ['value'] || $order->getGrandTotal() > $condition ['grand_total'] ['value']) {
                        $grand_total_check = true;
                    }
                }
                    ;
                    break;
                case 'lteq' :
                    {
                    if ($order->getGrandTotal() == $condition ['grand_total'] ['value'] || $order->getGrandTotal() < $condition ['grand_total'] ['value']) {
                        $grand_total_check = true;
                    }
                }
                    ;
                    break;
                default:
                    ;
                    break;
            }
        } else {
            $grand_total_check = true;
        }
        
        //notice to admin
        if (!$grand_total_check && $isTest) {
            $message .= Mage::helper('adminhtml')->__('The grand total  of order is not satisfied');
        }
        
        /**
         * check sku
         */
        $sku_check = false;
        if (! isset($condition ['sku']) || $condition ['sku'] == '') {
            $sku_check = true;
        } else {
            $skus = explode(',', $condition ['sku']);
            /**
             *
             * @var $orderItem Mage_Sales_Model_Order_Item();
             */
            $orderItems = $order->getAllItems();
            foreach ($orderItems as $orderItem) {
                $product = $orderItem->getProduct();
                $sku = $product->getSku();
                if (in_array($sku, $skus)) {
                    $sku_check = true;
                }
            }
        }
        
        if (!$sku_check && $isTest) {
            $message .= Mage::helper('adminhtml')->__('The skus condition of order is not satisfied');
        }
        
        #exlucde category check
        $cat_check = false;
        $excludeCats = $rule->getData('category_ids');
        
        if (!$excludeCats || $excludeCats =='') {
            $cat_check = true;
        } else {
            $excludeCatsArr = explode(',', $excludeCats);
            
            $orderItems = $order->getAllItems();
            foreach ($orderItems as $orderItem) {
                $product = $orderItem->getProduct();
                $catIds = $product->getCategoryIds();
                if (count(array_intersect($catIds, $excludeCatsArr)) == 0) {
                    $cat_check = true;
                }

                break;
            }
        }
        
        if (!$cat_check && $isTest) {
            $message .= Mage::helper('adminhtml')->__('The exclude category condition of order is not satisfied');
        }
        
        if ($grand_total_check && $status_check && $sku_check && $ok && $cat_check) {
            return true;
        }

        if ($message) {
            /* Mage_Adminhtml_Model_Session */
            Mage::getSingleton('adminhtml/session')->addNotice($message);
        }

        return false;
    }
}
