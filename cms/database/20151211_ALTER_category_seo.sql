ALTER TABLE category_seo ADD COLUMN title VARCHAR(100) NOT NULL DEFAULT '';
ALTER TABLE category_seo ADD COLUMN channel_id INT(11) unsigned NOT NULL COMMENT '频道ID';
ALTER TABLE `category_seo` ADD `intro` TEXT NOT NULL COMMENT '栏目简介，供app或者某些特殊栏目记录信息' AFTER `channel_id`;
ALTER TABLE `category_seo` ADD `tips` TEXT NOT NULL COMMENT '主持人相关信息存储' AFTER `intro`;
