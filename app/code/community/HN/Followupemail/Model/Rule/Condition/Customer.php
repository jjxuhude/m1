<?php
class HN_Followupemail_Model_Rule_Condition_Customer extends HN_Followupemail_Model_Rule_Condition_Abstract
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
        return 'Customer register';
    }
    
    /**
     *
     * @return string
     */
    public function getCode()
    {
        return 'customer_register_success';
    }
    public function listener($observer)
    {
        $customer = $observer->getData('customer');
        $this->process($customer);
        return $observer;
    }
    public function getTemplateForAdditionElement()
    {
        return 'followupemail/adminhtml_rule_edit_tab_condition_customer';
    }
    
    /**
     *
     * @param Mage_Customer_Model_Customer $customer
     */
    public function process($customer)
    {
        
        $rules = Mage::getResourceModel('followupemail/rule_collection')->getAvailableRule($this->getCode());
        
        if (! empty($rules)) {
            foreach ($rules as $rule_data) {
                $rule = Mage::getModel('followupemail/rule')->load($rule_data ['id']);
                
                $this->processEachRule($rule, $customer);
            } //foreach
        }
    }
    
    public function processEachRule($rule, $customer, $checkMailExist = true, $isTest = false)
    {
        $mail_collection = new Varien_Data_Collection();
        
        $isRuleSatisfied = $this->isRuleSatisfied($rule, $customer, $isTest);
        
        if (!$isRuleSatisfied) {
            Mage::getSingleton('adminhtml/session')->addWarning(Mage::helper('adminhtml')->__('The object is not validate to send follow up email'));
        }

        $isMailExist = $this->isMailExist($customer->getId(), $rule->getId());
        if ($isRuleSatisfied && (!$isMailExist || !$checkMailExist)) {
            // get Email chain
            $chains = $rule->getEmailChain();
            if (! empty($chains)) {
                foreach ($chains as $chain) {
                    $email_template_code = $chain->getData('template');
                    $email_template = Mage::getModel('core/email_template')->load($email_template_code);
                    $mail_text = $email_template->getData('template_text');
                    $mail_content = $this->replacePredefinedVariable($customer, $mail_text);
                        
                    $mail_content = Mage::getModel('followupemail/mail')->preCoupon($mail_content, $rule);
					
                    $emailTemplateVariables = array (
                            'customer' => $customer
                    );
                    $mail_text = $email_template->getProcessedTemplate($emailTemplateVariables);
                        
                    $mail_subject = $email_template->getData('template_subject');
                        
                    $mailBean = Mage::getModel('followupemail/mail')->prepareMail($rule, $chain, $mail_content, $mail_subject, $customer->getName(), $customer->getEmail(), $customer->getId(), $isTest)->save();
                    $mail = $mailBean->save();
                        
                    $mail_collection->addItem($mail);
                }
            }
        }

        return $mail_collection;
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
    /**
     *
     * @param Mage_Customer_Model_Customer $customer
     * @param string $word
     * @return string
     */
    public function replacePredefinedVariable($customer, $word)
    {
        $predefined = array (
                '{{hn_customer_name}}' => $customer->getName(),
                '{{hn_customer_first_name}}' => $customer->getFirstname(),
                '{{hn_customer_email}}' => $customer->getEmail()
        );
        $content = strtr($word, $predefined);
        
        return $content;
    }
    
    /**
     * @param int $customer_id
     * @param int $rule_id
     * @return boolean
     */
    public function isMailExist($customer_id, $rule_id)
    {
        $collection =Mage::getModel('followupemail/mail')->getCollection();
        $collection->getSelect()->where('rule_id = ?', $rule_id)->where('event_info = ?', $customer_id)->where('is_test !=?', 1);
    
        if ($collection->getSize() > 0) {
            return true;
        }

        return false;
    }
}
