CREATE TABLE `spec_comment` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`themname`  varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '主题名称' ,
`publishway`  enum('2','1') NOT NULL DEFAULT '1' COMMENT '发布方式:1-先发后审|2-先审后发' ,
`anonymous`  enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否允许匿名评论' ,
`prevent_action`  enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否开启防刷机制' ,
`interval`  smallint(2) NULL DEFAULT 5 COMMENT '防刷间隔时间' ,
`create_at`  int(10) NOT NULL COMMENT '创建时间' ,
`adminid`  int(10) NOT NULL COMMENT '管理员ID' ,
 PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
ROW_FORMAT=COMPACT;

INSERT INTO `spec_comment` (`themname`, `create_at`, `adminid`) VALUES ('疯狂主播秀', '0', '1')