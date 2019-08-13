ALTER TABLE `queues`
ADD COLUMN `single`  int(11) NULL COMMENT '是否个推' AFTER `status`,
ADD COLUMN `push_single_client`  varchar(256) NULL COMMENT '个推id号' AFTER `single`,
ADD COLUMN `push_terminal`  int(11) NULL DEFAULT 0 COMMENT '客户端类型，1：安卓，2：IOS，3：全推' AFTER `push_single_client`,
ADD COLUMN `push_content`  varchar(256) NULL COMMENT '推送内容' AFTER `push_terminal`;



