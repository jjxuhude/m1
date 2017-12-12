<?php
$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();
$installer->run(
    "
		ALTER TABLE  {$this->getTable('followupemail/rule')}  ADD  `bcc` VARCHAR(45) NULL
		"
);
$installer->endSetup();
