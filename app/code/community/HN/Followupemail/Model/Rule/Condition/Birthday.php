<?php
class HN_Followupemail_Model_Rule_Condition_Birthday extends HN_Followupemail_Model_Rule_Condition_Customer
{
    public function getName()
    {
        return Mage::helper('followupemail')->__('Customer birthday');
    }
    
    /**
     *
     * @return string
     */
    public function getCode()
    {
        return 'customer_birthday';
    }
    public function getTemplateForAdditionElement()
    {
        return 'followupemail/adminhtml_rule_edit_tab_condition_customer';
    }
    public function cron()
    {
        $rules = Mage::getResourceModel('followupemail/rule_collection')->getAvailableRule($this->getCode());
        
        if (!empty($rules)) {
            foreach ($rules as $rule_data) {
                $rule = Mage::getModel('followupemail/rule')->load($rule_data ['id']);
                
                $this->processEachRule($rule);
            }
        }
    }
    
    /**
     * @param HN_Followupemail_Model_Rule $rule
     */
    public function processEachRule($rule, $customer = null, $checkMailExist = true, $isTest = false)
    {
        $emailChains = $rule->getEmailChain();
        
        if (count($emailChains) > 0) {
            foreach ($emailChains as $chain) {
                $seconds =  $chain->getData('day') * 60* 60*24 + $chain->getData('hour')*60*60 + $chain->getData('min')*60;
                $customers = $this->collectBirthday($seconds);
            
                if (!empty($customers)) {
                    foreach ($customers as $customerData) {
                        $customer = Mage::getModel('customer/customer')->load($customerData['entity_id']);
                        $dob = $customerData['value'];
                    
                        ///////////////////
                    
                    
                        $isRuleSatisfied = $this->isRuleSatisfied($rule, $customer);
                    
                        if (!$isRuleSatisfied) {
                            Mage::getSingleton('adminhtml/session')->addWarning(Mage::helper('adminhtml')->__('The object is not validate to send follow up email'));
                        }

                        $isMailExist = $this->isMailExist($customer->getId(), $rule->getId());
                        if ($isRuleSatisfied && (!$isMailExist || !$checkMailExist)) {
                            // get Email chain
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
                                $mail->send();
                        }

                        ///////////////////
                    }
                }
            } //foreach
        }
    }
    /**
     * @param int $before
     * @return array
     */
    public function collectBirthday($before)
    {
        $customerEntityTypeID = Mage::getModel('eav/entity_type')->loadByCode('customer')->getId();
        $customerDOBAttributeId = Mage::getModel('eav/entity_attribute')->loadByCode($customerEntityTypeID, 'dob')->getId();
        
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        
        $customer_entity = $resource->getTableName('customer/entity');
        $dobTbl = $customer_entity . '_datetime';
        
        $time = time();
        
        $select = $read->select()->from(
            array (
                'dob' => $dobTbl
            ),
            array (
                'entity_id',
                'value'
            )
        )->join(
            array (
                'customer' => $customer_entity
                ),
            'customer.entity_id=dob.entity_id AND customer.entity_type_id=dob.entity_type_id',
            'store_id'
        )
        ->where('dob.entity_type_id=?', $customerEntityTypeID)
        ->where('dob.attribute_id=?', $customerDOBAttributeId)
        ->where('DATE_FORMAT(dob.value, "%m-%d")=?', date('m-d', $time - ( int ) ($before)));


        $customers = $read->fetchAll($select);
        return $customers;
    }
    
    public function isMailExist($cart_id, $rule_id)
    {
        $collection =Mage::getModel('followupemail/mail')->getCollection();
        $current_date_time = new DateTime(now());
        $current_date_time->modify('-300 days');
        $format = 'Y-m-d H:i:s';
        $last_year = $current_date_time->format($format);
        $collection->getSelect()->where('rule_id = ?', $rule_id)->where('event_info = ?', $cart_id) ->where('send_at < ?', $last_year);
    
        if ($collection->getSize() > 0) {
            return true;
        }

        return false;
    }
}
