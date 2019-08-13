CREATE TABLE `wx_cdkey` (
  `id` int(11) NOT NULL COMMENT 'id',
  `open_id` varchar(100) NOT NULL COMMENT '微信用户id',
  `keyword_code` varchar(10) NOT NULL COMMENT '关键词代码',
  `cdkey` varchar(100) NOT NULL COMMENT '兑换码',
  `msg_id` varchar(20) NOT NULL COMMENT '消息id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信兑换码';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wx_cdkey`
--
ALTER TABLE `wx_cdkey`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `open_id_2` (`open_id`,`keyword_code`),
  ADD KEY `open_id` (`open_id`),
  ADD KEY `cdkey` (`cdkey`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `wx_cdkey`
--
ALTER TABLE `wx_cdkey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

CREATE TABLE `wx_keyword` (
  `id` int(11) NOT NULL COMMENT 'id',
  `wx_keyword` varchar(100) NOT NULL COMMENT '关键词',
  `callback_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '返回响应类型',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `keyword_code` varchar(10) NOT NULL COMMENT '关键词编号',
  `answer_text` text COMMENT '返回消息内容'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信回复关键词';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wx_keyword`
--
ALTER TABLE `wx_keyword`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `wx_keyword`
--
ALTER TABLE `wx_keyword`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';