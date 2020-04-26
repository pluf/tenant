
ALTER TABLE `tenants` CHANGE `level` `level` int(11) DEFAULT '0';
ALTER TABLE `tenants` CHANGE `title` `title` varchar(100) DEFAULT '';
ALTER TABLE `tenants` CHANGE `validate` `validate` tinyint(1) DEFAULT '0';
ALTER TABLE `tenants` CHANGE `email` `email` varchar(150) DEFAULT '';
ALTER TABLE `tenants` CHANGE `phone` `phone` varchar(150) DEFAULT '';
  
  
  
  
