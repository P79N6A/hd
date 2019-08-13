ALTER TABLE `template_friends` ADD `channel_id` INT(11) UNSIGNED NOT NULL COMMENT '频道ID' AFTER `id`, ADD `domain_id` INT(11) UNSIGNED NOT NULL COMMENT '域名ID' AFTER `channel_id`;
ALTER TABLE `template_friends` CHANGE `friend_type` `friend_type` ENUM('category','data','region') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'category' COMMENT '关联数据类型';
ALTER TABLE `template_friends` DROP INDEX url;
ALTER TABLE `template_friends` DROP INDEX friend_id;
ALTER TABLE `template_friends` ADD UNIQUE( `domain_id`, `url`);
ALTER TABLE `template_friends` ADD INDEX(`channel_id`);
ALTER TABLE `template_friends` ADD `friend2_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '关联数据值2' AFTER `friend_type`, ADD `friend2_type` ENUM('category','data','region','null') NOT NULL DEFAULT 'null' COMMENT '关联数据类型2' AFTER `friend2_id`, ADD `friend3_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '关联数据值3' AFTER `friend2_type`, ADD `friend3_type` ENUM('category','data','region','null') NOT NULL DEFAULT 'null' COMMENT '关联数据类型3' AFTER `friend3_id`;
ALTER TABLE `template_friends` ADD UNIQUE( `domain_id`, `friend_id`, `friend_type`, `friend2_id`, `friend2_type`, `friend3_id`, `friend3_type`);
ALTER TABLE `template_friends` ADD `template_id` INT(11) UNSIGNED NOT NULL COMMENT '模板ID' AFTER `domain_id`, ADD INDEX (`template_id`);
CREATE TABLE `blocks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `code` varchar(128) NOT NULL COMMENT '区块代码',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `author_id` int(11) unsigned NOT NULL COMMENT '创建者',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `channel_id` (`channel_id`,`code`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='区块表';
CREATE TABLE `block_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `block_id` int(11) unsigned NOT NULL COMMENT '区块ID',
  `name` varchar(128) NOT NULL COMMENT '区块值名称',
  `value` text NOT NULL COMMENT '区块值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `block_id` (`block_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;