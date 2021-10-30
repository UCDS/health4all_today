-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 16, 2021 at 12:11 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `h4atoday_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `defaults`
--

DROP TABLE IF EXISTS `defaults`;
CREATE TABLE IF NOT EXISTS `defaults` (
  `default_id` varchar(50) NOT NULL,
  `default_tilte` varchar(100) NOT NULL,
  `default_description` mediumtext DEFAULT NULL,
  `default_type` varchar(100) NOT NULL,
  `default_unit` varchar(50) DEFAULT NULL,
  `lower_range` varchar(10) DEFAULT NULL,
  `upper_range` varchar(10) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`default_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `defaults`
--

INSERT INTO `defaults` (`default_id`, `default_tilte`, `default_description`, `default_type`, `default_unit`, `lower_range`, `upper_range`, `value`) VALUES
('user_display_images', 'Property to display and hide images in application', 'Set the value as 1to display images and set 0 to hide images', 'Numeric', '', '', '', '1'),
('display_images', 'Property to display and hide images in application', 'Set the value as 1to display images and set 0 to hide images', 'Numeric', '', '', '', '1'),
('display_max_height', 'Patient Document Image Maximum Height', 'Image height limit for Patient Documents to be uploaded. The maximum height (in pixels) that the image can be. Set to zero for no limit.', 'Numeric', 'Pixel', '', '', '200'),
('bootstrap_question_col_values', 'Bootstrap question column width values', 'bootstarp left and right column width values. Lower range is for left column and Upper range is for right column. sum of upper and lower range should be 12.', 'Numeric', '', '6', '6', '0'),
('upload_max_size', 'Patient Document Maximum Size', 'File size limit for Patient Documents to be uploaded. The maximum size (in kilobytes) that the file can be. Set to zero for no limit. Note: Most PHP installations have their own limit, as specified in the php.ini file. Usually 2 MB (or 2048 KB) by default.', 'Numeric', 'KB', '', '', '3072'),
('upload_max_width', 'Patient Document Image Maximum Width', 'Image width limit for Patient Documents to be uploaded. The minimum width (in pixels) that the image can be. Set to zero for no limit.', 'Numeric', 'Pixel', '', '', '3000'),
('upload_max_height', 'Patient Document Image Maximum Height', 'Image height limit for Patient Documents to be uploaded. The maximum height (in pixels) that the image can be. Set to zero for no limit.', 'Numeric', 'Pixel', '', '', '3000'),
('upload_allowed_types', 'Patient Document files types', 'Patient Document files types allowed for upload. The mime types corresponding to the types of files you allow to be uploaded. Usually the file extension can be used as the mime type. Can be either an array or a pipe-separated string.', 'Text', 'File Type', '', '', 'gif|jpg|jpeg|png'),
('upload_remove_spaces', 'Remove spaces in Patient Document file name', 'If set to TRUE, any spaces in the file name will be converted to underscores. This is recommended.', 'Text', '', '', '', 'TRUE'),
('upload_overwrite', 'Overwrite feature for document upload', 'If set to true, if a file with the same name as the one you are uploading exists, it will be overwritten. If set to false, a number will be appended to the filename if another with the same name exists.', 'Text', '', '', '', 'TRUE'),
('pagination', 'Pagination', 'Default number of rows to be loaded for reports', 'Numeric', NULL, '10', '200', '25'),
('display_max_width', 'Patient Document Image Maximum Width', 'Image width limit for Patient Documents to be uploaded. The minimum width (in pixels) that the image can be. Set to zero for no limit.', 'Numeric', 'Pixel', '', '', '100');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

UPDATE `answer_option` SET `answer_image` = 'NULL' WHERE `answer_image` = '';
UPDATE `question` SET `question_image`='NULL' WHERE `question_image`='';
UPDATE `question` SET `explanation_image`='NULL' WHERE `explanation_image`='';


ALTER TABLE `question` ADD `question_image_width` INT(3) NOT NULL DEFAULT '50' AFTER `question_image`;
ALTER TABLE `question` ADD `explanation_image_width` INT(3) NOT NULL DEFAULT '50' AFTER `explanation_image`;
ALTER TABLE `answer_option` ADD `answer_image_width` INT(3) NOT NULL DEFAULT '50' AFTER `answer_image`;



--
-- Table structure for table `transliterate_question`
--

DROP TABLE IF EXISTS `transliterate_question`;
CREATE TABLE IF NOT EXISTS `transliterate_question` (
  `question_transliterate_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `question_transliterate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `explanation_transliterate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`question_transliterate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

DROP TABLE IF EXISTS `transliterate_answer`;
CREATE TABLE IF NOT EXISTS `transliterate_answer` (
  `transliterate_answer_id` int(11) NOT NULL AUTO_INCREMENT,
  `answer_option_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `answer_option_transliterate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`transliterate_answer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


--
-- Adding new rows to the defaults table
--
INSERT INTO `defaults` (`default_id`, `default_tilte`, `default_description`, `default_type`, `default_unit`, `lower_range`, `upper_range`, `value`) VALUES
('display_transliterate', 'Property to display and hide transliterate in application at admin level', 'Set the value as 1 to display transliterate option and set 0 to hide transliterate option ', 'Numeric', '', '', '', '1'),
('user_display_transliterate', 'Property holds the value of displaying transliterate for user', 'Set the value as 1 to check  transliterate option and set 0 to uncheck transliterate option', 'Numeric', '', '', '', '1');
COMMIT;
