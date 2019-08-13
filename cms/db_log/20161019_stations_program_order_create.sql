CREATE TABLE `stations_program_order` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `partition_by` smallint(4) UNSIGNED NOT NULL COMMENT '年分区'
) ENGINE=InnoDB DEFAULT CHARSET=latin1
PARTITION BY RANGE COLUMNS(partition_by)
(
PARTITION p2016 VALUES LESS THAN (2017)ENGINE=InnoDB,
PARTITION p2017 VALUES LESS THAN (2018)ENGINE=InnoDB,
PARTITION p2018 VALUES LESS THAN (2019)ENGINE=InnoDB,
PARTITION p2019 VALUES LESS THAN (2020)ENGINE=InnoDB,
PARTITION p2020 VALUES LESS THAN (2021)ENGINE=InnoDB,
PARTITION p2022 VALUES LESS THAN (2023)ENGINE=InnoDB,
PARTITION p2023 VALUES LESS THAN (2024)ENGINE=InnoDB,
PARTITION p2024 VALUES LESS THAN (2025)ENGINE=InnoDB,
PARTITION p2025 VALUES LESS THAN (2026)ENGINE=InnoDB,
PARTITION p2026 VALUES LESS THAN (2027)ENGINE=InnoDB,
PARTITION pmax VALUES LESS THAN (MAXVALUE)ENGINE=InnoDB
);

ALTER TABLE `stations_program_order`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD KEY `program_id` (`program_id`);
ALTER TABLE `stations_program_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;