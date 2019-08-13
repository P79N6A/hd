

CREATE TABLE IF NOT EXISTS ` announ_status` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `notice_id` int(8) NOT NULL COMMENT '公告ID',
  `admin_id` int(8) NOT NULL COMMENT '用户ID',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

