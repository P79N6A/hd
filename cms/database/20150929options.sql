-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 �?09 �?29 �?09:44
-- 服务器版本: 5.6.21-log
-- PHP 版本: 5.5.18

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `mythink`
--

-- --------------------------------------------------------

--
-- 表的结构 `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '投票主题内容id',
  `theme_id` int(11) NOT NULL COMMENT '投票主题编号',
  `options_content` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '投票内容名字',
  `count` int(11) NOT NULL COMMENT '内容投票数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='投票内容表' AUTO_INCREMENT=57 ;

--
-- 转存表中的数据 `options`
--

INSERT INTO `options` (`id`, `theme_id`, `options_content`, `count`) VALUES
(49, 28, '查询', 0),
(50, 28, 'cxsd', 0),
(51, 28, '大声道', 0),
(52, 28, '问问', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
