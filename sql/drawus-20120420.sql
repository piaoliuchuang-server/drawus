/*
SQLyog Community Edition- MySQL GUI v6.16 RC2
MySQL - 5.5.20-log : Database - drawus
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

create database if not exists `drawus`;

USE `drawus`;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `game_info` */

DROP TABLE IF EXISTS `game_info`;

CREATE TABLE `game_info` (
  `game_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '游戏ID',
  `game_starttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '游戏开始时间',
  `game_endtime` timestamp NULL DEFAULT NULL COMMENT '游戏结束时间',
  `current_status` tinyint(4) NOT NULL COMMENT '游戏当前状态',
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

/*Table structure for table `game_user_info` */

DROP TABLE IF EXISTS `game_user_info`;

CREATE TABLE `game_user_info` (
  `game_id` int(11) NOT NULL COMMENT '游戏ID',
  `user_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '用户ID',
  `position` tinyint(4) NOT NULL COMMENT '身份（draw，guess）',
  PRIMARY KEY (`game_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `picture_info` */

DROP TABLE IF EXISTS `picture_info`;

CREATE TABLE `picture_info` (
  `picture_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图画id',
  `word` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '图画对应的词',
  `game_id` int(11) NOT NULL COMMENT '图片对应的游戏id',
  `picture_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '画图时间',
  `picture_path` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '存放图画的路径',
  PRIMARY KEY (`picture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `picture_players_info` */

DROP TABLE IF EXISTS `picture_players_info`;

CREATE TABLE `picture_players_info` (
  `picture_id` int(11) NOT NULL COMMENT '图画id',
  `user_id` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '用户id',
  `result_status` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '用户猜图的状态',
  `time` time DEFAULT NULL COMMENT '猜（画）图时间',
  PRIMARY KEY (`picture_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `user_info` */

DROP TABLE IF EXISTS `user_info`;

CREATE TABLE `user_info` (
  `user_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '用户ID',
  `uuid` varchar(60) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'iphone客户端标识',
  `token` varchar(80) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT 'iphone客户端token',
  `password` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '密码',
  `register_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '注册时间',
  `user_score` int(6) NOT NULL DEFAULT '0' COMMENT '金币数',
  PRIMARY KEY (`user_id`),
  KEY `udid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `words` */

DROP TABLE IF EXISTS `words`;

CREATE TABLE `words` (
  `word` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '单词',
  `word_type` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '单词类型',
  `word_degree` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT '单词难度',
  PRIMARY KEY (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
