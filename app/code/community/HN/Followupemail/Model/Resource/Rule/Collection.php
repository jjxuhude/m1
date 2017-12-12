<?php


class HN_Followupemail_Model_Resource_Rule_Collection extends Mage_SalesRule_Model_Resource_Rule_Collection
{
    public function setValidationFilter($websiteId, $customerGroupId, $couponCode = '', $now = null)
    {
        if (!$this->getFlag('validation_filter')) {
            
            /* We need to overwrite joinLeft if coupon is applied */
            $this->getSelect()->reset();
            $this->getSelect()->from(array('main_table' => $this->getMainTable()));
            
            $this->addWebsiteGroupDateFilter($websiteId, $customerGroupId, $now);
            $select = $this->getSelect();
            
            $connection = $this->getConnection();
            if (strlen($couponCode)) {
                $select->joinLeft(
                    array('rule_coupons' => $this->getTable('salesrule/coupon')),
                    $connection->quoteInto(
                        'main_table.rule_id = rule_coupons.rule_id AND main_table.coupon_type != ?',
                        Mage_SalesRule_Model_Rule::COUPON_TYPE_NO_COUPON
                        ),
                    array('code')
                    );
                
                $noCouponCondition = $connection->quoteInto(
                    'main_table.coupon_type = ? ',
                    Mage_SalesRule_Model_Rule::COUPON_TYPE_NO_COUPON
                    );
                
                $orWhereConditions = array(
                    $connection->quoteInto(
                        '(main_table.coupon_type = ? AND rule_coupons.type = 0)',
                        Mage_SalesRule_Model_Rule::COUPON_TYPE_AUTO
                        ),
                    $connection->quoteInto(
                        '(main_table.coupon_type = ? AND main_table.use_auto_generation = 1 AND rule_coupons.type = 1)',
                        Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC
                        ),
                    $connection->quoteInto(
                        '(main_table.coupon_type = ? AND main_table.use_auto_generation = 0 AND rule_coupons.type = 0)',
                        Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC
                        ),
                    $connection->quoteInto(
                        '(main_table.coupon_type = 4 AND main_table.use_auto_generation = 0 AND rule_coupons.is_fue = 1)'
                        )
                );
                $orWhereCondition = implode(' OR ', $orWhereConditions);
                $select->where(
                    $noCouponCondition . ' OR ((' . $orWhereCondition . ') AND rule_coupons.code = ?)', $couponCode
                    );
            } else {
                $this->addFieldToFilter('main_table.coupon_type', Mage_SalesRule_Model_Rule::COUPON_TYPE_NO_COUPON);
            }
            $this->setOrder('sort_order', self::SORT_ORDER_ASC);
            $this->setFlag('validation_filter', true);
        }
        
        return $this;
    }
}

