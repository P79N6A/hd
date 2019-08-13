/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : town

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-07-26 14:45:17
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `cdn_return_log`
-- ----------------------------
DROP TABLE IF EXISTS `cdn_return_log`;
CREATE TABLE `cdn_return_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cdn_id` int(11) NOT NULL DEFAULT '1' COMMENT '对应cdn_id',
  `type` int(3) NOT NULL DEFAULT '0' COMMENT 'cdn回调数据类型0:同步回调 1：异步回调',
  `content` text COMMENT '回调内容',
  `create_time` varchar(255) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cdn_return_log
-- ----------------------------
