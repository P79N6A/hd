ALTER TABLE `lottery_contacts` ADD `channel_id` INT NOT NULL AFTER `id`;
ALTER TABLE `lottery_prizes` ADD `channel_id` INT NOT NULL AFTER `id`;
ALTER TABLE `lottery_winnings` ADD `channel_id` INT NOT NULL AFTER `id`;