<?php
class HN_Followupemail_Block_Adminhtml_Track_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('track_Grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('followupemail/track')->getCollection();
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
                'header' => Mage::helper('followupemail')->__('Send time'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'send_at',
                'type' => 'datetime'
            )
        );
        
        $this->addColumn(
            'visit_at',
            array (
                'header' => Mage::helper('followupemail')->__('Visited time'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'visit_at',
                'type' => 'datetime'
            )
        );
        

        $this->addColumn(
            'unique_no',
            array (
                'header' => Mage::helper('followupemail')->__('Unique No'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'unique_no',
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
    }
}
