<?php
class HN_Followupemail_Model_Rule_Condition_Wishlistadditem extends HN_Followupemail_Model_Rule_Condition_Abstract
{
    public function wishlistShareListener($observer)
    {
        $event = $observer->getEvent();
        /**
         * @var items array of Mage_Wishlist_Model_Item
         */
        $items = $event->getData('items');
        // Mage_Wishlist_Model_Item
        $this->process($items);
    }
    
    public function getName()
    {
        return 'Wishlist add item';
    }
    
    public function getCode()
    {
        return 'wishlist_item_add';
    }
    public function getTemplateForAdditionElement()
    {
        return 'followupemail/adminhtml_rule_edit_tab_condition_customer';
    }
    
    public function wishlistProductAddListener($observer)
    {
        /**@var $items is array of  Mage_Wishlist_Model_Item */
        $items = $observer->getItems();
        $this->process($items);
        return $observer;
    }
    /**
     *
     * @param Mage_Wishlist_Model_Item $items
     */
    public function process($items)
    {
        
        // //////////////////////////////
        $rules = Mage::getResourceModel('followupemail/rule_collection')->getAvailableRule($this->getCode());
        if (! empty($rules)) {
            foreach ($rules as $rule_data) {
                $rule = Mage::getModel('followupemail/rule')->load($rule_data ['id']);
                
                $isRuleSatisfied = $this->isRuleSatisfied($rule, $items);
                
                if ($isRuleSatisfied) {
                    // get Email chain
                    $chains = $rule->getEmailChain();
                    if (! empty($chains)) {
                        foreach ($chains as $chain) {
                            $email_template_code = $chain->getData('template');
                            $email_template = Mage::getModel('core/email_template')->load($email_template_code);
                            $mail_text = $email_template->getData('template_text');
                            $mail_content = $this->replacePredefinedVariable($items, $mail_text);
                            
                            $mail_content = Mage::getModel('followupemail/mail')->preCoupon($mail_content, $rule);
                            $emailTemplateVariables = array (
                                    '$items' => $items
                            );
                            $mail_text = $email_template->getProcessedTemplate($emailTemplateVariables);
                            
                            $mail_subject = $email_template->getData('template_subject');
                            
                            $item = $items[key($items)];
                            $wishlist = Mage::getModel('wishlist/wishlist')->load($item->getWishlistId());
                            $store_id = $wishlist->getStore()->getId();
                            $customer_id = $wishlist->getCustomerId();
                            $customer = Mage::getModel('customer/customer')->load($customer_id);
                            
                            $mailBean = Mage::getModel('followupemail/mail')->prepareMail($rule, $chain, $mail_content, $mail_subject, $customer->getName(), $customer->getEmail(), $item->getId())->save();
                            $mailBean->save();
                        }
                    }
                }
            }
        }
    }
    
    /**
     *
     * @param unknown $rule
     * @param Mage_Wishlist_Model_Wishlist $wishlist
     */
    public function isRuleSatisfied($rule, $items)
    {
        $ok = false;
        foreach ($items as $item) {
            //Mage_Wishlist_Model_Item
            $wishlist = Mage::getModel('wishlist/wishlist')->load($item->getWishlistId());
            $store_id = $wishlist->getStore()->getId();
            $customer_id = $wishlist->getCustomerId();
            $customer = Mage::getModel('customer/customer')->load($customer_id);
            $customer_group_id = $customer->getGroupId();
            $ok = $this->isValidateGeneral($customer_id, $customer_group_id, $store_id, $rule);
            
            if (! $ok) {
                continue;
            }
            
            $condition = unserialize(base64_decode($rule->getData('conditions_serialized')));
            
            $ok_sku = false;
            if (isset($condition ['sku']) && $condition ['sku'] != '') {
                $skus = explode(',', $condition ['sku']);
                /**
                 *
                 * @var $item Mage_Wishlist_Model_Item
                 */
                $product_id = $item->getProductId();
                
                $product = Mage::getModel('catalog/product')->load($product_id);
                $sku = $product->getSku();
                
                if (in_array($ku, $skus)) { // satisfied condition
                    $ok_sku = true;
                    $ok = $ok && $ok_sku;
                    
                    if ($ok) {
                        return true;
                    }
                }
            }
        }

        return $ok;
    }
    
    /**
     *
     * @param Mage_Wishlist_Model_Wishlist $wishlist
     * @param string $word
     * @return string
     */
    public function replacePredefinedVariable($items, $word)
    {
        $item = $items [key($items)];
        $wishlist = Mage::getModel('wishlist/wishlist')->load($item->getWishlistId());
        
        $customer = Mage::getModel('customer/customer')->load($wishlist->getCustomerId());
        $predefined = array (
                '{{hn_customer_name}}' => $customer->getCustomerName(),
                '{{hn_customer_first_name}}' => $customer->getCustomerFirstName(),
                '{{hn_customer_email}}' => $customer->getCustomerEmail()
        );
        $content = strtr($word, $predefined);
        
        return $content;
    }
}
