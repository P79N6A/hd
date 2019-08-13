--
-- 表的结构 `credit_comment`
--

CREATE TABLE IF NOT EXISTS `credit_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `comment_type` tinyint(4) NOT NULL COMMENT '1.点播 2.直播',
  `created_at` int(11) NOT NULL,
  `partition_by` smallint(4) NOT NULL,
  PRIMARY KEY (`id`,`partition_by`),
  UNIQUE KEY `channel_id` (`channel_id`,`user_id`,`video_id`,`comment_type`,`partition_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION p2026 VALUES LESS THAN (2027) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */