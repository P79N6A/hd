/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : town

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-07-26 14:44:51
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `cdn_insert_error_log`
-- ----------------------------
DROP TABLE IF EXISTS `cdn_insert_error_log`;
CREATE TABLE `cdn_insert_error_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `error_type` int(11) NOT NULL DEFAULT '0' COMMENT '1 empty main data;2 wrong app_secret;3 Redis fail connect ;4 wrong json type;5 主任务入库出错 ;6 子任务入库出错',
  `task_id` int(11) DEFAULT NULL COMMENT '错误任务id（来源系统唯一id）',
  `msg` varchar(255) DEFAULT NULL COMMENT '错误描述',
  `data` text NOT NULL COMMENT '报错json数据',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cdn_insert_error_log
-- ----------------------------
