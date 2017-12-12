<?php
class HN_Followupemail_Adminhtml_RuleController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Follow up email'));
        
        $this->loadLayout()->_setActiveMenu('followupemail/rule');
        
        $this->_addContent($this->getLayout()->createBlock('followupemail/adminhtml_rule_rule'));
        
        $this->renderLayout();
    }
    
    public function newAction()
    {
        $this->_title($this->__('Follow up email'));
        
        $this->loadLayout();
        $this->_setActiveMenu('followupemail/rule');
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Followup email'), Mage::helper('adminhtml')->__('Rules'));
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Followup email'), Mage::helper('adminhtml')->__('Rules'));
    
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
    
        $this->_addContent($this->getLayout()->createBlock('followupemail/adminhtml_rule_edit'))
        ->_addLeft($this->getLayout()->createBlock('followupemail/adminhtml_rule_edit_tabs'));
    
        $this->renderLayout();
    }
    
    public function editAction()
    {
        $this->_title($this->__('Follow up email'));
        
        Mage::register('edit', true);
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('followupemail/rule')->load($id);
        //var_dump(base64_decode($model->getData('store_id')));
        $model->setData('store_id', unserialize(base64_decode($model->getData('store_id'))));
        $model->setData('customer_group', unserialize(base64_decode($model->getData('customer_group'))));
        $model->setData('conditions_serialized', unserialize(base64_decode($model->getData('conditions_serialized'))));
     //  print_r($model->getData('conditions_serialized')) ;
         //store_id
        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::getModel('core/session')->setData('gcedit', true);

            //$_SESSION['gcedit'] = true;
            Mage::register('giftcert_data', $model);
            Mage::register('ruleId', $id);
            $this->loadLayout();
            $this->_setActiveMenu('giftcert/items');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Warehouse Manager'), Mage::helper('adminhtml')->__('Gift cert Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Warehouse Manager'), Mage::helper('adminhtml')->__('Gift cert Manager'));
    
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
    
            $this->_addContent($this->getLayout()->createBlock('followupemail/adminhtml_rule_edit'))
            ->_addLeft($this->getLayout()->createBlock('followupemail/adminhtml_rule_edit_tabs'));
    
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('followupemail')->__('Gift cert does not exist'));
            $this->_redirect('*/*/');
        }
    }
    
    public function gridAction()
    {
        $this->loadLayout()->_setActiveMenu('catalog/product');
        $this->_addContent($this->getLayout()->createBlock('followupemail/adminhtml_rule_grid'));
        $this->renderLayout();
    }
    
    /**
     */
    public function changeStatusAction()
    {
        $ticketIds = ( array ) $this->getRequest()->getParam('id');
        
        $status = $this->getRequest()->getParam('status');
        if (empty($ticketIds) || $status == null) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
            $this->_redirect('*/*/index');
            return;
        }

        try {
            foreach ($ticketIds as $id) {
                $ticket = Mage::getModel('followupemail/rule')->load($id);
                $ticket->setData('payment_status', $status);
                $ticket->save();
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully updated', count($ticketIds)));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }
    public function redeemAction()
    {
        $ticketIds = ( array ) $this->getRequest()->getParam('id');
        
        $status = $this->getRequest()->getParam('status');
        if (empty($ticketIds) || $status == null) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
            $this->_redirect('*/*/index');
            return;
        }

        try {
            foreach ($ticketIds as $id) {
                $ticket = Mage::getModel('ticket/ticket')->load($id);
                $ticket->setData('redeem_status', $status);
                $ticket->save();
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully updated', count($ticketIds)));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }
    /**
     */
    public function deleteAction()
    {
        $pinIds = $this->getRequest()->getParam('id');
        if (! is_array($pinIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($pinIds as $id) {
                    $pin_model = Mage::getModel('followupemail/rule')->load($id);
                    $pin_model->delete();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($pinIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
    
    public function saveAction()
    {
        $params = $this->getRequest()->getParams();
        $rule_data = array();
        $conditon = base64_encode(serialize($params['condition']));
        
        if (isset($params['store_id']) && !empty($params['store_id'])) {
            foreach ($params['store_id'] as $id) {
            }
        }

        /** processing chain email data*/
        $chains =   $params['chain'];
        if (empty($chains)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('followupemail')->__('The email chains need to be fill out'));
            $this->_redirect('*/*/');
        }

        $rule_data['chain'] = '';
        foreach ($chains as $chain) {
            if (!isset($chain['day']) || !$chain['day']) {
                $chain['day'] =0;
            }

            if ($chain['time'] !=''  && $chain['hour']>=0 && $chain['min']>=0 && $chain['template']) {
                $rule_data['chain']  .=$chain['time'] .';' .$chain['day'] . ';' . $chain['hour'] .';'.
                                   $chain['min'] .';' .$chain['template'].':';
            }
        }
         
        $rule_data['name'] = $params['name'];
        $rule_data['event'] = $params['event'];
        $rule_data['event_name'] = $params['event_name'];
        if ($rule_data['event'] =='wishlist_item_discount') {
            $rule_data['event_name'] = __('Items in wishlist is sale off');
        }

        $rule_data['description'] = $params['description'];
        $rule_data['is_active'] = $params['is_active'];
        $rule_data['store_id'] = base64_encode(serialize($params['store_id']));
        $rule_data['customer_group'] = base64_encode(serialize($params['customer_group']));
        $rule_data['from_date'] = $params['from_date'];
        $rule_data['to_date'] = $params['to_date'];
        $rule_data['conditions_serialized'] = $conditon;
        $rule_data['subscriber_only'] = $params['subscriber_only'];
        $rule_data['category_ids'] = $params['category_ids'];
        $rule_data['coupon_active'] = $params['coupon_active'];
        $rule_data['coupon_rule'] = isset($params['coupon_rule'])? $params['coupon_rule'] : '';
        $rule_data['coupon_prefix'] = $params['coupon_prefix'];
        $rule_data['coupon_sufix'] = $params['coupon_sufix'];
        $rule_data['coupon_length'] = $params['coupon_length'];
        $rule_data['expired_after_day'] = $params['expired_after_day'];
        $rule_data['bcc'] = $params['bcc'];
// 		$rule_data[''] = $params[''];
// 		$rule_data[''] = $params[''];
        if (isset($params['id']) && $params['id']) {
            $rule_data['id']= $params['id'];
        }
     
        //var_dump($conditon);
        $params['condition'] = $conditon;
        
        Mage::dispatchEvent('before_save_rule', array('data' => $rule_data));
        
        Mage::getModel('followupemail/rule')->setData($rule_data)->save();
        $this->_redirect('*/*/index');
    }
    
    public function save()
    {
        $params = $this->getRequest()->getParams();
        $rule_data = array();
        
        $conditon = base64_encode(serialize($params['condition']));
        
        if (isset($params['store_id']) && !empty($params['store_id'])) {
            foreach ($params['store_id'] as $id) {
            }
        }

        /** processing chain email data*/
        $chains =   $params['chain'];
        if (empty($chains)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('followupemail')->__('The email chains need to be fill out'));
            $this->_redirect('*/*/');
        }

        $rule_data['chain'] = '';
        foreach ($chains as $chain) {
            if (!isset($chain['day']) ||  !$chain['day']) {
                $chain['day'] = 0;
            }

            if ($chain['time'] !=''  && $chain['hour']>=0 && $chain['min']>=0 && $chain['template']) {
                $rule_data['chain']  .=$chain['time'] .';' .$chain['day'] . ';' . $chain['hour'] .';'.
                $chain['min'] .';' .$chain['template'].':';
            }
        }
        
        $rule_data['name'] = $params['name'];
        $rule_data['event'] = $params['event'];
        $rule_data['event_name'] = $params['event_name'];

        //fix the wishlist
        if ($rule_data['event'] =='wishlist_item_discount') {
            $rule_data['event_name'] = __('Items in wishlist is sale off');
        }

        $rule_data['description'] = $params['description'];
        $rule_data['is_active'] = $params['is_active'];
        $rule_data['store_id'] = base64_encode(serialize($params['store_id']));
        $rule_data['customer_group'] = base64_encode(serialize($params['customer_group']));
        $rule_data['from_date'] = $params['from_date'];
        $rule_data['to_date'] = $params['to_date'];
        $rule_data['conditions_serialized'] = $conditon;
        $rule_data['subscriber_only'] = $params['subscriber_only'];
        $rule_data['category_ids'] = $params['category_ids'];
        $rule_data['coupon_active'] = $params['coupon_active'];
        $rule_data['coupon_rule'] = isset($params['coupon_rule'])? $params['coupon_rule'] : '';
        $rule_data['coupon_prefix'] = $params['coupon_prefix'];
        $rule_data['coupon_sufix'] = $params['coupon_sufix'];
        $rule_data['coupon_length'] = $params['coupon_length'];
        $rule_data['expired_after_day'] = $params['expired_after_day'];
        $rule_data['bcc'] = $params['bcc'];
        // 		$rule_data[''] = $params[''];
        // 		$rule_data[''] = $params[''];
        if (isset($params['id']) && $params['id']) {
            $rule_data['id']= $params['id'];
        }
        
        //var_dump($conditon);
        $params['condition'] = $conditon;
        
        Mage::dispatchEvent('before_save_rule', array('data' => $rule_data));
        
        $rule_id = Mage::getModel('followupemail/rule')->setData($rule_data)->save()->getId();
        
        return $rule_id;
    }
    
    /**
     *
     */
    public function saveandsendtestmailAction()
    {
        $rule_id = $this->save();
        
        if (!$rule_id) {
            return;
        }

        $rule = Mage::getModel('followupemail/rule') ->load($rule_id);
        
        $event = $this->getRequest()->getParam('event');
        
        $test_recipient = $this->getRequest()->getParam('test_recipient');
        
        $subject  =$test_recipient;
        
        $pattern = '/(.*?)<(.*?)>/';
        $result = preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);
        if ($result && $matches[0] && $matches[0][1] && $matches[0][2]) {
            $name = $matches[0][1];
            $email = $matches[0][2];
        } else {
            $name = substr($subject, 0, strpos($subject, '<'));
            $email = substr($subject, strpos($subject, '<'), strpos($subject, '>'));
        }

        switch ($event) {
            case 'customer_register_success':
            case 'customer_save_before':
            case 'customer_login':
            case 'customer_logout':
            case 'newsletter_subscriber_save_commit_after':
            case 'customer_birthday':
                 {
                
                $customer_id = $this->getRequest()->getParam('test_customer_id');
                
                if (!$customer_id || $customer_id == '') {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('You do not specify customer id for testing'));
                    $this->_redirect('*/*/index');
                    return;
                }
                
                $customer = Mage::getModel('customer/customer')->load($customer_id);
                
                /* @var $model HN_Followupemail_Model_Rule_Condition_Customer */
                $model = Mage::getModel('followupemail/rule_condition_customer');
                
                try {
                    $mail_collection = $model->processEachRule($rule, $customer, false, true);
                    
                    if (count($mail_collection) > 0) {
                        foreach ($mail_collection as $mail) {
                            $mail->send($name, $email);
                        }

                        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The test mail is sent'));
                    }
                    
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The rule is saved'));
                } catch (Exception $exception) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__($exception->getMessage()));
                    $this->_redirect('*/*/index');
                    return;
                }

                break;
            }
            ;
            break;
            case 'sales_order_save_after' :{
                $incrementId = $this->getRequest()->getParam('test_order_no');
                
                if (!$incrementId || $incrementId == '') {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('You do not specify order id for testing'));
                    $this->_redirect('*/*/index');
                    return;
                }
                
                
                $order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
                //$order = new Mage_Sales_Model_Order();
                $model = Mage::getModel('followupemail/rule_condition_order');
                
                try {
                    $mail_collection = $model->processEachRule($rule, $order, false, true);
                    if (count($mail_collection) > 0) {
                        foreach ($mail_collection as $mail) {
                            $mail->send($name, $email);
                        }

                        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The test mail is sent'));
                    }
                        
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The rule is saved'));
                } catch (Exception $exception) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__($exception->getMessage()));
                    $this->_redirect('*/*/index');
                    return;
                }
                
                break;
            }
            
            case 'wishlist_share' : {
                
                $id = $this->getRequest() ->getParam('test_wishlist_id');
                
                if (!$id || $id == '') {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please specify wishlist id for testing abandoned cart reminder'));
                    $this->_redirect('*/*/index');
                    return;
                }
                
                $wishlist = Mage::getModel('wishlist/wishlist')->load($id);
                $model = Mage::getModel('followupemail/rule_condition_wishlist');
                
                try {
                    $mail_collection = $model->processEachRule($rule, $wishlist, false, true);
                    if (count($mail_collection) > 0) {
                        foreach ($mail_collection as $mail) {
                            $mail->send($name, $email);
                        }

                        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The test mail is sent'));
                    }
                
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The rule is saved'));
                } catch (Exception $exception) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__($exception->getMessage()));
                    $this->_redirect('*/*/index');
                    return;
                }
                
                break;
            }
            case 'abandoned_cart' : {
                $id = $this->getRequest() ->getParam('test_cart_id');
                
                if (!$id || $id == '') {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please specify quote id for testing abandoned cart reminder'));
                    $this->_redirect('*/*/index');
                    return;
                }
               
                /* @var $quote Mage_Sales_Model_Quote */
                $quote = Mage::getModel('sales/quote')->loadByIdWithoutStore($id);
                if (!$quote->getId()) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Quote does not exist'));
                    //$this->_redirect ( '*/*/index' );
                    //return;
                }

                $cart_id = $quote->getId();
                $customer_name = $quote->getFirstName();
                $model = Mage::getModel('followupemail/rule_condition_abandonedcart');
                
                try {
                    $mail_collection = $model->processEachRule($rule, $quote, false, true);
                    if (count($mail_collection) > 0) {
                        foreach ($mail_collection as $mail) {
                            $mail->send($name, $email);
                        }

                        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The test mail is sent'));
                    }
                
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The rule is saved'));
                } catch (Exception $exception) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__($exception->getMessage()));
                    $this->_redirect('*/*/index');
                    return;
                }

                break;
            }
            default:
                ;
                break;
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Check is allowed access to action
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('followupemail/send_fue');
    }
}
