ALTER TABLE special_category DROP INDEX channel_id;
ALTER TABLE special_category DROP INDEX special_id;
ALTER TABLE special_category DROP INDEX special_id_2;
ALTER TABLE `special_category` ADD INDEX(`channel_id`);
ALTER TABLE `special_category` ADD UNIQUE( `channel_id`, `name`);
ALTER TABLE `special_category` ADD UNIQUE( `special_id`, `code`);