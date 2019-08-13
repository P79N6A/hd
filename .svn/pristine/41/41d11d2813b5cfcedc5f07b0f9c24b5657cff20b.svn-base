-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-09-15 09:45:14
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
-- 表的结构 `task_contents`
--

CREATE TABLE IF NOT EXISTS `task_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL COMMENT '任务id',
  `isolate_code` int(11) NOT NULL COMMENT '版本树编码',
  `Lft` int(11) NOT NULL COMMENT '版本树左值',
  `Rgt` int(11) NOT NULL COMMENT '版本树右值',
  `title` varchar(1024) NOT NULL COMMENT '任务名称',
  `intro` varchar(1024) DEFAULT NULL,
  `content` text COMMENT '任务详情',
  `signature` varchar(32) DEFAULT NULL COMMENT '版本签名',
  `encrypt` tinyint(2) NOT NULL COMMENT '加密级别0,1,2',
  `status` tinyint(2) NOT NULL COMMENT '1:审核 2:未审核 3:删除',
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `isolate_code` (`isolate_code`),
  KEY `Lft` (`Lft`),
  KEY `Rgt` (`Rgt`),
  KEY `title` (`title`(255)),
  KEY `signature` (`signature`),
  KEY `encrypt` (`encrypt`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='任务内容' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
