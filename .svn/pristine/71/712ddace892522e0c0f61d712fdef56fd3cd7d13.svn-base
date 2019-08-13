CREATE TABLE IF NOT EXISTS `referer` (
  `id` int(11) unsigned NOT NULL COMMENT '供应源ID',
  `channel_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '频道ID',
  `name` varchar(99) NOT NULL DEFAULT '' COMMENT '供应源名称',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1：显示 0：隐藏'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='稿件来源表';
ALTER TABLE `referer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `channel_id_2` (`channel_id`,`name`),
  ADD KEY `status` (`status`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `sort` (`sort`);
ALTER TABLE `referer`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '供应源ID';