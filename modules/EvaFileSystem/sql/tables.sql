SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `scrapy` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `scrapy`;

DROP TABLE IF EXISTS `eva_file_files`;
CREATE TABLE IF NOT EXISTS `eva_file_files` (
  `id` bigint(30) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('deleted','draft','published','pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'published',
  `storageAdapter` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'local',
  `isImage` tinyint(1) NOT NULL DEFAULT '0',
  `fileName` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `fileExtension` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `originalName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `filePath` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `fileHash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fileSize` bigint(20) DEFAULT NULL,
  `mimeType` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `imageWidth` smallint(5) DEFAULT NULL,
  `imageHeight` smallint(5) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sortOrder` int(10) DEFAULT NULL,
  `userId` int(10) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=283 ;
