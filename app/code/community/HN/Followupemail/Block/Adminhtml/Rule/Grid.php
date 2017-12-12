<?php
class HN_Followupemail_Block_Adminhtml_Rule_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ticketGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('followupemail/rule')->getCollection();
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
            'name',
            array (
                'header' => Mage::helper('followupemail')->__('Name'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'name'
            )
        );
        
        $this->addColumn(
            'event_name',
            array (
                'header' => Mage::helper('followupemail')->__('Trigger when'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'event_name'
            )
        );
        

        $this->addColumn(
            'is_active',
            array (
                'header' => Mage::helper('followupemail')->__('Enable'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'is_active',
                'type' =>'options',
                'options' => array(
                            0 => Mage::helper('followupemail')->__('Disable'),
                        1 => Mage::helper('followupemail')->__('Enable')
            
            )
            )
        );
        $this->addColumn(
            'from_date',
            array (
                'header' => Mage::helper('followupemail')->__('From date'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'from_date',
                'type' =>'datetime',
                
            )
        );
        $this->addColumn(
            'to_date',
            array (
                'header' => Mage::helper('followupemail')->__('To date'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'to_date',
                'type' =>'datetime',
        
            )
        );
        
        
// 		$this->addColumn ( 'product_name', array (
// 				'header' => Mage::helper ( 'ticket' )->__ ( 'Product Name' ),
// 				'align' => 'right',
// 				'width' => '50px',
// 				'index' => 'product_name' 
// 		) );
        
// 		$this->addColumn ( 'type', array (
// 				'header' => Mage::helper ( 'ticket' )->__ ( 'Type' ),
// 				'align' => 'right',
// 				'width' => '50px',
// 				'index' => 'type' 
// 		) );
        
// 		$this->addColumn ( 'customer_name', array (
// 				'header' => Mage::helper ( 'ticket' )->__ ( 'Customer name' ),
// 				'align' => 'right',
// 				'width' => '50px',
// 				'index' => 'customer_name' 
// 		) );
        
// 		$this->addColumn ( 'customer_email', array (
// 				'header' => Mage::helper ( 'ticket' )->__ ( 'Customer email' ),
// 				'align' => 'right',
// 				'width' => '50px',
// 				'index' => 'customer_email' 
// 		) );
        
// 		$this->addColumn ( 'order_increment_id', array (
// 				'header' => Mage::helper ( 'ticket' )->__ ( 'Order id' ),
// 				'align' => 'right',
// 				'width' => '50px',
// 				'index' => 'order_increment_id' 
// 		) );
        
// 		$this->addColumn ( 'code', array (
// 				'header' => Mage::helper ( 'ticket' )->__ ( 'Code' ),
// 				'align' => 'right',
// 				'width' => '50px',
// 				'index' => 'code' 
// 		) );
        
// 		$options = array (
// 				0 => Mage::helper ( 'ticket' )->__ ( 'Redeem' ),
// 				1 => Mage::helper ( 'ticket' )->__ ( 'Redeemed' ) 
// 		);
        
// 		$this->addColumn ( 'redeem_status', array (
// 				'header' => Mage::helper ( 'ticket' )->__ ( 'Redeem status' ),
// 				'align' => 'left',
// 				'width' => '80px',
// 				'index' => 'redeem_status',
// 				'type' => 'options',
// 				'options' => $options 
// 		) );
        
// 		$options = array (
// 				HN_Ticket_Model_Ticket::STATUS_PENDING => Mage::helper ( 'ticket' )->__ ( 'Pending' ),
// 				HN_Ticket_Model_Ticket::STATUS_SHIPPED => Mage::helper ( 'ticket' )->__ ( 'Shipped' ),
// 				HN_Ticket_Model_Ticket::STATUS_INVOICED => Mage::helper ( 'ticket' )->__ ( 'Invoiced' ),
// 				HN_Ticket_Model_Ticket::STATUS_BACKORDERED => Mage::helper ( 'ticket' )->__ ( 'Backordered' ),
// 				HN_Ticket_Model_Ticket::STATUS_CANCELED => Mage::helper ( 'ticket' )->__ ( 'Canceled' ),
// 				HN_Ticket_Model_Ticket::STATUS_PARTIAL => Mage::helper ( 'ticket' )->__ ( 'Partial' ),
// 				HN_Ticket_Model_Ticket::STATUS_MIXED => Mage::helper ( 'ticket' )->__ ( 'Mixed' ),
// 				HN_Ticket_Model_Ticket::STATUS_REFUNDED => Mage::helper ( 'ticket' )->__ ( 'Refunded' ),
// 				HN_Ticket_Model_Ticket::STATUS_RETURNED => Mage::helper ( 'ticket' )->__ ( 'Returned' ) 
// 		);
        
// 		$this->addColumn ( 'payment_status', array (
// 				'header' => Mage::helper ( 'ticket' )->__ ( 'Payment status' ),
// 				'align' => 'left',
// 				'width' => '80px',
// 				'index' => 'payment_status',
// 				'type' => 'options',
// 				'options' => $options 
// 		) );
        
        $this->addExportType('*/*/exportCsv', Mage::helper('followupemail')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('followupemail')->__('Excel XML'));
        return parent::_prepareColumns();
    }
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            array (
                'id' => $row->getId()
            )
        );
    }
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');
        $this->getMassactionBlock()->setUseSelectAll(true);
    
    
        $this->getMassactionBlock()->addItem(
            'delete',
            array (
                'label' => Mage::helper('followupemail')->__('Delete'),
                'url' => $this->getUrl('*/adminhtml_rule/delete')
            )
        );
    }
}
