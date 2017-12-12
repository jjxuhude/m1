<?php
class HN_Followupemail_Model_System_Config_Source_Salesrule
{
    public function toOptionArray()
    {
        $options_rule = array ();
        $coupons = Mage::getModel('salesrule/rule')->getCollection()->addFilter('coupon_type', 2);
        
        foreach ($coupons as $coupon) {
            $options_rule [] = array (
                    'value' =>$coupon->getId(),
                    'label' => $coupon->getName()
            );
            return $options_rule;
        }
    }
    public function toArray()
    {
        $options_rule = array ();
        $coupons = Mage::getModel('salesrule/rule')->getCollection()->addFilter('coupon_type', 2);
        
        foreach ($coupons as $coupon) {
            $options_rule [] = array (
                    $coupon->getId() => $coupon->getName()
            );
            return $options_rule;
        }
    }
}
