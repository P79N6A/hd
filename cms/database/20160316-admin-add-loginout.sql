ALTER TABLE `admin` ADD `logincount` INT UNSIGNED NOT NULL  DEFAULT '0' AFTER `salt`;