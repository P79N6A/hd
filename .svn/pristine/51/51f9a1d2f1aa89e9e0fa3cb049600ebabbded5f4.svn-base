DROP TABLE IF EXISTS `subscription`;
CREATE TABLE `subscription` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `set_id` int(11) NOT NULL COMMENT '订阅专辑id'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `subscription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `set_id` (`set_id`);

ALTER TABLE `subscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `subscription_set`;
CREATE TABLE `subscription_set` (
  `id` int(11) NOT NULL,
  `set_id` int(11) NOT NULL COMMENT '订阅专辑id',
  `name` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '订阅专辑名称'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='订阅专辑';

ALTER TABLE `subscription_set`
  ADD PRIMARY KEY (`id`),
  ADD KEY `set_id` (`set_id`);

ALTER TABLE `subscription_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

DROP TABLE IF EXISTS `subscription_set_info`;
CREATE TABLE `subscription_set_info` (
  `id` int(11) NOT NULL,
  `set_id` int(11) NOT NULL COMMENT '订阅专辑id',
  `set_cover` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '专辑封面',
  `subscription_number` int(11) NOT NULL DEFAULT '0' COMMENT '订阅数量',
  `is_keyword` int(11) NOT NULL DEFAULT '0' COMMENT '是否设为热词',
  `sort` int(11) NOT NULL DEFAULT '1' COMMENT '排序权重'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='订阅专辑信息';

ALTER TABLE `subscription_set_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `set_id` (`set_id`);

ALTER TABLE `subscription_set_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `subscription` ADD UNIQUE( `uid`, `set_id`);