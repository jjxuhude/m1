<?php
/**
 * Created by Magenest
 * User: Luu Thanh Thuy
 * Date: 21/09/2015
 * Time: 13:46
 */

class HN_Followupemail_Block_Adminhtml_Cart_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('cartGrid');
        $this->setDefaultSort('id');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $ab = new HN_Followupemail_Model_Rule_Condition_Abandonedcart();

        $collection = $ab->_collectAbandonedCarts();

        if ($collection) {
            $this->setCollection($collection);
            return parent::_prepareCollection();
        }
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
            'header' => Mage::helper('followupemail')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'entity_id'
            )
        );

        $this->addColumn(
            'created_at',
            array(
            'header' => Mage::helper('followupemail')->__('Created at'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'created_at'
            )
        );

        $this->addColumn(
            'updated_at',
            array(
            'header' => Mage::helper('followupemail')->__('Updated at'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'updated_at'
            )
        );
    }
}
