 cp -r /var/www/magento/app/code/local/HN/Followupemail/* /media/0721154BA31B3890/zen/followup.email/app/code/local/HN/Followupemail/
 cp -r  /var/www/magento/app/design/adminhtml/default/default/template/hn/followupemail/* /media/0721154BA31B3890/zen/followup.email/app/design/adminhtml/default/default/template/hn/followupemail/
 cp /var/www/magento/app/design/adminhtml/default/default/layout/hn_followupemail.xml /media/0721154BA31B3890/zen/followup.email/app/design/adminhtml/default/default/layout
 cp -r /var/www/magento/js/adminhtml/hn/fue/* /media/0721154BA31B3890/zen/followup.email/js/adminhtml/hn/fue

cp /var/www/magento/app/design/frontend/default/default/layout/hn_followupemail.xml /media/0721154BA31B3890/zen/followup.email/app/design/frontend/default/default/layout/
cp -r /var/www/magento/app/design/frontend/default/default/template/email/* /media/0721154BA31B3890/zen/followup.email//app/design/frontend/default/default/template/email


How to add category tree 
in class Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs 
<code>  $this->addTab('categories', array(
                'label'     => Mage::helper('catalog')->__('Categories'),
                'url'       => $this->getUrl('*/*/categories', array('_current' => true)),
                'class'     => 'ajax',
            ));
</code>

In the class Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
Mage_Core_Model_Email_Template();
Debug stack 
<code>
http://127.0.0.1/magento/index.php/followupemail/test/mailcontent (suspended)	
	layoutDirective(): /mag/app/code/core/Mage/Core/Model/Email/Template/Filter.php at line 204	
	filter(): /mag/lib/Varien/Filter/Template.php at line 134	
	filter(): /mag/app/code/core/Mage/Core/Model/Email/Template/Filter.php at line 504	
	getProcessedTemplate(): /mag/app/code/core/Mage/Core/Model/Email/Template.php at line 345	
	mailcontentAction(): /mag/app/code/local/HN/Followupemail/controllers/TestController.php at line 92	
	dispatch(): /mag/app/code/core/Mage/Core/Controller/Varien/Action.php at line 418	
	match(): /mag/app/code/core/Mage/Core/Controller/Varien/Router/Standard.php at line 250	
	dispatch(): /mag/app/code/core/Mage/Core/Controller/Varien/Front.php at line 172	
	run(): /mag/app/code/core/Mage/Core/Model/App.php at line 354	
	run(): /mag/app/Mage.php at line 683	
	/mag/index.php at line 88	
</code>	

http://127.0.0.1/magento/index.php/admin/promo_quote/generate/key/b50580707da05a76d74022537fbfed7b/?isAjax=true

parameters
dash	0
form_key	ZlbtAXj1YgRt0ym9
format	alphanum
length	12
prefix	x
qty	12
rule_id	1
suffix	1
to_date	
uses_per_coupon	0
uses_per_customer	0

How to uninstall
drop table hn_followupemail_rule

$('fue_event').setValue('wishlist_item_add');
fueControl.reloadCondition(event);

Uninstall 
ALTER TABLE `salesrule_coupon`
  DROP `is_fue`,
  DROP `mail_id`;
DROP TABLE hn_followupemail_mail;
Drop table hn_followupemail_rule;
Drop table hn_followupemail_track;
DELETE FROM `magento`.`core_resource` WHERE `core_resource`.`code` = \'followupemail_setup\