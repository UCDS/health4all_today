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

---
--- Making username as unique 
---
ALTER TABLE `user` ADD UNIQUE(`username`);

---
--- Creating user_signin table
---
DROP TABLE IF EXISTS `user_signin`;
CREATE TABLE IF NOT EXISTS `user_signin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `signin_date_time` datetime NOT NULL,
  `is_success` tinyint(1) NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table for storing the user login activities';