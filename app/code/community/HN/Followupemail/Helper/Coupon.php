<?php
class HN_Followupemail_Helper_Coupon extends Mage_Core_Helper_Abstract
{
    
    /**
     * @param int $qty
     * @param HN_Followupemail_Model_Rule $rule
     */
    public function generateCoupon($qty, $rule)
    {
        $used_coupon = $rule->getCouponActive();
        $length = $rule->getCouponLength();
        $prefix = $rule->getCouponPrefix();
        $rule_id = $rule->getCouponRule();
        $suffix = $rule->getCouponSufix();
        
        $expired_after_day = $rule->getExpiredAfterDay();
        if ($expired_after_day) {
            $date = new DateTime(now());
        
            $date->modify('+'.$expired_after_day.' day');
        //echo $date->format('Y-m-d') . "\n";
            $date->format('yyyy-MM-dd HH:mm:ss');
        } else {
            $date = null;
        }

        if ($used_coupon) {
            $codes = $this->createCoupon($length, $prefix, $qty, $rule_id, $suffix, $date);
            
            return $codes;
        }
    }
    
    public function createCoupon($length, $prefix, $qty, $rule_id, $suffix, $date)
    {
        if (!$length) {
            $length = 10;
        }

        $generator = new HN_Followupemail_Model_Coupon_Massgenerator();
        if ($date) {
            $generator->setToDate($date);
        }

        $data = array(
                'format'=>'alphanum'    ,
                'length'=>$length,
                'prefix'=>$prefix,
                'qty' =>$qty,
                'rule_id'=>     $rule_id,
                'suffix'=>  $suffix,
                'uses_per_coupon'=>     0,
                'uses_per_customer'=>   0,
                'is_fue' =>1
        );
        $generator->setData($data);
        $codes = $generator->generatePool();
        return $codes;
    }
}
