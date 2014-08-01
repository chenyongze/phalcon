-- phpMyAdmin SQL Dump
-- version 4.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 30, 2014 at 02:38 PM
-- Server version: 5.6.19-log
-- PHP Version: 5.5.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `scrapy`
--

-- --------------------------------------------------------

--
-- Table structure for table `eva_comment_comments`
--

DROP TABLE IF EXISTS `eva_comment_comments`;
CREATE TABLE IF NOT EXISTS `eva_comment_comments` (
`id` int(11) NOT NULL,
  `threadId` int(11) NOT NULL,
  `status` enum('approved','pending','spam','deleted','ham','dangerous') NOT NULL DEFAULT 'pending',
  `codeType` varchar(30) DEFAULT NULL,
  `content` text NOT NULL,
  `rootId` int(11) DEFAULT NULL,
  `parentId` int(11) DEFAULT NULL,
  `parentPath` varchar(200) DEFAULT NULL,
  `depth` tinyint(4) DEFAULT NULL,
  `userId` int(10) DEFAULT NULL,
  `username` varchar(64) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `userSite` varchar(255) DEFAULT NULL,
  `userType` varchar(50) DEFAULT NULL,
  `sourceName` varchar(50) DEFAULT NULL,
  `sourceUrl` varchar(255) DEFAULT NULL,
  `createdAt` int(10) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=70 ;

-- --------------------------------------------------------

--
-- Table structure for table `eva_comment_filter`
--

DROP TABLE IF EXISTS `eva_comment_filter`;
CREATE TABLE IF NOT EXISTS `eva_comment_filter` (
`id` int(11) NOT NULL,
  `siteid` int(11) NOT NULL,
  `word` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `level` tinyint(3) NOT NULL DEFAULT '2' COMMENT '可疑级别1黄2红',
  `scappeal` tinyint(3) NOT NULL DEFAULT '0' COMMENT '存在状态',
  `ambit` tinyint(3) NOT NULL DEFAULT '0' COMMENT '适用范围'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=134 ;

-- --------------------------------------------------------

--
-- Table structure for table `eva_comment_threads`
--

DROP TABLE IF EXISTS `eva_comment_threads`;
CREATE TABLE IF NOT EXISTS `eva_comment_threads` (
`id` int(11) NOT NULL,
  `uniqueKey` varchar(255) NOT NULL,
  `permalink` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `defaultCommentStatus` enum('approved','pending') NOT NULL DEFAULT 'approved',
  `numComments` varchar(45) DEFAULT NULL,
  `lastCommentAt` int(10) DEFAULT NULL,
  `channel` varchar(45) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=926 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eva_comment_comments`
--
ALTER TABLE `eva_comment_comments`
 ADD PRIMARY KEY (`id`), ADD KEY `threadId` (`threadId`);

--
-- Indexes for table `eva_comment_filter`
--
ALTER TABLE `eva_comment_filter`
 ADD PRIMARY KEY (`id`), ADD KEY `siteid` (`siteid`);

--
-- Indexes for table `eva_comment_threads`
--
ALTER TABLE `eva_comment_threads`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `threadKey` (`uniqueKey`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eva_comment_comments`
--
ALTER TABLE `eva_comment_comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `eva_comment_filter`
--
ALTER TABLE `eva_comment_filter`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=134;
--
-- AUTO_INCREMENT for table `eva_comment_threads`
--
ALTER TABLE `eva_comment_threads`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=926;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
