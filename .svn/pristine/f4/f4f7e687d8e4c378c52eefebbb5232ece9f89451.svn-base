ALTER TABLE `special_category` ADD `channel_id` INT(11) NOT NULL DEFAULT '0' COMMENT '频道ID' AFTER `id`;
UPDATE `special_category` SET `channel_id` = (SELECT `channel_id` FROM `specials` WHERE `id` = `special_category`.`special_id`) WHERE `channel_id` = 0;
ALTER TABLE `special_category` ADD UNIQUE( `channel_id`, `code`);
ALTER TABLE `special_category` CHANGE `channel_id` `channel_id` INT(11) NOT NULL COMMENT '频道ID';