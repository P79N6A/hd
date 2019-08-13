
CREATE TABLE `attach_file` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '附件文件ID',
  `attach_id` int(11) UNSIGNED NOT NULL COMMENT '附件ID',
  `path` varchar(255) NOT NULL COMMENT '附件路径',
  `intro` text NOT NULL COMMENT '附件文件描述',
  `ext` varchar(255) NOT NULL COMMENT '附件文件扩展名',
  `file_type` TINYINT(1) UNSIGNED NOT NULL COMMENT '文件类型 0:未知,1:图片,2:视频，3:文档',
  `partition_by` smallint(4) UNSIGNED NOT NULL COMMENT '分区/年'
) ENGINE=InnoDB DEFAULT CHARSET=utf8
PARTITION BY RANGE COLUMNS(partition_by)
(
PARTITION p2014 VALUES LESS THAN (2015)ENGINE=InnoDB,
PARTITION p2015 VALUES LESS THAN (2016)ENGINE=InnoDB,
PARTITION p2016 VALUES LESS THAN (2017)ENGINE=InnoDB,
PARTITION p2017 VALUES LESS THAN (2018)ENGINE=InnoDB,
PARTITION p2018 VALUES LESS THAN (2019)ENGINE=InnoDB,
PARTITION p2019 VALUES LESS THAN (2020)ENGINE=InnoDB,
PARTITION p2020 VALUES LESS THAN (2021)ENGINE=InnoDB,
PARTITION p2022 VALUES LESS THAN (2023)ENGINE=InnoDB,
PARTITION p2023 VALUES LESS THAN (2024)ENGINE=InnoDB,
PARTITION p2024 VALUES LESS THAN (2025)ENGINE=InnoDB,
PARTITION p2025 VALUES LESS THAN (2026)ENGINE=InnoDB,
PARTITION pmax VALUES LESS THAN (MAXVALUE)ENGINE=InnoDB
);

ALTER TABLE `attach_file`
  ADD PRIMARY KEY (`id`,`partition_by`);

ALTER TABLE `attach_file`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '附件文件ID';