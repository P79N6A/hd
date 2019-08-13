DROP TABLE IF EXISTS `item_log`;
CREATE TABLE IF NOT EXISTS `item_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `create_day` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `channel_id` INT(11) NOT NULL DEFAULT 1,
  `title` VARCHAR(255) DEFAULT NULL COMMENT '新闻或相册标题',
  `type` TINYINT(2) DEFAULT 1 COMMENT '1新闻，2相册，3视频',
  `item_id` INT(11) NOT NULL DEFAULT 0,
  `editor_id` INT(11) NOT NULL DEFAULT 0,
  `total_hits` INT(11) DEFAULT 1 COMMENT '新闻的总点击量',
  `total_valid_hits` INT(11) DEFAULT 1 COMMENT '有效点击量表示来自于不同IP，或者相同IP不同天的点击总数',
  `total_web_hits` INT(11) DEFAULT 1 COMMENT '来自网页的点击',
  `total_app_hits` INT(11) DEFAULT 1 COMMENT '来自app的点击',
  `total_wap_hits` INT(11) DEFAULT 1 COMMENT '来自wap的点击',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
