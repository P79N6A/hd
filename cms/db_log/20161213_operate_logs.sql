CREATE TABLE `operate_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '操作人ID',
  `element_id` int(11) NOT NULL COMMENT '操作的原子',
  `item_id` int(11) NOT NULL COMMENT '操作的数据ID',
  `created_at` int(11) NOT NULL COMMENT '操作的时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台操作记录';

ALTER TABLE `operate_logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `operate_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;