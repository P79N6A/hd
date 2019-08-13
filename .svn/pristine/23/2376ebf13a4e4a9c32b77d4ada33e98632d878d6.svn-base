/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : cms_online

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2016-12-09 10:34:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for signal_drm
-- ----------------------------
DROP TABLE IF EXISTS `signal_drm`;
CREATE TABLE `signal_drm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `drm_name` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT '防盗链名称',
  `drm_value` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT '防盗链值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='防盗链设置表';

-- ----------------------------
-- Table structure for signal_epg
-- ----------------------------
DROP TABLE IF EXISTS `signal_epg`;
CREATE TABLE `signal_epg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lives_id` int(11) DEFAULT NULL COMMENT '直播id(signals表id)',
  `livesource_id` int(11) DEFAULT NULL COMMENT 'signal_source表id',
  `vender_id` int(11) DEFAULT NULL COMMENT '厂家id（signal_producer表id）',
  `remarks` text COLLATE utf8_unicode_ci COMMENT '备注',
  `isp2p` smallint(4) DEFAULT NULL,
  `isdrm` smallint(4) DEFAULT NULL,
  `drm_id` int(11) DEFAULT NULL COMMENT '防盗链表的id',
  `type` enum('flv','rtmp','m3u8') COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '类型',
  PRIMARY KEY (`id`),
  KEY `livesource_id` (`livesource_id`) USING BTREE,
  KEY `lives_id` (`lives_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='cdn播放设置';

-- ----------------------------
-- Table structure for signal_playurl
-- ----------------------------
DROP TABLE IF EXISTS `signal_playurl`;
CREATE TABLE `signal_playurl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epg_id` int(11) DEFAULT NULL COMMENT 'signal_epg表id',
  `play_url` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'cdn 直播地址',
  `rate_id` int(11) DEFAULT NULL COMMENT '码率id（signal_rate表id）',
  PRIMARY KEY (`id`),
  KEY `rate_id` (`rate_id`) USING BTREE,
  KEY `epg_id` (`epg_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='cdn播放流地址 表';

-- ----------------------------
-- Table structure for signal_producer
-- ----------------------------
DROP TABLE IF EXISTS `signal_producer`;
CREATE TABLE `signal_producer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vender_name` varchar(128) CHARACTER SET utf8 NOT NULL COMMENT '设备版本',
  `vender_code` varchar(256) NOT NULL COMMENT '厂家编码',
  `weight` int(4) unsigned DEFAULT '0' COMMENT '权重',
  `remarks` text COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='直播流厂家';

-- ----------------------------
-- Table structure for signal_rates
-- ----------------------------
DROP TABLE IF EXISTS `signal_rates`;
CREATE TABLE `signal_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate_type` smallint(2) DEFAULT NULL COMMENT '视频类型:0，视频，1，音频',
  `rate_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '码率名称',
  `rate_kpbs` int(64) DEFAULT NULL COMMENT '码率',
  `rate_weight` int(32) unsigned DEFAULT '0' COMMENT '权重',
  PRIMARY KEY (`id`),
  KEY `rate_kpbs` (`rate_kpbs`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='码率类型表(高清，标清等)';

-- ----------------------------
-- Table structure for signal_source
-- ----------------------------
DROP TABLE IF EXISTS `signal_source`;
CREATE TABLE `signal_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lives_id` int(11) NOT NULL COMMENT '直播流id（signals表id）',
  `father_id` int(4) NOT NULL DEFAULT '0' COMMENT '父节点id',
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '流地址',
  `rate_id` int(11) DEFAULT NULL COMMENT '码率id(signal_rates表id)',
  PRIMARY KEY (`id`),
  KEY `singnals_id` (`lives_id`) USING BTREE,
  KEY `father_id` (`father_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='直播流关联表';

-- ----------------------------
-- Table structure for signals
-- ----------------------------
DROP TABLE IF EXISTS `signals`;
CREATE TABLE `signals` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '直播ID',
  `channel_id` int(11) DEFAULT NULL COMMENT '频道 id',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `content` longtext COMMENT '视频介绍',
  `live_type` int(11) NOT NULL COMMENT '电台类型 1：电视直播 2：广播直播 3：虚拟直播 4：活动直播 5:手机直播',
  `live_status` tinyint(4) unsigned DEFAULT NULL COMMENT '直播状态（开启1，禁用0，播放结束3，直播中2）',
  `sort` int(4) DEFAULT NULL COMMENT '排序权重',
  `isfeatured` tinyint(4) DEFAULT NULL COMMENT '是否到推荐位： 0 ： 否， 1 ： 是',
  `rate_id` int(11) DEFAULT NULL COMMENT '默认码率id(signal_rate表id)',
  `notstarted_img` varchar(255) DEFAULT NULL COMMENT '未开始logo',
  `unauthorized_img` varchar(255) DEFAULT NULL COMMENT '未授权logo',
  `complete_img` varchar(255) DEFAULT NULL COMMENT '已结束logo',
  `buffering_img` varchar(255) DEFAULT NULL COMMENT '缓冲中logo',
  `danmu` smallint(2) unsigned DEFAULT NULL COMMENT '1 开启弹幕 0 关闭弹幕',
  `firstlook` smallint(2) unsigned DEFAULT NULL COMMENT '抢先看 1、正常跟播 0',
  `paylist` varchar(64) DEFAULT NULL COMMENT '付费码率（以逗号隔开）',
  `trylook` tinyint(4) DEFAULT NULL COMMENT '密钥值',
  `comment_type` smallint(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `live_type` (`live_type`),
  KEY `sort` (`sort`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='直播表';


ALTER TABLE `signal_epg`
ADD COLUMN `rate_id`  int(11) NULL COMMENT '默认码率' AFTER `type`;

ALTER TABLE `signals`
DROP COLUMN `rate_id`;
