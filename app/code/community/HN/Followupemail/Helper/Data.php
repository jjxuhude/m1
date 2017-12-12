<?php
/**
 *
 * @category   Hungnam Event Ticket
 * @package    Hungnamecommerce solutions
 * @author     Luu Thanh Thuy, <luuthuy205@gmail.com>
 */
class HN_Followupemail_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Hash Gen
     *
     * @author Kyle Coots
     * @version 1.0
     *          Allow you to create a unique hash with a maximum value of 32.
     *          Hash Gen uses phps substr, md5, uniqid, and rand to generate a unique
     *          id or hash and allow you to have some added functionality.
     *
     * @see subtr()
     * @see md5()
     * @see uniqid()
     * @see rand() You can also supply a hash to be prefixed or appened
     *      to the hash. hash[optional] is by default appened to the hash
     *      unless the param prefix[optional] is set to prefix[true].
     *
     * @param
     *          start[optional]
     * @param
     *          end[optional]
     * @param
     *          hash[optional]
     * @param
     *          prefix bool[optional]
     *
     * @return string a unique string max[32] character
     */
    function hash_gen($start = null, $end = 0, $hash = false, $prefix = false)
    {
        
        // start IS set NO hash
        if (isset($start, $end) && ($hash == false)) {
            $md_hash = substr(md5(uniqid(rand(), true)), $start, $end);
            $new_hash = $md_hash;
        } else { // start IS set WITH hash NOT prefixing
            if (isset($start, $end) && ($hash != false) && ($prefix == false)) {
                $md_hash = substr(md5(uniqid(rand(), true)), $start, $end);
                $new_hash = $md_hash . $hash;
            } else { // start NOT set WITH hash NOT prefixing
                if (! isset($start, $end) && ($hash != false) && ($prefix == false)) {
                    $md_hash = md5(uniqid(rand(), true));
                    $new_hash = $md_hash . $hash;
                } else { // start IS set WITH hash IS prefixing
                    if (isset($start, $end) && ($hash != false) && ($prefix == true)) {
                        $md_hash = substr(md5(uniqid(rand(), true)), $start, $end);
                        $new_hash = $hash . $md_hash;
                    } else {      // start NOT set WITH hash IS prefixing
                        if (! isset($start, $end) && ($hash != false) && ($prefix == true)) {
                            $md_hash = md5(uniqid(rand(), true));
                            $new_hash = $hash . $md_hash;
                        } else {
                            $new_hash = md5(uniqid(rand(), true));
                        }
                    }
                }
            }
        }
        
        return $new_hash;
    }
    public function generateNoTrack()
    {
        $model = 'eav/entity_increment_numeric';
        $incrementInstance = Mage::getModel($model)->setPrefix('1')->setPadLength(10)->setPadChar('0')->setLastId($this->getIncrementLastId());

        
        
        /**
         * do read lock on eav/entity_store to solve potential timing issues
         * (most probably already done by beginTransaction of entity save)
         */
        $incrementId = $incrementInstance->getNextId();
        return $incrementId;
    }
    
    /**
     * @param string $hash
     * @return string
     */
    public function generateRestoreCartLink($hash)
    {
        $route = 'followupemail/track/restoreCart';
        $params = array('hash' =>$hash);
        return Mage::getUrl($route, $params);
    }
    public function getIncrementLastId()
    {
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $res = Mage::getSingleton('core/resource')->getConnection('core_read')->select();
        $tableName = Mage::getResourceModel('followupemail/mail_collection')->getMainTable();//;Mage::getSingleton ( 'core/resource' )->getTable ( 'catalog / product' );
        $res->from(
            $tableName,
            array (
                'MAX(`unique_no`) as max'
            )
        );
        $max=$db->fetchAll($res);
        return $max[0]['max'];
    }
    
    public function generateCoupon()
    {
            $generator = new Mage_SalesRule_Model_Coupon_Massgenerator();
            $data = array(
                    'format'=>'alphanum'    ,
                    'length'=>10,
                    'prefix'=>'FUE',
                    'qty' =>12,
                    'length'=>  12,
        
                    'rule_id'=>     1,
                    'suffix'=>  1,
                    'uses_per_coupon'=>     0,
                    'uses_per_customer'=>   0
            );
            $generator->setData($data);
            $generator->generatePool();
            $generated = $generator->getGeneratedCount();
            return $generated;
    }
}
