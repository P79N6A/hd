/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : town

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-07-26 14:45:28
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `cdn_user_operation_log`
-- ----------------------------
DROP TABLE IF EXISTS `cdn_user_operation_log`;
CREATE TABLE `cdn_user_operation_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_user_id` int(11) NOT NULL COMMENT '后台用户id',
  `admin_user_name` varchar(255) NOT NULL COMMENT '后台用户名称',
  `data_type` int(11) NOT NULL COMMENT '操作对象类型 1：主任务 2：子任务',
  `data_id` int(11) NOT NULL COMMENT '被操作任务id',
  `data_status` int(11) NOT NULL COMMENT '操作类型 1重发 2删除',
  `create_time` int(11) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cdn_user_operation_log
-- ----------------------------
