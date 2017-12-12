<?php
class HN_Followupemail_Model_Rule_Condition_Wishlist extends HN_Followupemail_Model_Rule_Condition_Abstract
{
    public function wishlistShareListener($observer)
    {
        $event = $observer->getEvent();
        $wishlist = $event->getData('wishlist');
        
        $this->process($wishlist);
        return $observer;
    }
    public function getName()
    {
        return 'Wishlist share';
    }
    
    public function getCode()
    {
        return 'wishlist_share';
    }
    public function getTemplateForAdditionElement()
    {
        return 'followupemail/adminhtml_rule_edit_tab_condition_customer';
    }
    /**
     *
     * @param Mage_Wishlist_Model_Wishlist $wishlist
     */
    public function process($wishlist)
    {
        $items = $wishlist->getItemCollection();
        if (count($items)) {
            foreach ($items as $item) {
            }
        }

        // //////////////////////////////
        $rules = Mage::getResourceModel('followupemail/rule_collection')->getAvailableRule($this->getCode());
        //var_dump ( $rules );
        if (! empty($rules)) {
            foreach ($rules as $rule_data) {
                $rule = Mage::getModel('followupemail/rule')->load($rule_data ['id']);
                $this->processEachRule($rule, $wishlist);
            }
        }
    }
    
    /**
     * @param unknown $rule
     * @param Mage_Wishlist_Model_Wishlist $wishlist
     * @param string $checkMailExist
     * @return Varien_Data_Collection
     */
    public function processEachRule($rule, $wishlist, $checkMailExist = true, $isTest = false)
    {
        $mail_collection = new Varien_Data_Collection();
        
        $isRuleSatisfied = $this->isRuleSatisfied($rule, $wishlist);
        
        if (!$isRuleSatisfied) {
            Mage::getSingleton('adminhtml/session')->addWarning(Mage::helper('adminhtml')->__('The object is not validate to send follow up email'));
        }

        $isMailExist = $this->isMailExist($wishlist->getId(), $rule->getId());
        
        if ($isRuleSatisfied && (!$isMailExist || !$checkMailExist)) {
            // get Email chain
            $chains = $rule->getEmailChain();
            if (! empty($chains)) {
                foreach ($chains as $chain) {
                    $email_template_code = $chain->getData('template');
                    $email_template = Mage::getModel('core/email_template')->load($email_template_code);
                    $mail_text = $email_template->getData('template_text');
                    $mail_content = $this->replacePredefinedVariable($wishlist, $mail_text);
                        
                    $mail_content = Mage::getModel('followupemail/mail')->preCoupon($mail_content, $rule);
                    $emailTemplateVariables = array (
                            '$wishlist' => $wishlist
                    );
                    $mail_text = $email_template->getProcessedTemplate($emailTemplateVariables);
                        
                    $mail_subject = $email_template->getData('template_subject');
                    $customer =Mage::getModel('customer/customer')->load($wishlist->getCustomerId());
                        
                    $mailBean = Mage::getModel('followupemail/mail')->prepareMail($rule, $chain, $mail_content, $mail_subject, $customer->getName(), $customer->getEmail(), $wishlist->getId(), $isTest)->save();
                    $mailBean = $mailBean->save();
                    $mail_collection->addItem($mailBean);
                }
            }
        }
        
        return $mail_collection;
    }
    /**
     *
     * @param unknown $rule
     * @param Mage_Wishlist_Model_Wishlist $wishlist
     */
    public function isRuleSatisfied($rule, $wishlist)
    {
        $store_id = $wishlist->getStore()->getId();
        $customer_id = $wishlist->getCustomerId();
        $customer = Mage::getModel('customer/customer')->load($customer_id);
        //$customer = new Mage_Customer_Model_Customer ();
        $customer_group_id = $customer->getGroupId();
        // $store_id = $order->getStoreId ();
        // $customer_id = $order->getCustomerGroupId ();
        // $customer_group_id = $order->getCustomerGroupId ();
        
        $ok = $this->isValidateGeneral($customer_id, $customer_group_id, $store_id, $rule);
        
        if (! $ok) {
            return false;
        }

        $items = $wishlist->getItemCollection();
        
        $condition = unserialize(base64_decode($rule->getData('conditions_serialized')));
        
        $ok_sku = false;
        if (isset($condition ['sku']) && $condition ['sku'] != '') {
            $skus = explode(',', $condition ['sku']);
            if (count($items)) {
                foreach ($items as $item) {
                    /**
                     *
                     * @var $item Mage_Wishlist_Model_Item
                     */
                    $product_id = $item->getProductId();
                    
                    $product = Mage::getModel('catalog/product')->load($product_id);
                    $sku = $product->getSku();
                    
                    if (in_array($ku, $skus)) { // satisfied condition
                        $ok_sku = true;
                        break;
                    }
                }
            } else {
                $ok_sku = true;
            }
        } else {
            $ok_sku = true;
        }
        
        return true;
    }
    
    /**
     *
     * @param Mage_Wishlist_Model_Wishlist $wishlist
     * @param string $word
     * @return string
     */
    public function replacePredefinedVariable($wishlist, $word)
    {
        $customer = Mage::getModel('customer/customer')->load($wishlist->getCustomerId());
        $predefined = array (
                '{{hn_customer_name}}' => $customer->getCustomerName(),
                '{{hn_customer_first_name}}' => $customer->getCustomerFirstName(),
                '{{hn_customer_email}}' => $customer->getCustomerEmail()
        );
        $content = strtr($word, $predefined);
        
        return $content;
    }
    
    /**
     * @param int $cart_id
     * @param int $rule_id
     * @return boolean
     */
}
