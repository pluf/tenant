CREATE TABLE `tenant_comments` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL DEFAULT '',
  `description` varchar(2048) DEFAULT '',
  `creation_dtime` datetime DEFAULT '0000-00-00 00:00:00',
  `modif_dtime` datetime DEFAULT '0000-00-00 00:00:00',
  `author_id` mediumint(9) unsigned NOT NULL DEFAULT 0,
  `ticket_id` mediumint(9) unsigned NOT NULL DEFAULT 0,
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `author_id_foreignkey_idx` (`author_id`),
  KEY `ticket_id_foreignkey_idx` (`ticket_id`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `tenant_configurations` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(250) NOT NULL DEFAULT '',
  `value` varchar(250) DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `creation_dtime` datetime DEFAULT '0000-00-00 00:00:00',
  `modif_dtime` datetime DEFAULT '0000-00-00 00:00:00',
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_unique_idx` (`tenant`,`key`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `tenant_invoices` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT '',
  `description` varchar(500) DEFAULT '',
  `amount` int(11) NOT NULL DEFAULT 0,
  `due_dtime` date NOT NULL DEFAULT '0000-00-00',
  `status` varchar(50) NOT NULL DEFAULT '',
  `discount_code` varchar(50) DEFAULT '',
  `creation_dtime` datetime DEFAULT '0000-00-00 00:00:00',
  `modif_dtime` datetime DEFAULT '0000-00-00 00:00:00',
  `payment_id` mediumint(9) unsigned NOT NULL DEFAULT 0,
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `payment_id_foreignkey_idx` (`payment_id`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `tenant_settings` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `mode` int(11) NOT NULL DEFAULT 0,
  `key` varchar(250) NOT NULL DEFAULT '',
  `value` varchar(250) DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `creation_dtime` datetime DEFAULT '0000-00-00 00:00:00',
  `modif_dtime` datetime DEFAULT '0000-00-00 00:00:00',
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_unique_idx` (`tenant`,`key`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `tenant_spas` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `state` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `version` varchar(100) NOT NULL DEFAULT '',
  `last_version` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(50) DEFAULT '',
  `license` varchar(250) DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `path` varchar(100) NOT NULL DEFAULT '',
  `main_page` varchar(100) NOT NULL DEFAULT 'index.html',
  `homepage` varchar(100) DEFAULT '',
  `creation_dtime` datetime DEFAULT '0000-00-00 00:00:00',
  `modif_dtime` datetime DEFAULT '0000-00-00 00:00:00',
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `spa_idx` (`tenant`,`name`,`version`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `tenant_tickets` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(256) DEFAULT '',
  `subject` varchar(256) NOT NULL DEFAULT '',
  `description` varchar(2048) DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT '',
  `creation_dtime` datetime DEFAULT '0000-00-00 00:00:00',
  `modif_dtime` datetime DEFAULT '0000-00-00 00:00:00',
  `requester_id` mediumint(9) unsigned NOT NULL DEFAULT 0,
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `requester_id_foreignkey_idx` (`requester_id`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
