ALTER TABLE `domains` ADD `cdn_alias` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'CDN别名' AFTER `name`;