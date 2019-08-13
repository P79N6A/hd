ALTER TABLE `signal_playurl`
ADD COLUMN `is_pushing`  tinyint(4) UNSIGNED NULL DEFAULT 0 COMMENT '是否推送中' AFTER `rate_id`;



