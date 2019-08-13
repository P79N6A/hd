CREATE TABLE IF NOT EXISTS `sobeysupplies` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `source_id` int(11) unsigned NOT NULL COMMENT '数据源',
  `supply_category_id` int(11) unsigned NOT NULL COMMENT '供应分类ID',
  `origin_content` text COMMENT '原始数据',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '年分区'
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `supplies`
--
ALTER TABLE `sobeysupplies`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD KEY `source_id` (`source_id`,`status`),
  ADD KEY `supply_category_id` (`supply_category_id`),
  ADD KEY `channel_id` (`channel_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `supplies`
--
ALTER TABLE `sobeysupplies`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID';

ALTER TABLE `private_category` ADD `father_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父节点' AFTER `name`;


CREATE TABLE IF NOT EXISTS `supply_to_privatecategory` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `supply_category_id` int(11) unsigned NOT NULL COMMENT '供应数据分类ID',
  `private_category_id` int(11) unsigned NOT NULL COMMENT '私有栏目ID',
  `origin_type` tinyint(1) unsigned NOT NULL COMMENT '来源ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='外部供应数据与发布渠道的对应关系';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `supply_to_category`
--
ALTER TABLE `supply_to_privatecategory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supply_category_id` (`supply_category_id`,`private_category_id`),
  ADD KEY `private_channel_id` (`channel_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `supply_to_category`
--
ALTER TABLE `supply_to_privatecategory`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID';