/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : hd_cztv

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-04-25 16:58:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for running_guest
-- ----------------------------
DROP TABLE IF EXISTS `running_guest`;
CREATE TABLE `running_guest` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `phase_id` int(10) NOT NULL COMMENT '第几期',
  `guest_name` varchar(255) NOT NULL COMMENT '嘉宾名称',
  `guest_img` varchar(255) NOT NULL COMMENT '嘉宾头像图片',
  `guest_step` int(10) NOT NULL DEFAULT '10000' COMMENT '步数',
  `sort` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `phase_id` (`phase_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
