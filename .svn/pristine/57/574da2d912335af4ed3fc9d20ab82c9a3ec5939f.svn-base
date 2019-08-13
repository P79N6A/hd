-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 10.1.121.56
-- Generation Time: 2016-09-13 18:16:53
-- 服务器版本： 10.0.21-MariaDB
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `usercenter`
--

-- --------------------------------------------------------

--
-- 表的结构 `stations_program`
--

DROP TABLE IF EXISTS `stations_program`;
CREATE TABLE `stations_program` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '节目ID',
  `stations_id` int(11) UNSIGNED NOT NULL COMMENT '电视id',
  `title` varchar(99) NOT NULL DEFAULT '' COMMENT '节目名称',
  `start_time` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '结束时间',
  `start_date` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '开始日期',
  `duration` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '时长',
  `status` int(11) NOT NULL COMMENT '1.正常 2.未审核 3.删除',
  `replay` int(11) NOT NULL DEFAULT '1' COMMENT '1.可回放 2.不可回放',
  `order` int(11) NOT NULL DEFAULT '2' COMMENT '1.可预约 2.不可预约',
  `rate` varchar(50) NOT NULL DEFAULT '' COMMENT '码率',
  `format` varchar(30) NOT NULL DEFAULT '' COMMENT '格式',
  `type` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '0,整档；1,拆条',
  `partition_by` smallint(4) UNSIGNED NOT NULL COMMENT '分区/年',
  `rate_status` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否录制,1:无,2:收录成功,3:收录失败,4:录像成功,5:录像失败'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='电视节目单'
PARTITION BY RANGE COLUMNS(partition_by)
(
PARTITION p2014 VALUES LESS THAN (2015)ENGINE=InnoDB,
PARTITION p2015 VALUES LESS THAN (2016)ENGINE=InnoDB,
PARTITION p2016 VALUES LESS THAN (2017)ENGINE=InnoDB,
PARTITION p2017 VALUES LESS THAN (2018)ENGINE=InnoDB,
PARTITION p2018 VALUES LESS THAN (2019)ENGINE=InnoDB,
PARTITION p2019 VALUES LESS THAN (2020)ENGINE=InnoDB,
PARTITION p2020 VALUES LESS THAN (2021)ENGINE=InnoDB,
PARTITION p2022 VALUES LESS THAN (2023)ENGINE=InnoDB,
PARTITION p2023 VALUES LESS THAN (2024)ENGINE=InnoDB,
PARTITION p2024 VALUES LESS THAN (2025)ENGINE=InnoDB,
PARTITION p2025 VALUES LESS THAN (2026)ENGINE=InnoDB,
PARTITION pmax VALUES LESS THAN (MAXVALUE)ENGINE=InnoDB
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `stations_program`
--
ALTER TABLE `stations_program`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD KEY `channel_id` (`stations_id`) USING BTREE,
  ADD KEY `start_time` (`start_time`),
  ADD KEY `end_time` (`end_time`),
  ADD KEY `status` (`status`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `stations_program`
--
ALTER TABLE `stations_program`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '节目ID';