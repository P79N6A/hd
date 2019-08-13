CREATE TABLE IF NOT EXISTS `cms_dzzz` (
  `id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `hs_area` text NOT NULL,
  `spotpool` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `cms_dzzz`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `cms_dzzz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;