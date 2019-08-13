ALTER TABLE `news`
  DROP `title`,
  DROP `intro`,
  DROP `thumb`,
  DROP `source`;
 ALTER TABLE `data` CHANGE `intro` `intro` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '源简介';
 ALTER TABLE `album`
  DROP `title`,
  DROP `intro`,
  DROP `thumb`;
  ALTER TABLE `videos`
  DROP `title`,
  DROP `intro`,
  DROP `thumb`,
  DROP `no_comment`;
  ALTER TABLE `news` DROP `no_comment`;
  ALTER TABLE `news_group` DROP `title`;
  ALTER TABLE `news_group` DROP `intro`;
  ALTER TABLE `specials`
  DROP `title`,
  DROP `intro`,
  DROP `thumb`;
  ALTER TABLE `peoples`
  DROP `name`,
  DROP `intro`;
  ALTER TABLE `peoples`
  DROP `avatar`,
  DROP `photo`;
  ALTER TABLE `video_collections`
  DROP `title`,
  DROP `intro`,
  DROP `thumb`,
  DROP `no_comment`;
  ALTER TABLE `data` ADD `comment_type` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '允许评论' AFTER `partition_by`, ADD INDEX (`comment_type`);
  ALTER TABLE `data` ADD `referer_id` INT(11) UNSIGNED NOT NULL COMMENT '来源ID' AFTER `comment_type`, ADD INDEX (`referer_id`);
  ALTER TABLE `data` CHANGE `referer_id` `referer_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '来源ID';
  ALTER TABLE `data` ADD `referer_url` VARCHAR(255) NULL COMMENT '来源URL' AFTER `referer_id`;