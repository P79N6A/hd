ALTER TABLE `lottery_channels` ADD `channel_id` INT(11) UNSIGNED NOT NULL DEFAULT '1' COMMENT '频道ID' AFTER `id`, ADD INDEX (`channel_id`);
ALTER TABLE `lotteries` ADD `channel_id` INT(11) UNSIGNED NOT NULL COMMENT '频道ID' AFTER `id`, ADD `group_id` INT(11) UNSIGNED NOT NULL COMMENT '会场ID' AFTER `channel_id`;
ALTER TABLE `lotteries` ADD INDEX( `channel_id`, `group_id`);