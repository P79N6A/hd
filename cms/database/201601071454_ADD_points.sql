ALTER TABLE `users` ADD `credits` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '会员积分' AFTER `grade`;

