---
--- Dropping user_access table
---
DROP TABLE `user_access`

---
--- Creating table user_function 
---
DROP TABLE IF EXISTS `user_function`;
CREATE TABLE IF NOT EXISTS `user_function` (
  `user_function_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_function` varchar(50) NOT NULL,
  `user_function_display` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`user_function_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


---
--- Creating table user_function_link 
---
CREATE TABLE IF NOT EXISTS `user_function_link` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `function_id` int(11) NOT NULL,
  `add` tinyint(1) NOT NULL,
  `edit` tinyint(1) NOT NULL,
  `view` tinyint(1) NOT NULL,
  `remove` tinyint(1) NOT NULL,
  `active` int(1) NOT NULL DEFAULT 1 COMMENT '1- Function active with user, 0- Function disabled.',
  PRIMARY KEY (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Links users to functions with specific permissions';

---
--- Creating table user_language_link
---
CREATE TABLE IF NOT EXISTS `user_language_link` ( 
    `link_id` INT NOT NULL AUTO_INCREMENT ,  
    `user_id` INT(11) NOT NULL ,  
    `language_id` INT(11) NOT NULL ,  
    `created_datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    PRIMARY KEY  (`link_id`)
) ENGINE = MyISAM;

---
--- Adding created_by column to user_language_link table
---
ALTER TABLE `user_language_link` ADD `created_by` INT(11) NULL DEFAULT NULL AFTER `language_id`;

---
--- Adding updated_by, updated_datetime column to user_language_link table
---
ALTER TABLE `user_language_link` 
ADD `updated_by` INT NULL DEFAULT NULL AFTER `created_datetime`, 
ADD `updated_datetime` TIMESTAMP NULL DEFAULT NULL AFTER `updated_by`;

---
--- Adding active_status column to the user table
---
ALTER TABLE `user` ADD `active_status` TINYINT(1) NULL DEFAULT NULL COMMENT '1- Active user, 0-InActive user . ' AFTER `password`;