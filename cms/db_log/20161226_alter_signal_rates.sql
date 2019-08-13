ALTER TABLE `signal_rates`
ADD COLUMN `rate_unit`  varchar(16) NULL COMMENT '单位（k，m等）' AFTER `rate_weight`;

