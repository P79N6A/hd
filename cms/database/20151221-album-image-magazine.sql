CREATE TABLE IF NOT EXISTS `magazine` (
  `id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `hs_area` text NOT NULL,
  `spotpool` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `magazine`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `magazine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;