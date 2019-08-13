/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : town

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-07-26 14:44:59
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `cdn_main_log`
-- ----------------------------
DROP TABLE IF EXISTS `cdn_main_log`;
CREATE TABLE `cdn_main_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '频道ID',
  `title` varchar(255) NOT NULL COMMENT '任务标题',
  `task_user_id` int(11) NOT NULL DEFAULT '1' COMMENT '任务发起者id',
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '该任务在来源系统中的唯一id',
  `operation` int(11) NOT NULL DEFAULT '1' COMMENT '任务操作命令:1下发;2更新;3删除;',
  `item_num` int(11) NOT NULL DEFAULT '1' COMMENT '该条主任务下面子任务的待完成的数量，用来解决主任务完全分发成功后的状态判断',
  `content` text NOT NULL COMMENT '分发任务主体内容json',
  `cdn_id` varchar(255) NOT NULL COMMENT '选择的cdn产商(后期因为会有多厂商的情况字段类型选择varchar)',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '分发任务状态:1待分发;2分发中;3分发成功;4分发失败;',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '任务创建时间',
  `admin_user_id` int(11) DEFAULT '0' COMMENT '最后一次操作后台人员id',
  `admin_user_name` varchar(255) DEFAULT NULL COMMENT '最后一次操作后台人员用户名',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后一次更新时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '任务结束时间',
  `is_del` int(11) NOT NULL DEFAULT '0' COMMENT '是否删除 0不删除 1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cdn_main_log
-- ----------------------------
