ALTER TABLE `client` ADD `device_token` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '苹果推送id' AFTER `sdk_version`;