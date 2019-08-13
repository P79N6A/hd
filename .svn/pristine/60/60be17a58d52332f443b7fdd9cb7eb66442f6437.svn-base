/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : cms_online

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2016-10-17 11:18:07
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for stations_set
-- ----------------------------
DROP TABLE IF EXISTS `stations_set`;
CREATE TABLE `stations_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vms_siteId` int(11) NOT NULL DEFAULT '0' COMMENT '站点ID',
  `station_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '站点名称',
  `station_file` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '存放路径',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `pinyin` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `bitrate` int(11) DEFAULT NULL COMMENT '码率',
  `format` char(128) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '视频存放格式',
  `station_guid` varchar(128) COLLATE utf8_unicode_ci DEFAULT '' COMMENT 'guid',
  `live_stream` varchar(256) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '直播流收录地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;
