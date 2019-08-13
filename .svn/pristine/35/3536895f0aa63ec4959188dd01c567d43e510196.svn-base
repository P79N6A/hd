/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : town

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-07-26 14:45:05
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `cdn_producer`
-- ----------------------------
DROP TABLE IF EXISTS `cdn_producer`;
CREATE TABLE `cdn_producer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producer_name` varchar(255) NOT NULL COMMENT '产商名称',
  `username` varchar(255) NOT NULL COMMENT 'cdn厂家授权用户名',
  `password` varchar(255) DEFAULT NULL COMMENT 'cdn厂家授权用户名密码',
  `push_url` varchar(255) DEFAULT NULL COMMENT '请求地址',
  `return_url` varchar(255) DEFAULT NULL COMMENT '回调地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cdn_producer
-- ----------------------------
INSERT INTO cdn_producer VALUES ('1', '云帆视音频', 'cztv_inject_test_yf2', 'yf@@0721@cztvinject', 'http://api.fileinject.yunfancdn.com/file/inject?from=cztv', 'https://test-iyun.cztv.com/cdn_yff/yunfanrt');
INSERT INTO cdn_producer VALUES ('2', '云帆小文件', 'cztv@yunfan.com', 'cztv@06_01', 'http://api.yfcache.com/cont/add_purge/', 'https://test-iyun.cztv.com/cdn_yff/yunfanurlrt');
