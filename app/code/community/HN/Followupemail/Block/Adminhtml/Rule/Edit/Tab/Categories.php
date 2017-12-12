<?php
class HN_Followupemail_Block_Adminhtml_Rule_Edit_Tab_Categories extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
{
    public function getLoadTreeUrl($expanded = null)
    {
        $routePath = 'adminhtml/catalog_product/categoriesJson';
        $routeParams = '';
        $url = Mage::getModel('core/url')->getRouteUrl($routePath, $routeParams);
        return $url;
    }
}
