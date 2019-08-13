ALTER TABLE `comment`
ADD COLUMN `nickname`  varchar(255) NULL comment '昵称' AFTER `partition_by`,
ADD COLUMN `avatar`  varchar(255) NULL comment '头像' AFTER `nickname`,
ADD COLUMN `browersinfo`  varchar(255) NULL comment '浏览器信息' AFTER `avatar`,
ADD COLUMN `auditerid`  int(10) NULL COMMENT '审核管理员ID' AFTER `browersinfo`,
ADD COLUMN `aduit_at`  int(10) NULL COMMENT '审核时间' AFTER `auditerid`,
ADD COLUMN `audit_memo`  varchar(255) NULL COMMENT '审核备注' AFTER `aduit_at`,
ADD COLUMN `isspeccomment`  tinyint(1) NULL DEFAULT 0 COMMENT '是否为主题评论' AFTER `audit_memo`;