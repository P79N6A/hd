-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-11-19 09:04:06
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
-- 表的结构 `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '模板ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '渠道ID',
  `domain_id` int(11) unsigned NOT NULL COMMENT '域名ID',
  `author_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `type` enum('index','category','detail','layout','static','error','custom') DEFAULT 'custom' COMMENT '类型',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '文件名',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `content` text NOT NULL COMMENT '内容',
  `url_rules` varchar(255) NOT NULL DEFAULT '' COMMENT '路径规则',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` smallint(2) unsigned NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_id` (`domain_id`,`path`),
  KEY `channel_id` (`channel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='模板' AUTO_INCREMENT=32 ;

--
-- 转存表中的数据 `templates`
--

INSERT INTO `templates` (`id`, `channel_id`, `domain_id`, `author_id`, `type`, `name`, `path`, `content`, `url_rules`, `created_at`, `updated_at`, `status`) VALUES
(1, 0, 1, 81, 'index', 'index.tpl', 'index.tpl', '{extends file="mem:main.tpl"}\r\n{block name="title" append}\r\n详情123 of {$data_id}\r\n{/block}\r\n{block name="body"}\r\n<h1>{$data_id} 的详情</h1>\r\n{/block}', '/', 1447728607, 1447913520, 1),
(2, 0, 1, 81, 'category', 'category.tpl', 'category.tpl', '{extends file="mem:main.tpl"} \n{block name="title" append}\nID 为 {$category_id} 的分类\n{/block}\n{block name="body"}\n<h1>ID 为 {$category_id} 的分类</h1>\n{/block}', '/cagories/{category_id}/', 1447728607, 1447728622, 1),
(3, 0, 1, 81, 'detail', 'detail.tpl', 'detail.tpl', '{extends file="mem:main.tpl"} \n{block name="title" append}\n详情 of {$data_id}\n{/block}\n{block name="body"}\n<h1>{$data_id} 的详情</h1>\n{/block}', '/view/{data_id}/', 1447728607, 1447728622, 1),
(4, 0, 1, 81, 'custom', 'world.tpl', 'world.tpl', '{extends file="mem:main.tpl"} \n{block name="title" append}\n世界新闻\n{/block}\n{block name="body"}\n<h1>这里是新世界</h1>\n{/block}', '/world/', 1447728607, 1447728622, 1),
(5, 0, 1, 81, 'layout', 'main.tpl', 'main.tpl', '<html>\n  <head>\n    <title>{block name="title"}标题 - {/block}</title>\n  </head>\n  <body>\n	{block name="body"} <h1>Default Layout</h1>{/block}\n  </body>\n</html>', '', 1447728610, 1447728622, 1),
(6, 0, 1, 81, 'custom', 'debug.tpl', 'debug.tpl', '{extends file="mem:main.tpl"} \n{block name="title" append}\n调试\n{/block}\n{block name="body"}\n<h1>调试调试</h1>\n{/block}', '/debug/', 1447728615, 1447728622, 1),
(7, 0, 1, 81, 'error', 'error.tpl', 'error.tpl', '{extends file="mem:main.tpl"} \n{block name="title" append}\n出错啦\n{/block}\n{block name="body"}\n<h1>55555~~~真的出错啦...</h1>\n{/block}', '', 1447728622, 1447728622, 1),
(8, 0, 1, 179, 'custom', '12345.tpl', '12345.tpl', '{extends file="mem:main.tpl"}\r\n{block name="title" append}\r\n详情4568 of {$data_id}\r\n{/block}\r\n{block name="body"}\r\n<h1>{$data_id} 的详情</h1>\r\n{/block}', '/12345/', 1447836272, 1447913520, 1),
(12, 0, 1, 179, 'static', '20151028103740.png', '0/static/images/20151028103740.png', 'null', 'null', 1447844133, 1447845129, 1),
(15, 0, 1, 179, 'static', '234701621.jpg', '0/static/images/234701621.jpg', 'null', 'null', 1447898251, 1447898251, 1),
(17, 0, 1, 179, 'custom', '123.tpl', '123.tpl', '{extends file="mem:main.tpl"}\r\n{block name="title" append}\r\n详情 of {$data_id}\r\n{/block}\r\n{block name="body"}\r\n<h1>{$data_id} 的详情</h1>\r\n{/block}', '/123/', 1447913520, 1447913520, 1),
(30, 0, 1, 179, 'static', '12234.jpg', 'static/image/12234.jpg', 'null', 'null', 1447917778, 1447917778, 1),
(31, 0, 1, 179, 'static', '122.png', 'static/image/122.png', 'null', 'null', 1447917778, 1447917778, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
