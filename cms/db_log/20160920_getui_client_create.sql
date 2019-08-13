
CREATE TABLE `getui_client` (
  `id` int(11) NOT NULL,
  `sdk_version` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '设备版本',
  `device_token` varchar(64) CHARACTER SET utf8 NOT NULL COMMENT '苹果推送',
  `push_client` varchar(32) NOT NULL COMMENT '个推推送',
  `updated_at` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='个推导入的推送cid列表';


ALTER TABLE `getui_client`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sdk_version` (`sdk_version`);

ALTER TABLE `getui_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;