-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-09-15 09:44:40
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hd_cztv`
--

-- --------------------------------------------------------

--
-- 表的结构 `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属频道id',
  `title` varchar(1024) NOT NULL COMMENT '任务名称',
  `content_id` int(11) NOT NULL,
  `attachnum` int(11) NOT NULL COMMENT '附件数量',
  `receiver` int(11) NOT NULL DEFAULT '0' COMMENT '接收者',
  `receiver_name` varchar(99) NOT NULL COMMENT '存放姓名拼音 排序用',
  `creator` int(11) NOT NULL DEFAULT '0' COMMENT '创建者',
  `creator_name` varchar(99) NOT NULL,
  `progress` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0:新建(未分配) 1:未接收 2:拒绝 3:重新打开 4:进行中 5:审核驳回 6:提交审核 7:同意完成 8:已评分',
  `subs_complete` smallint(11) DEFAULT '1' COMMENT ' 0: 无分拆 1：未完成 2：全部完成',
  `notify` tinyint(4) NOT NULL COMMENT '是否在通知栏展示',
  `score` varchar(255) DEFAULT NULL,
  `priority` smallint(6) DEFAULT NULL COMMENT '优先级',
  `start` int(11) DEFAULT NULL COMMENT '预计开始',
  `end` int(11) DEFAULT NULL,
  `actual_start` int(11) DEFAULT NULL COMMENT '实际开始',
  `actual_end` int(11) DEFAULT NULL,
  `is_main` tinyint(4) NOT NULL DEFAULT '1' COMMENT '主任务',
  `isolate_code` int(11) NOT NULL COMMENT '任务树编号',
  `Lft` int(11) NOT NULL COMMENT '任务树左值',
  `Rgt` int(11) NOT NULL COMMENT '任务树右值',
  `depth` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1:审核 2:未审核 3:删除',
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `progress` (`progress`),
  KEY `status` (`status`),
  KEY `isolate_code` (`isolate_code`),
  KEY `Lft` (`Lft`),
  KEY `Rgt` (`Rgt`),
  KEY `created` (`created`),
  KEY `priority` (`priority`),
  KEY `is_main` (`is_main`),
  KEY `subs_complete` (`subs_complete`),
  KEY `receiver` (`receiver`),
  KEY `creator` (`creator`),
  KEY `receiver_name` (`receiver_name`),
  KEY `start` (`start`),
  KEY `end` (`end`),
  KEY `actual_end` (`actual_end`),
  KEY `actual_start` (`actual_start`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='任务表' AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `tasks`
--

INSERT INTO `tasks` (`id`, `channel_id`, `title`, `content_id`, `attachnum`, `receiver`, `receiver_name`, `creator`, `creator_name`, `progress`, `subs_complete`, `notify`, `score`, `priority`, `start`, `end`, `actual_start`, `actual_end`, `is_main`, `isolate_code`, `Lft`, `Rgt`, `depth`, `status`, `created`) VALUES
(1, 1, '我的主任务', 0, 0, 1, '1', 1, '1', 0, 1, 0, '', 0, 1234567, 1234567, 1234567, 1234567, 1, 1, 1, 16, 1, 1, 1442191688),
(2, 1, '一级子任务', 0, 0, 1, '1', 1, '1', 0, 0, 0, '', 0, 1234567, 1234567, 1234567, 1234567, 0, 1, 2, 3, 2, 1, 1442191713),
(3, 1, '大事发生', 0, 0, 1, '1', 1, '1', 0, 1, 0, '', 0, 1234567, 1234567, 1234567, 1234567, 1, 3, 1, 4, 1, 1, 1442191761),
(4, 1, '的飞洒地方生', 0, 0, 1, '1', 1, '1', 0, 0, 0, '', 0, 1234567, 1234567, 1234567, 1234567, 0, 3, 2, 3, 2, 1, 1442191773),
(5, 1, '大事发生', 0, 0, 1, '1', 1, '1', 1, 1, 0, '', 0, 1234567, 1234567, 1234567, 1234567, 0, 1, 4, 11, 2, 1, 1442215104),
(6, 1, '大事发生', 0, 0, 1, '1', 1, '1', 1, 0, 0, '', 0, 1234567, 1234567, 1234567, 1234567, 0, 1, 5, 6, 3, 1, 1442215126),
(7, 1, '大事发生', 0, 0, 1, '1', 1, '1', 1, 0, 0, '', 0, 1234567, 1234567, 1234567, 1234567, 0, 1, 7, 8, 3, 1, 1442215136),
(8, 1, '大事发生', 0, 0, 1, '1', 1, '1', 1, 0, 0, '', 0, 1234567, 1234567, 1234567, 1234567, 0, 1, 9, 10, 3, 1, 1442215150),
(9, 1, '大事发生', 0, 0, 1, '1', 1, '1', 1, 0, 0, '', 0, 1234567, 1234567, 1234567, 1234567, 0, 1, 12, 13, 2, 1, 1442215172),
(10, 1, '大事发生', 0, 0, 1, '1', 1, '1', 1, 0, 0, '', 0, 1234567, 1234567, 1234567, 1234567, 0, 1, 14, 15, 2, 1, 1442215189);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
