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
  `game_id` int(50) NOT NULL AUTO_INCREMENT COMMENT '游戏ID',
  `picture_id` int(50) DEFAULT NULL COMMENT '图片id',
  `game_starttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '游戏开始时间',
  `game_endtime` timestamp NULL DEFAULT NULL COMMENT '游戏结束时间',
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `game_info` */

/*Table structure for table `game_user_info` */

DROP TABLE IF EXISTS `game_user_info`;

CREATE TABLE `game_user_info` (
  `game_id` int(11) NOT NULL COMMENT '游戏ID',
  `user_id` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '用户ID',
  PRIMARY KEY (`game_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `game_user_info` */

/*Table structure for table `picture_info` */

DROP TABLE IF EXISTS `picture_info`;

CREATE TABLE `picture_info` (
  `picture_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图片id',
  `picture_word` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '图片对应的词',
  `picture_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '画图时间',
  `picture_author` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '作者',
  PRIMARY KEY (`picture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `picture_info` */

/*Table structure for table `user_info` */

DROP TABLE IF EXISTS `user_info`;

CREATE TABLE `user_info` (
  `user_id` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '用户ID',
  `password` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '密码',
  `register_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '注册时间',
  `user_score` int(6) NOT NULL DEFAULT '0' COMMENT '金币数',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `user_info` */

insert  into `user_info`(`user_id`,`password`,`register_time`,`user_score`) values ('fangxin','fangxin','2012-04-05 17:50:19',0);

/*Table structure for table `words` */

DROP TABLE IF EXISTS `words`;

CREATE TABLE `words` (
  `word` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '单词',
  `meaning` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '汉语解释',
  PRIMARY KEY (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `words` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
