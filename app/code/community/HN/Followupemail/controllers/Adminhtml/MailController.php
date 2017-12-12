<?php
class HN_Followupemail_Adminhtml_MailController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Mail inFollow up email'));
        
        $this->loadLayout()->_setActiveMenu('followupemail/rule');
        
        $this->_addContent($this->getLayout()->createBlock('followupemail/adminhtml_mail_grid'));
        
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
                    $model = Mage::getModel('followupemail/mail')->load($id);
                    $model->delete();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($Ids)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
    public function sendAction()
    {
        $Ids = $this->getRequest()->getParam('id');
        if (! is_array($Ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($Ids as $id) {
                    $model = Mage::getModel('followupemail/mail')->load($id);
                    $model->send();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully send', count($Ids)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
    public function cancelAction()
    {
        $Ids = $this->getRequest()->getParam('id');
        if (! is_array($Ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($Ids as $id) {
                    $model = Mage::getModel('followupemail/mail')->load($id);
                    $model->setStatus(3)->save();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully send', count($Ids)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('followupemail/mail')->load($id);
        Mage::register('data', $model);
        $this->_title($this->__('Edit Mail inFollow up email'));
        $this->loadLayout();
        
        $this->_addContent($this->getLayout()->createBlock('followupemail/adminhtml_mail_edit'));
        
        $this->renderLayout();
    }
    
    public function saveAction()
    {
        $params = $this->getRequest()->getParams();
        
        if ($params['id']) {
            $model = Mage::getModel('followupemail/mail')->load($params['id']);
          
            if ($params['email_subject'] && $params['email_content']) {
                $model->setEmailSubject($params['email_subject'])->setEmailContent($params['email_content']) ->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Mail is saved successfully'));
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
        return Mage::getSingleton('admin/session')->isAllowed('followupemail/send_fue');
    }
}
