-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 06, 2013 at 07:15 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `staffdirectory`
--

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE IF NOT EXISTS `divisions` (
  `id` int(11) NOT NULL DEFAULT '0',
  `division_name` varchar(100) DEFAULT NULL,
  `fax` varchar(100) DEFAULT NULL,
  `short_location_name` varchar(100) DEFAULT NULL,
  `is_branch` tinyint(11) NOT NULL DEFAULT '0',
  `is_hours_location` tinyint(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hour_categories`
--

CREATE TABLE IF NOT EXISTS `hour_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(30) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `hour_categories`
--

INSERT INTO `hour_categories` (`id`, `category`, `description`) VALUES
(1, 'Regular', 'Includes:\r\n-Fall Term: Beginning of September - End of April'),
(5, 'Holiday', 'Includes:\r\n- Thanksgiving: Oct\r\n- Remembrance Day: Nov\r\n- Christmas: December - January\r\n- Easter: April\r\n- Victoria Day: May \r\n- Canada Day: July 1\r\n- BC Day: Aug 1\r\n- Labour Day: Sept\r\n'),
(6, 'Exam', 'Includes:\r\n- Mid April - End of April\r\n- Beginning of December - Mid December'),
(7, 'Exception', 'Includes:\r\n- Days within another range that are different but not a holiday, exam, intercession, etc.'),
(4, 'Summer Alternate', 'Includes:\r\n-Summer Term Alternate Hours'),
(2, 'Intersession', 'Includes:\r\n-Intersession Spring: End of April - Beginning of May\r\n-Intersession Fall: Mid August - Beginning of September'),
(3, 'Summer', 'Includes:\r\n-Summer Term: Beginning of May - Mid August');

-- --------------------------------------------------------

--
-- Table structure for table `hour_date_ranges`
--

CREATE TABLE IF NOT EXISTS `hour_date_ranges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hour_category_id` int(11) DEFAULT NULL,
  `description` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL,
  `print_note` text COLLATE utf8_bin,
  `modified_by` varchar(50) CHARACTER SET utf8 NOT NULL,
  `modified_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;

-- --------------------------------------------------------

--
-- Table structure for table `hour_days`
--

CREATE TABLE IF NOT EXISTS `hour_days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hour_grouping_id` int(11) NOT NULL,
  `day_of_week` varchar(10) COLLATE utf8_bin NOT NULL,
  `open_time` time NOT NULL,
  `close_time` time NOT NULL,
  `is_closed` int(1) NOT NULL,
  `is_tbd` int(1) NOT NULL,
  `modified_by` varchar(50) COLLATE utf8_bin NOT NULL,
  `modified_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;

-- --------------------------------------------------------

--
-- Table structure for table `hour_groupings`
--

CREATE TABLE IF NOT EXISTS `hour_groupings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hour_date_range_id` int(11) NOT NULL,
  `hour_location_id` int(11) NOT NULL,
  `hour_type_id` int(11) NOT NULL,
  `hour_category_id` int(11) NOT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `modified_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `hour_locations`
--

CREATE TABLE IF NOT EXISTS `hour_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_position` int(2) NOT NULL,
  `sub_location` int(1) NOT NULL DEFAULT '0',
  `parent_hour_location_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `division_id` int(11) DEFAULT NULL,
  `service_point_id` int(11) DEFAULT NULL,
  `map_code` text,
  `name` varchar(150) DEFAULT NULL,
  `description` text,
  `address` text,
  `phone` varchar(250) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `accessibility_url` varchar(500) DEFAULT NULL,
  `hours_notes` text,
  `modified_by` varchar(150) DEFAULT NULL,
  `modified_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `display` int(1) NOT NULL COMMENT 'a way to check yes/no for display',
  `widget_note` text,
  `login` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `hour_types`
--

CREATE TABLE IF NOT EXISTS `hour_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL DEFAULT '0',
  `location_name` varchar(100) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `postal_code` varchar(100) DEFAULT NULL,
  `ubc_map_id` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
