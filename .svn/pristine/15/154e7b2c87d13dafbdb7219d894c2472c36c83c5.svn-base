ALTER TABLE `activity_signup`
ADD COLUMN `status`  tinyint(1) DEFAULT '0' COMMENT '状态，0-未操作|1-同意|2-拒绝|3-删除|4-ALL',
ADD COLUMN `ext_fields` text COMMENT '扩展字段json',
ADD COLUMN `ext_values` text COMMENT '扩展字段值';

ALTER TABLE `activity` DROP `status`;
ALTER TABLE `activity` DROP `ext_fields`;
ALTER TABLE `activity` DROP `ext_values`;