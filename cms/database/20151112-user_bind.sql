CREATE TABLE IF NOT EXISTS `user_bind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `open_id` VARCHAR(40) NOT NULL COMMENT '用户OpenId',
  `user_id` int(11) NOT NULL COMMENT '用户UserId',
   PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
alter table `user_bind` add column `channel_id` int(11);
alter table `users` change `username` `name` varchar(20);