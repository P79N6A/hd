ALTER TABLE `features` ADD `type` ENUM('category', 'live' ) NOT NULL DEFAULT 'category' COMMENT '栏目推荐，直播推荐' AFTER `position`;
ALTER TABLE `features` ADD INDEX(`type`);
