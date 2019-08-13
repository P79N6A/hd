/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : hd_cztv

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-08-05 11:07:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for activity_signup_attachment
-- ----------------------------
DROP TABLE IF EXISTS `activity_signup_attachment`;
CREATE TABLE `activity_signup_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ext_id` int(11) DEFAULT NULL COMMENT 'redis扩展外键id',
  `signup_id` int(11) DEFAULT NULL COMMENT '活动报名id',
  `type` tinyint(1) DEFAULT NULL COMMENT '媒资类型1-视频2-图片3-文本',
  `title` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL COMMENT '封面图片',
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of activity_signup_attachment
-- ----------------------------
