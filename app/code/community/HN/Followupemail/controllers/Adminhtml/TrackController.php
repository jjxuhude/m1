<?php
class HN_Followupemail_Adminhtml_TrackController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Link Tracking Follow up email'));
    
        $this->loadLayout()->_setActiveMenu('followupemail/rule');
    
        $this->_addContent($this->getLayout()->createBlock('followupemail/adminhtml_track_grid'));
    
        $this->renderLayout();
    }

    /**
     * Check is allowed access to action
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('followupemail/link_tack');
    }
}
