ALTER TABLE `favorites` ADD `create_at` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '收藏日期' AFTER `data_id`, ADD INDEX (`create_at`);