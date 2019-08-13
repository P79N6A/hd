
ALTER TABLE `stations_program`
CHANGE COLUMN `start` `start_time`  int(11) UNSIGNED NOT NULL COMMENT '开始时间' AFTER `title`,
MODIFY COLUMN `duration`  int(11) UNSIGNED ZEROFILL NOT NULL COMMENT '时长' AFTER `start_time`,
ADD COLUMN `end_time`  int(11) UNSIGNED ZEROFILL NOT NULL COMMENT '结束时间' AFTER `start_time`,
ADD COLUMN `start_date`  int(11) UNSIGNED ZEROFILL NOT NULL COMMENT '开始日期' AFTER `end_time`,
ADD COLUMN `rate`  varchar(50) NOT NULL DEFAULT '' COMMENT '码率' AFTER `duration`,
ADD COLUMN `format`  varchar(30) NOT NULL DEFAULT '' COMMENT '格式' AFTER `rate`,
ADD COLUMN `entire`  int(11) UNSIGNED ZEROFILL NOT NULL COMMENT '整档' AFTER `format`,
ADD COLUMN `tear`  int(11) UNSIGNED ZEROFILL NOT NULL COMMENT '拆条' AFTER `entire`,
ADD COLUMN `records`  varchar(255) NOT NULL DEFAULT '' AFTER `tear`,
ADD COLUMN `is_rate`  tinyint(4) UNSIGNED ZEROFILL NOT NULL COMMENT '是否录制，0:无,1:录制' AFTER `records`;




UPDATE auth_element set is_system = 0 and is_hide = 0 where controller = 'stations_program' and action ='index';
UPDATE auth_element set is_system = 0 and is_hide = 1 where controller = 'stations_program' and action ='create';
UPDATE auth_element set is_system = 0 and is_hide = 1 where controller = 'stations_program' and action ='delete';
UPDATE auth_element set is_system = 0 and is_hide = 1 where controller = 'stations_program' and action ='modify';

