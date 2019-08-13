/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : hd_cztv

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-04-25 16:58:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for running_man
-- ----------------------------
DROP TABLE IF EXISTS `running_man`;
CREATE TABLE `running_man` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `phase_name` varchar(255) NOT NULL COMMENT '期数：常驻MC//第一期',
  `sort` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发布1;下架0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
