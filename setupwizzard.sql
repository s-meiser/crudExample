-- Adminer 4.7.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `setupwizard` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `setupwizard`;

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `uuid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `category` (`uuid`, `name`) VALUES
('1',	'test1'),
('2',	'test2');

DROP TABLE IF EXISTS `listofcategory`;
CREATE TABLE `listofcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `listofcategory` (`id`, `name`, `comment`) VALUES
(1,	'test1',	'comment1'),
(2,	'test2',	'comment2'),
(3,	'test2',	'comment'),
(4,	'asd',	'456456');

DROP TABLE IF EXISTS `migration_versions`;
CREATE TABLE `migration_versions` (
  `version` varchar(14) NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2DA17977F85E0677` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `username`, `roles`, `password`) VALUES
(1,	'admin',	'{\"role\":\"ROLE_ADMIN\"}',	'$argon2id$v=19$m=65536,t=4,p=1$YjR6Zi90MHI2S211eEJPQQ$oHeIXCvAduZAPtkhEazBSXPlSNapfkxcqqDQvReAsMI');

-- 2020-02-03 16:31:05
