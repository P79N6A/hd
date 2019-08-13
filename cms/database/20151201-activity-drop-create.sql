DROP TABLE activity_signup;
DROP TABLE activity;
CREATE TABLE IF NOT EXISTS `activity` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '频道ID',
  `thumb` varchar(255) NOT NULL,
  `content` text NOT NULL COMMENT '活动详情',
  `intro` text NOT NULL,
  `location` varchar(255) NOT NULL DEFAULT '' COMMENT '活动地点',
  `start_time` int(11) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(11) unsigned NOT NULL COMMENT '结束时间',
  `singup_count` int(11) NOT NULL DEFAULT '0' COMMENT '报名人数'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `activity` ADD PRIMARY KEY(`id`);
ALTER TABLE `activity` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `activity` ADD INDEX(`channel_id`);
ALTER TABLE `activity` ADD INDEX(`location`);
ALTER TABLE `activity` ADD INDEX(`start_time`);
ALTER TABLE `activity` ADD INDEX(`end_time`);

CREATE TABLE IF NOT EXISTS `activity_signup` (
  `id` int(11) NOT NULL COMMENT '活动报名ID',
  `channel_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '频道ID',
  `activity_id` int(11) NOT NULL COMMENT '活动ID',
  `mobile` varchar(16) NOT NULL,
  `name` varchar(99) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `user_name` varchar(30) NOT NULL DEFAULT '' COMMENT '用户名冗余',
  `create_at` int(11) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `activity_signup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_name` (`user_name`),
  ADD KEY `create_at` (`create_at`),
  ADD KEY `mobile` (`mobile`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `name` (`name`);
  ALTER TABLE `activity_signup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '活动报名ID';

