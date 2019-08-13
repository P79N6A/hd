ALTER TABLE referer DROP INDEX name;
ALTER TABLE `referer` ADD UNIQUE( `channel_id`, `name`);