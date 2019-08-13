ALTER TABLE `push_msg`
ADD COLUMN `admin_id`  int NULL DEFAULT 0 COMMENT '操作员,API调用为0' AFTER `remark`