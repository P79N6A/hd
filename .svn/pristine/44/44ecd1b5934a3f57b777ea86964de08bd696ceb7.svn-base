-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-09-17 08:01:42
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
-- 表的结构 `attachment_common`
--

CREATE TABLE IF NOT EXISTS `attachment_common` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `origin_name` varchar(256) DEFAULT NULL COMMENT '原始附件名',
  `name` varchar(256) DEFAULT NULL COMMENT '上传后的保存名',
  `created` int(11) DEFAULT NULL COMMENT '附件上传时间',
  `type` varchar(20) DEFAULT NULL COMMENT '附件类型 1-视频 2-图片 0-未知',
  `path` varchar(1024) DEFAULT NULL COMMENT '存储相对路径',
  `ext` varchar(255) DEFAULT NULL COMMENT '附件后缀',
  `u_id` int(11) DEFAULT '0' COMMENT '上传者id default：-1',
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`),
  KEY `ext` (`ext`),
  KEY `type` (`type`),
  KEY `created` (`created`),
  KEY `name` (`name`(255))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用于头图，介绍视频，图片，logo等介绍性附件的存放' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
