ALTER TABLE `data` DROP INDEX `status`;
ALTER TABLE `data` DROP INDEX `channel_id`;
ALTER TABLE `data` ADD INDEX( `channel_id`, `status`);
ALTER TABLE `category_data` DROP INDEX data_id;
ALTER TABLE `category_data` ADD UNIQUE(`category_id`, `data_id`, `partition_by`);