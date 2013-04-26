-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 25, 2013 at 06:47 PM
-- Server version: 5.5.27-log
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `uwreggiedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE IF NOT EXISTS `alerts` (
  `userId` int(50) NOT NULL,
  `classYear` int(4) NOT NULL,
  `classTerm` enum('winter','spring','summer','autumn') NOT NULL,
  `classDept` varchar(50) NOT NULL,
  `classNumber` int(10) NOT NULL,
  `classSection` varchar(10) NOT NULL,
  `lastContacted` int(20) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alertsSent` int(50) NOT NULL DEFAULT '0',
  `created` int(50) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7025 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE IF NOT EXISTS `user_accounts` (
  `userId` int(50) NOT NULL AUTO_INCREMENT,
  `userEmail` varchar(75) NOT NULL,
  `phoneVerified` enum('true','false') NOT NULL DEFAULT 'false',
  `emailVerified` enum('true','false') NOT NULL DEFAULT 'false',
  `emailEnabled` enum('true','false') NOT NULL DEFAULT 'true',
  `phoneEnabled` enum('true','false') NOT NULL DEFAULT 'true',
  `userPassword` varchar(500) NOT NULL,
  `userPhone` varchar(15) NOT NULL,
  `phoneCarrier` text NOT NULL,
  `contactInterval` int(20) NOT NULL DEFAULT '43200',
  `lastLogin` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userId`),
  UNIQUE KEY `userEmail` (`userEmail`),
  UNIQUE KEY `userId` (`userId`),
  KEY `userId_2` (`userId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=866 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
