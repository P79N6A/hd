ALTER TABLE `lottery_winnings` ADD `contacts_token` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '联系方式关联' AFTER `client_id`;
ALTER TABLE `lottery_winnings` ADD `sum` INT(11) UNSIGNED NOT NULL DEFAULT '1' COMMENT '中奖次数' AFTER `lottery_channel_id`;
ALTER TABLE `usercenter`.`lottery_winnings` DROP INDEX `lottery_group_id`, ADD INDEX `lottery_group_id` (`lottery_group_id`, `client_id`) USING BTREE;
