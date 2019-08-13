-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-06-02 10:53:09
-- �������汾�� 5.6.20
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `hd_cztv`
--

-- --------------------------------------------------------

--
-- ��Ľṹ `supplyout_rsync`
--

CREATE TABLE IF NOT EXISTS `supplyout_rsync` (
  `channel_id` int(10) unsigned NOT NULL COMMENT 'Ƶ��ID',
  `origin_type` tinyint(1) unsigned NOT NULL COMMENT '���ͷ�',
  `origin_id` int(11) NOT NULL COMMENT '�Է�ID',
  `data_id` int(10) unsigned NOT NULL COMMENT '�ҷ�ID',
  `category_id` int(10) unsigned NOT NULL COMMENT '��ĿID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='������Ŀ����ͬ���ǻ���ɽ';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `supplyout_rsync`
--
ALTER TABLE `supplyout_rsync`
  ADD UNIQUE KEY `data_id` (`channel_id`,`origin_type`,`origin_id`,`data_id`,`category_id`),
  ADD KEY `origin_type` (`origin_type`);
