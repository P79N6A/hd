CREATE TABLE `data_template_params` (
  `id` int(11) NOT NULL,
  `data_template_id` int(11) NOT NULL COMMENT '模板id',
  `param_id` int(11) NOT NULL COMMENT '参数id',
  `is_required` tinyint(4) NOT NULL COMMENT '是否必填',
  `param_default` text NOT NULL COMMENT '参数默认值',
  `param_order` int(11) NOT NULL COMMENT '字段排序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `data_template_params`
  ADD PRIMARY KEY (`id`),
  ADD KEY `data_template_id` (`data_template_id`);


ALTER TABLE `data_template_params`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
