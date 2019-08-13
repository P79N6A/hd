DROP TABLE IF EXISTS `activity_signup`;
DROP TABLE IF EXISTS `activity`;

CREATE TABLE `activity` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
	`thumb` VARCHAR(255) NOT NULL,
	`description` VARCHAR(255) DEFAULT NULL COMMENT '活动标题，如《麦当劳新品试吃》',
	`intro` VARCHAR(500) DEFAULT NULL COMMENT '活动详细介绍',
	`status` TINYINT(1) DEFAULT 1 COMMENT '0 活动取消，1 活动正常',
	`location` VARCHAR(255) DEFAULT NULL COMMENT '活动地点',
	`start_time` TIMESTAMP NOT NULL COMMENT '开始时间',
	`end_time` TIMESTAMP NOT NULL COMMENT '结束时间',
	`count` INT(11) NOT NULL DEFAULT 0 COMMENT '报名人数',
	PRIMARY KEY(`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO activity(thumb, description, intro, location, start_time, end_time, `count`) VALUES('http://img.cztv.com/test.jpg@10p', 'test activity1', 'this is test activity1', 'hangzhou', CURDATE()-6, CURDATE()-3, 100);
INSERT INTO activity(thumb, description, intro, location, start_time, end_time, `count`) VALUES('http://img.cztv.com/test.jpg@10p', 'test activity2', 'this is test activity2', 'hangzhou', CURDATE()-1, CURDATE()+1, 100);
INSERT INTO activity(thumb, description, intro, location, start_time, end_time, `count`) VALUES('http://img.cztv.com/test.jpg@10p', 'test activity3', 'this is test activity4', 'hangzhou', '2015-08-05 18:19:03', '2015-08-07 18:19:03', 100);
INSERT INTO activity(thumb, description, intro, location, start_time, end_time, `count`) VALUES('http://img.cztv.com/test.jpg@10p', 'test activity4', 'this is test activity5', 'hangzhou', '2015-06-05 18:19:03', '2015-06-17 18:19:03', 100);
--
-- TODO add user_id foreign key constraint and set user_id not null
-- ADD UNIQUE `user_activity_id` (`activity_id`, `user_id`)
-- 
CREATE TABLE `activity_signup` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
	`activity_id` INT(11) NOT NULL COMMENT '外键关联到活动表',
	`mobile` VARCHAR(16) NOT NULL,
	`name` VARCHAR(99) NOT NULL,
	`user_id` INT(11) DEFAULT NULL COMMENT '外键关联到用户表',
	PRIMARY KEY(`id`),
	CONSTRAINT `f_activity` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO users(mobile) VALUES('13656666666');
