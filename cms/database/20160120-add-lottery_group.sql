CREATE TABLE IF NOT EXISTS `lottery_group` (
  `id` int(11) unsigned NOT NULL COMMENT '组ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `name` varchar(255) NOT NULL COMMENT '标题',
  `sub_name` varchar(255) DEFAULT NULL COMMENT '副标题',
  `thumb` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `top_banner` varchar(255) DEFAULT NULL COMMENT '头部',
  `open_time` int(11) unsigned NOT NULL COMMENT '开始时间',
  `close_time` int(11) unsigned NOT NULL COMMENT '结束时间',
  `is_single` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否单会场',
  `win_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '中奖时效，1：整场，整天',
  `intro` text NOT NULL COMMENT '简介',
  `content` text NOT NULL COMMENT '内容',
  `rule` text NOT NULL COMMENT '抽奖规则',
  `copyright` text NOT NULL COMMENT '版权',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='摇奖会场表';

ALTER TABLE `lottery_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `open_time` (`open_time`),
  ADD KEY `close_time` (`close_time`),
  ADD KEY `is_single` (`is_single`),
  ADD KEY `win_type` (`win_type`),
  ADD KEY `channel_id` (`channel_id`);

  ALTER TABLE `lottery_group`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '组ID',AUTO_INCREMENT=1;