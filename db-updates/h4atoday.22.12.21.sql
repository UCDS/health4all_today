---
--- Adding default_language_id to the user table
---
ALTER TABLE `user` ADD `default_language_id` INT(11) NULL DEFAULT NULL AFTER `phone`;

---
--- Adding new coulmns to the user table
---
ALTER TABLE `user`
    ADD `created_by` INT(11) NULL DEFAULT NULL AFTER `active_status`, 
    ADD `created_datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_by`, 
    ADD `updated_by` INT(11) NULL DEFAULT NULL AFTER `created_datetime`, 
    ADD `updated_datetime` TIMESTAMP NULL DEFAULT NULL AFTER `updated_by`;
