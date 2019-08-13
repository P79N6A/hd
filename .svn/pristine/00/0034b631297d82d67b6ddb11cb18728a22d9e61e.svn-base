-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 �?09 �?29 �?09:43
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
-- 表的结构 `votetheme`
--

CREATE TABLE IF NOT EXISTS `votetheme` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '投票项目编号',
  `type` tinyint(1) NOT NULL COMMENT '投票项目类型',
  `theme_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '投票主题名称',
  `status` tinyint(1) NOT NULL COMMENT '状态1通过0未审核',
  `vote_star` int(11) NOT NULL COMMENT '投票开始时间',
  `vote_end` int(11) NOT NULL COMMENT '投票结束时间',
  `limit_num` int(11) NOT NULL COMMENT '限制次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='投票主题表' AUTO_INCREMENT=30 ;

--
-- 转存表中的数据 `votetheme`
--

INSERT INTO `votetheme` (`id`, `type`, `theme_title`, `status`, `vote_star`, `vote_end`, `limit_num`) VALUES
(28, 2, '阿达说的', 1, 1441141500, 1441055100, 22222);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
