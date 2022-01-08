---
--- Adding new column display to the groups table
---
ALTER TABLE `groups` ADD `display` TINYINT NOT NULL DEFAULT '1' AFTER `default_group`;