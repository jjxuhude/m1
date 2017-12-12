<?php
/**
 * Created by Magenest
 * User: Luu Thanh Thuy
 * Date: 21/09/2015
 * Time: 13:41
 */

class HN_Followupemail_Adminhtml_CartController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Abandoned Cart Management - Follow up email'));

        $this->loadLayout()->_setActiveMenu('followupemail/abandoned_cart');

        $this->_addContent($this->getLayout()->createBlock('followupemail/adminhtml_cart_grid'));

        $this->renderLayout();
    }

    /**
     * Check is allowed access to action
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('followupemail/abandoned_cart');
    }
}
