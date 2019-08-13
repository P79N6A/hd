CREATE TABLE IF NOT EXISTS `credit_rules` (
  `id` int(11) unsigned NOT NULL COMMENT '主ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '所属ID',
  `type` smallint(3) unsigned NOT NULL COMMENT '规则类型',
  `single` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '单次送积分',
  `single_min` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '单次送积分上限',
  `single_max` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '单次送积分下限',
  `range_step` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '连续操作步进值',
  `range_max` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '连续操作最大值',
  `day_limit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '每日上限,0无限',
  `user_limit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '每个用户上限',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '标题/名称',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `credit_rules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `channel_id` (`channel_id`,`name`),
  ADD KEY `type` (`type`);


  ALTER TABLE `credit_rules`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主ID';

  ALTER TABLE `credit_rules` ADD `rule_group` TINYINT(4) NOT NULL COMMENT '0:新手任务 1.日常任务' AFTER `type`;

ALTER TABLE `credit_rules` ADD `ruledesc` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '规则描述' AFTER `name`;
`ALTER TABLE credit_rules DROP INDEX channel_id;`


  CREATE TABLE IF NOT EXISTS `credit_transactions` (
  `id` int(11) unsigned NOT NULL COMMENT '主ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '所属ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '操作类型',
  `credits` bigint(20) NOT NULL COMMENT '交易积分',
  `trader` varchar(20) NOT NULL DEFAULT 'system' COMMENT '交易方, 系统, API, 兑吧',
  `orderNum` varchar(255) NOT NULL DEFAULT '' COMMENT '兑吧交易流水',
  `actualPrice` int(11) NOT NULL DEFAULT '0' COMMENT '兑吧实际价格',
  `timestamp` varchar(20) NOT NULL DEFAULT '' COMMENT '兑吧时间戳',
  `detail` text COMMENT '流水详情',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '年分区',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员积分流水表'
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
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;


 ALTER TABLE `credit_transactions`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD KEY `orderNum` (`orderNum`),
  ADD KEY `channel_id` (`channel_id`,`trader`,`partition_by`);

  ALTER TABLE `credit_transactions`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主ID';


  CREATE TABLE IF NOT EXISTS `credit_url_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `platform_type` tinyint(4) NOT NULL COMMENT '1.微信 2.微博 3.QQ空间',
  `created_at` int(11) NOT NULL,
  `partition_by` smallint(4) NOT NULL,
  PRIMARY KEY (`id`,`partition_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




ALTER TABLE credit_url_share PARTITION BY RANGE COLUMNS(partition_by) (PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB, PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB, PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB, PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB, PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB, PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB, PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB, PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB, PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB, PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB, PARTITION p2026 VALUES LESS THAN (2027) ENGINE = InnoDB, PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB);
ALTER TABLE `credit_url_share` ADD UNIQUE( `channel_id`, `user_id`, `url`, `platform_type`, `partition_by`);

