-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-08-16 14:41:24
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `usercenter`
--

-- --------------------------------------------------------

--
-- 表的结构 `ugcyun_live_video`
--

CREATE TABLE IF NOT EXISTS `ugcyun_live_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate` int(255) DEFAULT NULL COMMENT '码率',
  `data_id` int(11) DEFAULT NULL COMMENT '容器ID',
  `stream_id` int(11) NOT NULL COMMENT '流ID',
  `file_url` varchar(255) DEFAULT NULL COMMENT '播放文件URL',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
