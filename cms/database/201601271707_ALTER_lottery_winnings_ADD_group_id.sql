ALTER TABLE `lottery_winnings` ADD INDEX(`channel_id`);
ALTER TABLE `lottery_winnings` ADD INDEX(`lottery_id`);
ALTER TABLE `lottery_winnings` ADD INDEX(`lottery_channel_id`);
ALTER TABLE `lottery_winnings` ADD `lottery_group_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '摇奖会场ID' AFTER `channel_id`;
ALTER TABLE `lottery_contacts` DROP `channel_id`;
ALTER TABLE `lottery_winnings` ADD UNIQUE( `lottery_group_id`, `client_id`);