<?php
class HN_Followupemail_Helper_Config extends Mage_Core_Helper_Abstract
{
    protected static $_events;
    public function getActiveEvents($store = null)
    {
        $events = array();
        $config = Mage::getStoreConfig('followupemailevent', $store);
        foreach ($config as $code => $eventConfig) {
            if (Mage::getStoreConfigFlag('followupemailevent/'.$code.'/active', $store)) {
                $eventModel = $this->_getEvent($code, $eventConfig, $store);
                if ($eventModel) {
                    $events[$code] = $eventModel;
                }
            }
        }

        return $events;
    }
    protected function _getEvent($code, $config, $store = null)
    {
        if (!isset($config['model'])) {
            return false;
        }

        $modelName = $config['model'];
    
        /**
         * Added protection from not existing models usage.
         * Related with module uninstall process
         */
        try {
            $event = Mage::getModel($modelName);
        } catch (Exception $e) {
            Mage::logException($e);
            return false;
        }

        //$event->setId($code)->setStore($store);
        self::$_events[$code] = $event;
        return self::$_events[$code];
    }
}
