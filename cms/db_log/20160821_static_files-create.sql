

CREATE TABLE IF NOT EXISTS `static_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `data_id` int(11) NOT NULL DEFAULT '0',
  `author_id` int(11) NOT NULL,
  `father` int(11) NOT NULL DEFAULT '0' COMMENT '父级目录',
  `is_folder` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否目录',
  `name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '文件名称',
  `type` enum('js','css','swf','jpg','png','gif','mp3') CHARACTER SET utf8 DEFAULT NULL COMMENT '文件类型',
  `path` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '文件路径',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '发布状态',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '年分区',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`,`partition_by`),
  KEY `channel_id` (`channel_id`),
  KEY `category_id` (`category_id`,`data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='静态文件'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION p2026 VALUES LESS THAN (2027) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;