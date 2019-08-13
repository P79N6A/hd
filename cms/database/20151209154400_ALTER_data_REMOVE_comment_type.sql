UPDATE news SET keywords = '' WHERE keywords IS NULL;
ALTER TABLE `news` CHANGE `keywords` `keywords` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '关键词';
ALTER TABLE `news` ADD `comment_type` SMALLINT(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '评论类型' AFTER `keywords`;

UPDATE album SET keywords = '' WHERE keywords IS NULL;
ALTER TABLE `album` CHANGE `keywords` `keywords` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '关键词';
ALTER TABLE `album` CHANGE `no_comment` `comment_type` SMALLINT(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '评论类型' AFTER `keywords`;

UPDATE videos SET keywords = '' WHERE keywords IS NULL;
ALTER TABLE `videos` CHANGE `keywords` `keywords` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '关键词';
ALTER TABLE `videos` ADD `comment_type` SMALLINT(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '评论类型' AFTER `keywords`;

UPDATE video_collections SET keywords = '' WHERE keywords IS NULL;
ALTER TABLE `video_collections` CHANGE `keywords` `keywords` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '关键词' AFTER `extra`;
ALTER TABLE `video_collections` ADD `comment_type` SMALLINT(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '评论类型' AFTER `keywords`;

ALTER TABLE `specials` ADD `keywords` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '关键词' AFTER `end_time`;
ALTER TABLE `specials` ADD `comment_type` SMALLINT(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '评论类型' AFTER `keywords`;
ALTER TABLE `specials` DROP `author_id`, DROP `author_name`;

ALTER TABLE `data` DROP `comment_type`;