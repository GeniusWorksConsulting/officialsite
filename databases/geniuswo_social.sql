-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Dec 10, 2018 at 11:16 AM
-- Server version: 5.6.33
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `geniuswo_social`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `answer_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `answer` varchar(255) DEFAULT NULL,
  `rating` decimal(10,2) NOT NULL,
  `weighting` decimal(10,3) NOT NULL,
  `is_zero` tinyint(1) NOT NULL COMMENT '0: No 1: Whole Assessment 0',
  `section_zero` tinyint(1) NOT NULL COMMENT '0: No 1: Whole Section 0',
  `question_id` int(11) unsigned NOT NULL COMMENT 'FK Question Table',
  `created` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`answer_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`answer_id`, `answer`, `rating`, `weighting`, `is_zero`, `section_zero`, `question_id`, `created`, `status`) VALUES
(1, 'Completely missed the point of the customer needs', '0.00', '0.000', 0, 0, 1, '2018-10-25 08:22:27', 0),
(2, 'Partially understood the customer''s needs', '50.00', '0.033', 0, 0, 1, '2018-10-25 08:22:27', 0),
(3, 'Completely understood and addressed all of the customer''s needs', '100.00', '0.066', 0, 0, 1, '2018-10-25 08:22:27', 0),
(4, 'Yes', '100.00', '0.066', 0, 0, 2, '2018-10-25 08:25:41', 0),
(5, 'No', '0.00', '0.000', 0, 0, 2, '2018-10-25 08:25:41', 0),
(6, 'No', '0.00', '0.000', 0, 0, 3, '2018-10-25 12:12:54', 0),
(7, 'Identified other needs , new products and sales over and above to meet customer needs', '100.00', '0.050', 0, 0, 3, '2018-10-25 12:12:54', 0),
(8, 'Yes', '0.00', '0.000', 0, 0, 4, '2018-10-25 12:14:27', 0),
(9, 'No', '0.00', '0.000', 0, 0, 4, '2018-10-25 12:14:27', 0),
(10, 'Yes', '100.00', '0.025', 0, 0, 5, '2018-10-25 12:21:40', 0),
(11, 'No', '0.00', '0.000', 0, 0, 5, '2018-10-25 12:21:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `assessment`
--

CREATE TABLE IF NOT EXISTS `assessment` (
  `assessment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`assessment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `assessment`
--

INSERT INTO `assessment` (`assessment_id`, `name`) VALUES
(1, 'Text'),
(2, 'Voice');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `cat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) DEFAULT NULL,
  `weighting` decimal(10,2) DEFAULT NULL,
  `sub_ass_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `sub_ass_id` (`sub_ass_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`, `weighting`, `sub_ass_id`, `user_id`, `created`, `status`) VALUES
(1, 'Was a solution provided in the Email interaction?', '10.00', 1, 2, '2018-10-24 12:59:00', 0),
(2, 'Displayed right actions for Email query resolution?', '11.16', 1, 2, '2018-10-24 12:42:07', 0),
(3, 'Was a solution provided in the Webchat interaction?', '10.00', 2, 2, '2018-10-24 12:55:09', 0),
(4, 'Email interaction', '10.00', 1, 2, '2018-10-25 11:48:44', 0),
(5, 'Did the Customer feel the love?', '10.00', 1, 2, '2018-10-25 11:50:38', 1),
(6, 'Was a solution provided in the Webchat  interaction?', '100.00', 2, 2, '2018-10-25 11:51:14', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('0eae80a8d26d1dab2534cbb06417b89617c9fe09', '123.201.225.40', 1540876705, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534303837363730353b),
('41e11b11172d741bffc1a2683954b19196d25074', '196.212.60.87', 1540882451, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534303838323138313b6964656e746974797c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b656d61696c7c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b757365725f69647c733a313a2231223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231353430343635323536223b6c6173745f636865636b7c693a313534303838323232353b),
('cb47577ccde8d8320a1c56324507c47672e6d752', '196.212.60.87', 1540882753, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534303838323438383b6964656e746974797c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b656d61696c7c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b757365725f69647c733a313a2231223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231353430343635323536223b6c6173745f636865636b7c693a313534303838323232353b),
('6c99b8fc9322ab114ac446a37a11c9396e43d9ab', '196.212.60.87', 1541665839, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534313636353833383b),
('a8f1415fb732201bef3439bf6014e1d516ac3d4a', '196.212.60.87', 1541665840, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534313636353834303b),
('577738bf1e580bac68b7225d0f206874a364cdd1', '196.212.60.87', 1541666134, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534313636353834333b6964656e746974797c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b656d61696c7c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b757365725f69647c733a313a2231223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231353430383832323235223b6c6173745f636865636b7c693a313534313636353837313b),
('a3b29b3f62a93c8513cf009e068e397f03c608c6', '196.212.60.87', 1541666294, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534313636363136373b6964656e746974797c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b656d61696c7c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b757365725f69647c733a313a2231223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231353430383832323235223b6c6173745f636865636b7c693a313534313636353837313b),
('7bba0867c93ee6e110bdc0bcf764ac088864b4d4', '196.212.60.87', 1541667151, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534313636373131353b6964656e746974797c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b656d61696c7c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b757365725f69647c733a313a2231223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231353430383832323235223b6c6173745f636865636b7c693a313534313636353837313b),
('0b5faa35f0bedf5c56ce87ba666cb6152ef540da', '196.212.60.87', 1541667679, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534313636373530373b6964656e746974797c733a31363a2263686972616740676d61696c2e636f6d223b656d61696c7c733a31363a2263686972616740676d61696c2e636f6d223b757365725f69647c733a313a2233223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231353430343730323835223b6c6173745f636865636b7c693a313534313636373533303b),
('f5e3a0384a9332582b75f8cad204b1611020f39c', '102.253.104.161', 1541954633, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534313935343633333b),
('9d5fe43cfc6ea4ba32c95ae18405da5edd6fb199', '105.22.41.78', 1542290221, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534323239303134373b6964656e746974797c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b656d61696c7c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b757365725f69647c733a313a2231223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231353431363635383731223b6c6173745f636865636b7c693a313534323239303135373b),
('dcca79268da4f0ee5cd436251cb9e25ad9e9a7b8', '196.212.60.87', 1543577012, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534333537373031323b),
('e7fe003d9b71098a26f60e5475ad90601c9208b7', '196.212.60.87', 1543822516, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534333832323531353b),
('c95c1560efb4cbc6e7770d04fb63fefa0aaf012a', '196.212.60.87', 1543824461, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534333832343434373b6964656e746974797c733a31363a2263686972616740676d61696c2e636f6d223b656d61696c7c733a31363a2263686972616740676d61696c2e636f6d223b757365725f69647c733a313a2233223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231353431363637353330223b6c6173745f636865636b7c693a313534333832343436303b),
('26cb7fc0c4e2833a2b599e617a20afdbf1b02b1b', '219.91.239.242', 1543831830, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534333833313832383b),
('6d5fef26ae63331c3ab2857d2c05982a97225cd2', '49.44.139.231', 1543832445, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534333833323432313b6964656e746974797c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b656d61696c7c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b757365725f69647c733a313a2231223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231353432323930313537223b6c6173745f636865636b7c693a313534333833323434343b),
('0b9795851f9824d57a95fea43f09233133c79f41', '219.91.239.242', 1543832739, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534333833323731383b6964656e746974797c733a32353a2267696c6c69616e4067656e697573776f726b732e636f2e7a61223b656d61696c7c733a32353a2267696c6c69616e4067656e697573776f726b732e636f2e7a61223b757365725f69647c733a313a2234223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231353433383332373235223b6c6173745f636865636b7c693a313534333833323733393b),
('f69755c176d626d765ef1b00a4bd825d897df617', '49.44.139.234', 1543833161, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534333833333135353b),
('7d090e09c7e79f6d4a17b9b977d9ca994b297dd9', '219.91.238.221', 1544424985, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534343432343930313b6d6573736167657c733a34383a223c703e54656d706f726172696c79204c6f636b6564204f75742e202054727920616761696e206c617465722e3c2f703e223b5f5f63695f766172737c613a313a7b733a373a226d657373616765223b733a333a226f6c64223b7d),
('3749825ad48001d4c75e47b8b713abde1bbdcf2b', '219.91.238.151', 1544433102, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534343433333032313b6964656e746974797c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b656d61696c7c733a31383a2269616d6f72616e6340676d61696c2e636f6d223b757365725f69647c733a313a2231223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231353433383332363836223b6c6173745f636865636b7c693a313534343433333035333b);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'superadmin', 'System Admin'),
(2, 'admin', 'Admin User'),
(3, 'Qa', 'QA Users'),
(4, 'Basic', 'Agents'),
(5, 'Manager', 'Manager'),
(6, 'Lead', 'Team Leader'),
(7, 'HR', 'Human Resources'),
(8, 'Finance', 'Finance');

-- --------------------------------------------------------

--
-- Table structure for table `groups_permissions`
--

CREATE TABLE IF NOT EXISTS `groups_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` mediumint(8) unsigned NOT NULL,
  `perm_id` int(11) unsigned NOT NULL,
  `value` tinyint(4) DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roleID_2` (`group_id`,`perm_id`),
  KEY `perm_id` (`perm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `groups_permissions`
--

INSERT INTO `groups_permissions` (`id`, `group_id`, `perm_id`, `value`, `created_at`, `updated_at`) VALUES
(1, 6, 6, 1, 1538738901, 1538738901);

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE IF NOT EXISTS `levels` (
  `level_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `level_name` varchar(100) DEFAULT NULL,
  `value` decimal(10,2) NOT NULL,
  `date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0 Active 1 Delete',
  PRIMARY KEY (`level_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `login`, `time`) VALUES
(5, '219.91.238.221', 'iamoranc@gmail.com', 1544424924),
(6, '219.91.238.221', 'iamoranc@gmail.com', 1544424938),
(7, '219.91.238.221', 'iamoranc@gmail.com', 1544424960);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(4);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `perm_key` varchar(30) NOT NULL,
  `perm_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permKey` (`perm_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `perm_key`, `perm_name`) VALUES
(5, 'qa', 'QA USERS'),
(6, 'squad', 'SQUAD MEMBERS');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `question_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  `evaluation` text,
  `weight` decimal(10,2) NOT NULL,
  `cat_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL COMMENT 'Admin Id',
  `is_parent` tinyint(1) NOT NULL COMMENT '0: Parent 1: Child',
  `has_child` tinyint(1) NOT NULL COMMENT '0: No 1: Yes',
  `answer_id` int(11) unsigned NOT NULL,
  `count` tinyint(1) NOT NULL COMMENT '0: No 1: Yes',
  `parent_id` int(11) unsigned NOT NULL,
  `no_answer` int(5) NOT NULL,
  `created` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`question_id`),
  KEY `cat_id` (`cat_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `description`, `evaluation`, `weight`, `cat_id`, `user_id`, `is_parent`, `has_child`, `answer_id`, `count`, `parent_id`, `no_answer`, `created`, `status`) VALUES
(1, 'Was the customer''s need/s fully addressed?', '', '6.66', 1, 2, 0, 0, 0, 0, 0, 3, '2018-10-25 08:22:27', 0),
(2, 'Was the customer provided with the best solution possible?', 'Was the most efficient and effective method used to solve the customer''s problem?', '6.66', 1, 2, 0, 0, 0, 0, 0, 2, '2018-10-25 08:25:41', 0),
(3, 'Did they go above and beyond the stated need?', '', '5.00', 1, 2, 0, 0, 0, 0, 0, 2, '2018-10-25 12:12:54', 0),
(4, 'Are there alternative channels applicable for this type of request?', '', '2.50', 1, 2, 0, 1, 0, 0, 0, 2, '2018-10-25 12:14:27', 0),
(5, 'Was the customer informed of alternative channels available to meet this request?', 'As per the omnichannel strategy - was the customer informed of  the various ways in which they can do their banking? E.g. internet banking, mobile apps, emails, chat, etc.', '2.50', 1, 2, 1, 0, 8, 0, 4, 2, '2018-10-25 12:21:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `scheduled`
--

CREATE TABLE IF NOT EXISTS `scheduled` (
  `schedule_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `admin_id` int(11) unsigned NOT NULL,
  `squad_id` int(11) unsigned NOT NULL,
  `schedule_date` date DEFAULT NULL,
  `from_time` varchar(20) DEFAULT NULL,
  `to_time` varchar(20) DEFAULT NULL,
  `week` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0: Active 1: Deleted',
  PRIMARY KEY (`schedule_id`),
  KEY `sender_id` (`sender_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `squad_group`
--

CREATE TABLE IF NOT EXISTS `squad_group` (
  `squad_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `squad_name` varchar(50) DEFAULT NULL,
  `site` varchar(100) DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0: Active 1: Deleted',
  PRIMARY KEY (`squad_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `squad_group`
--

INSERT INTO `squad_group` (`squad_id`, `squad_name`, `site`, `user_id`, `created`, `status`) VALUES
(3, 'GW Squad', 'JHB', 6, '2018-10-30 08:56:35', 0),
(4, 'Genius', '', 2, '2018-10-30 08:58:10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `squad_users`
--

CREATE TABLE IF NOT EXISTS `squad_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `squad_id` int(11) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0: Active 1: Delete',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `squad_id` (`squad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sub_assessment`
--

CREATE TABLE IF NOT EXISTS `sub_assessment` (
  `sub_ass_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `assessment_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL COMMENT 'Admin Id',
  `created` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0: Active 1: Delete',
  PRIMARY KEY (`sub_ass_id`),
  KEY `assessment_id` (`assessment_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `sub_assessment`
--

INSERT INTO `sub_assessment` (`sub_ass_id`, `name`, `assessment_id`, `user_id`, `created`, `status`) VALUES
(1, 'Text Email', 1, 2, '2018-10-24 09:16:22', 0),
(2, 'Text WebChat', 1, 2, '2018-10-24 09:22:14', 0),
(3, 'Inbound', 2, 2, '2018-10-24 09:22:43', 0),
(4, 'Outbound', 2, 2, '2018-10-24 09:22:52', 0),
(5, 'WhatsApp Chat', 1, 2, '2018-11-08 10:52:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sub_levels`
--

CREATE TABLE IF NOT EXISTS `sub_levels` (
  `sub_level_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `level_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `sub_level_name` varchar(100) DEFAULT NULL,
  `value` decimal(10,2) NOT NULL,
  `created` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0: Active 1: Deleted',
  PRIMARY KEY (`sub_level_id`),
  KEY `level_id` (`level_id`),
  KEY `admin_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `email` varchar(254) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `profile`) VALUES
(1, '127.0.0.1', 'superadmin', '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', '', 'iamoranc@gmail.com', '', NULL, NULL, NULL, 1268889823, 1544433053, 1, 'Oran', 'Cohen', 'Genius Works', '0', NULL),
(2, '219.91.239.104', 'oran@geniusworks.co.za', '$2y$08$LJbvxpT4gUBuJkpm2JEdzO66zfx1t.vjmfMUze5.dhGy1tVlw1Wza', NULL, 'oran@geniusworks.co.za', NULL, NULL, NULL, NULL, 1537526200, 1538139355, 1, 'Oran', 'Cohen', 'Genius Works', '', NULL),
(3, '219.91.239.6', 'chirag@gmail.com', '$2y$08$KsaPeuSuEqeahC.0MWeMEeQ.44Owdg1RDBl8bL2iB7YY8YwMxlwne', NULL, 'chirag@gmail.com', NULL, NULL, NULL, NULL, 1540293862, 1543824460, 1, 'Chirag', 'Kevadiya', NULL, '', '1540294872.png'),
(4, '196.212.60.87', 'gillian@geniusworks.co.za', '$2y$08$gyTGfmoymlPOnfbbnijxkeURC59XThEo775v1OkWBFM0JXWrQex56', NULL, 'gillian@geniusworks.co.za', NULL, NULL, NULL, NULL, 1540882323, 1543832739, 1, 'Gillian', 'M', NULL, '', NULL),
(5, '196.212.60.87', 'thandazo@geniusworks.co.za', '$2y$08$IxXzrdx/RSJJJZOqJnoCQ.CsVDezJTMsejQP7eqDYnZzhLjzT0cfO', NULL, 'thandazo@geniusworks.co.za', NULL, NULL, NULL, NULL, 1540882354, NULL, 1, 'Thandazo', 'M', NULL, '', NULL),
(6, '196.212.60.87', 'darryl@geniusworks.co.za', '$2y$08$UC0hZaKdnpU7Z/IpMdJBZuxr/Oac/3PpWdsWaAwRvL6spN2.bR7Ia', NULL, 'darryl@geniusworks.co.za', NULL, NULL, NULL, NULL, 1540882488, NULL, 1, 'Darryl', 'H', 'Genius Works', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_admin`
--

CREATE TABLE IF NOT EXISTS `users_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `admin_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users_admin`
--

INSERT INTO `users_admin` (`id`, `user_id`, `admin_id`) VALUES
(1, 3, 2),
(2, 4, 2),
(3, 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 4),
(6, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users_permissions`
--

CREATE TABLE IF NOT EXISTS `users_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `perm_id` int(11) unsigned NOT NULL,
  `value` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userID` (`user_id`,`perm_id`),
  KEY `perm_id` (`perm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=90 ;

--
-- Dumping data for table `users_permissions`
--

INSERT INTO `users_permissions` (`id`, `user_id`, `perm_id`, `value`, `created_at`, `updated_at`) VALUES
(88, 2, 5, 1, 1538139338, 1538139338),
(89, 2, 6, 1, 1538139338, 1538139338);

-- --------------------------------------------------------

--
-- Table structure for table `week`
--

CREATE TABLE IF NOT EXISTS `week` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `week` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0 Active 1 Deleted',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`sub_ass_id`) REFERENCES `sub_assessment` (`sub_ass_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `groups_permissions`
--
ALTER TABLE `groups_permissions`
  ADD CONSTRAINT `groups_permissions_ibfk_1` FOREIGN KEY (`perm_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_permissions_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `levels`
--
ALTER TABLE `levels`
  ADD CONSTRAINT `levels_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `question_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `scheduled`
--
ALTER TABLE `scheduled`
  ADD CONSTRAINT `scheduled_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `scheduled_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `squad_group`
--
ALTER TABLE `squad_group`
  ADD CONSTRAINT `squad_group_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_assessment`
--
ALTER TABLE `sub_assessment`
  ADD CONSTRAINT `sub_assessment_ibfk_1` FOREIGN KEY (`assessment_id`) REFERENCES `assessment` (`assessment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sub_assessment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_levels`
--
ALTER TABLE `sub_levels`
  ADD CONSTRAINT `sub_levels_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `levels` (`level_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sub_levels_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_admin`
--
ALTER TABLE `users_admin`
  ADD CONSTRAINT `users_admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_admin_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_permissions`
--
ALTER TABLE `users_permissions`
  ADD CONSTRAINT `users_permissions_ibfk_1` FOREIGN KEY (`perm_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_permissions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `week`
--
ALTER TABLE `week`
  ADD CONSTRAINT `week_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
