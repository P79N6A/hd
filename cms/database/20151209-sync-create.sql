CREATE TABLE IF NOT EXISTS `sync` (
  `id` int(11) unsigned NOT NULL COMMENT '数据导入ID',
  `channel_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '频道ID',
  `old_table` varchar(30) NOT NULL COMMENT '导入原表',
  `new_table` varchar(30) NOT NULL COMMENT '新表',
  `type` varchar(10) NOT NULL COMMENT '数据类型',
  `old_id` int(11) unsigned NOT NULL COMMENT '老数据ID',
  `new_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '新数据ID',
  `old_url` varchar(255) NOT NULL DEFAULT '' COMMENT '老url',
  `new_url` varchar(255) NOT NULL DEFAULT '' COMMENT '新url',
  `domain` varchar(99) NOT NULL COMMENT '域名',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `sync`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `new_table` (`new_table`),
  ADD KEY `type` (`type`),
  ADD KEY `old_id` (`old_id`),
  ADD KEY `new_id` (`new_id`),
  ADD KEY `domain` (`domain`),
  ADD KEY `stauts` (`status`);

  ALTER TABLE `sync`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '数据导入ID';