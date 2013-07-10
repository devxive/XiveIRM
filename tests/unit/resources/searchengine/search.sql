-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 09, 2012 at 09:40 AM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `search`
--

-- --------------------------------------------------------

--
-- Table structure for table `search`
--

CREATE TABLE IF NOT EXISTS `search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `search`
--

INSERT INTO `search` (`id`, `title`, `description`, `url`, `keywords`) VALUES
(1, 'sinimma Youtube Channel', 'The sinimma Youtube Channel.', 'http://www.youtube.com/sinimma', 'sinimma youtube channel'),
(2, 'Google', 'this is google', 'http://www.google.com', 'google search engine'),
(3, 'Yahoo!', 'The Yahoo! search engine.', 'http://www.yahoo.com', 'yahoo search engine'),
(4, 'YouTube - Broadcast Yourself!', 'The biggest video sharing website in the world.', 'http://www.youtube.com', 'youtube google video sharing'),
(5, 'Apple', 'The official Apple website.', 'http://www.apple.com', 'apple website iphone ipad imac ios');
