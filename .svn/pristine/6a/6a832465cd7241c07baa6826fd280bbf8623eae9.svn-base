CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) unsigned NOT NULL COMMENT '菜单ID',
  `channel_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '频道ID',
  `icon` varchar(255) NOT NULL COMMENT '图标',
  `name` varchar(50) NOT NULL COMMENT '菜单名',
  `type` enum('news','live','vod','radio','gov','act','album','person','tips') NOT NULL DEFAULT 'news' COMMENT '菜单类型',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '绑定栏目ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 1：显示 0：隐藏'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `type` (`type`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `sort` (`sort`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `status` (`status`);
  ALTER TABLE `menu`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',AUTO_INCREMENT=1;