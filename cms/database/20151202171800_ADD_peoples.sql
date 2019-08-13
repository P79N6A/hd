CREATE TABLE `peoples` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '专题',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `people_template_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '人物模板ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `birthday` date NOT NULL COMMENT '生日',
  `shengxiao` enum('鼠','牛','虎','兔','龙','蛇','马','羊','猴','鸡','狗','猪') DEFAULT NULL,
  `zodiac` enum('Aries','Taurus','Gemini','Leo','Virgo','Libra','Scorpio','Sagittarius','Capricorn','Aquarius','Pisces') DEFAULT NULL,
  `intro` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `avatar` varchar(255) DEFAULT '' COMMENT '源缩略图',
  `photo` varchar(255) DEFAULT NULL COMMENT '全身照',
  `author_id` int(11) unsigned NOT NULL COMMENT '作者ID',
  `author_name` varchar(30) NOT NULL DEFAULT '' COMMENT '作者姓名',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`),
  KEY `author_id` (`author_id`),
  KEY `channel_id_2` (`channel_id`,`people_template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='人物表';

CREATE TABLE `people_templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '人物模板ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '人物模板名称',
  `fields` text NOT NULL COMMENT '人物模板自定义字段名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='人物模板表';

CREATE TABLE `people_extras` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '人物扩展表',
  `people_id` int(11) unsigned NOT NULL COMMENT '人物ID',
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '扩展键名',
  `value` text NOT NULL COMMENT '扩展键值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `people_id` (`people_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='人物扩展表';