-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-08-16 14:41:43
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
-- 表的结构 `ugcyun_live`
--

CREATE TABLE IF NOT EXISTS `ugcyun_live` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_id` int(11) DEFAULT NULL COMMENT '容器ID',
  `admin_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `stream_event` enum('end','start') DEFAULT 'start' COMMENT '流状态,start/end',
  `stream_type` enum('pull','push') DEFAULT 'push' COMMENT 'push/pull',
  `source_ip` varchar(15) DEFAULT NULL COMMENT '上层IP',
  `start_time` int(11) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `domain` varchar(255) DEFAULT NULL COMMENT '推流域名host push.a.com',
  `stream_name` varchar(255) DEFAULT NULL COMMENT '流id,id冗余字段',
  `path` varchar(255) DEFAULT NULL COMMENT 'host后面路径,live',
  `rtmp_url` varchar(255) DEFAULT NULL COMMENT '"rtmp://push.a.com/live/123", //rtmp地址',
  `push_tool` varchar(255) DEFAULT 'obs_0202' COMMENT '推流工具 ,取不到默认空',
  `width` double DEFAULT '1280' COMMENT '视频宽',
  `height` double DEFAULT '720' COMMENT '视频高',
  `vidio_framerate` int(255) DEFAULT '123' COMMENT '视频帧率',
  `videorate` int(255) DEFAULT '234' COMMENT '视频码率,单位kbps',
  `videocoding_algorithm` varchar(255) DEFAULT 'ACC' COMMENT '视频压缩算法',
  `audiorate` varchar(255) DEFAULT '64' COMMENT '音频码率,单位kbps',
  `audio_framerate` varchar(255) DEFAULT '12' COMMENT '音频帧率',
  `audio_samplingrate` varchar(255) DEFAULT '44,100' COMMENT '音频采样率,单位Hz',
  `audio_channel` smallint(255) DEFAULT '2' COMMENT '音频声道',
  `push_args` varchar(255) DEFAULT NULL COMMENT '用户推流参数',
  `cdn_url1` varchar(255) DEFAULT NULL,
  `cdn_url2` varchar(255) DEFAULT NULL,
  `cdn_url3` varchar(255) DEFAULT NULL,
  `is_rec` tinyint(1) DEFAULT '1' COMMENT '是否录制文件',
  `terminal` tinyint(1) DEFAULT '1' COMMENT '主播终端类型，1:IOS,2:安卓',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='UGC直播媒资媒资文件' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
