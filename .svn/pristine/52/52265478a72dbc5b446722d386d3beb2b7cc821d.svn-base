CREATE TABLE IF NOT EXISTS `supply_rsync` (
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `origin_id` int(11) unsigned NOT NULL COMMENT '视频ID',
  `data_id` int(11) unsigned NOT NULL COMMENT '数据ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='同步映射表';

ALTER TABLE `supply_rsync` ADD `origin_type` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '供应源类型，1：观止' AFTER `channel_id`, ADD INDEX (`origin_type`);
ALTER TABLE `supply_rsync` ADD UNIQUE( `channel_id`, `origin_type`, `origin_id`, `data_id`);


ALTER TABLE `supply_categories` DROP `source_id`;
ALTER TABLE `supplies` ADD `channel_id` INT(11) UNSIGNED NOT NULL COMMENT '频道ID' AFTER `id`, ADD INDEX (`channel_id`);
ALTER TABLE `supply_categories` ADD `channel_id` INT(11) UNSIGNED NOT NULL COMMENT '频道ID' AFTER `id`, ADD INDEX (`channel_id`);
ALTER TABLE `supply_to_category` ADD `channel_id` INT(11) UNSIGNED NOT NULL COMMENT '频道ID' AFTER `id`, ADD INDEX (`channel_id`);