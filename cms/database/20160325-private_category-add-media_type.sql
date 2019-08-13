ALTER TABLE `private_category` ADD `media_type` TINYINT NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `private_category` ADD INDEX(`media_type`);
ALTER TABLE `private_category` ADD `father_id` INT NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `private_category` ADD INDEX(`father_id`);