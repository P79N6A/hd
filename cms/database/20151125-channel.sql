ALTER TABLE `channel` CHANGE `name` `name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '频道名';
ALTER TABLE `channel` ADD `shortname` VARCHAR(50) NOT NULL COMMENT '短标识' AFTER `name`;
ALTER TABLE `channel` ADD `channel_info` TEXT NOT NULL COMMENT '频道详情' AFTER `channel_instr`;
ALTER TABLE `channel` ADD `channel_logo_slave` VARCHAR(255) NULL COMMENT '副logo' AFTER `channel_logo`;
