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
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

/*Data for the table `game_info` */

insert  into `game_info`(`game_id`,`game_starttime`,`game_endtime`) values (6,'2012-04-11 21:41:36',NULL),(7,'2012-04-11 21:42:26',NULL),(8,'2012-04-11 22:00:15',NULL),(9,'2012-04-11 22:07:13',NULL),(10,'2012-04-11 22:18:13',NULL),(11,'2012-04-11 22:19:03',NULL),(12,'2012-04-11 22:19:42',NULL),(13,'2012-04-12 14:50:13',NULL),(14,'2012-04-12 15:32:54',NULL),(15,'2012-04-12 18:26:49',NULL),(16,'2012-04-12 18:27:16',NULL),(17,'2012-04-12 18:34:39',NULL),(18,'2012-04-12 18:36:43',NULL),(19,'2012-04-12 18:37:30',NULL),(20,'2012-04-12 18:39:31',NULL),(21,'2012-04-12 18:39:56',NULL),(22,'2012-04-12 19:16:16',NULL),(23,'2012-04-13 13:49:24',NULL);

/*Table structure for table `game_user_info` */

DROP TABLE IF EXISTS `game_user_info`;

CREATE TABLE `game_user_info` (
  `game_id` int(11) NOT NULL COMMENT '游戏ID',
  `user_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '用户ID',
  `position` varchar(5) CHARACTER SET utf8 NOT NULL COMMENT '身份（draw，guess）',
  PRIMARY KEY (`game_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `game_user_info` */

insert  into `game_user_info`(`game_id`,`user_id`,`position`) values (22,'fangxin','guess'),(22,'yutianhang111','draw'),(23,'fangxin','draw'),(23,'yutianhang','guess');

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

/*Data for the table `picture_info` */

/*Table structure for table `picture_players_info` */

DROP TABLE IF EXISTS `picture_players_info`;

CREATE TABLE `picture_players_info` (
  `picture_id` int(11) NOT NULL COMMENT '图画id',
  `user_id` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '用户id',
  `player_position` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '用户身份（画画者还是猜画者）',
  `guess_status` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT 'doing' COMMENT '用户猜图的状态',
  `guess_time` time DEFAULT NULL COMMENT '用户猜图时间',
  PRIMARY KEY (`picture_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `picture_players_info` */

/*Table structure for table `user_info` */

DROP TABLE IF EXISTS `user_info`;

CREATE TABLE `user_info` (
  `user_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '用户ID',
  `udid` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'iphone客户端标识',
  `password` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '密码',
  `register_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '注册时间',
  `user_score` int(6) NOT NULL DEFAULT '0' COMMENT '金币数',
  PRIMARY KEY (`user_id`),
  KEY `udid` (`udid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `user_info` */

insert  into `user_info`(`user_id`,`udid`,`password`,`register_time`,`user_score`) values ('fangxin','','fangxin','2012-04-05 17:50:19',0),('yutianhang','','yutianhang','2012-04-09 18:13:42',2);

/*Table structure for table `words` */

DROP TABLE IF EXISTS `words`;

CREATE TABLE `words` (
  `word` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '单词',
  `word_type` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '单词类型',
  `word_degree` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT '单词难度',
  PRIMARY KEY (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `words` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
