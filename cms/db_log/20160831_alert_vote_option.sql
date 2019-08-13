ALTER TABLE `vote_option` ADD `video_url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '视频播放地址' AFTER `picture`, ADD `other` TINYINT UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否需要填写' AFTER `video_url`;
ALTER TABLE `vote` ADD INDEX(`status`);