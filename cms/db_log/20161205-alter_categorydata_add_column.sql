ALTER TABLE `category_data`
ADD COLUMN `publish_at`  int(11) NOT NULL COMMENT '发布时间' AFTER `weight`;