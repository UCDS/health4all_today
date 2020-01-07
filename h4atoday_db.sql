-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 07, 2020 at 12:43 PM
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
-- Table structure for table `answer_option`
--

DROP TABLE IF EXISTS `answer_option`;
CREATE TABLE IF NOT EXISTS `answer_option` (
  `answer_option_id` int(11) NOT NULL AUTO_INCREMENT,
  `answer` text NOT NULL,
  `question_id` int(11) NOT NULL,
  `correct_option` tinyint(1) NOT NULL,
  `answer_image` text NOT NULL,
  `reference_note` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `last_modified_by` int(11) NOT NULL,
  `created_date_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_modified_date_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`answer_option_id`),
  KEY `answer_question` (`question_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answer_option`
--

INSERT INTO `answer_option` (`answer_option_id`, `answer`, `question_id`, `correct_option`, `answer_image`, `reference_note`, `created_by`, `last_modified_by`, `created_date_time`, `last_modified_date_time`) VALUES
(1, 'Automated Electrical Defibrilater', 1, 0, '', '', 0, 0, '2019-12-26 12:11:42', '0000-00-00 00:00:00'),
(2, 'Automated  External Defebrilator', 1, 1, '', '', 0, 0, '2019-12-26 12:11:42', '0000-00-00 00:00:00'),
(3, '30:1', 2, 0, '', '', 0, 0, '2019-12-26 12:18:51', '0000-00-00 00:00:00'),
(4, '30:2', 2, 1, '', '', 0, 0, '2019-12-26 12:18:51', '0000-00-00 00:00:00'),
(5, '15:1', 2, 0, '', '', 0, 0, '2019-12-26 12:18:51', '0000-00-00 00:00:00'),
(6, '15:2', 2, 0, '', '', 0, 0, '2019-12-26 12:18:51', '0000-00-00 00:00:00'),
(7, 'After every 1 cycle of CPR', 3, 0, '', '', 0, 0, '2019-12-26 12:20:54', '0000-00-00 00:00:00'),
(8, 'After every 2 cycles of CPR', 3, 0, '', '', 0, 0, '2019-12-26 12:20:54', '0000-00-00 00:00:00'),
(9, 'After every 5 cycles of CPR', 3, 1, '', '', 0, 0, '2019-12-26 12:20:54', '0000-00-00 00:00:00'),
(10, 'After every 10 cycles of CPR', 3, 0, '', '', 0, 0, '2019-12-26 12:20:54', '0000-00-00 00:00:00'),
(11, 'Power on the AED, attach electrode pads, shock the individual, and analyze the rhythm', 4, 0, '', '', 0, 0, '2019-12-26 12:35:46', '0000-00-00 00:00:00'),
(12, 'Power on the AED, attach electrode pads, analyze the rhythm, clear the individual, and deliver shock', 4, 1, '', '', 0, 0, '2019-12-26 12:35:46', '0000-00-00 00:00:00'),
(13, 'Attach electrode pads, check pulse, shock individual, and analyze rhythm', 4, 0, '', '', 0, 0, '2019-12-26 12:35:46', '0000-00-00 00:00:00'),
(14, 'Check pulse, attach electrode pads, analyze rhythm, shock patient', 4, 0, '', '', 0, 0, '2019-12-26 12:35:46', '0000-00-00 00:00:00'),
(15, 'Brachial artery', 5, 0, '', '', 0, 0, '2019-12-26 12:43:25', '0000-00-00 00:00:00'),
(16, 'Ulnar artery', 5, 0, '', '', 0, 0, '2019-12-26 12:43:25', '0000-00-00 00:00:00'),
(17, 'Temporal artery', 5, 0, '', '', 0, 0, '2019-12-26 12:43:25', '0000-00-00 00:00:00'),
(18, 'Carotid or femoral artery', 5, 1, '', '', 0, 0, '2019-12-26 12:43:25', '0000-00-00 00:00:00'),
(19, 'Assess the individual, give two rescue breaths, defibrillate, and start CPR', 6, 0, '', '', 0, 0, '2019-12-26 12:48:27', '0000-00-00 00:00:00'),
(20, 'Assess the individual, activate EMS and get AED, check pulse, and start CPR', 6, 1, '', '', 0, 0, '2019-12-26 12:48:27', '0000-00-00 00:00:00'),
(21, 'Check pulse, give rescue breaths, assess the individual, and defibrillate', 6, 0, '', '', 0, 0, '2019-12-26 12:48:27', '0000-00-00 00:00:00'),
(22, 'Assess the individual, start CPR, give two rescue breaths, and defibrillate', 6, 0, '', '', 0, 0, '2019-12-26 12:48:27', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `banner_text`
--

DROP TABLE IF EXISTS `banner_text`;
CREATE TABLE IF NOT EXISTS `banner_text` (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_text` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banner_text`
--

INSERT INTO `banner_text` (`banner_id`, `banner_text`, `status`) VALUES
(1, 'Health is Wealth! perhaps Health is greather than Wealth. Sickness takes away our Happiness and Wealth. Prevention is better than Cure - Oh! lets walk the talk.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` text NOT NULL,
  `group_image` text NOT NULL,
  `default_group` tinyint(1) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`, `group_image`, `default_group`) VALUES
(1, 'BLS', '', 1),
(2, 'NCD', '', 0),
(3, 'OSTEOARTHRITIS', '', 0),
(4, 'HYPERTENSION', '', 0),
(5, 'ALCOHOL', '', 0),
(6, 'DIABETES', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `language`;
CREATE TABLE IF NOT EXISTS `language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(20) NOT NULL,
  `default_click` tinyint(1) NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`language_id`, `language`, `default_click`) VALUES
(1, 'ENGLISH', 0),
(2, 'HINDI', 0),
(3, 'TELUGU', 1);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `explanation` text NOT NULL,
  `question_image` text NOT NULL,
  `explanation_image` text NOT NULL,
  `status_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `default_question_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `last_modified_by` int(11) NOT NULL,
  `created_date_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_modified_date_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`question_id`),
  KEY `question_status` (`status_id`),
  KEY `question_level` (`level_id`),
  KEY `question_language` (`language_id`),
  KEY `default_question` (`default_question_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `question`, `explanation`, `question_image`, `explanation_image`, `status_id`, `level_id`, `language_id`, `default_question_id`, `created_by`, `last_modified_by`, `created_date_time`, `last_modified_date_time`) VALUES
(1, 'What is AED', '', '', '', 1, 1, 2, 1, 0, 0, '2019-12-26 12:11:42', '0000-00-00 00:00:00'),
(2, 'The compression to ventilation ratio for one rescuer giving CPR to individuals of ANY age is:', '10:1 is not a correct compression to ventilation ratio and will result in inadequate perfusion.30:1 is not a correct ratio and will result in inadequate ventilation. 30:2 is the AHA expert consensus for the correct ratio of compressions to ventilations. 15:2 is not the correct ratio for compressions to ventilations in single rescuer CPR.', '', '', 1, 2, 1, 2, 0, 0, '2019-12-26 12:18:51', '0000-00-00 00:00:00'),
(3, 'How often should rescuers switch roles when performing two-rescuer CPR?', '', '', '', 1, 2, 1, 3, 0, 0, '2019-12-26 12:20:54', '0000-00-00 00:00:00'),
(4, 'The proper steps for operating an AED are:', '', '', '', 1, 2, 1, 4, 0, 0, '2019-12-26 12:35:46', '0000-00-00 00:00:00'),
(5, 'Where should you attempt to perform a pulse check in a child who is anywhere from one year to puberty?', '', '', '', 1, 1, 1, 1, 0, 0, '2019-12-26 12:43:25', '0000-00-00 00:00:00'),
(6, 'The initial Basic Life Support (BLS) steps for adults are:', '', '', '', 1, 2, 1, 1, 0, 0, '2019-12-26 12:48:27', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `question_grouping`
--

DROP TABLE IF EXISTS `question_grouping`;
CREATE TABLE IF NOT EXISTS `question_grouping` (
  `grouping_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `sub_group_id` int(11) NOT NULL,
  PRIMARY KEY (`grouping_id`),
  KEY `question_group` (`group_id`,`question_id`,`sub_group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_grouping`
--

INSERT INTO `question_grouping` (`grouping_id`, `question_id`, `group_id`, `sub_group_id`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 2),
(3, 3, 2, 2),
(4, 4, 3, 2),
(5, 5, 1, 1),
(6, 6, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `question_level`
--

DROP TABLE IF EXISTS `question_level`;
CREATE TABLE IF NOT EXISTS `question_level` (
  `level_id` int(11) NOT NULL AUTO_INCREMENT,
  `level` text NOT NULL,
  `level_image` text NOT NULL,
  PRIMARY KEY (`level_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_level`
--

INSERT INTO `question_level` (`level_id`, `level`, `level_image`) VALUES
(1, 'BASIC', ''),
(2, 'MEDIUM', ''),
(3, 'MEDICAL', '');

-- --------------------------------------------------------

--
-- Table structure for table `question_status`
--

DROP TABLE IF EXISTS `question_status`;
CREATE TABLE IF NOT EXISTS `question_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` text NOT NULL,
  `display` tinyint(4) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_status`
--

INSERT INTO `question_status` (`status_id`, `status`, `display`) VALUES
(1, 'SUBMITTED', 0),
(2, 'APPROVED', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sub_groups`
--

DROP TABLE IF EXISTS `sub_groups`;
CREATE TABLE IF NOT EXISTS `sub_groups` (
  `sub_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_group` text NOT NULL,
  `group_id` int(11) NOT NULL,
  `sub_group_image` text NOT NULL,
  PRIMARY KEY (`sub_group_id`),
  KEY `parent_group` (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_groups`
--

INSERT INTO `sub_groups` (`sub_group_id`, `sub_group`, `group_id`, `sub_group_image`) VALUES
(1, 'Cancer', 2, ''),
(2, 'COPD', 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `note` text NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

DROP TABLE IF EXISTS `user_access`;
CREATE TABLE IF NOT EXISTS `user_access` (
  `access_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  PRIMARY KEY (`access_id`),
  KEY `user_access` (`user_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
