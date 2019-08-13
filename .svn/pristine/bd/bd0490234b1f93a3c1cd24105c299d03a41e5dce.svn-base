CREATE TABLE `features` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道 ID',
  `position` smallint(2) unsigned NOT NULL COMMENT '位置',
  `category_id` int(11) unsigned NOT NULL COMMENT '分类ID, 主页调用为0',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题/名称',
  `author_id` int(11) unsigned NOT NULL COMMENT '作者ID',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` smallint(1) unsigned NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `channel_id` (`channel_id`,`position`,`category_id`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='特色推荐表';

CREATE TABLE `featured_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主ID',
  `feature_id` int(11) unsigned NOT NULL COMMENT '特色推荐ID',
  `data_id` int(11) unsigned NOT NULL COMMENT '媒资ID',
  `feature_title` varchar(255) NOT NULL DEFAULT '' COMMENT '推荐标题',
  `feature_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '推荐缩略图',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `sort` smallint(2) unsigned NOT NULL DEFAULT '1' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `feature_id` (`feature_id`,`data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='特色推荐媒资表';

ALTER TABLE `blocks` ADD `template_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '模板ID' AFTER `channel_id`;
ALTER TABLE `blocks` ADD `category_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分类ID' AFTER `code`;
ALTER TABLE `blocks` ADD `region_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '地区ID' AFTER `category_id`;
ALTER TABLE `blocks` ADD INDEX( `channel_id`, `category_id`);
ALTER TABLE `blocks` ADD INDEX( `channel_id`, `region_id`);
ALTER TABLE `block_values` ADD `upload_value` VARCHAR(500) NOT NULL DEFAULT '' COMMENT '上传数据值' AFTER `value`;