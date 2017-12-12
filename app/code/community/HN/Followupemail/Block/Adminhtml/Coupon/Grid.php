<?php
class HN_Followupemail_Block_Adminhtml_Coupon_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('couponGrid');
        $this->setDefaultSort('id');
        $this->setSaveParametersInSession(true);
    }
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('salesrule/coupon')->getCollection()->addFilter('is_fue', 1);
        ;
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {
        $this->addColumn(
            'coupon_id',
            array (
                'header' => Mage::helper('followupemail')->__('ID'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'coupon_id'
            )
        );
        
        $this->addColumn(
            'rule_id',
            array (
                'header' => Mage::helper('followupemail')->__('Rule id'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'rule_id'
            )
        );
        
        $this->addColumn(
            'code',
            array (
                'header' => Mage::helper('followupemail')->__('Code'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'code'
            )
        );
        

        $this->addColumn(
            'expiration_date',
            array (
                'header' => Mage::helper('followupemail')->__('Expiration date'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'expiration_date',
                'type' =>'datetime',
            )
        );
        
        $this->addColumn(
            'created_at',
            array (
                'header' => Mage::helper('followupemail')->__('Created at'),
                'align' => 'right',
                'width' => '50px',
                'index' => 'created_at',
                'type' =>'datetime',
                
            )
        );
        
        
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
                'url' => $this->getUrl('*/adminhtml_coupon/delete')
            )
        );
    }
}
