# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.19-log)
# Database: scrapy
# Generation Time: 2014-08-21 02:17:59 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table eva_comment_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eva_comment_comments`;

CREATE TABLE `eva_comment_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `threadId` int(11) NOT NULL,
  `status` enum('approved','pending','spam','deleted','ham','dangerous') NOT NULL DEFAULT 'pending',
  `codeType` varchar(30) DEFAULT NULL,
  `content` text NOT NULL,
  `rootId` int(11) DEFAULT NULL,
  `parentId` int(11) DEFAULT NULL,
  `parentPath` varchar(200) DEFAULT NULL,
  `depth` tinyint(4) DEFAULT NULL,
  `numReply` int(11) NOT NULL DEFAULT '0',
  `upVotes` int(11) NOT NULL DEFAULT '0',
  `downVotes` int(11) NOT NULL DEFAULT '0',
  `userId` int(10) DEFAULT NULL,
  `username` varchar(64) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `userSite` varchar(255) DEFAULT NULL,
  `userType` varchar(50) DEFAULT NULL,
  `sourceName` varchar(50) DEFAULT NULL,
  `sourceUrl` varchar(255) DEFAULT NULL,
  `updatedAt` int(10) NOT NULL,
  `createdAt` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `threadId` (`threadId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table eva_comment_filters
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eva_comment_filters`;

CREATE TABLE `eva_comment_filters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `level` tinyint(3) NOT NULL DEFAULT '2' COMMENT '可疑级别1黄2红',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `eva_comment_filters` WRITE;
/*!40000 ALTER TABLE `eva_comment_filters` DISABLE KEYS */;

INSERT INTO `eva_comment_filters` (`id`, `word`, `level`)
VALUES
	(5,'洪志',2),
	(8,'法-輪',2),
	(9,'fa-lun',2),
	(11,'护法',2),
	(16,'真善忍',1),
	(17,'毛泽东',1),
	(21,'东突',2),
	(22,'暴动',2),
	(23,'台海局势',2),
	(24,'白衣行动',2),
	(25,'达赖',2),
	(26,'藏-独',2),
	(27,'靖国神社',2),
	(28,'GCD',2),
	(29,'联名信',2),
	(30,'集会',2),
	(31,'法lun',2),
	(32,'跳楼',2),
	(33,'坠楼',2),
	(34,'08宪章',2),
	(35,'09行动',2),
	(37,'黑心',2),
	(38,'八平方',2),
	(39,'陈良宇',2),
	(40,'法货',2),
	(41,'绝食',2),
	(43,'六-四',2),
	(44,'四立方',2),
	(45,'戆大',2),
	(46,'甲流',2),
	(47,'俞正声',2),
	(48,'强-奸',2),
	(49,'黑社会',1),
	(51,'施皓',2),
	(52,'自杀',2),
	(53,'圣战',2),
	(56,'涛哥',2),
	(57,'温影帝',2),
	(58,'邬宪伟',2),
	(59,'夏瑤',2),
	(60,'美国国际领袖基金会',2),
	(61,'马德秀',2),
	(66,'自杀',2),
	(120,'法-轮-功',2),
	(134,'q',2),
	(135,'Q',2);

/*!40000 ALTER TABLE `eva_comment_filters` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table eva_comment_threads
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eva_comment_threads`;

CREATE TABLE `eva_comment_threads` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table eva_comment_votes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `eva_comment_votes`;

CREATE TABLE `eva_comment_votes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `commentId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `action` enum('up','down') NOT NULL DEFAULT 'up',
  `createdAt` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
