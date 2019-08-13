/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : town

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-07-26 14:45:11
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `cdn_push_error_log`
-- ----------------------------
DROP TABLE IF EXISTS `cdn_push_error_log`;
CREATE TABLE `cdn_push_error_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cdn_id` int(11) NOT NULL DEFAULT '0' COMMENT '出错cdn_id',
  `content` text NOT NULL COMMENT '错误内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cdn_push_error_log
-- ----------------------------
