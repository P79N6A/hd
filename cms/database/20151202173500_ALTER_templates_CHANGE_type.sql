ALTER TABLE `templates` CHANGE `type` `type` ENUM('index','category','region','region_category','detail','album','page','error','layout','static','custom') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'custom' COMMENT '类型';