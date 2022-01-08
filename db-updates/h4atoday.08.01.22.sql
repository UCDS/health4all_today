---
--- Adding new column display to the groups table
---
ALTER TABLE `groups` ADD `display` TINYINT NOT NULL DEFAULT '1' AFTER `default_group`;

---
---
---
ALTER TABLE answer_option CHANGE updated_datetime updated_datetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP