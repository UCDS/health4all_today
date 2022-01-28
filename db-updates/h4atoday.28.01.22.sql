---
--- Updated default value of active_status in user table
---
ALTER TABLE `user` CHANGE `active_status` `active_status` TINYINT(1) NULL DEFAULT '1' COMMENT '1- Active user, 0-InActive user . ';