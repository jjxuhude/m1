<?php
class HN_Followupemail_Adminhtml_CouponController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Coupon management - Follow up email'));
    
        $this->loadLayout()->_setActiveMenu('followupemail/rule');
    
        $this->_addContent($this->getLayout()->createBlock('followupemail/adminhtml_coupon_grid'));
    
        $this->renderLayout();
    }
    public function deleteAction()
    {
        $Ids = $this->getRequest()->getParam('id');
        if (! is_array($Ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($Ids as $id) {
                    $model = Mage::getModel('salesrule/coupon')->load($id);
                    $model->delete();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($Ids)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Check is allowed access to action
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('followupemail/coupon');
    }
}
