ALTER TABLE `advert` ADD `status` TINYINT(1) UNSIGNED NOT NULL COMMENT '0禁用1启用' AFTER `listorder`;