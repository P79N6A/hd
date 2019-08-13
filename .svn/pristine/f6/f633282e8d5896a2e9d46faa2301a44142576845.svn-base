
CREATE TABLE `data_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '媒资模板名称',
  `media_type` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1：启用，启用后该模板即成为系统的一部分，无法再次修改或删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `data_templates`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `data_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;