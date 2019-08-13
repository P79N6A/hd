CREATE TABLE `category_seo` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `logo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `category_seo`
  ADD KEY `category_id` (`category_id`);