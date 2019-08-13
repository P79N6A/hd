DROP TABLE IF EXISTS `table_id_mappings`;
CREATE TABLE `table_id_mappings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `old_id` int(11) unsigned NOT NULL,
  `new_id` int(11) unsigned NOT NULL,
  `type` enum('news','album','video', 'user', 'category') NOT NULL DEFAULT 'news' COMMENT '类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

ALTER TABLE users ADD COLUMN realname VARCHAR(100) DEFAULT NULL;
ALTER TABLE users ADD COLUMN email VARCHAR(100) DEFAULT NULL;
ALTER TABLE users ADD COLUMN qq VARCHAR(15) DEFAULT NULL;
ALTER TABLE users ADD COLUMN head_image VARCHAR(255) DEFAULT NULL;
ALTER TABLE users ADD COLUMN gender TINYINT(1) DEFAULT 1;

alter table `user_bind` add column `type` enum('weibo', 'qq') NOT NULL DEFAULT 'qq';

