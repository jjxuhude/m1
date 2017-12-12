<?php
/**
 * Created by Magenest
 * User: Luu Thanh Thuy
 * Date: 28/09/2015
 * Time: 20:42
 */
class HN_Followupemail_Model_Rule_Condition_Wishlist_Item_Discount extends HN_Followupemail_Model_Rule_Condition_Abstract
{

    public function getName()
    {
        return __('Send FUE when items in wishlist is sale off');
    }

    public function getCode()
    {
        return "wishlist_item_discount";
    }

    public function getTemplateForAdditionElement()
    {
        return 'followupemail/adminhtml_rule_edit_tab_condition_customer';
    }

    /**
     *
     * @param unknown $rule
     * @param Mage_Customer_Model_Customer $customer
     * @return boolean
     */
    public function isRuleSatisfied($rule, $customer, $isTest = false)
    {
        $validate = false;
        $customer_id = $customer->getId();
        $storeIds = $customer->getSharedStoreIds();
        $customerGroupId = $customer->getGroupId();
        foreach ($storeIds as $storeId) {
            $validate = parent::isValidateGeneral($customer_id, $customerGroupId, $storeId, $rule, $isTest);

            if ($validate) {
                return true;
            }
        }

        return $validate;
    }

    public function afterProductSave($observer)
    {
        $params = Mage::app()->getRequest()->getParams();
        if (isset($params['send_fue'])) {
        /* @var $product Mage_Catalog_Model_Product */
            $product =  $observer->getEvent()->getProduct();

            $special_price = $product->getSpecialPrice();

            $wishlistCollection = $this->getWishlistContainsProduct($product->getId());
            if ($special_price) {
                $rules = Mage::getResourceModel('followupemail/rule_collection')->getAvailableRule($this->getCode());

                if (count($rules) > 0) {
                    foreach ($rules as $rule) {

                        /** @var  $wishlist Mage_Wishlist_Model_Wishlist */
                        foreach ($wishlistCollection as $wishlist) {
                            $customer_id = $wishlist->getCustomerId();
                            $customer  = Mage::getModel('customer/customer')->load($customer_id);

                            $rule = Mage::getModel('followupemail/rule')->load($rule['id']);
                            $is_meet_condition = $this->isRuleSatisfied($rule, $customer);


                            if ($is_meet_condition) {
                                $this->processEachRule($rule, $customer, $product, false, false);
                            }
                        }
                    }
                }

                //perhaps send fue if there is rule exist
            }
        }
    }

    public function processEachRule($rule, $customer, $product, $checkMailExist = false, $isTest = false)
    {
        $emailChains = $rule->getEmailChain();

        if (count($emailChains) > 0) {
            foreach ($emailChains as $chain) {
                        ///////////////////

                //Mage_Core_Model_Email_Template
                        $email_template_code = $chain->getData('template');
                        $email_template = Mage::getModel('core/email_template')->load($email_template_code);
                        $mail_text = $email_template->getData('template_text');
                        //$email_template->setStoreId(1);
                        $designConfig = array('area'=>'frontend' ,'store' =>1);

                        $email_template->setDesignConfig($designConfig);
                        $emailTemplateVariables = array (
                            'customer' => $customer,
                            'product' =>$product
                        );
                        $mail_text = $email_template->getProcessedTemplate($emailTemplateVariables);
                        $mail_content = $mail_text;//$this->replacePredefinedVariable ( $order, $mail_text );

                        $mail_content = Mage::getModel('followupemail/mail')->preCoupon($mail_content, $rule);


                        $mail_subject = $email_template->getData('template_subject');

                        $mailBean = Mage::getModel('followupemail/mail')->prepareMail($rule, $chain, $mail_content, $mail_subject, $customer->getName(), $product->getCustomerEmail(), $product->getId(), $isTest)->save();
                        $mailBean->save();
            }

                        ///////////////////
        } //foreach
    }

    /**\
     * base on customer_id, rule_id, product_id
     * @return bool
     */
    public function alreadyGenerateFUE()
    {

        $generated = true;
        $mailCollection =  Mage::getResourceModel('followupemail/mail_collection');


        return $generated;
    }
    public function afterRuleSave($observer)
    {
        /* @var $rule Mage_SalesRule_Model_Rule */
        $rule = $observer->getEvent()->getRule();

        $discount_amount = $rule->getDiscountAmount();

        $productIds =   $rule->getProductIds();
        $customerGroupIds = $rule->getCustomerGroupIds();
    }

    public function createFollowUpEmailForSpecialPrice($product_id)
    {

        /** @var  $product Mage_Catalog_Model_Product */
        $product = Mage::getModel('catalog/product');

        $wishlistCollection = $this->getWishlistContainsProduct($product_id);
        if ($wishlistCollection->getSize() > 0) {

            /** @var  $wishlist Mage_Wishlist_Model_Wishlist */
            foreach ($wishlistCollection as $wishlist) {
                $customerId =  $wishlist->getCustomerId();
            }
        }
    }

    public function getWishlistContainsProduct($product_id)
    {

        /** @var  $wishlist_collection Mage_Wishlist_Model_Mysql4_Wishlist_Collection */
        $wishlist_collection=  Mage::getResourceModel('wishlist/wishlist_collection');

        $resource = Mage::getSingleton('core/resource');

        /**
         * Get the table name
         */
        $itemTbl = $resource->getTableName('wishlist/item');
        $wishlist_collection->getSelect()->join(array('i' =>$itemTbl), 'main_table.wishlist_id = i.wishlist_id', array('product_id'=>'i.product_id'))
            ->where('i.product_id=?', $product_id);

        return $wishlist_collection;
    }
}
