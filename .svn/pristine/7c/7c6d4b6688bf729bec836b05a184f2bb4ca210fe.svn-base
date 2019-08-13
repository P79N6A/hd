-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-09-11 03:19:18
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
-- 表的结构 `stations_epg`
--

CREATE TABLE IF NOT EXISTS `stations_epg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '直播流ID',
  `stations_id` int(11) unsigned NOT NULL COMMENT '电视台ID',
  `name` varchar(99) NOT NULL COMMENT '直播流名',
  `width` smallint(4) unsigned NOT NULL COMMENT '宽度',
  `height` smallint(4) unsigned NOT NULL COMMENT '高度',
  `cdn` text NOT NULL COMMENT 'cdn',
  `percent` text NOT NULL,
  `kpbs` smallint(4) unsigned NOT NULL COMMENT '码率',
  `audiokpbs` smallint(11) NOT NULL COMMENT '音频码率',
  `drm` tinyint(4) NOT NULL COMMENT '防盗链',
  PRIMARY KEY (`id`),
  KEY `stations_id` (`stations_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='电视节目流' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
