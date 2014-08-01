-- phpMyAdmin SQL Dump
-- version 4.2.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2014-07-31 17:20:02
-- 服务器版本： 5.6.16
-- PHP Version: 5.5.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `eva`
--

-- --------------------------------------------------------

--
-- 表的结构 `eva_wiki_categories`
--

CREATE TABLE IF NOT EXISTS `eva_wiki_categories` (
`id` int(10) NOT NULL,
  `categoryName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `initial` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `isRoot` tinyint(1) DEFAULT '1',
  `sortOrder` int(10) DEFAULT '0',
  `createdAt` int(10) DEFAULT NULL,
  `count` int(10) DEFAULT '0',
  `imageId` int(10) DEFAULT '0',
  `image` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_wiki_categories_categories`
--

CREATE TABLE IF NOT EXISTS `eva_wiki_categories_categories` (
  `categoryId` int(11) NOT NULL,
  `parentId` int(11) NOT NULL COMMENT '上级分类 ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='wiki 词条分类与分类的关系表';

-- --------------------------------------------------------

--
-- 表的结构 `eva_wiki_categories_entries`
--

CREATE TABLE IF NOT EXISTS `eva_wiki_categories_entries` (
  `categoryId` int(11) NOT NULL,
  `entryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `eva_wiki_entries`
--

CREATE TABLE IF NOT EXISTS `eva_wiki_entries` (
`id` int(10) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `initial` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '首字母',
  `status` enum('deleted','draft','published','pending') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `flag` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `visibility` enum('public','private','password') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'public',
  `codeType` enum('html','markdown') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'markdown',
  `language` varchar(5) COLLATE utf8_unicode_ci DEFAULT 'zh',
  `createdAt` int(10) NOT NULL,
  `userId` int(10) DEFAULT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updatedAt` int(10) DEFAULT NULL,
  `editorId` int(10) DEFAULT NULL,
  `editorName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `count` bigint(20) DEFAULT '0',
  `imageId` int(10) unsigned NOT NULL DEFAULT '0',
  `image` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `summary` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sourceName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sourceUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `eva_wiki_entry_keywords`
--

CREATE TABLE IF NOT EXISTS `eva_wiki_entry_keywords` (
  `entryId` int(10) NOT NULL,
  `keyword` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '关键词'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='关联词条';

-- --------------------------------------------------------

--
-- 表的结构 `eva_wiki_entry_texts`
--

CREATE TABLE IF NOT EXISTS `eva_wiki_entry_texts` (
  `entryId` int(10) NOT NULL,
  `metaKeywords` text COLLATE utf8_unicode_ci,
  `metaDescription` text COLLATE utf8_unicode_ci,
  `toc` text COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eva_wiki_categories`
--
ALTER TABLE `eva_wiki_categories`
 ADD PRIMARY KEY (`id`), ADD KEY `categoryName` (`categoryName`);

--
-- Indexes for table `eva_wiki_categories_categories`
--
ALTER TABLE `eva_wiki_categories_categories`
 ADD PRIMARY KEY (`categoryId`,`parentId`);

--
-- Indexes for table `eva_wiki_categories_entries`
--
ALTER TABLE `eva_wiki_categories_entries`
 ADD PRIMARY KEY (`categoryId`,`entryId`);

--
-- Indexes for table `eva_wiki_entries`
--
ALTER TABLE `eva_wiki_entries`
 ADD PRIMARY KEY (`id`), ADD KEY `createdAt` (`createdAt`);

--
-- Indexes for table `eva_wiki_entry_keywords`
--
ALTER TABLE `eva_wiki_entry_keywords`
 ADD UNIQUE KEY `entry_keyword` (`entryId`,`keyword`);

--
-- Indexes for table `eva_wiki_entry_texts`
--
ALTER TABLE `eva_wiki_entry_texts`
 ADD PRIMARY KEY (`entryId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eva_wiki_categories`
--
ALTER TABLE `eva_wiki_categories`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `eva_wiki_entries`
--
ALTER TABLE `eva_wiki_entries`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;