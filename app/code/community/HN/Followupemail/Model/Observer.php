<?php
class HN_Followupemail_Model_Observer
{
    public function addCouponType($observer)
    {
        $trans = $observer->getData('transport');
        $coupon_type = $trans->getData('coupon_types');
        $coupon_type [4] = Mage::helper('followupemail')->__('FUE Auto generated');
        $trans->setData('coupon_types', $coupon_type);
        return $this;
    }


    /**
     * cron job
     */
    public function scheduledSend()
    {
        //cancel the abandoned cart reminders since the customer already bought items
        $cancelledMail = Mage::getResourceModel('followupemail/mail_collection')->getAbandonedCartMailNeedCancel();

        if ($cancelledMail->getSize() > 0) {
            foreach ($cancelledMail as $item) {
                if ($item->getStatus() == 0 || $item->getStatus() == 2) {
                    $item->setStatus(3)->save(); //cancel it
                }
            }
        }

        $mails = Mage::getResourceModel('followupemail/mail_collection')->getAvailableMailToSend();
        
        if (count($mails) > 0) {
            foreach ($mails as $mail) {
                $mailBean = Mage::getModel('followupemail/mail')->load($mail['id']);
                try {
                    $mailBean->send();
                } catch (Exception $e) {
                    Mage::logException($e);
                    continue;
                }
            }
        }
    }
}
