ALTER TABLE `push_msg`
ADD COLUMN `push_ios_version`  int(8) NULL COMMENT 'ios版本：0：10.0以下版本，1：10.0及以上版本' AFTER `push_single`;