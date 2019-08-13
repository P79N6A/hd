-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-11-04 04:00:40
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
-- 表的结构 `advert`
--

CREATE TABLE IF NOT EXISTS `advert` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道id',
  `name` varchar(40) NOT NULL COMMENT '标题',
  `spaceid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '版位id',
  `type` varchar(10) NOT NULL COMMENT '类型',
  `setting` text NOT NULL,
  `startdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上线时间',
  `enddate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下线时间',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `clicks` smallint(5) unsigned NOT NULL DEFAULT '0',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `spaceid` (`spaceid`,`listorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `advert`
--

INSERT INTO `advert` (`id`, `channel_id`, `name`, `spaceid`, `type`, `setting`, `startdate`, `enddate`, `addtime`, `clicks`, `listorder`) VALUES
(2, 0, 'phpcmsv9', 2, 'images', '', 1285816298, 1446249600, 1285816310, 1, 0),
(3, 0, 'phpcms下载推荐', 3, 'images', '', 1286504815, 1446249600, 1286504865, 1, 0),
(4, 0, 'phpcms广告', 4, 'images', '', 1286505141, 1446249600, 1286505178, 0, 0),
(5, 0, 'phpcms下载', 5, 'images', '', 1286509363, 1446249600, 1286509401, 0, 0),
(6, 0, 'phpcms下载推荐1', 6, 'images', '', 1286510183, 1446249600, 1286510227, 0, 0),
(7, 0, 'phpcms下载详情', 7, 'images', '', 1286510314, 1446249600, 1286510341, 0, 0),
(8, 0, 'phpcms下载页', 8, 'images', '', 1286522084, 1446249600, 1286522125, 0, 0),
(9, 0, 'phpcms v9广告', 9, 'images', '', 1287041759, 1446249600, 1287041804, 0, 0),
(10, 0, 'phpcms', 10, 'images', '', 1289270509, 1446249600, 1289270541, 0, 0),
(11, 0, 'banner', 1, 'images', '', 1285813808, 1446249600, 1285813833, 1, 2),
(13, 0, '广告位12', 1, 'images', '{"linkurl":"http:\\/\\/www.baidu.com","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/logos\\/2015\\/11\\/02\\/16cb52c85e165304303b360df0d47878.png","alt":"\\u6587\\u5b57"}', 1292466600, 1402463580, 1446452551, 1, 2),
(14, 0, '广告位12', 1, 'images', '{"linkurl":"http:\\/\\/www.baidu.com","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/logos\\/2015\\/11\\/02\\/16cb52c85e165304303b360df0d47878.png","alt":"\\u6587\\u5b57"}', 1292466600, 1402463580, 1446452551, 1, 2),
(15, 0, 'banner', 1, 'images', '{"linkurl":"http:\\/\\/www.baidu.com","imageurl":"","alt":""}', 1285813800, 1446249600, 1285813833, 1, 2),
(16, 0, 'phpcms v9广告', 9, 'images', '', 1287041759, 1446249600, 1287041804, 0, 0),
(17, 0, 'phpcms下载推荐1', 6, 'images', '', 1286510183, 1446249600, 1286510227, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `advert_space`
--

CREATE TABLE IF NOT EXISTS `advert_space` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) unsigned NOT NULL DEFAULT '0',
  `name` char(50) NOT NULL COMMENT '版位名称',
  `type` char(30) NOT NULL COMMENT '版位类型',
  `intype` smallint(5) NOT NULL COMMENT '内置广告类型',
  `path` varchar(100) DEFAULT NULL COMMENT 'js路径',
  `width` smallint(4) unsigned NOT NULL DEFAULT '0',
  `height` smallint(4) unsigned NOT NULL DEFAULT '0',
  `setting` char(100) NOT NULL,
  `description` char(100) DEFAULT NULL COMMENT '版位描述',
  `status` tinyint(1) unsigned NOT NULL COMMENT '0禁用1启用',
  PRIMARY KEY (`id`),
  KEY `disabled` (`channel_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `advert_space`
--

INSERT INTO `advert_space` (`id`, `channel_id`, `name`, `type`, `intype`, `path`, `width`, `height`, `setting`, `description`, `status`) VALUES
(1, 0, '顶部搜索右侧广告位', 'text', 1, '123.js', 0, 0, '{"paddleft":"0","paddtop":"0"}', '                                                1111111', 0),
(2, 0, '会员登录页广告', 'code', 0, '', 0, 0, '{"paddleft":"0","paddtop":"0"}', '                        会员登录页广告右侧代替外部通行证广告', 0),
(3, 0, '图片频道图片排行下方', 'banner', 0, '', 249, 87, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1),
(4, 0, '新闻中心推荐链接左侧', 'banner', 0, '', 748, 91, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1),
(5, 0, '下载列表页右侧顶部', 'banner', 0, '', 248, 162, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1),
(6, 0, '下载详情页右侧顶部', 'banner', 0, '', 248, 162, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1),
(7, 0, '下载详情页右侧下部', 'banner', 0, '', 248, 162, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1),
(8, 0, '下载频道首页', 'banner', 0, '', 698, 80, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1),
(9, 0, '下载详情页地址列表右侧', 'banner', 0, '', 330, 50, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1),
(10, 0, '首页关注下方广告', 'banner', 0, '', 307, 60, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1),
(11, 0, '顶部搜索右侧广告位', 'fixure', 0, '', 430, 63, '', '版位描述', 1),
(12, 0, '顶部搜索右侧广告位', 'fixure', 0, '', 430, 63, '', '版位描述', 1),
(13, 0, '广告位12', 'banner', 0, NULL, 120, 110, '{"paddleft":"","paddtop":""}', '', 1),
(14, 0, '广告位12', 'banner', 0, 'space_js/14.js', 120, 110, '{"paddleft":"","paddtop":""}', '', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
