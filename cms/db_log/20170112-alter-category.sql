ALTER TABLE `category`
ADD COLUMN `publish_status`  tinyint(1) UNSIGNED NULL COMMENT '发布默认状态（0，未发布，1，已发布）' AFTER `sort`,
ADD COLUMN `comment_status`  tinyint(1) UNSIGNED NULL COMMENT '评论模块开关（0，关闭，1，打开）' AFTER `publish_status`,
ADD COLUMN `secret_status`  tinyint(1) UNSIGNED NULL COMMENT '口令默认开关（0，关闭， 1，打开）' AFTER `comment_status`,
ADD COLUMN `wechat_status`  tinyint(1) UNSIGNED NULL COMMENT '微信默认开关（0，关闭，1，打开）' AFTER `secret_status`,
ADD COLUMN `coerce_status`  tinyint(8) UNSIGNED NULL COMMENT '强制开关（' AFTER `wechat_status`;


ALTER TABLE `category`
MODIFY COLUMN `publish_status`  tinyint(1) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT '发布默认状态（0，未发布，1，已发布）' AFTER `sort`,
MODIFY COLUMN `comment_status`  tinyint(1) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT '评论模块开关（0，关闭，1，打开）' AFTER `publish_status`,
MODIFY COLUMN `secret_status`  tinyint(1) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT '口令默认开关（0，关闭， 1，打开）' AFTER `comment_status`,
MODIFY COLUMN `wechat_status`  tinyint(1) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT '微信默认开关（0，关闭，1，打开）' AFTER `secret_status`,
MODIFY COLUMN `coerce_status`  tinyint(8) UNSIGNED ZEROFILL NULL DEFAULT NULL COMMENT '强制开关（' AFTER `wechat_status`;


ALTER TABLE `category`
ADD COLUMN `cover1`  varchar(255) NULL AFTER `cover`,
ADD COLUMN `cover2`  varchar(255) NULL AFTER `cover1`,
ADD COLUMN `cover3`  varchar(255) NULL AFTER `cover2`,
ADD COLUMN `author_id`  int(11) NULL AFTER `cover3`,
ADD COLUMN `author_name`  varchar(32) NULL AFTER `author_id`,
ADD COLUMN `redirect_url`  varchar(255) NULL AFTER `author_name`;


ALTER TABLE `category`
ADD COLUMN `publisher`  varchar(256) NULL COMMENT '允许发布者' AFTER `sort`;

ALTER TABLE `category`
DROP COLUMN `cover1`,
DROP COLUMN `cover2`,
DROP COLUMN `cover3`,
ADD COLUMN `timelimit_begin`  int(11) NULL COMMENT '时效开始时间' AFTER `coerce_status`,
ADD COLUMN `timelimit_end`  int(11) NULL COMMENT '时效结束时间' AFTER `timelimit_begin`;


ALTER TABLE `category`
DROP COLUMN `publisher`;

