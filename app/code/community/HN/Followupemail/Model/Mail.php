<?php
class HN_Followupemail_Model_Mail extends Mage_Core_Model_Abstract
{
    
    /*
	 * (non-PHPdoc) @see Varien_Object::_construct()
	 */
    public function _construct()
    {
        parent::_construct();
        $this->_init('followupemail/mail');
    }
    /**
     *
     * @param HN_Followupemail_Model_Rule $rule
     * @param Varien_Object $chain
     * @param string $mail_content
     * @return HN_Followupemail_Model_Mail
     */
    public function prepareMail($rule, $chain, $mail_content, $mail_subject, $recipient_name, $recipient_email, $event_id, $is_test = false)
    {
        $interval_string = 'P0Y0M';
        $before_or_after = $chain->getData('time');
        $interval_string .= $chain->getData('day') . 'DT';
        $interval_string .= $chain->getData('hour') . 'H';
        $interval_string .= $chain->getData('min') . 'M0S';
        $data = array ();
        $data ['rule_id'] = $rule->getData('id');
        $data ['event_name'] = $rule->getData('event_name'); // when
        $data ['rule_name'] = $rule->getData('name');
        $data ['bcc'] = $rule->getData('bcc');
        $data ['status'] = 0; // queue
        $data ['store_id'] = Mage::app()->getStore()->getId();
        $data['is_test'] = $is_test?1:0;
        $current_date_time = new DateTime(now());
// 		if (class_exists ( 'DateInterval' )) {
// 			$interval = new DateInterval ( $interval_string );
// 			$current_date_time->add ( $interval );
// 		} else {
            $modify = '+';
        if ($chain->getData('day') == 1) {
            $modify .= '+1 day';
        } elseif ($chain->getData('day') > 1) {
            $modify .= $chain->getData('day') . ' days';
        }
            
            // hour
        if ($chain->getData('hour') == 1) {
            $modify .= '1 hour';
        } elseif ($chain->getData('hour') > 1) {
            $modify .= $chain->getData('hour') . ' hours';
        }
            
            // mins
            $seconds = intval($chain->getData('min')) * 60;
            $modify .= $seconds . ' seconds';
            
        if ($modify != '+') {
            $current_date_time->modify($modify);
        }

        //}
        $format = 'Y-m-d H:i:s';
        $data ['created'] = now();
        $data ['send_at'] = $current_date_time->format($format);
        $data ['recipient_name'] = $recipient_name;
        $data ['recipient_email'] = $recipient_email;
        $data ['sender_name'] = Mage::getStoreConfig('followupemail/general/sender_name');
        $data ['sender_email'] = Mage::getStoreConfig('followupemail/general/sender_email');
        $data ['unique_no'] = Mage::helper('followupemail')->generateNoTrack();
        $data ['hash'] = md5($data ['unique_no']);
        
        $resume_link = Mage::helper('followupemail')->generateRestoreCartLink($data ['hash']);
        $mail_content = strtr(
            $mail_content,
            array (
                '{{hn_unique_no}}' => $data ['unique_no']
            )
        );

        $mail_content = strtr(
            $mail_content,
            array (
                '{{url_resume}}' => $resume_link
            )
        );

        $mail_content = strtr(
            $mail_content,
            array (
                '{{hn_customer_name}}' => $recipient_name
            )
        );

        $mail_content = strtr(
            $mail_content,
            array (
                '{{hn_customer_email}}' => $recipient_email
            )
        );



        $data ['email_content'] = $mail_content;
        $data ['email_subject'] = $mail_subject;
        $data ['event_info'] = $event_id;
        $this->setData($data);
        return $this;
    }
    public function send($recipient_name = null, $recipient_email = null)
    {
        if (! $this->getId()) {
            return;
        }

        if ($recipient_name) {
            $this->setRecipientName($recipient_name);
        }

        if ($recipient_email) {
            $this->setRecipientEmail($recipient_email);
        }

        $senderName = $this->getData('sender_name');
        $senderEmail = $this->getData('sender_email');
        if (! $recipient_name) {
            $recipient_name = $this->getData('recipient_name');
        }

        if (! $recipient_email) {
            $recipient_email = $this->getData('recipient_email');
        }

        $subject = $this->getData('email_subject');
        $content = $this->getData('email_content');
        try {
            if (!$senderEmail) {
                $senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');
            }

            if (!$senderName) {
                $senderName = Mage::getStoreConfig('trans_email/ident_general/name');
            }

            $this->sendEmail($senderName, $senderEmail, $recipient_name, $recipient_email, $subject, $content);
            
            $this->setStatus(1)->save();
        } catch (Exception $e) {
            $this->setStatus(2)->save();
        }
    }
    public function sendEmail($senderName, $senderEmail, $recipient_name, $recipient_email, $subject, $content)
    {
        /**
         *
         * @var $emailTemplate Mage_Core_Model_Email_Template
         */
        $emailTemplate = Mage::getModel('core/email_template');
        $emailTemplate->setSenderName($senderName);
        $emailTemplate->setSenderEmail($senderEmail);
        // $emailTemplate = new Mage_Core_Model_Email_Template();
//		$bcc = array (
//				'0' => 'joe@example.com',
//				'1' => 'doe@example.com'
//		);
//		$emailTemplate->addBcc ( $bcc );
        $emailTemplate->setTemplateSubject($subject);
        $emailTemplate->setTemplateText($content);
        $emailTemplate->send($recipient_email, $recipient_name);
    }
    /**
     *
     * @param string $text
     * @param HN_Followupemail_Model_Rule $rule
     */
    public function preCoupon($text, $rule)
    {
        $pattern = '/{{(coupon\d*)\.code}}/';
        $result = preg_match_all($pattern, $text, $matches, PREG_SET_ORDER);
        $coupon_qty = count($matches);
        $coupon_codes = Mage::helper('followupemail/coupon')->generateCoupon($coupon_qty, $rule);
        
        if (is_array($coupon_codes) && ! empty($coupon_codes)) {
            $replace = array ();
            foreach ($matches as $key => $match) {
                $replace [$match [0]] = $coupon_codes [$key];
            }

            $text = strtr($text, $replace);
            
            // replace the expired days of coupon
            if ($rule->getExpiredAfterDay()) {
                $pattern = '/{{coupon\d*\.expired_day}}/';
                $text = preg_replace($pattern, $rule->getExpiredAfterDay() . ' ' . Mage::helper('followupemail')->__('day'), $text);
            }
        }

        return $text;
    }
    public function preLinkTrack($text, $rule)
    {
    }
}
