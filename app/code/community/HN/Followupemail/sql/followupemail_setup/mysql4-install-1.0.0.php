<?php
/**
* HungnamEcommerce Co.
 *
 * @category   HN
 * @version    2.0.2
 * @copyright  Copyright (c) 2012-2013 HungnamEcommerce Co. (http://hungnamecommerce.com)
 * @license    http://hungnamecommerce.com/HN-LICENSE-COMMUNITY.txt
 */

/** @var  $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$connection = $installer->getConnection();

$query = "SHOW COLUMNS FROM {$this->getTable('salesrule/coupon')} like 'is_fue'";
$row = $connection->fetchRow($query);
Mage::log($row);
$time =date("Y-m-d H:i:s", time());


$installer->startSetup();
 $installer->run(
     "


CREATE TABLE IF NOT EXISTS {$this->getTable('followupemail/rule')} (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'Rule Id',
  `event` varchar(255) DEFAULT NULL COMMENT 'event',
  `event_name` varchar(255) DEFAULT NULL COMMENT 'event name',
  `name` varchar(255) DEFAULT NULL COMMENT 'Name',
  `description` text COMMENT 'Description',
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `is_active` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Is Active',
  `conditions_serialized` mediumtext COMMENT 'Conditions Serialized',
  `stop_rules_processing` mediumtext COMMENT 'Stop Rules Processing',
  `customer_group` text DEFAULT NULL COMMENT 'Customer group',
  `store_id` text DEFAULT NULL COMMENT 'Customer group',
  `category_ids` varchar(255) DEFAULT NULL,
  `subscriber_only` TINYINT NOT NULL DEFAULT '0',
  `chain` text COMMENt 'Email chain',
  `coupon_active`  smallint(6) NOT NULL DEFAULT '0' COMMENT 'Is Active',
  `coupon_rule` int(10)  NULL,
  `coupon_pattern` varchar(255) NULL,
  `coupon_prefix` varchar(255) NULL,
  `coupon_sufix` varchar(255) NULL,
  `coupon_length` varchar(255) NULL,
  `expired_after_day` int(10) null,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

  
  CREATE TABLE IF NOT EXISTS {$this->getTable('followupemail/mail')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `event_name` varchar(255) NULL,
  `rule_id` INT NULL,
  `rule_name` varchar(255)NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `store_id` text NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `sender_email` varchar(255) DEFAULT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `recipient_email` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `send_at` datetime NOT NULL,
  `stop_rules_processing` mediumtext COMMENT 'Stop Rules Processing' NULL,
  `email_subject` VARCHAR(45) NULL,
  `unique_no` VARCHAR(45) NULL,
  `bcc` VARCHAR(45) NULL,
  `hash` varchar(255) DEFAULT NULL,
  `email_content` mediumtext,
  `event_info` text ,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  
  
  CREATE TABLE IF NOT EXISTS {$this->getTable('followupemail/track')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `event_id` INT NULL,
  `event_name` INT NULL,
  `rule_id` INT NULL,
  `rule_name` INT NULL,
  `status` varchar(55) DEFAULT NULL,
  `store_id` VARCHAR(45) NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `recipient_email` varchar(255) DEFAULT NULL,
  `bcc` varchar(255) DEFAULT NULL,
  `unique_no` VARCHAR(45) NULL,
  `hash` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `send_at` datetime  NULL,
  `visit_at` datetime  NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 " 
 );


if (!isset($row['is_fue']))  {
    $installer->run(
        "
    ALTER TABLE  {$this->getTable('salesrule/coupon')}  ADD  `is_fue` smallint(6) NULL;

    "
    );
}if (!isset($row['mail_id']))  {
    $installer->run(
        "
ALTER TABLE  {$this->getTable('salesrule/coupon')}  ADD  `mail_id` int(11) NULL;

    "
    );
}


$installer->setConfigData('followupemail/time/abandonedcart_collect_lt', $time);
$installer->setConfigData('followupemail/time/sending_followupemail_lt', $time);
 $installer->endSetup();
