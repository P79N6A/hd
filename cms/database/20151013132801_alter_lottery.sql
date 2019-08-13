UPDATE  `hd_cztv`.`lotteries` SET  `times_limit` =  '3' WHERE  `lotteries`.`id` =1;
TRUNCATE TABLE lottery_winnings;
TRUNCATE TABLE lottery_contacts;
ALTER TABLE  `lottery_winnings` ADD  `lottery_channel_id` INT( 11 ) UNSIGNED NOT NULL COMMENT  '抽奖频道ID' AFTER  `lottery_id`;
ALTER TABLE  `lottery_winnings` ADD UNIQUE (
  `client_id` ,
  `lottery_channel_id`
);