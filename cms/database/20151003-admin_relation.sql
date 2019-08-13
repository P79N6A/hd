-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-10-03 14:10:43
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
-- 表的结构 `admin_relation`
--

CREATE TABLE IF NOT EXISTS `admin_relation` (
  `admin_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `relation_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:预留 1：任务 2：站内信',
  `freq` int(11) NOT NULL,
  UNIQUE KEY `admin_id` (`admin_id`,`relation_id`,`type`),
  KEY `relation_id` (`relation_id`),
  KEY `type` (`type`),
  KEY `freq` (`freq`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='常用联系人表';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
