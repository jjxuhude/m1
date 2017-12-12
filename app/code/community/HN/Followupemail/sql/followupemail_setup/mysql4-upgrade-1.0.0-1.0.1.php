<?php
$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();
$installer->run(
    "
		ALTER TABLE  {$this->getTable('followupemail/mail')}  ADD  `is_test` TINYINT NULL
		"
);
$installer->endSetup();
