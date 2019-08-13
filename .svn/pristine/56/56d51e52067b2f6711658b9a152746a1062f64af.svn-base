DROP TABLE `people_templates`;
CREATE TABLE `special_templates` (
  `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
  COMMENT '专题模板ID',
  `channel_id` INT(11) UNSIGNED NOT NULL
  COMMENT '频道ID',
  `name`       VARCHAR(50)      NOT NULL DEFAULT ''
  COMMENT '专题模板名称',
  `fields`     TEXT             NOT NULL
  COMMENT '专题模板自定义字段名',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = '专题模板表';

DROP TABLE `people_extras`;
CREATE TABLE `special_extras` (
  `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
  COMMENT '专题扩展表',
  `special_id` INT(11) UNSIGNED NOT NULL
  COMMENT '专题ID',
  `name`       VARCHAR(128)     NOT NULL DEFAULT ''
  COMMENT '扩展键名',
  `value`      TEXT             NOT NULL
  COMMENT '扩展键值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `people_id` (`special_id`, `name`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COMMENT = '专题扩展表';

ALTER TABLE `specials` ADD `start_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
COMMENT '专题开始时间,如: 行程开始, 人物出生'
AFTER `banner`, ADD `end_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
COMMENT '专题开始时间,如: 行程结束, 人物逝世'
AFTER `start_time`;

DROP TABLE `peoples`;