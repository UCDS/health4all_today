---
--- Adding default_language_id to the user table
---
ALTER TABLE `user` ADD `default_language_id` INT(11) NULL DEFAULT NULL AFTER `phone`;

