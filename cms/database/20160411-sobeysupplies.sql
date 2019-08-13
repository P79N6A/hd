CREATE TABLE IF NOT EXISTS `sobeysupplies` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT 'Ƶ��ID',
  `source_id` int(11) unsigned NOT NULL COMMENT '����Դ',
  `supply_category_id` int(11) unsigned NOT NULL COMMENT '��Ӧ����ID',
  `origin_content` text COMMENT 'ԭʼ����',
  `created_at` int(11) unsigned NOT NULL COMMENT '����ʱ��',
  `updated_at` int(11) unsigned NOT NULL COMMENT '����ʱ��',
  `status` tinyint(1) unsigned NOT NULL COMMENT '״̬',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '�����'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='�ⲿ��Ӧ���ݱ�'
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

ALTER TABLE `private_category` ADD `father_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '���ڵ�' AFTER `name`;


CREATE TABLE IF NOT EXISTS `supply_to_privatecategory` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT 'Ƶ��ID',
  `supply_category_id` int(11) unsigned NOT NULL COMMENT '��Ӧ���ݷ���ID',
  `private_category_id` int(11) unsigned NOT NULL COMMENT '˽����ĿID',
  `origin_type` tinyint(1) unsigned NOT NULL COMMENT '��ԴID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='�ⲿ��Ӧ�����뷢�������Ķ�Ӧ��ϵ';

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