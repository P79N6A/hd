/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : town

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-07-26 14:45:23
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `cdn_user`
-- ----------------------------
DROP TABLE IF EXISTS `cdn_user`;
CREATE TABLE `cdn_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL COMMENT '操作员姓名',
  `app_id` varchar(255) NOT NULL COMMENT '系统用户唯一id',
  `app_secret` varchar(255) NOT NULL COMMENT '系统用户密钥',
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cdn_user
-- ----------------------------
INSERT INTO `cdn_user` (`id`, `user_name`, `app_id`, `app_secret`) VALUES (1, '接入方一', 'app_1', 'b065fc08ceabcff9b2d38f1d7bfc05fa');

