CREATE TABLE `board` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '留言板ID' , `time` INT NOT NULL COMMENT '创建时间' , `contents` TEXT NOT NULL COMMENT '内容' , PRIMARY KEY (`id`)) ENGINE = InnoDB COMMENT = '留言板';
CREATE TABLE `board_status` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '状态ID' , `board_id` INT UNSIGNED NOT NULL COMMENT '留言板ID' , `admin_id` INT UNSIGNED NOT NULL COMMENT '用户ID' , `status` TINYINT NOT NULL COMMENT '状态' , PRIMARY KEY (`id`)) ENGINE = InnoDB COMMENT = '留言板状态';