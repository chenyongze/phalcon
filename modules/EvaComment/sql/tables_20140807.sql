-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 07, 2014 at 02:24 PM
-- Server version: 5.5.38-0ubuntu0.14.04.1-log
-- PHP Version: 5.5.9-1ubuntu4.3

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

CREATE TABLE IF NOT EXISTS `eva_comment_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `createdAt` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `threadId` (`threadId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=95 ;

--
-- Dumping data for table `eva_comment_comments`
--

INSERT INTO `eva_comment_comments` (`id`, `threadId`, `status`, `codeType`, `content`, `rootId`, `parentId`, `parentPath`, `depth`, `userId`, `username`, `email`, `userSite`, `userType`, `sourceName`, `sourceUrl`, `createdAt`) VALUES
(70, 926, 'approved', 'TEXT', '嘎达撒个', 0, 0, '', 0, NULL, '测试', NULL, NULL, NULL, NULL, NULL, 1407152311),
(71, 926, 'approved', 'TEXT', 'test', 70, 70, '70', 1, NULL, 'anonymous', NULL, NULL, NULL, NULL, NULL, 1407152316),
(72, 926, 'approved', 'TEXT', '反对撒范德萨', 0, 0, '', 0, NULL, '测试', NULL, NULL, NULL, NULL, NULL, 1407152327),
(73, 926, 'approved', 'TEXT', '士大夫的撒', 70, 71, '70/71', 2, NULL, 'anonymous', NULL, NULL, NULL, NULL, NULL, 1407152346),
(74, 929, 'approved', 'TEXT', '黄金市场目前正在密切关注是否会出现因工资', 0, 0, '', 0, NULL, '测试', NULL, NULL, NULL, NULL, NULL, 1407318726),
(75, 929, 'approved', 'TEXT', 'test', 74, 74, '74', 1, NULL, 'anonymous', NULL, NULL, NULL, NULL, NULL, 1407319262),
(76, 929, 'approved', 'TEXT', 'test', 74, 75, '74/75', 2, NULL, 'anonymous', NULL, NULL, NULL, NULL, NULL, 1407319266),
(77, 929, 'approved', 'TEXT', 'test', 74, 76, '74/75/76', 3, NULL, 'anonymous', NULL, NULL, NULL, NULL, NULL, 1407319270),
(78, 929, 'approved', 'TEXT', 'aaaa', 0, 0, '', 0, NULL, '测试', NULL, NULL, NULL, NULL, NULL, 1407319290),
(79, 929, 'approved', 'TEXT', 'asgdsagsd', 0, 0, '', 0, NULL, '测试', NULL, NULL, NULL, NULL, NULL, 1407319389),
(80, 930, 'approved', 'TEXT', 'test', 0, 0, '', 0, NULL, '测试', NULL, NULL, NULL, NULL, NULL, 1407319736),
(81, 930, 'approved', 'TEXT', 'test', 0, 0, '', 0, NULL, '测试', NULL, NULL, NULL, NULL, NULL, 1407319748),
(82, 930, 'approved', 'TEXT', 'test', 0, 0, '', 0, NULL, '测试', NULL, NULL, NULL, NULL, NULL, 1407319764),
(83, 930, 'approved', 'TEXT', 'gdsagdsagdsa', 0, 0, '', 0, NULL, '测试', NULL, NULL, NULL, NULL, NULL, 1407320096),
(84, 930, 'approved', 'TEXT', 'gdsagdsa', 83, 83, '83', 1, NULL, 'aaa', NULL, NULL, NULL, NULL, NULL, 1407320108),
(85, 930, 'approved', 'TEXT', 'dsafdsafsad', 83, 83, '83', 1, NULL, 'eee', NULL, NULL, NULL, NULL, NULL, 1407320120),
(86, 930, 'approved', 'TEXT', 'fdasfdsa', 0, 0, '', 0, NULL, 'rrrr', NULL, NULL, NULL, NULL, NULL, 1407320290),
(87, 930, 'approved', 'TEXT', 'ddddd', 0, 0, '', 0, NULL, 'eee', NULL, NULL, NULL, NULL, NULL, 1407320302),
(88, 930, 'approved', 'TEXT', 'dfsafdsa', 87, 87, '87', 1, NULL, 'aaa', NULL, NULL, NULL, NULL, NULL, 1407320321),
(89, 930, 'approved', 'TEXT', 'gdsagdsa', 87, 87, '87', 1, NULL, 'aaa', NULL, NULL, NULL, NULL, NULL, 1407320948),
(90, 930, 'approved', 'TEXT', 'fdsafdas', 0, 0, '', 0, NULL, 'test', NULL, NULL, NULL, NULL, NULL, 1407328749),
(91, 930, 'approved', 'TEXT', 'fdsafdsa', 90, 90, '90', 1, NULL, 'aaa', NULL, NULL, NULL, NULL, NULL, 1407328757),
(92, 930, 'approved', 'TEXT', 'test', 83, 84, '83/84', 2, NULL, '333', NULL, NULL, NULL, NULL, NULL, 1407328768),
(93, 931, 'approved', 'TEXT', 'test', 0, 0, '', 0, NULL, 'aaa', NULL, NULL, NULL, NULL, NULL, 1407333886),
(94, 931, 'pending', 'TEXT', 'qq', 0, 0, '', 0, NULL, 'qqq', NULL, NULL, NULL, NULL, NULL, 1407333893);

-- --------------------------------------------------------

--
-- Table structure for table `eva_comment_filters`
--

CREATE TABLE IF NOT EXISTS `eva_comment_filters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `level` tinyint(3) NOT NULL DEFAULT '2' COMMENT '可疑级别1黄2红',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=140 ;

--
-- Dumping data for table `eva_comment_filters`
--

INSERT INTO `eva_comment_filters` (`id`, `word`, `level`) VALUES
(5, '洪志', 2),
(8, '法-輪', 2),
(9, 'fa-lun', 2),
(11, '护法', 2),
(16, '真善忍', 1),
(17, '毛泽东', 1),
(21, '东突', 2),
(22, '暴动', 2),
(23, '台海局势', 2),
(24, '白衣行动', 2),
(25, '达赖', 2),
(26, '藏-独', 2),
(27, '靖国神社', 2),
(28, 'GCD', 2),
(29, '联名信', 2),
(30, '集会', 2),
(31, '法lun', 2),
(32, '跳楼', 2),
(33, '坠楼', 2),
(34, '08宪章', 2),
(35, '09行动', 2),
(37, '黑心', 2),
(38, '八平方', 2),
(39, '陈良宇', 2),
(40, '法货', 2),
(41, '绝食', 2),
(43, '六-四', 2),
(44, '四立方', 2),
(45, '戆大', 2),
(46, '甲流', 2),
(47, '俞正声', 2),
(48, '强-奸', 2),
(49, '黑社会', 1),
(51, '施皓', 2),
(52, '自杀', 2),
(53, '圣战', 2),
(56, '涛哥', 2),
(57, '温影帝', 2),
(58, '邬宪伟', 2),
(59, '夏瑤', 2),
(60, '美国国际领袖基金会', 2),
(61, '马德秀', 2),
(66, '自杀', 2),
(120, '法-轮-功', 2),
(134, 'q', 2),
(135, 'Q', 2);

-- --------------------------------------------------------

--
-- Table structure for table `eva_comment_threads`
--

CREATE TABLE IF NOT EXISTS `eva_comment_threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniqueKey` varchar(255) NOT NULL,
  `permalink` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `defaultCommentStatus` enum('approved','pending') NOT NULL DEFAULT 'approved',
  `numComments` varchar(45) DEFAULT NULL,
  `lastCommentAt` int(10) DEFAULT NULL,
  `channel` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `threadKey` (`uniqueKey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=932 ;

--
-- Dumping data for table `eva_comment_threads`
--

INSERT INTO `eva_comment_threads` (`id`, `uniqueKey`, `permalink`, `title`, `defaultCommentStatus`, `numComments`, `lastCommentAt`, `channel`) VALUES
(926, 'post_98931', 'http://rebirth.wallstreetcn.com/node/98931', 'undefined', 'approved', '4', 1407152346, '0'),
(927, 'post_98991', 'http://rebirth.wallstreetcn.com/node/98991', 'undefined', 'approved', '0', 1407201502, '0'),
(928, 'post_98861', 'http://rebirth.wallstreetcn.com/node/98861', 'undefined', 'approved', '0', 1407202553, '0'),
(929, 'post_98969', 'http://www.goldtoutiao.com/post/adc11e36d54c67b28a71aeb50f097329', 'undefined', 'approved', '6', 1407319389, '0'),
(930, 'post_52', 'http://www.goldtoutiao.com/post/messageboard', 'undefined', 'approved', '13', 1407328768, '0'),
(931, 'post_98972', 'http://www.goldtoutiao.com/post/fa01f31577abc594ff094247018f16fb', 'undefined', 'approved', '2', 1407333893, '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
