ALTER TABLE `data` CHANGE `type` `type` ENUM('news','album','video','special','people','live','news_collection','album_collection','video_collection') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'news' COMMENT '类型';

ALTER TABLE `peoples` CHANGE `shengxiao` `shengxiao` ENUM('鼠','牛','虎','兔','龙','蛇','马','羊','猴','鸡','狗','猪') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '生肖', CHANGE `zodiac` `zodiac` ENUM('Aries','Taurus','Gemini','Leo','Virgo','Libra','Scorpio','Sagittarius','Capricorn','Aquarius','Pisces') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '星座';
ALTER TABLE `peoples` ADD `bio` TEXT NULL COMMENT '履历' AFTER `zodiac`;