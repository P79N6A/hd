CREATE TABLE IF NOT EXISTS `lottery_goods` (
  `id` int(11) unsigned NOT NULL COMMENT '奖品ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `goods_name` varchar(99) NOT NULL COMMENT '通用名',
  `thumb` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `is_real` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '虚拟/实物',
  `is_rewin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '允许重复中奖',
  `is_vericode` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要短信验证',
  `overtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '超时多久取消资格',
  `is_recover` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否回收奖池',
  `content` text NOT NULL COMMENT '奖品描述'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='奖品主属性表';


ALTER TABLE `lottery_goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`);

  ALTER TABLE `lottery_goods`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '奖品ID',AUTO_INCREMENT=1;