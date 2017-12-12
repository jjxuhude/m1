<?php
/**
 * Created by Magenest
 * User: Luu Thanh Thuy
 * Date: 22/09/2015
 * Time: 09:28
 */

class HN_Followupemail_Adminhtml_EmulateController extends Mage_Adminhtml_Controller_Action
{

    public function collectAction()
    {
        try {
            $ab =  new HN_Followupemail_Model_Rule_Condition_Abandonedcart();
            $ab->scheduleCollectAbandonedCart();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The follow up email to reminder abandoned cart are generated'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

            Mage::logException($e);
        }

        $this->_redirect('followupemail/adminhtml_mail/index');
    }

    public function sendAction()
    {
        try {
            $observer = new HN_Followupemail_Model_Observer();
            $observer->scheduledSend();

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The follow up emails  are sent'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('followupemail/adminhtml_mail/index');
    }


    public function sendFUEBirthdayAction()
    {
        try {
            $model = new HN_Followupemail_Model_Rule_Condition_Birthday();
            $model->cron();

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('The follow up emails  are sent'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

      //  $this->_redirect ( 'followupemail/adminhtml_mail/index' );
    }

    /**
     * Check is allowed access to action
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('followupemail/collect_ac');
    }
}
