ALTER TABLE `baoliao` CHANGE `client` `client` ENUM('ios','android','web','wap') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '客户端类型';