-- --------------------------------------------------------------------
-- Tenant
-- 
-- Created by: 
--  Mohammad Hadi Mansouri
-- Date:
-- --------------------------------------------------------------------
CREATE TABLE `tenant_owner_tenant_tenant_assoc` (
	`tenant_tenant_id` mediumint(9) unsigned NOT NULL DEFAULT 0,
	`tenant_owner_id` mediumint(9) unsigned  NOT NULL DEFAULT 0,
	PRIMARY KEY (`tenant_owner_id`, `tenant_tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tenant_owner_tenant_tenant_assoc` 
   ADD CONSTRAINT `tenant_tenant_id_foreignkey_idx` 
   FOREIGN KEY (`tenant_tenant_id`) 
   REFERENCES `tenants` (`id`)
   ON DELETE CASCADE;
   
ALTER TABLE `tenant_owner_tenant_tenant_assoc` 
   ADD CONSTRAINT `tenant_owner_id_foreignkey_idx` 
   FOREIGN KEY (`tenant_owner_id`) 
   REFERENCES `user_accounts` (`id`);
   
   