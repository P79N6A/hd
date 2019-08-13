ALTER TABLE `advert_space` DROP `intype`;
ALTER TABLE `advert` CHANGE `type` `type` TINYINT NOT NULL COMMENT '广告类型';
ALTER TABLE `advert` ADD `duration` SMALLINT NOT NULL COMMENT '广告时长' AFTER `enddate`;


