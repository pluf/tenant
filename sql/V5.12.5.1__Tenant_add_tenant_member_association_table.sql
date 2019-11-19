-- --------------------------------------------------------------------
-- Tenant
-- 
-- Created by: 
--  Mohammad Hadi Mansouri
-- Date:
-- --------------------------------------------------------------------
CREATE TABLE `tenant_member_tenant_tenant_assoc` (
	`tenant_tenant_id` mediumint(9) unsigned NOT NULL DEFAULT 0,
	`tenant_member_id` mediumint(9) unsigned  NOT NULL DEFAULT 0,
	PRIMARY KEY (`tenant_member_id`, `tenant_tenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tenant_member_tenant_tenant_assoc` 
   ADD CONSTRAINT `tenant_tenant_id_foreignkey_idx` 
   FOREIGN KEY (`tenant_tenant_id`) 
   REFERENCES `tenants` (`id`)
   ON DELETE CASCADE;
   
ALTER TABLE `tenant_member_tenant_tenant_assoc` 
   ADD CONSTRAINT `tenant_member_id_foreignkey_idx` 
   FOREIGN KEY (`tenant_member_id`) 
   REFERENCES `user_accounts` (`id`);
   
   