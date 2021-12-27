---
--- Increasing sequnce column length to 1000 characters
---
ALTER TABLE `question_sequence` CHANGE `sequence` `sequence` VARCHAR(1000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;