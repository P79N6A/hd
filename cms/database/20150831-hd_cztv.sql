-- MySQL dump 10.13  Distrib 5.5.43, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: hd_cztv
-- ------------------------------------------------------
-- Server version	5.5.43-0ubuntu0.14.10.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `mobile` varchar(16) NOT NULL COMMENT '手机号',
  `name` int(99) NOT NULL COMMENT '用户名',
  `password` int(32) NOT NULL COMMENT '密码',
  `salt` int(16) NOT NULL COMMENT '密钥',
  PRIMARY KEY (`id`),
  UNIQUE KEY `channel_id` (`channel_id`,`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `album`
--

DROP TABLE IF EXISTS `album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `album` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '相册ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  `keywords` varchar(255) NOT NULL COMMENT '关键词',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='相册表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album`
--

LOCK TABLES `album` WRITE;
/*!40000 ALTER TABLE `album` DISABLE KEYS */;
/*!40000 ALTER TABLE `album` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `album_image`
--

DROP TABLE IF EXISTS `album_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `album_image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片ID',
  `album_id` int(11) unsigned NOT NULL COMMENT '相册ID',
  `path` varchar(255) NOT NULL COMMENT '路径',
  `intro` varchar(255) DEFAULT NULL COMMENT '简介',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年',
  PRIMARY KEY (`id`,`partition_by`),
  KEY `album_id` (`album_id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片表'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album_image`
--

LOCK TABLES `album_image` WRITE;
/*!40000 ALTER TABLE `album_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `album_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `api_doc`
--

DROP TABLE IF EXISTS `api_doc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_doc` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` int(99) NOT NULL COMMENT 'API名字',
  `url` varchar(255) NOT NULL COMMENT '请求URL',
  `method` varchar(9) NOT NULL COMMENT '请求方式',
  `params` text NOT NULL COMMENT '请求参数',
  `feedback` text NOT NULL COMMENT '返回值',
  `demo` varchar(255) NOT NULL COMMENT '示例URL',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='API';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `api_doc`
--

LOCK TABLES `api_doc` WRITE;
/*!40000 ALTER TABLE `api_doc` DISABLE KEYS */;
/*!40000 ALTER TABLE `api_doc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_assign`
--

DROP TABLE IF EXISTS `auth_assign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assign` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '授权ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `element_id` int(11) unsigned NOT NULL COMMENT '原子ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `channel_id` (`channel_id`,`user_id`,`element_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限授权表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_assign`
--

LOCK TABLES `auth_assign` WRITE;
/*!40000 ALTER TABLE `auth_assign` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_assign` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_element`
--

DROP TABLE IF EXISTS `auth_element`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_element` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '原子ID',
  `controller` varchar(9) NOT NULL COMMENT '控制器原子',
  `controller_name` varchar(30) NOT NULL COMMENT '控制器名',
  `action` varchar(30) NOT NULL COMMENT '动作原子',
  `action_name` varchar(30) NOT NULL COMMENT '原子名',
  PRIMARY KEY (`id`),
  UNIQUE KEY `controller` (`controller`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限原子表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_element`
--

LOCK TABLES `auth_element` WRITE;
/*!40000 ALTER TABLE `auth_element` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_element` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_role`
--

DROP TABLE IF EXISTS `auth_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `name` varchar(30) NOT NULL COMMENT '角色名',
  `element` text NOT NULL COMMENT '原子集',
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_role`
--

LOCK TABLES `auth_role` WRITE;
/*!40000 ALTER TABLE `auth_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `name` int(11) NOT NULL COMMENT '栏目名',
  `father_id` int(10) unsigned NOT NULL COMMENT '父ID',
  `type` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`),
  KEY `father_id` (`father_id`),
  KEY `type` (`type`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category_auth`
--

DROP TABLE IF EXISTS `category_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_auth` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目授权ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `category_id` int(11) unsigned NOT NULL COMMENT '栏目ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目权限表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_auth`
--

LOCK TABLES `category_auth` WRITE;
/*!40000 ALTER TABLE `category_auth` DISABLE KEYS */;
/*!40000 ALTER TABLE `category_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category_data`
--

DROP TABLE IF EXISTS `category_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '关联ID',
  `data_id` int(11) unsigned NOT NULL COMMENT '容器ID',
  `category_id` int(11) unsigned NOT NULL COMMENT '栏目ID',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年',
  PRIMARY KEY (`id`,`partition_by`),
  UNIQUE KEY `data_id` (`data_id`,`category_id`,`partition_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目容器关联表'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category_data`
--

LOCK TABLES `category_data` WRITE;
/*!40000 ALTER TABLE `category_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `category_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `channel`
--

DROP TABLE IF EXISTS `channel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `channel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `name` int(99) NOT NULL COMMENT '频道名',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='频道表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channel`
--

LOCK TABLES `channel` WRITE;
/*!40000 ALTER TABLE `channel` DISABLE KEYS */;
/*!40000 ALTER TABLE `channel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `channel_share`
--

DROP TABLE IF EXISTS `channel_share`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `channel_share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '认证ID',
  `origin_id` int(11) unsigned NOT NULL COMMENT '原始频道ID',
  `auth_id` int(1) unsigned NOT NULL COMMENT '授权频道ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `origin_id` (`origin_id`,`auth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='频道数据共享认证表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `channel_share`
--

LOCK TABLES `channel_share` WRITE;
/*!40000 ALTER TABLE `channel_share` DISABLE KEYS */;
/*!40000 ALTER TABLE `channel_share` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(11) unsigned NOT NULL COMMENT '评论ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `data_id` int(11) unsigned NOT NULL COMMENT '容器ID',
  `father_id` int(11) unsigned NOT NULL COMMENT '父ID',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `create_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `up` int(11) unsigned NOT NULL COMMENT '赞',
  `down` int(11) unsigned NOT NULL COMMENT '底',
  `domain` varchar(30) NOT NULL COMMENT '域名',
  `client` tinyint(1) unsigned NOT NULL COMMENT '终端',
  `ip` varchar(15) NOT NULL COMMENT 'IP',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区',
  PRIMARY KEY (`id`,`partition_by`),
  KEY `channel_id` (`channel_id`),
  KEY `data_id` (`data_id`),
  KEY `father_id` (`father_id`),
  KEY `create_at` (`create_at`),
  KEY `status` (`status`),
  KEY `up` (`up`),
  KEY `down` (`down`),
  KEY `client` (`client`),
  KEY `domain` (`domain`),
  KEY `user_id` (`user_id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data`
--

DROP TABLE IF EXISTS `data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '源类型',
  `source_id` int(11) unsigned NOT NULL COMMENT '源ID',
  `title` varchar(255) NOT NULL COMMENT '源标题',
  `intro` varchar(255) DEFAULT NULL COMMENT '源简介',
  `thumb` varchar(255) DEFAULT NULL COMMENT '源缩略图',
  `create_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `sort` int(11) unsigned NOT NULL COMMENT '排序',
  `weight` int(11) unsigned NOT NULL COMMENT '权重',
  `author_id` int(11) unsigned NOT NULL COMMENT '作者ID',
  `author_name` varchar(30) NOT NULL COMMENT '作者名字',
  `hits` int(11) unsigned NOT NULL COMMENT '点击',
  `status` tinyint(1) unsigned NOT NULL COMMENT '发布状态',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '年分区',
  PRIMARY KEY (`id`,`partition_by`),
  KEY `type` (`type`),
  KEY `source_id` (`source_id`),
  KEY `create_at` (`create_at`),
  KEY `update_at` (`update_at`),
  KEY `sort` (`sort`),
  KEY `partition_by` (`partition_by`),
  KEY `status` (`status`),
  KEY `weight` (`weight`),
  KEY `hits` (`hits`),
  KEY `author_id` (`author_id`),
  KEY `author_name` (`author_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='容器表'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data`
--

LOCK TABLES `data` WRITE;
/*!40000 ALTER TABLE `data` DISABLE KEYS */;
/*!40000 ALTER TABLE `data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_data`
--

DROP TABLE IF EXISTS `data_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '关联ID',
  `origin_id` int(11) unsigned NOT NULL COMMENT 'data库原始ID',
  `source_id` int(1) unsigned NOT NULL COMMENT 'data库被需求ID',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年',
  PRIMARY KEY (`id`,`partition_by`),
  UNIQUE KEY `origin_id` (`origin_id`,`source_id`,`partition_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='数据对数据管理中心'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_data`
--

LOCK TABLES `data_data` WRITE;
/*!40000 ALTER TABLE `data_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '新闻ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `content` text NOT NULL COMMENT '新闻内容',
  `source` varchar(255) DEFAULT NULL COMMENT '来源',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年',
  PRIMARY KEY (`id`,`partition_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_group`
--

DROP TABLE IF EXISTS `news_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '新闻集ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `keyword` varchar(255) NOT NULL COMMENT '关键词',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新闻集表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_group`
--

LOCK TABLES `news_group` WRITE;
/*!40000 ALTER TABLE `news_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setting` (
  `id` int(11) unsigned NOT NULL,
  `key` varchar(30) NOT NULL,
  `value` text NOT NULL,
  `channels_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='频道基础配置表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting`
--

LOCK TABLES `setting` WRITE;
/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site`
--

DROP TABLE IF EXISTS `site`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site` (
  `id` int(11) unsigned NOT NULL COMMENT '站点ID',
  `name` int(99) NOT NULL COMMENT '站点名',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `api_sign` varchar(32) NOT NULL COMMENT 'API签名',
  `logo` varchar(255) NOT NULL COMMENT '站点logo',
  `domain` varchar(30) NOT NULL COMMENT '域名',
  `stations` text NOT NULL COMMENT '授权电台ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  KEY `domain` (`domain`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='站点表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site`
--

LOCK TABLES `site` WRITE;
/*!40000 ALTER TABLE `site` DISABLE KEYS */;
/*!40000 ALTER TABLE `site` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stations`
--

DROP TABLE IF EXISTS `stations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '电台ID',
  `is_system` tinyint(1) unsigned NOT NULL COMMENT '是否系统级',
  `channel_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所属频道',
  `code` int(11) unsigned NOT NULL COMMENT '代码',
  `name` varchar(99) NOT NULL COMMENT '电台名',
  `logo` varchar(255) DEFAULT NULL COMMENT 'logo',
  PRIMARY KEY (`id`),
  KEY `is_system` (`is_system`),
  KEY `channel_id` (`channel_id`),
  KEY `code` (`code`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='电视台表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stations`
--

LOCK TABLES `stations` WRITE;
/*!40000 ALTER TABLE `stations` DISABLE KEYS */;
/*!40000 ALTER TABLE `stations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stations_epg`
--

DROP TABLE IF EXISTS `stations_epg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stations_epg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '直播流ID',
  `stations_id` int(11) unsigned NOT NULL COMMENT '电视台ID',
  `name` varchar(99) NOT NULL COMMENT '直播流名',
  `width` smallint(4) unsigned NOT NULL COMMENT '宽度',
  `height` smallint(4) unsigned NOT NULL COMMENT '高度',
  `cdn` text NOT NULL COMMENT 'cdn',
  `kpbs` smallint(4) unsigned NOT NULL COMMENT '码率',
  PRIMARY KEY (`id`),
  KEY `stations_id` (`stations_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='电视节目流';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stations_epg`
--

LOCK TABLES `stations_epg` WRITE;
/*!40000 ALTER TABLE `stations_epg` DISABLE KEYS */;
/*!40000 ALTER TABLE `stations_epg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stations_program`
--

DROP TABLE IF EXISTS `stations_program`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stations_program` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '节目ID',
  `stations_id` int(11) unsigned NOT NULL COMMENT '电视ID',
  `title` varchar(99) NOT NULL COMMENT '标题',
  `start` int(11) unsigned NOT NULL COMMENT '开始时间',
  `duration` int(11) unsigned NOT NULL COMMENT '时长',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年',
  PRIMARY KEY (`id`,`partition_by`),
  KEY `stations_id` (`stations_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='电视节目单'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stations_program`
--

LOCK TABLES `stations_program` WRITE;
/*!40000 ALTER TABLE `stations_program` DISABLE KEYS */;
/*!40000 ALTER TABLE `stations_program` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `theme`
--

DROP TABLE IF EXISTS `theme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `theme` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '专题ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  `keyword` varchar(255) NOT NULL COMMENT '关键词',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专题表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `theme`
--

LOCK TABLES `theme` WRITE;
/*!40000 ALTER TABLE `theme` DISABLE KEYS */;
/*!40000 ALTER TABLE `theme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `channels_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `mobile` varchar(16) NOT NULL COMMENT '手机',
  `name` varchar(99) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `salt` varchar(16) NOT NULL COMMENT '密钥',
  `grade` varchar(9) NOT NULL DEFAULT 'normal' COMMENT '会员等级',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年',
  PRIMARY KEY (`id`,`partition_by`),
  UNIQUE KEY `channels_id` (`channels_id`,`mobile`,`partition_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_grade`
--

DROP TABLE IF EXISTS `users_grade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_grade` (
  `id` int(11) unsigned NOT NULL COMMENT '等级ID',
  `code` varchar(9) NOT NULL COMMENT '等级代码',
  `name` varchar(30) NOT NULL COMMENT '等级名',
  `credit` int(11) unsigned NOT NULL COMMENT '所需积分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员等级表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_grade`
--

LOCK TABLES `users_grade` WRITE;
/*!40000 ALTER TABLE `users_grade` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_grade` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '视频ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  `keyword` varchar(255) NOT NULL COMMENT '关键词',
  `duration` int(11) unsigned NOT NULL COMMENT '播放时长',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年',
  PRIMARY KEY (`id`,`partition_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video`
--

LOCK TABLES `video` WRITE;
/*!40000 ALTER TABLE `video` DISABLE KEYS */;
/*!40000 ALTER TABLE `video` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `video_group`
--

DROP TABLE IF EXISTS `video_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `intro` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='视频集';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `video_group`
--

LOCK TABLES `video_group` WRITE;
/*!40000 ALTER TABLE `video_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `video_group` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-08-31 10:26:09
