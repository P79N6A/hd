CREATE TABLE `custom_params` (
  `id` int(11) NOT NULL,
  `param_label` varchar(50) NOT NULL COMMENT '字段名 例：年龄',
  `param_name` varchar(30) NOT NULL COMMENT '字段存储名 例：age ，data表，news表，videos表，album表中存在的字段作为保留字',
  `param_fun_type` varchar(30) NOT NULL COMMENT '自定义的控件类型 text file radio checkbox select 等',
  `param_data` text NOT NULL COMMENT '自定义控件选项标示值',
  `param_default` text NOT NULL COMMENT '字段默认值',
  `param_validate` varchar(255) DEFAULT NULL COMMENT '字段校验 email, text, date, phone, number, id card等',
  `param_validate_msg` varchar(255) DEFAULT NULL COMMENT '字段校验提示',
  `param_remark` text COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `custom_params`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `custom_params`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;