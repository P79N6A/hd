CREATE TABLE IF NOT EXISTS `baoliao_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `baoliao_id` int(10) unsigned NOT NULL,
  `reply` text CHARACTER SET utf8 NOT NULL COMMENT '回复正文',
  `author_id` int(10) unsigned NOT NULL COMMENT '作者id',
  `author_name` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '作者姓名',
  `create_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;