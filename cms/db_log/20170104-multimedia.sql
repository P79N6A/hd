
CREATE TABLE `multimedia` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '新闻ID',
  `channel_id` int(11) UNSIGNED NOT NULL COMMENT '频道ID',
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

ALTER TABLE `multimedia`
  ADD PRIMARY KEY (`id`,`partition_by`);

ALTER TABLE `multimedia`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '新闻ID';