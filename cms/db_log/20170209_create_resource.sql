/*
Navicat MySQL Data Transfer

Source Server         : cztv
Source Server Version : 100021
Source Host           : 10.1.121.56:3306
Source Database       : usercenter

Target Server Type    : MYSQL
Target Server Version : 100021
File Encoding         : 65001

Date: 2017-02-09 16:48:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for resources
-- ----------------------------
DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_title` varchar(255) DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '静态资源名字',
  `code_version` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '代码版本号',
  `cdn_version` int(5) NOT NULL DEFAULT '0' COMMENT 'cdn资源版本',
  `path` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'cdn上的路径',
  `updated_at` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `intro` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '介绍',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = '静态资源';
SET FOREIGN_KEY_CHECKS=1;
