ALTER TABLE users DROP INDEX mobile;
ALTER TABLE `users` ADD UNIQUE( `channel_id`, `mobile`, `partition_by`);
ALTER TABLE `users` CHANGE `name` `name` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户名', CHANGE `nickname` `nickname` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户昵称', CHANGE `realname` `realname` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '真实姓名';
ALTER TABLE `users` CHANGE `email` `email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '电子邮箱';
ALTER TABLE `users` CHANGE `grade` `grade` SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '会员等级';
ALTER TABLE `users` ADD `created_at` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间' AFTER `gender`, ADD `updated_at` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间' AFTER `created_at`;
ALTER TABLE `users` CHANGE `created_at` `created_at` INT(11) UNSIGNED NOT NULL COMMENT '创建时间', CHANGE `updated_at` `updated_at` INT(11) UNSIGNED NOT NULL COMMENT '修改时间';