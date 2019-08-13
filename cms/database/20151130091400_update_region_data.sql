ALTER TABLE `region_data` CHANGE `data_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `region_data` ADD `data_id` INT(11) UNSIGNED NOT NULL COMMENT '媒资ID' AFTER `id`;
ALTER TABLE region_data DROP INDEX country_id;
ALTER TABLE `region_data` ADD UNIQUE( `data_id`, `country_id`, `province_id`, `city_id`, `county_id`, `village_id`);