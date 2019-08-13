ALTER TABLE special_category DROP INDEX channel_id_2;
ALTER TABLE `special_category` ADD UNIQUE( `special_id`, `name`);