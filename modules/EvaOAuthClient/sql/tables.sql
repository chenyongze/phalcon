DROP TABLE IF EXISTS `eva_oauth_accesstokens`;
CREATE TABLE IF NOT EXISTS `eva_oauth_accesstokens` (
  `adapterKey` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(520) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `version` enum('OAuth1','OAuth2') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'OAuth2',
  `tokenStatus` enum('active','expried') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `scope` text COLLATE utf8_unicode_ci,
  `refreshToken` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `refreshedAt` int(10) DEFAULT NULL,
  `expireTime` datetime DEFAULT NULL,
  `remoteToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remoteUserId` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `remoteUserName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remoteNickName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remoteEmail` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remoteImageUrl` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remoteExtra` mediumtext COLLATE utf8_unicode_ci,
  `userId` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adapterKey`,`version`,`remoteUserId`),
  KEY `user_id` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
