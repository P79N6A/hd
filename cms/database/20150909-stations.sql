-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-09-09 11:09:30
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
-- 表的结构 `stations`
--

CREATE TABLE IF NOT EXISTS `stations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '电台ID',
  `is_system` tinyint(1) unsigned NOT NULL COMMENT '是否系统级',
  `channel_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所属频道',
  `code` int(11) unsigned NOT NULL COMMENT '电台编号',
  `name` varchar(99) NOT NULL COMMENT '电台名',
  `type` int(11) NOT NULL COMMENT '电台类型 1：电视台 2：广播台',
  `logo` varchar(255) DEFAULT NULL COMMENT 'logo',
  `channel_name` varchar(99) NOT NULL COMMENT '直播流相关字段1',
  `customer_name` varchar(99) NOT NULL COMMENT '直播流相关字段2',
  `epg_path` varchar(255) NOT NULL COMMENT '直播流相关字段3',
  PRIMARY KEY (`id`),
  KEY `is_system` (`is_system`),
  KEY `channel_id` (`channel_id`),
  KEY `code` (`code`),
  KEY `name` (`name`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='电视台表' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
