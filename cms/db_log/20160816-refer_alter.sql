ALTER TABLE `referer`
ADD COLUMN `url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '���õ�ַ' AFTER `status`;
update referer set url = '';
