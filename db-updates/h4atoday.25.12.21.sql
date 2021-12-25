---
--- New table for question_sequence
---
CREATE TABLE IF NOT EXISTS `question_sequence` (
  `group_id` int(11) NOT NULL,
  `sub_group_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `sequence` varchar(500) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`group_id`,`sub_group_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;