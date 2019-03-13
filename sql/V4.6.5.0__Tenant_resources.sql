CREATE TABLE `tenant_resources` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(64) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT 'no title',
  `description` varchar(2048) NOT NULL DEFAULT 'auto created content',
  `mime_type` varchar(64) NOT NULL DEFAULT 'application/octet-stream',
  `media_type` varchar(64) NOT NULL DEFAULT 'application/octet-stream',
  `file_path` varchar(250) NOT NULL DEFAULT '',
  `file_name` varchar(250) NOT NULL DEFAULT 'unknown',
  `file_size` int(11) NOT NULL DEFAULT 0,
  `downloads` int(11) NOT NULL DEFAULT 0,
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `path_unique_idx` (`tenant`,`path`),
  KEY `content_mime_filter_idx` (`tenant`,`mime_type`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

