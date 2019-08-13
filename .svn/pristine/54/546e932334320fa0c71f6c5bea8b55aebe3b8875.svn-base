ALTER TABLE `template_friends` DROP `friend_type`, DROP `friend2_type`, DROP `friend3_type`;

ALTER TABLE `template_friends` CHANGE `friend_id` `category_id` INT(11) UNSIGNED NOT NULL COMMENT '关联数据ID', CHANGE `friend2_id` `region_id` INT(11) UNSIGNED NOT NULL COMMENT '关联数据值2', CHANGE `friend3_id` `data_id` INT(11) UNSIGNED NOT NULL COMMENT '关联数据值3';

ALTER TABLE template_friends DROP INDEX domain_id_2;
ALTER TABLE `template_friends` ADD UNIQUE( `domain_id`, `category_id`, `region_id`, `data_id`);