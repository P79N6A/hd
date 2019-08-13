/*
Navicat MySQL Data Transfer

Source Server         : 10.1.121.56
Source Server Version : 50505
Source Host           : 10.1.121.56:3306
Source Database       : usercenter

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-09-03 09:58:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `data_statistics`
-- ----------------------------
DROP TABLE IF EXISTS `data_statistics`;
CREATE TABLE `data_statistics` (
  `data_id` int(11) NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  `hits_fake` int(11) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `likes_fake` int(11) NOT NULL DEFAULT '0',
  `shares` int(11) NOT NULL DEFAULT '0' COMMENT '转发量',
  `shares_fake` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0' COMMENT '评论量',
  `comments_fake` int(11) NOT NULL DEFAULT '0',
  `formulas` varchar(1000) DEFAULT NULL COMMENT '虚拟值公式',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '年分区',
  PRIMARY KEY (`data_id`,`partition_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='统计表'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;

-- ----------------------------
-- Records of data_statistics
-- ----------------------------
INSERT INTO `data_statistics` VALUES ('240700', '0', '158', '2', '802', '9', '72', '1', '80', '', '2016');
INSERT INTO `data_statistics` VALUES ('240744', '0', '0', '1', '1', '0', '0', '0', '0', '', '2016');
