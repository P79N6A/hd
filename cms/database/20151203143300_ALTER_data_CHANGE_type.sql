ALTER TABLE `data` CHANGE `type` `type` ENUM('news','album','video','special','live','news_collection','album_collection','video_collection') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'news' COMMENT '类型';
