ALTER TABLE `category` DROP INDEX `name`;
ALTER TABLE `category` DROP INDEX `channel_id`;
ALTER TABLE `category` DROP INDEX `father_id`;
ALTER TABLE `category` ADD INDEX( `channel_id`, `name`);
ALTER TABLE `category` ADD UNIQUE( `channel_id`, `code`);
ALTER TABLE `category` ADD INDEX( `channel_id`, `father_id`);