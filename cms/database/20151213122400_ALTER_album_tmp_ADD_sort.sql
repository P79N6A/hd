ALTER TABLE `album_tmp` ADD `intro` TEXT NOT NULL COMMENT '简介' AFTER `path`;
ALTER TABLE `album_tmp` ADD `sort` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序' AFTER `intro`;