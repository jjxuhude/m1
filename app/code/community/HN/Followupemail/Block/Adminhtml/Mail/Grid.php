<?php
class HN_Followupemail_Block_Adminhtml_Mail_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('mail_queue_Grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('followupemail/mail')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            array (
                'header' => Mage::helper('followupemail')->__('ID'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'id'
            )
        );
        
        $this->addColumn(
            'status',
            array (
                'header' => Mage::helper('followupemail')->__('Status'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'status',
                'type' => 'options',
                'options' => Mage::getSingleton('followupemail/system_config_source_mailqueuestatus')->toArray()
            )
        );
        
        $this->addColumn(
            'created',
            array (
                'header' => Mage::helper('followupemail')->__('Created time'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'created',
                'type' => 'datetime'
            )
        );
        
        $this->addColumn(
            'send_at',
            array (
                'header' => Mage::helper('followupemail')->__('Scheduled send time'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'send_at',
                'type' => 'datetime'
            )
        );
        
        $this->addColumn(
            'event_name',
            array (
                'header' => Mage::helper('followupemail')->__('Event'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'event_name'
            )
        );
        $this->addColumn(
            'rule_name',
            array (
                'header' => Mage::helper('followupemail')->__('Rule'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'rule_name'
            )
        );
        $this->addColumn(
            'recipient_name',
            array (
                'header' => Mage::helper('followupemail')->__('Recipient name'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'recipient_name'
            )
        );
        $this->addColumn(
            'recipient_email',
            array (
                'header' => Mage::helper('followupemail')->__('Recipient email'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'recipient_email'
            )
        );
        
        $this->addColumn(
            'email_content',
            array(
                        'header'    => Mage::helper('followupemail')->__('Mail content'),
                        'width'     => '50px',
                        'type'      => 'action',
                        'getter'     => 'getId',
                        'actions'   => array(
                                array(
                                        'caption' => Mage::helper('followupemail')->__('View/Edit Mail'),
                                        'url'     => array('base'=>'*/adminhtml_mail/edit'),
                                        'field'   => 'id'
                                )
                        ),
                        'filter'    => false,
                        'sortable'  => false,
                        'index'     => 'email_content',
                        'is_system' => true,
                )
        );
    }
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');
        $this->getMassactionBlock()->setUseSelectAll(true);
        
        $this->getMassactionBlock()->addItem(
            'send',
            array (
                'label' => Mage::helper('followupemail')->__('Send now'),
                'url' => $this->getUrl('*/adminhtml_mail/send')
            )
        );
        
        $this->getMassactionBlock()->addItem(
            'cancel',
            array (
                'label' => Mage::helper('followupemail')->__('Cancel'),
                'url' => $this->getUrl('*/adminhtml_mail/cancel')
            )
        );
        
        $this->getMassactionBlock()->addItem(
            'delete',
            array (
                'label' => Mage::helper('followupemail')->__('Delete'),
                'url' => $this->getUrl('*/adminhtml_mail/delete')
            )
        );
    }
}
