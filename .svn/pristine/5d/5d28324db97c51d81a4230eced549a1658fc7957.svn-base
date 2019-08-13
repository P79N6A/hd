-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-12-14 03:43:05
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xh_cztv`
--

-- --------------------------------------------------------

--
-- 表的结构 `app_push`
--

CREATE TABLE IF NOT EXISTS `app_push` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_id` int(11) NOT NULL DEFAULT '0' COMMENT '媒资id',
  `title` varchar(255) NOT NULL COMMENT '标题或手机号',
  `type` enum('app','cdn','msg') NOT NULL COMMENT 'app:客户端推送 cdn:网站缓存 msg:短信',
  `terminal` tinyint(4) DEFAULT '0' COMMENT 'app推送必填，1:安卓 2:苹果 3:全部',
  `cdn_type` varchar(50) DEFAULT NULL COMMENT 'cdn厂家类型 lanxun,kuaiwang,letv',
  `push_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '推送时间',
  `status` smallint(4) NOT NULL DEFAULT '0' COMMENT '0:没推送，1:推送成功，2:推送失败，3:定时待推送',
  `content` varchar(1024) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL COMMENT '返回值',
  `created_at` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `data_id` (`data_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `app_push`
--

INSERT INTO `app_push` (`id`, `data_id`, `title`, `type`, `terminal`, `cdn_type`, `push_time`, `status`, `content`, `remark`, `created_at`) VALUES
(6, 1234, '测试新闻app推送', 'app', 3, NULL, 1450059851, 0, NULL, NULL, 0),
(7, 139814, '萧山区政府召开工作务虚会议', 'app', 3, NULL, 1450059851, 0, NULL, NULL, 1450059731),
(8, 139814, '萧山区政府召开工作务虚会议', 'app', 0, NULL, 1450059851, 0, '{"data_id":"139814","title":"\\u8427\\u5c71\\u533a\\u653f\\u5e9c\\u53ec\\u5f00\\u5de5\\u4f5c\\u52a1\\u865a\\u4f1a\\u8bae","image":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/","url":"","station_id":"105","":"live"}', NULL, 1450059851);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
