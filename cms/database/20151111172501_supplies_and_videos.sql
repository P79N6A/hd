CREATE TABLE `supplies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `source_id` int(11) unsigned NOT NULL COMMENT '数据源',
  `supply_category_id` int(11) unsigned NOT NULL COMMENT '供应分类ID',
  `origin_content` text COMMENT '原始数据',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '年分区',
  PRIMARY KEY (`id`,`partition_by`),
  KEY `source_id` (`source_id`,`status`),
  KEY `supply_category_id` (`supply_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='外部供应数据表'
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

CREATE TABLE `supply_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `source_id` int(11) unsigned NOT NULL COMMENT '源ID',
  `origin_id` int(11) unsigned NOT NULL COMMENT '原始数据分类ID',
  `origin_name` varchar(128) NOT NULL DEFAULT '' COMMENT '原始数据分类名',
  PRIMARY KEY (`id`),
  KEY `source_id` (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='外部供应数据 - 分类表';

CREATE TABLE `supply_sources` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '供应源ID',
  `type` enum('video','news') NOT NULL DEFAULT 'video' COMMENT '供应源类型',
  `name` varchar(256) NOT NULL DEFAULT '' COMMENT '供应源名称',
  `eshort` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='外部供应数据 - 源数据';

CREATE TABLE `supply_to_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `supply_category_id` int(11) unsigned NOT NULL COMMENT '供应数据分类ID',
  `category_id` int(11) unsigned NOT NULL COMMENT '分类数据',
  PRIMARY KEY (`id`),
  KEY `supply_category_id` (`supply_category_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='外部供应数据与发布渠道的对应关系';

CREATE TABLE `video_collections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '视频集ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  `thumb` varchar(255) NOT NULL COMMENT '封面',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `author_id` int(11) unsigned NOT NULL COMMENT '作者ID',
  `author_name` varchar(30) NOT NULL COMMENT '作者姓名',
  `no_comment` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '禁止评论',
  `type` enum('variety','movie','series','cartoon','music') DEFAULT NULL COMMENT '类型',
  `extra` text COMMENT '额外字段',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  KEY `channel_id` (`channel_id`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`),
  KEY `author_name` (`author_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='视频集';

CREATE TABLE `video_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `video_id` int(11) NOT NULL COMMENT '视频ID',
  `path` varchar(256) NOT NULL DEFAULT '' COMMENT '路径',
  `rate` varchar(50) DEFAULT NULL COMMENT '评分',
  `format` varchar(50) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `width` varchar(50) DEFAULT NULL,
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '年分区',
  PRIMARY KEY (`id`,`partition_by`),
  KEY `video_id` (`video_id`),
  KEY `rate` (`rate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='视频文件'
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

CREATE TABLE `videos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '新闻ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `collection_id` int(11) unsigned NOT NULL COMMENT '视频集ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  `thumb` varchar(255) DEFAULT NULL COMMENT '源缩略图',
  `author_id` int(11) unsigned NOT NULL COMMENT '作者ID',
  `author_name` varchar(30) NOT NULL COMMENT '作者名字',
  `supply_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '供应源ID',
  `duration` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '修改时间',
  `no_comment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '禁止评论',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年',
  PRIMARY KEY (`id`,`partition_by`),
  KEY `weight` (`created_at`),
  KEY `created_at` (`created_at`),
  KEY `updated_at` (`updated_at`),
  KEY `author_id` (`author_id`),
  KEY `author_name` (`author_name`),
  KEY `channel_id` (`channel_id`),
  KEY `collection_id` (`collection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='视频文件'
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