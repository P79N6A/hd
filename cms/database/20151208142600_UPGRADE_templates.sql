ALTER TABLE `templates` CHANGE `type` `type` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'custom' COMMENT '类型';
UPDATE `templates` SET `type` = 0 WHERE `type` = 'static';
UPDATE `templates` SET `type` = 1 WHERE `type` = 'layout';
UPDATE `templates` SET `type` = 2 WHERE `type` = 'custom';
UPDATE `templates` SET `type` = 10 WHERE `type` = 'page';
UPDATE `templates` SET `type` = 11 WHERE `type` = 'index';
UPDATE `templates` SET `type` = 12 WHERE `type` = 'error';
UPDATE `templates` SET `type` = 101 WHERE `type` = 'detail';
UPDATE `templates` SET `type` = 102 WHERE `type` = 'album';
UPDATE `templates` SET `type` = 201 WHERE `type` = 'category';
UPDATE `templates` SET `type` = 300 WHERE `type` = 'region';
UPDATE `templates` SET `type` = 301 WHERE `type` = 'region_category';
ALTER TABLE `templates` CHANGE `type` `type` SMALLINT(4) NOT NULL DEFAULT '2' COMMENT '类型';
ALTER TABLE `videos` ADD `keywords` VARCHAR(255) NULL COMMENT '关键词' AFTER `id`;
ALTER TABLE `templates` ADD INDEX( `domain_id`, `type`);