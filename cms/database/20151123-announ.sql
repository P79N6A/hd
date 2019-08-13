
CREATE TABLE IF NOT EXISTS `announ` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '公告内容',
  `time` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '发布时间',
  `return` longtext CHARACTER SET utf8 COLLATE utf8_bin COMMENT '留言消息',
  `user` int(9) NOT NULL COMMENT '发布者ID',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '公告标题',
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '发布名字',
  `rednum` int(9) NOT NULL COMMENT '已读人数',
  `pic` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

