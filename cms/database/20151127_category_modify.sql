ALTER TABLE category ADD COLUMN comment_type TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 先发后审， 1 先审后发';
ALTER TABLE category ADD COLUMN allow_comment TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 禁止评论， 1 允许评论';
ALTER TABLE category ADD COLUMN app_style TINYINT(2) NOT NULL DEFAULT 0 COMMENT 'APP 栏目样式';
ALTER TABLE category ADD COLUMN allow_type enum('news','album','video','topic','live','news_collection','album_collection','video_collection') NOT NULL DEFAULT 'news' COMMENT '允许媒资类型：文章，文集，图片，相册，视频，专辑';