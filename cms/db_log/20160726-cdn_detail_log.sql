/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : town

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-07-26 14:44:33
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `cdn_detail_log`
-- ----------------------------
DROP TABLE IF EXISTS `cdn_detail_log`;
CREATE TABLE `cdn_detail_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `main_id` int(11) NOT NULL DEFAULT '0' COMMENT '该条子任务对应的主任务id',
  `task_user_id` int(11) NOT NULL DEFAULT '1' COMMENT '任务发起者id',
  `item_id` int(11) NOT NULL DEFAULT '0' COMMENT '同一条主任务的子任务之间区分id',
  `operation` int(11) NOT NULL DEFAULT '1' COMMENT '任务操作命令:1下发;2更新;3删除;',
  `cdn_id` varchar(255) NOT NULL COMMENT '选择的cdn产商(后期因为会有多厂商的情况字段类型选择varchar)',
  `file_type` int(11) NOT NULL DEFAULT '1' COMMENT '文件类型:1视频;2图片;3音频,4:网页;',
  `source_path` varchar(255) DEFAULT NULL COMMENT 'cdn回源url',
  `publish_path` varchar(255) DEFAULT NULL COMMENT '外网访问url',
  `md5` varchar(255) DEFAULT NULL COMMENT '文件md5值',
  `file_size` int(11) DEFAULT NULL COMMENT '文件大小',
  `ext_option` varchar(255) DEFAULT NULL COMMENT '额外操作指令',
  `file_level` int(11) NOT NULL DEFAULT '0' COMMENT '文件级别：0. 不作操作 1. 只发源站 2. 各个区域父层 3. 父层和边缘节点',
  `content` text NOT NULL COMMENT '子任务主体',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '子任务状态:1待分发;2分发中;3分发成功;4分发失败;',
  `status_str` text COMMENT '状态内容',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后一次更新时间',
  `admin_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '最后一次更新人,0为系统',
  `admin_user_name` varchar(255) DEFAULT NULL COMMENT '最后一次更新人名称',
  `is_del` int(11) NOT NULL DEFAULT '0' COMMENT '是否删除 0不删除 1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cdn_detail_log
-- ----------------------------