<?php
class HN_Followupemail_TrackController extends Mage_Core_Controller_Front_Action
{
    /**
     * http://magenest.com/followupemail/track/restoreCart/hash/tmkfhteifda8999
     */
    public function restoreCartAction()
    {
        $hash = $this->getRequest()->getParam('hash');
        
        $mail = Mage::getModel('followupemail/mail')->load($hash, 'hash');
        /**
         *
         * @var $mail HN_Followupemail_Model_Mail();
         */
        
        if ($mail->getId()) {
            $quoteId = $mail->getEventInfo();
            $quote = Mage::getModel('sales/quote')->load($quoteId);
            
            $rule = Mage::getModel('followupemail/track')->setData(
                array (
                    'event_name' => $mail->getData('event_name'),
                    'rule_id' => $mail->getData('rule_id'),
                    'rule_name' => $mail->getData('rule_name'),
                    'status' => 'visited',
                    'store_id' => $mail->getData('store_id'),
                    'recipient_name' => $mail->getData('recipient_name'),
                    'recipient_email' => $mail->getData('recipient_email'),
                    'unique_no' => $mail->getData('unique_no'),
                    'hash' => $mail->getData('hash'),
                    'visit_at' => now()
                )
            );
            
            $rule->save();
            
            if ($quote->getId()) {
                Mage::getSingleton('checkout/session')->replaceQuote($quote);
                $this->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
            } else {
                Mage::getSingleton('core/session')->addError($this->__('Quote does not exist'));
                    
                $this->_redirect('*/*/index');
            }
        } else {
            Mage::getSingleton('core/session')->addError($this->__('Resume code is not correct'));
            
            $this->_redirect('*/*/index');
        }
    }
}
