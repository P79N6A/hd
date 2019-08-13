-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: rdswenz3l0a822ir8ape.mysql.rds.aliyuncs.com
-- Generation Time: 2016-03-16 17:28:42
-- 服务器版本： 5.6.16-log
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `town`
--

-- --------------------------------------------------------

--
-- 表的结构 `backend_logs`
--

CREATE TABLE `backend_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL COMMENT '操作用户',
  `ip` varchar(50) NOT NULL COMMENT 'ip地址',
  `channel_id` int(11) UNSIGNED NOT NULL COMMENT '频道id',
  `controller` varchar(50) NOT NULL COMMENT '控制器',
  `type` tinyint(2) UNSIGNED NOT NULL COMMENT '操作类型',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `created_at` int(11) NOT NULL COMMENT '操作时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `backend_logs`
--
ALTER TABLE `backend_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`,`created_at`),
  ADD KEY `user_id` (`user_id`,`created_at`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `backend_logs`
--
ALTER TABLE `backend_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
