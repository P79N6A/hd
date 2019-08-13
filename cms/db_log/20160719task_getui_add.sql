/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : hd_cztv

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-07-19 17:43:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for task_getui
-- ----------------------------
DROP TABLE IF EXISTS `task_getui`;
CREATE TABLE `task_getui` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` int(11) DEFAULT NULL COMMENT '开始时间',
  `channel_id` int(11) DEFAULT NULL COMMENT '频道ID',
  `admin_id` int(11) DEFAULT NULL COMMENT '0 标识系统操作执行',
  `client_type` varchar(50) DEFAULT NULL COMMENT '终端类型',
  `getui_range` tinyint(1) DEFAULT NULL COMMENT '个推是否全量推送',
  `getui_type` tinyint(1) DEFAULT NULL,
  `mess_body` varchar(255) DEFAULT NULL,
  `mess_title` varchar(255) DEFAULT NULL,
  `mess_id` int(11) DEFAULT NULL COMMENT '消息ID/媒资ID、',
  `mess_url` varchar(255) DEFAULT NULL COMMENT '个推消息URL',
  `ret_status` varchar(10) DEFAULT NULL COMMENT '推送成功与否返回状态值',
  `ret_contentId` varchar(255) DEFAULT NULL COMMENT '返回CONTENTID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
