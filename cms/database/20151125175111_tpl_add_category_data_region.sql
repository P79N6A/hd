ALTER TABLE `templates` CHANGE `type` `type` ENUM('index','category','region','region_category','detail','album','layout','error','static','custom') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'custom' COMMENT '类型';

CREATE TABLE `template_friends` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主ID',
  `friend_id` int(11) unsigned NOT NULL COMMENT '关联数据ID',
  `friend_type` enum('category','data') NOT NULL DEFAULT 'category' COMMENT '关联数据类型',
  `url` varchar(255) NOT NULL COMMENT '路径',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `friend_id` (`friend_id`,`friend_type`),
  UNIQUE KEY `url` (`url`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='模板关联数据';