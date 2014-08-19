-- phpMyAdmin SQL Dump
-- version 4.2.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2014-08-19 17:00:14
-- 服务器版本： 5.6.16
-- PHP Version: 5.5.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `eva`
--

-- --------------------------------------------------------

--
-- 表的结构 `eva_sundry_blocks`
--

CREATE TABLE IF NOT EXISTS `eva_sundry_blocks` (
`id` mediumint(8) unsigned NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `visibility` enum('visible','invisible') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'visible',
  `createdAt` int(10) unsigned NOT NULL,
  `updatedAt` int(10) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `eva_sundry_blocks`
--

INSERT INTO `eva_sundry_blocks` (`id`, `name`, `content`, `visibility`, `createdAt`, `updatedAt`) VALUES
(1, 'friendlinks', '<p class="friend-links"><span class="title">财经</span> <a class="link" href="">Hao123</a> | <a class="link" href="">新浪财经</a> | <a class="link" href="">腾讯财经</a> | <a class="link" href="">和讯财经</a> | <a class="link" href="">财经网</a> |</p>\r\n\r\n<p class="friend-links"><span class="title">财经</span> <a class="link" href="">Hao123</a> | <a class="link" href="">新浪财经</a> | <a class="link" href="">腾讯财经</a> | <a class="link" href="">和讯财经</a> | <a class="link" href="">财经网</a> |</p>\r\n\r\n<p class="friend-links"><span class="title">财经</span> <a class="link" href="">Hao123</a> | <a class="link" href="">新浪财经</a> | <a class="link" href="">腾讯财经</a> | <a class="link" href="">和讯财经</a> | <a class="link" href="">财经网</a> |</p>\r\n\r\n<p class="friend-links"><span class="title">财经</span> <a class="link" href="">Hao123</a> | <a class="link" href="">新浪财经</a> | <a class="link" href="">腾讯财经</a> | <a class="link" href="">和讯财经</a> | <a class="link" href="">财经网</a> |</p>\r\n', 'visible', 1408438688, 1408438688);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eva_sundry_blocks`
--
ALTER TABLE `eva_sundry_blocks`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eva_sundry_blocks`
--
ALTER TABLE `eva_sundry_blocks`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;