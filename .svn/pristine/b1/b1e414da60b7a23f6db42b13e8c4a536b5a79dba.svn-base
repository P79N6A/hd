-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-11-24 11:32:09
-- 服务器版本： 5.6.20
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hd_cztv`
--

-- --------------------------------------------------------

--
-- 表的结构 `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `id` int(11) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `introduction` varchar(500) DEFAULT NULL,
  `site` varchar(255) DEFAULT NULL COMMENT '活动地点',
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '开始时间',
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `singup_count` int(11) NOT NULL DEFAULT '0' COMMENT '报名人数'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `activity`
--

INSERT INTO `activity` (`id`, `pic`, `description`, `introduction`, `site`, `start_time`, `end_time`, `singup_count`) VALUES
(1, '//pic', 'test activity', 'this is test activity', 'hangzhou', '2015-09-23 16:00:00', '2015-09-26 16:00:00', 199);

-- --------------------------------------------------------

--
-- 表的结构 `activity_signup`
--

CREATE TABLE IF NOT EXISTS `activity_signup` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL COMMENT '外键关联到活动表',
  `mobile` varchar(16) NOT NULL,
  `name` varchar(99) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '外键关联到用户表'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(11) NOT NULL COMMENT '手机号',
  `name` varchar(99) NOT NULL COMMENT '用户名',
  `password` varchar(40) NOT NULL COMMENT '密码',
  `salt` varchar(16) NOT NULL COMMENT '密钥',
  `last_time` int(11) unsigned NOT NULL DEFAULT '0',
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(32) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '0:禁用/1:正常/2:未激活'
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8 COMMENT='管理员表';

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `channel_id`, `is_admin`, `mobile`, `name`, `password`, `salt`, `last_time`, `avatar`, `remember_token`, `status`) VALUES
(1, 1, 0, '13857169999', '陈立波', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_8ef787f21b0a922c21a5475f75bda6a0.jpg', NULL, 2),
(2, 1, 0, '18605718766', '钱黎明', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_486e87d3834775d53188b20b5de85f57.jpg', NULL, 2),
(3, 1, 0, '13588717677', '王俊', 'none', 'none', 0, '', NULL, 2),
(4, 1, 0, '13588120066', '林刚', 'none', 'none', 0, '', NULL, 2),
(5, 1, 0, '13805789973', '王霞', 'none', 'none', 0, '', NULL, 2),
(6, 1, 0, '13757187983', '罗列异', 'none', 'none', 0, '', NULL, 2),
(7, 1, 0, '13757193624', '袁爽', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_0336887160a0e0c507b2852f9f384a93.jpg', NULL, 2),
(8, 1, 0, '13738198557', '余云', 'none', 'none', 0, '', NULL, 2),
(9, 1, 0, '15857175764', '汪晨', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_286b7829abd3c2951552c56c9442f9c9.jpg', NULL, 2),
(10, 1, 0, '13606643699', '陈佩君', 'none', 'none', 0, '', NULL, 2),
(11, 1, 0, '13858030052', '吕琳', 'none', 'none', 0, '', NULL, 2),
(12, 1, 0, '13857193995', '王楠青', 'none', 'none', 0, '', NULL, 2),
(13, 1, 0, '13905815277', '周建仕', 'none', 'none', 0, '', NULL, 2),
(14, 1, 0, '13575457226', '董玲巧', 'none', 'none', 0, '', NULL, 2),
(15, 1, 0, '15858259046', '黄茜', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_d6d6ae584465105689ded75ab9163865.jpg', NULL, 2),
(16, 1, 0, '15267178035', '杨飞婷', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_3399c0de60f54d95cd593abc0ae4cbb1.jpg', NULL, 2),
(17, 1, 0, '13588877792', '安雪琪', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_f17e4eb8ad09798d8a9a11abf0240ca5.jpg', NULL, 2),
(18, 1, 0, '15868833202', '戴思逸', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_9c158a8b6b358cc9d5467aece2baf0fb.jpg', NULL, 2),
(19, 1, 0, '15868161259', '程亚争', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_d2edca092da57e65f03d854c6ae43a51.jpg', NULL, 2),
(20, 1, 0, '13777823313', '陈洁', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_b05f822208bbee859020bf9753f86756.jpg', NULL, 2),
(21, 1, 0, '18506859966', '姚映秀', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_397ad40fbf59f0bc650ad68eeb7301fc.jpg', NULL, 2),
(22, 1, 0, '18621699899', '黄捷', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_02b92bf0e50b8c48a0baf8cefcd84433.jpg', NULL, 2),
(23, 1, 0, '15158053616', '王鑫', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_5b7aa950a617d2341986c82ff58780b0.jpg', NULL, 2),
(24, 1, 0, '13675864746', '徐立', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_ab267dedf3c232279734a7473c9ccfab.jpg', NULL, 2),
(25, 1, 0, '15088616160', '李景兰', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_3ee939648e44a6f01a7026c770e945bd.jpg', NULL, 2),
(26, 1, 0, '18968186733', '宋静', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_757114791bf59247b08d1b242da7b96e.jpg', NULL, 2),
(27, 1, 0, '13656665251', '许婷', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_1e1bb9593fd33a426a509f260cacd56c.jpg', NULL, 2),
(28, 1, 0, '13429125519', '周凡琦', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_81b56bb89f89b7a113bbbb9849933007.jpg', NULL, 2),
(29, 1, 0, '13777405017', '吴敏洁', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_955e00c7eae3bdf0754d0a882013b501.jpg', NULL, 2),
(30, 1, 0, '15068891576', '杨立群', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_0dfbb8ee73f68676082691430e34a17e.jpg', NULL, 2),
(31, 1, 0, '15067193210', '陈颖', 'none', 'none', 0, '', NULL, 2),
(32, 1, 0, '18805811645', '谢家军', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_f7ea8a83d0bfbe6f8d608b60ca22be1f.jpg', NULL, 2),
(33, 1, 0, '15868877061', '王海烽', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_3c9bb2f020f766dfaf71d883d5b5881b.jpg', NULL, 2),
(34, 1, 0, '13906513648', '刘栋', 'none', 'none', 0, '', NULL, 0),
(35, 1, 0, '18058711166', '邰小丽', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_21e9c2f30c8e5f4eaf4ee19d690f18ea.jpg', NULL, 2),
(36, 1, 0, '13858110372', '黄吉琦', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_255b878ab6aacdb3839e359f83242965.jpg', NULL, 2),
(37, 1, 0, '13805713311', '祁洋', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_e41e99376905efad8082bebef790a2fe.jpg', NULL, 2),
(38, 1, 0, '18667150906', '王从周', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_9990bda9e3cac9e7dd4bd77e10c60a2e.jpg', NULL, 2),
(39, 1, 0, '13606500884', '孙莉 ', 'none', 'none', 0, '', NULL, 2),
(40, 1, 0, '13588125253', '边斯洁', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_f4304c773c1d27b3430d2e53dd502dbb.jpg', NULL, 2),
(41, 1, 0, '13255716851', '胡辛宜', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_fa4a261d42289456c4d67f74f22af1be.jpg', NULL, 2),
(42, 1, 0, '15158023599', '金晨', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_2ce73a2893dc99ea8bc0b199f88293ea.jpg', NULL, 2),
(43, 1, 0, '13205817399', '陈艳', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_81dac804b74f9281d4a991621bfd8e72.jpg', NULL, 2),
(44, 1, 0, '15088644268', '何谐', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_5d627cbf5a660b543d6f90fbdb88724c.jpg', NULL, 2),
(45, 1, 0, '15868475737', '潘笑坤', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_9e267309a89962bd2e775559a438aed0.jpg', NULL, 2),
(46, 1, 0, '15088652258', '周颖卉', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_1372e03b285a3112a8297e20ccde53fe.jpg', NULL, 2),
(47, 1, 0, '15988899668', '郑雯莉', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_032414686c465f3685a849267655cd24.jpg', NULL, 2),
(48, 1, 0, '18958026900', '王健飞', 'none', 'none', 0, '', NULL, 2),
(49, 1, 0, '15968801886', '蔡景伟', 'none', 'none', 0, '', NULL, 2),
(50, 1, 0, '13506782346', '金桑羽', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_39140690b0b314a13e92949becc7dace.jpg', NULL, 2),
(51, 1, 0, '13685768364', '袁巧铃', 'none', 'none', 0, '', NULL, 2),
(52, 1, 0, '13634106866', '林巧', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_c37bcb015960540fa61f6284d73f14df.jpg', NULL, 2),
(53, 1, 0, '15858277616', '王东宇', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_f6b28bce136c100d2fb071e52223cbc4.jpg', NULL, 2),
(54, 1, 0, '15906625406', '冯超琪', 'none', 'none', 0, '', NULL, 2),
(55, 1, 0, '13486181469', '梅玫梦', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_11362dfff37953c829adbcd57e08af93.jpg', NULL, 2),
(56, 1, 0, '18758277610', '洪郑超', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_9882def4b51c1ae4e63518d471a3008f.jpg', NULL, 2),
(57, 1, 0, '15990033308', '倪威', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_aa914c76d065a5720ce96c6ad67922bb.jpg', NULL, 2),
(58, 1, 0, '13705718387', '章晓雯', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_df6e71b0f674b6a90a4bfbe19d95ee8a.jpg', NULL, 2),
(59, 1, 0, '13588064280', '鲍雯君', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_9bcac4ba1c75a03a51125255da3d32ce.jpg', NULL, 2),
(60, 1, 0, '13867488768', '陈超', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_0efd544ad3efb7008a64018e9a270aaf.jpg', NULL, 2),
(61, 1, 0, '18667177722', '李斯文', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_540238ece921b1e3ccd80e29198f6500.jpg', NULL, 2),
(62, 1, 0, '15858296564', '金维龙', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_81d0d7f9559a303eedfd8a1518f9f6c7.jpg', NULL, 2),
(63, 1, 0, '13958059606', '裘诤', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_af5d290b6c1e443be3ff965e9e0570d6.jpg', NULL, 2),
(64, 1, 0, '13805791110', '娄志刚', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_e17f7c45c23bfee5b319675dc804e733.jpg', NULL, 2),
(65, 1, 0, '13516879226', '唐华荣', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_c99750013e00d3a3c7e0dfb2eaf3b76e.jpg', NULL, 2),
(66, 1, 0, '13777861745', '刘阳', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_cbbe0951a2c3ba1c77d16472b611ce98.jpg', NULL, 2),
(67, 1, 0, '13738088164', '刘迎曦', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_fc63f9877c36b01e44836ddbd2dc651e.jpg', NULL, 2),
(68, 1, 0, '13819194562', '洪翼', 'none', 'none', 0, '', NULL, 2),
(69, 1, 0, '18058815378', '陈建华', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_0cfaad2dffa7bbb4710b21e6bd66288f.jpg', NULL, 2),
(70, 1, 0, '18968181713', '章玲', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_bd54d841ec03be725ceac8f4d134e6dc.jpg', NULL, 2),
(71, 1, 0, '13868153639', '王勇', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_a75a4fcf9b567f8733bcadbe1295cf54.jpg', NULL, 2),
(72, 1, 0, '18658136877', '陈煜', 'none', 'none', 0, '', NULL, 2),
(73, 1, 0, '13968133341', '潘哲', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_2ab89f266c988996cec3612582e4b3ad.jpg', NULL, 2),
(74, 1, 0, '13957136320', '韩丽', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_f8914c0620e15dfe4bc25e41bed0a610.jpg', NULL, 2),
(75, 1, 0, '15505710177', '邱琳琳', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_6d3b4e1cf53a2bf5fb67f8080beeeb50.jpg', NULL, 2),
(76, 1, 0, '13357160172', '苏丹', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_153ff19c8e595e0733ae3f03c30a2681.jpg', NULL, 2),
(77, 1, 0, '13588006649', '范涛', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_cadab02fe81f8dae048fefcd1ad5f26c.jpg', NULL, 2),
(78, 1, 0, '13666691784', '张佳兵', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_5011a2d77ba635093bec046ca54ba151.jpg', NULL, 2),
(79, 1, 0, '15088649587', '程利斌', 'none', 'none', 0, '', NULL, 2),
(80, 1, 0, '13656696510', '陈邦鸿', 'none', 'none', 0, '', NULL, 2),
(81, 0, 1, '15968885105', '薛炜', '8b50fc2b61e637ea88b6915c9eae6c283dfd5662', 'tU7NzWRedjtRar9T', 1448263001, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/21/2efd3a5296c5aa69d288b7bcd84c9b8f.jpg', NULL, 1),
(82, 0, 1, '15968871212', '匡高峰', 'f5e3efa9631e49245c80d4f155dba58ebdbbe8b7', 'wgx0N23X0SpYlqak', 1448335508, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/avatars/2015/10/21/86ae1f8836732f8f96465044624c60d9.png', NULL, 1),
(83, 0, 1, '15958985105', '颜腾威', '4bbff2d455baf478befd3bfc6340047d148512af', 'y5pd7O1gqImqM08u', 1447840848, '', NULL, 1),
(84, 1, 1, '13837837835', '张海盼', '6ac57460f70c4293ce51364bc0c32436ee0f57c0', 'r1SG5ZTUo24e3bJ4', 1448243993, 'http://signuphdcztv.oss-cn-hangzhou.aliyuncs.com/cztv/1/avatar_file_29bf3eb5f9c5663ea1a424aa7df13018.png', NULL, 1),
(85, 0, 1, '18968048381', '於涛', 'f67edbc04ca803c207a6e4f7d5f469a2a939d482', 'wgx0N23X0SpYlqak', 1448330433, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/avatars/2015/11/23/bfd1649e7f5de5523894f039aac19c83.gif', NULL, 1),
(86, 1, 0, '13588898445', '王迪先', 'none', 'none', 0, 'http://signuphdcztv.oss-cn-hangzhou.aliyuncs.com/cztv/1/avatar_file_e13ec693b92273f9a1f0e5ba3912eaba.jpg', NULL, 2),
(87, 0, 1, '15990028508', '张亦弛', '1124dc9b9ac8ff813ffb5796c9acbb2e0eef2194', 'r1SG5ZTUo24e3bJ4', 1448332147, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/avatars/2015/10/23/ba3afb7bc9b187ddf138c99eb1792dc1.jpg', NULL, 1),
(88, 1, 0, '13735818154', '汪晶菁', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_21a84df7c31aa9ddc981792a96d6b2e7.jpg', NULL, 2),
(89, 1, 0, '13675828196', '吴晓敏', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_50ba01baa24df1feff0505fd85404441.jpg', NULL, 2),
(90, 0, 0, '15658897903', '吴洪鹏', '91e2bb03861b88935853e7ebd7ec9f5926de42f8', 'adoykZKMvATGW5gC', 1448265068, 'http://img01.cztv.com/channel/cztv/1/avatar_file_0185b051fbd875dc2fa154c899aeeb87.jpg', NULL, 1),
(91, 1, 0, '13625713219', '黎一鸣', 'none', 'none', 0, '', NULL, 0),
(92, 1, 0, '18657111077', '胡茜棋', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_64ef666644f42e9bd959e23c9a542d36.jpg', NULL, 2),
(93, 1, 0, '15088648913', '方丽玲', 'none', 'none', 0, '', NULL, 2),
(94, 1, 0, '13675850464', '王梦茜', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_60a5b7587475b630ec193232ab26f06a.jpg', NULL, 2),
(95, 1, 0, '18957180306', '何欣', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_27deea6b41265eae8ba96618bd9690ee.jpg', NULL, 2),
(96, 1, 0, '13666638861', '俞妮娜', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_d72f7e29155bcb14d2d2abfdc33d0875.jpg', NULL, 2),
(97, 1, 0, '13456917201', '蓝新华', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_634be10d86ebf58211eb23f88703ea18.jpg', NULL, 2),
(98, 1, 0, '13761582974', '赵宏垚', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_8ec6af8444b3ff7df50d9f54aa96d4ff.jpg', NULL, 2),
(99, 1, 0, '18257102610', '季从林', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_0f99d162bdce434e4921c0ca917aad40.jpg', NULL, 2),
(100, 1, 0, '13738041869', '王怡', 'none', 'none', 0, '', NULL, 2),
(101, 1, 0, '15858262261', '王杰', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_c1a40eef009083a24558d30d5111f802.jpg', NULL, 2),
(104, 1, 1, '15850690558', '谢东', 'none', 'none', 0, '', NULL, 1),
(105, 1, 0, '13646826584', '金松', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_84208a2cf0a3246c92624601ccc8a4d3.jpg', NULL, 2),
(106, 1, 1, '13705713288', '孟庆强', 'ab133074bc01d0d3698c9c923e73e11748cb3271', '2U6wbLBI1viVwt5O', 0, '', NULL, 1),
(107, 1, 0, '18167103221', '鲍洲', 'none', 'none', 0, 'http://signuphdcztv.oss-cn-hangzhou.aliyuncs.com/cztv/1/avatar_file_41f5399d22b939d7285d836af94fd4fe.jpg', NULL, 2),
(108, 1, 0, '15990166753', '贾佳', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_ed4c9ec992dfaaee6f966af4e1379717.jpg', NULL, 2),
(109, 1, 0, '18058702686', '徐辉', 'none', 'none', 0, '', NULL, 2),
(110, 1, 0, '13706812591', '史伟强', 'none', 'none', 0, 'http://signuphdcztv.oss-cn-hangzhou.aliyuncs.com/cztv/1/avatar_file_e4351f027f2d2567bf903b4a4a41ec6d.jpg', NULL, 2),
(111, 0, 1, '13989452011', '章海泉', 'bc1326cd3b4b54d8bb2e6ce951e2a776c27285f1', 'r1SG5ZTUo24e3bJ4', 1448240896, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/avatars/2015/11/19/10243333c3d65a417a9d494ce3215a70.jpg', NULL, 1),
(112, 1, 0, '15088649655', '应灵伟', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_b62421060b251171bef5d4bc7f780480.png', NULL, 2),
(113, 1, 0, '13515816332', '郑磊', 'none', 'none', 0, '', NULL, 2),
(114, 1, 0, '13516873140', '张珺珩 ', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_d400decdcaa249b7f5632df9dbc37dc6.jpg', NULL, 2),
(115, 1, 0, '13738141230', '郑广', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_560349636a0cd6607e1112debdff3e92.jpg', NULL, 2),
(116, 1, 0, '15224080432', '汪伟', 'none', 'none', 0, '', NULL, 2),
(117, 1, 0, '18072981240', '余海峰', 'none', 'none', 0, '', NULL, 2),
(118, 1, 0, '18658809171', '陆学捷', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_0c8e0f04645239de456b2b4a84f88fd0.jpg', NULL, 2),
(119, 1, 0, '18768197823', '黄严锋', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_e20d25acb9a6cdf80a47ac5019d65d74.jpg', NULL, 2),
(120, 1, 0, '18606536239', '任益斌', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_429260ba8f6404ffc773a8f0aa58a79b.jpg', NULL, 2),
(121, 1, 0, '15968843393', '崔丽萍', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_76a11f808101702fc582b03d10048e53.jpg', NULL, 2),
(122, 1, 0, '18626861581', '杜占民', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_6b07db3e79a71b290aaa0a42c45da421.jpg', NULL, 2),
(123, 1, 0, '13588024215', '虞甄陶', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_706528628b063034054c5ffb58f6dbb8.jpg', NULL, 2),
(124, 1, 0, '13857183811', '楼加鑫', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_6d762856eed5b618476ecf4de7facea7.jpg', NULL, 2),
(125, 1, 0, '13738071255', '蒋汉儒', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_b247a6168cc5ad7fc86e2c9986269740.jpg', NULL, 2),
(126, 1, 0, '13067728977', '孟晓晨', 'none', 'none', 0, '', NULL, 2),
(127, 1, 0, '15267059082', '凌娜', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_93295d2e5b7c0d02b9fb0cb9ace1936b.jpg', NULL, 2),
(128, 1, 0, '13958179022', '陆勇', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_9683b0648b5a9c7e4f238fa25d398547.jpg', NULL, 2),
(129, 1, 0, '13958186766', '周松华', 'none', 'none', 0, '', NULL, 2),
(130, 1, 0, '13958105905', '蒋向山', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_0ba7dee4c57d128f3e5c7c346592af81.jpg', NULL, 2),
(131, 1, 0, '13666681424', '徐驰', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_eed962226d9ceb22d7c74e67f925ad2b.jpg', NULL, 2),
(132, 1, 0, '13754302233', '黄燕平', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_40c132aee682f852d73b9623dbc2e986.jpg', NULL, 2),
(133, 1, 0, '13989452186', '王伟', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_97430bb88bd91a69bab45e994d87548a.jpg', NULL, 2),
(134, 1, 0, '13758240202', '朱春勤', 'none', 'none', 0, '', NULL, 2),
(135, 1, 0, '13675825710', '董南', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_32580492f0f92bfd42e4e77e720865b6.jpg', NULL, 0),
(136, 1, 0, '18606772812', '周顺飞', 'none', 'none', 0, '', NULL, 2),
(137, 1, 0, '13967180808', '徐小燕', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_9e6ecfb16963580e3ca6261b9edf6154.jpg', NULL, 2),
(138, 1, 0, '13600536526', '杜静', 'none', 'none', 0, 'http://img01.cztv.com/channel/cztv/1/avatar_file_c1b01f41efaf84a602d52787545e17c3.jpg', NULL, 2),
(139, 1, 0, '15801207618', '张盈辉', 'none', 'none', 0, '', NULL, 0),
(140, 1, 0, '18961608298', '周玉钰', 'none', 'none', 0, '', NULL, 2),
(141, 1, 0, '13305717700', '倪奕虹', 'none', 'none', 0, '', NULL, 0),
(142, 1, 0, '18667046615', '邵一然', 'none', 'none', 0, '', NULL, 2),
(143, 1, 0, '18513853057', '郭子龙', 'none', 'none', 0, '', NULL, 0),
(144, 1, 0, '18657112277', '孔祥谱', 'none', 'none', 0, '', NULL, 0),
(145, 1, 0, '13989452020', 'haiquan1201', 'none', 'none', 0, '', NULL, 0),
(146, 1, 0, '13901309628', '宋玉林', 'none', 'none', 0, '', NULL, 0),
(147, 1, 0, '13819106994', '张建明', 'none', 'none', 0, '', NULL, 2),
(148, 1, 0, '18158101866', '郑卫飞', 'none', 'none', 0, '', NULL, 0),
(149, 1, 0, '13805782652', '徐凯', 'none', 'none', 0, '', NULL, 0),
(150, 1, 0, '13806760006', '叶炜', 'none', 'none', 0, '', NULL, 0),
(151, 1, 0, '15988245663', '罗黎明', 'none', 'none', 0, '', NULL, 0),
(152, 0, 1, '18958055189', '孟庆强', '1d7b4f579178b200f08aee2aa1fa1b88b45f5cce', 'avRlQ8PK0UKi2Szf', 0, '', NULL, 1),
(158, 0, 0, '13837837836', '张三2', '56a7bc6ebe178189c338ad869abf31bdd0e6cfe1', 'Xbgz7878wDWWIAS6', 0, '', NULL, 0),
(159, 0, 0, '15236941362', '小明1', '4b2f6831b1f5d74cb29bb8599df7497eed38a2a8', 'eBPkU0yzxTAokze3', 0, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/admin/avatar/f7acc13cc1a59698635ec6a62e337ce.png', NULL, 0),
(160, 0, 0, '13111111111', '  阿萨德', 'e9edc3f83819be2c50cc938cf6bad60aa93fcf19', 'uiD5BI1VnnbU8HWC', 0, '', NULL, 0),
(161, 0, 0, '13211111111', ' 阿萨德', '71754e107cafa7bce5993c36b7f7ebb30bb326c6', '8E2Y3g7KODNoq3BD', 0, '', NULL, 0),
(162, 0, 0, '13122211134', '  水电费', '6951acda43f2af2fffb774e7166a94b5ea0d99bf', 'ipH47ErkCm62iqyv', 0, '', NULL, 0),
(164, 0, 0, '13235252468', '  欣赏欣赏', 'b5bd3ef964041eac24a22033fe4ff0caa816d844', '1', 0, '1', NULL, 0),
(165, 0, 0, '13911111111', '撒大声地', 'b5bd3ef964041eac24a22033fe4ff0caa816d844', '1', 0, '1', NULL, 0),
(166, 30, 1, '13837837830', 'sss', '038a55809660132789f5237b77c044b7ba708bfd', 'a7PEGzJGZNDELKjV', 0, '', NULL, 1),
(167, 30, 0, '13837837833', '测试', 'db47995cd01598b57a115fd87bc92299db23c103', '1', 0, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/admin/avatar/f7acc13cc1a59698635ec6a62e337ce.png', NULL, 1),
(168, 0, 0, '13989457890', '7890', 'd2dfea3e2a14751a8329ef5bd550969db61efdea', 'SWn3EsuyVhmWzh1E', 0, '', NULL, 0),
(169, 1, 1, '13989452055', '章数问', 'c050ad9d22fdb30b5cb23c229449e9591de10c8f', 'wiFxyqMllFAraxvn', 0, '', NULL, 1),
(170, 1, 0, '15968885105', '薛炜', '8b50fc2b61e637ea88b6915c9eae6c283dfd5662', 'tU7NzWRedjtRar9T', 0, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/21/2efd3a5296c5aa69d288b7bcd84c9b8f.jpg', NULL, 1),
(172, 33, 1, '15990028508', '张亦弛', '5c8b60f9cc6b386ac97f075968d98b8e1f923af5', 'r1SG5ZTUo24e3bJ4', 1447817770, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/avatars/2015/10/22/276cbc771c0fabb98a53c3bfb93e65e2.jpg', NULL, 1),
(174, 1, 0, '13112345678', '看到就分开了', '26817a86075e5ed1c01604891dde6153e01a7d97', 'MyuP4liLlbis9kxe', 0, NULL, NULL, 1),
(175, 1, 0, '13111111111', '导入测试1', '5bd31e86f39aadce61595cc268d13937e0f7efda', 'E4f9PM6ROCSziteT', 0, NULL, NULL, 1),
(176, 1, 0, '13122222222', '导入测试2', '0fc24eafa8bdeec18c37b60c651e601b96b396c9', 'md2ea3jgX2MLfGF6', 0, NULL, NULL, 1),
(177, 1, 0, '13133333333', '看到就分开了', '634546b6b2aa7bce30915b1d0629e5eb2cea7353', 'u9pOVZO8FyqABgKF', 0, NULL, NULL, 1),
(179, 1, 1, '18757116296', '向叶', 'a46ac55c1057e3ce4935663b8a6584ed25a51811', 'x2Gzjum0Y4Gkxuha', 0, NULL, NULL, 1),
(188, 0, 0, '13735585531', 'ytw', 'cc1da958ffd35690543475ff4af3d2b3866cb5e5', 'Zf8wryxYxBF2D2sr', 1446720807, NULL, NULL, 1),
(191, 0, 1, '15869006590', '谢东', '8f60ac05b0414ee836468ffa79372b0211761c82', 'yyLMGwoLYEEedoM8', 0, NULL, NULL, 1),
(192, 0, 1, '13837837835', '张海盼', '6ac57460f70c4293ce51364bc0c32436ee0f57c0', 'r1SG5ZTUo24e3bJ4', 1448333523, 'http://signuphdcztv.oss-cn-hangzhou.aliyuncs.com/cztv/1/avatar_file_29bf3eb5f9c5663ea1a424aa7df13018.png', NULL, 1),
(193, 0, 0, '13545678901', '哈哈哈', '92a853aa3779daa855aafc026552d7acfa8bd2d9', 'fm9YClRLHMhDOI52', 0, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/11/23/7554ba71630f3d3a47f6c4f50632d1b0.jpg', NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `admin_ext`
--

CREATE TABLE IF NOT EXISTS `admin_ext` (
  `admin_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `pinyin` varchar(32) DEFAULT NULL,
  `department` int(11) DEFAULT NULL COMMENT '部门',
  `duty` int(11) DEFAULT NULL COMMENT '岗位',
  `sort` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

--
-- 转存表中的数据 `admin_ext`
--

INSERT INTO `admin_ext` (`admin_id`, `pinyin`, `department`, `duty`, `sort`) VALUES
(1, 'clb', 1, 1, 0),
(2, 'qlm', 1, 1, 0),
(3, 'wj', 1, 1, 0),
(4, 'lg', 1, 1, 0),
(5, 'wx', 1, 1, 0),
(6, 'lly', 1, 1, 0),
(7, 'ys', 1, 1, 0),
(8, 'yy', 1, 1, 0),
(9, 'wc', 1, 1, 0),
(10, 'cpj', 1, 1, 0),
(11, 'll', 1, 1, 0),
(12, 'wnq', 1, 1, 0),
(13, 'zjs', 1, 1, 0),
(14, 'dlq', 1, 1, 0),
(15, 'hx', 1, 1, 0),
(16, 'yft', 1, 1, 0),
(17, 'axq', 1, 1, 0),
(18, 'dsy', 1, 1, 0),
(19, 'cyz', 1, 1, 0),
(20, 'cj', 1, 1, 0),
(21, 'yyx', 1, 1, 0),
(22, 'hj', 1, 1, 0),
(23, 'wx', 1, 1, 0),
(24, 'xl', 1, 1, 0),
(25, 'ljl', 1, 1, 0),
(26, 'sj', 1, 1, 0),
(27, 'xt', 1, 1, 0),
(28, 'zfq', 1, 1, 0),
(29, 'wmj', 1, 1, 0),
(30, 'ylq', 1, 1, 0),
(31, 'cy', 1, 1, 0),
(32, 'xjj', 1, 1, 0),
(33, 'whf', 1, 1, 0),
(34, 'ld', 1, 1, 0),
(35, 'txl', 1, 1, 0),
(36, 'hjq', 1, 1, 0),
(37, 'qy', 1, 1, 0),
(38, 'wcz', 1, 1, 0),
(39, 'sl ', 1, 1, 0),
(40, 'bsj', 1, 1, 0),
(41, 'hxy', 1, 1, 0),
(42, 'jc', 1, 1, 0),
(43, 'cy', 1, 1, 0),
(44, 'hx', 1, 1, 0),
(45, 'pxk', 1, 1, 0),
(46, 'zyh', 1, 1, 0),
(47, 'zwl', 1, 1, 0),
(48, 'wjf', 1, 1, 0),
(49, 'cjw', 1, 1, 0),
(50, 'jsy', 1, 1, 0),
(51, 'yql', 1, 1, 0),
(52, 'lq', 1, 1, 0),
(53, 'wdy', 1, 1, 0),
(54, 'fcq', 1, 1, 0),
(55, 'mmm', 1, 1, 0),
(56, 'hzc', 1, 1, 0),
(57, 'nw', 1, 1, 0),
(58, 'zxw', 1, 1, 0),
(59, 'bwj', 1, 1, 0),
(60, 'cc', 1, 1, 0),
(61, 'lsw', 1, 1, 0),
(62, 'jwl', 1, 1, 0),
(63, 'qz', 1, 1, 0),
(64, 'lzg', 1, 1, 0),
(65, 'thr', 1, 1, 0),
(66, 'ly', 1, 1, 0),
(67, 'lyx', 1, 1, 0),
(68, 'hy', 1, 1, 0),
(69, 'cjh', 1, 1, 0),
(70, 'zl', 1, 1, 0),
(71, 'wy', 1, 1, 0),
(72, 'cy', 1, 1, 0),
(73, 'pz', 1, 1, 0),
(74, 'hl', 1, 1, 0),
(75, 'qll', 1, 1, 0),
(76, 'sd', 1, 1, 0),
(77, 'ft', 1, 1, 0),
(78, 'zjb', 1, 1, 0),
(79, 'clb', 1, 1, 0),
(80, 'cbh', 1, 1, 0),
(81, 'xw', 1, 1, 0),
(82, 'kgf', 1, 1, 0),
(83, 'ytw', 1, 1, 0),
(84, 'zhp', 12, 0, 0),
(85, 'yt', 1, 0, 0),
(86, 'wdx', 1, 1, 0),
(87, 'zyc', 1, 0, 0),
(88, 'wjj', 1, 1, 0),
(89, 'wxm', 1, 1, 0),
(90, 'whp', 1, 0, 0),
(91, 'lym', 1, 1, 0),
(92, 'hxq', 1, 1, 0),
(93, 'fll', 1, 1, 0),
(94, 'wmx', 1, 1, 0),
(95, 'hx', 1, 1, 0),
(96, 'ynn', 1, 1, 0),
(97, 'lxh', 1, 1, 0),
(98, 'zhy', 1, 1, 0),
(99, 'jcl', 1, 1, 0),
(100, 'wy', 1, 1, 0),
(101, 'wj', 1, 1, 0),
(104, 'xd', 1, 1, 0),
(105, 'js', 1, 1, 0),
(106, 'mqq', 1, 1, 0),
(107, 'bz', 1, 1, 0),
(108, 'jj', 1, 1, 0),
(109, 'xh', 1, 1, 0),
(110, 'swq', 1, 1, 0),
(111, 'zhq', 1, 1, 0),
(112, 'ylw', 1, 1, 0),
(113, 'zl', 1, 1, 0),
(114, 'zjh', 1, 1, 0),
(115, 'zg', 1, 1, 0),
(116, 'ww', 1, 1, 0),
(117, 'yhf', 1, 1, 0),
(118, 'lxj', 1, 1, 0),
(119, 'hyf', 1, 1, 0),
(120, 'ryb', 1, 1, 0),
(121, 'clp', 1, 1, 0),
(122, 'dzm', 1, 1, 0),
(123, 'yzt', 1, 1, 0),
(124, 'ljx', 1, 1, 0),
(125, 'jhr', 1, 1, 0),
(126, 'mxc', 1, 1, 0),
(127, 'ln', 1, 1, 0),
(128, 'ly', 1, 1, 0),
(129, 'zsh', 1, 1, 0),
(130, 'jxs', 1, 1, 0),
(131, 'xc', 1, 1, 0),
(132, 'hyp', 1, 1, 0),
(133, 'ww', 1, 1, 0),
(134, 'zcq', 1, 1, 0),
(135, 'dn', 1, 1, 0),
(136, 'zsf', 1, 1, 0),
(137, 'xxy', 1, 1, 0),
(138, 'dj', 1, 1, 0),
(139, 'zyh', 1, 1, 0),
(140, 'zyy', 1, 1, 0),
(141, 'nyh', 1, 1, 0),
(142, 'syr', 1, 1, 0),
(143, 'gzl', 1, 1, 0),
(144, 'kxp', 1, 1, 0),
(145, 'haiquan12*1', 1, 1, 0),
(146, 'syl', 1, 1, 0),
(147, 'zjm', 1, 1, 0),
(148, 'zwf', 1, 1, 0),
(149, 'xk', 1, 1, 0),
(150, 'y*', 1, 1, 0),
(151, 'llm', 1, 1, 0),
(152, 'mqq', 1, 1, 0),
(158, 'zs2', 1, 1, 0),
(159, 'xm1', 1, 1, 0),
(160, '  asd', 1, 1, 0),
(161, ' asd', 1, 1, 0),
(162, '  sdf', 1, 1, 0),
(163, 'king', NULL, NULL, 0),
(164, '  xsxs', 1, 1, 0),
(165, 'sdsd', 12, 14, 0),
(166, 'sss', 0, 0, 0),
(167, 'cs', 1, 1, 0),
(168, '789*', 0, 0, 0),
(169, 'zsw', 0, 0, 0),
(170, 'xw', 12, 6, 0),
(171, 'kgf', 12, 0, 0),
(172, 'zyc', 12, 0, 0),
(174, 'kdjfkl', 0, 0, 0),
(175, 'drcs1', 1, 1, 0),
(176, 'drcs2', 1, 1, 0),
(177, 'kdjfkl', 1, 1, 0),
(179, 'xy', 0, 0, 0),
(188, 'ytw', 22, 18, 0),
(191, 'xd', 0, 0, 0),
(193, 'hhh', 0, 4, 0);

-- --------------------------------------------------------

--
-- 表的结构 `admin_relation`
--

CREATE TABLE IF NOT EXISTS `admin_relation` (
  `admin_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `relation_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:预留 1：任务 2：站内信',
  `freq` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='常用联系人表';

-- --------------------------------------------------------

--
-- 表的结构 `advert`
--

CREATE TABLE IF NOT EXISTS `advert` (
  `id` smallint(5) unsigned NOT NULL,
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道id',
  `name` varchar(40) NOT NULL COMMENT '标题',
  `spaceid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '版位id',
  `type` varchar(10) NOT NULL COMMENT '类型',
  `setting` text NOT NULL,
  `startdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上线时间',
  `enddate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下线时间',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `clicks` smallint(5) unsigned NOT NULL DEFAULT '0',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL COMMENT '0禁用1启用'
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `advert`
--

INSERT INTO `advert` (`id`, `channel_id`, `name`, `spaceid`, `type`, `setting`, `startdate`, `enddate`, `addtime`, `clicks`, `listorder`, `status`) VALUES
(22, 0, '新篮TVshi', 19, 'images', '[{"linkurl":"http:\\/\\/www.baidu.com","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/advert\\/2015\\/11\\/12\\/e7f5f8d3c12ee7be964f5497b9de34a4.jpg","alt":""},{"linkurl":"http:\\/\\/www.taobao.com","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/advert\\/2015\\/11\\/12\\/640ba900c241a701025f89cd425b04d7.jpg","alt":""}]', 1447317420, 1449909420, 1447317446, 1, 0, 0),
(23, 0, '固定', 16, 'images', '[{"linkurl":"http:\\/\\/www.baidu.com","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/advert\\/2015\\/11\\/12\\/e2672c46b458de4d6eaee9d21e34bc05.jpg","alt":""}]', 1447317420, 1449909420, 1447317499, 1, 0, 1),
(24, 0, '漂浮', 17, 'images', '[{"linkurl":"","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/advert\\/2015\\/11\\/12\\/b9bd2347cc8a39dd73f7a775eb28d1e1.jpg","alt":""}]', 1447317480, 1449909480, 1447317532, 1, 0, 1),
(25, 0, '文字', 20, 'text', '[{"linkurl":"http:\\/\\/www.baidu.com","alt":"\\u6587\\u5b57\\u5e7f\\u544a\\u6587\\u5b57\\u5ba3\\u4f20"}]', 1447317540, 1449909540, 1447317575, 1, 0, 1),
(26, 0, '图片轮播', 21, 'images', '[{"linkurl":"http:\\/\\/www.baidu.com","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/advert\\/2015\\/11\\/12\\/e8a0ed05c70610f6a943c55386be7918.jpg","alt":""},{"linkurl":"http:\\/\\/www.baidu.com","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/advert\\/2015\\/11\\/12\\/9ae61b6d229ce5bb1077f7e8b3f58afa.jpg","alt":""},{"linkurl":"http:\\/\\/www.baidu.com","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/advert\\/2015\\/11\\/12\\/9433275b9673d934c0201a5cf36b3c13.jpg","alt":""}]', 1447317600, 1449909600, 1447317673, 1, 0, 1),
(27, 0, '列表广告', 22, 'images', '[{"linkurl":"","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/advert\\/2015\\/11\\/12\\/146199eb5e4a6782c533387a652aeff2.jpg","alt":""},{"linkurl":"","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/advert\\/2015\\/11\\/12\\/58838f847488d90e18a7a98a6609c1d9.jpg","alt":""},{"linkurl":"","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/advert\\/2015\\/11\\/12\\/b1afe565c77aca0c7248cf9e81d38550.jpg","alt":""}]', 1447317660, 1449909660, 1447317705, 1, 0, 1),
(28, 0, '代码广告', 23, 'code', '[{"code":"hello world"}]', 1447317660, 1449909660, 1447317739, 1, 0, 1),
(29, 0, '漂浮广告', 18, 'images', '[{"linkurl":"","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/advert\\/2015\\/11\\/12\\/dd852133ed1bf44f7f395e53f13f8ab8.jpg","alt":""}]', 1447317840, 1449909840, 1447317875, 1, 0, 1),
(30, 0, '漂浮广告1', 18, 'images', '[{"linkurl":"","imageurl":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/advert\\/2015\\/11\\/12\\/2731c8b1e786155395cae21697bddd54.jpg","alt":""}]', 1447318980, 1449910980, 1447319014, 1, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `advert_space`
--

CREATE TABLE IF NOT EXISTS `advert_space` (
  `id` int(11) unsigned NOT NULL,
  `channel_id` int(11) unsigned NOT NULL DEFAULT '0',
  `name` char(50) NOT NULL COMMENT '版位名称',
  `type` char(30) NOT NULL COMMENT '版位类型',
  `intype` smallint(5) NOT NULL COMMENT '内置广告类型',
  `path` varchar(100) DEFAULT NULL COMMENT 'js路径',
  `width` smallint(4) unsigned NOT NULL DEFAULT '0',
  `height` smallint(4) unsigned NOT NULL DEFAULT '0',
  `setting` char(100) NOT NULL,
  `description` char(100) DEFAULT NULL COMMENT '版位描述',
  `status` tinyint(1) unsigned NOT NULL COMMENT '0禁用1启用'
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `advert_space`
--

INSERT INTO `advert_space` (`id`, `channel_id`, `name`, `type`, `intype`, `path`, `width`, `height`, `setting`, `description`, `status`) VALUES
(16, 0, '顶部搜索右侧广告位', 'banner', 0, 'space_js/16.js', 250, 250, '{"paddleft":"0","paddtop":"0"}', '', 1),
(17, 0, '顶部搜索左侧广告位', 'fixure', 0, 'space_js/17.js', 250, 350, '{"paddleft":"150","paddtop":"200"}', '', 1),
(18, 0, '顶部搜索右侧广告位1', 'float', 0, 'space_js/18.js', 125, 110, '{"paddleft":"450","paddtop":"250"}', '', 1),
(19, 0, '顶部搜索右侧广告位2', 'couplet', 0, 'space_js/19.js', 110, 130, '{"paddleft":"150","paddtop":"120"}', '', 1),
(20, 0, '44444', 'text', 0, 'space_js/20.js', 111, 111, '{"paddleft":"0","paddtop":"0"}', '', 1),
(21, 0, '版位轮换', 'imagechange', 0, 'space_js/21.js', 150, 120, '{"paddleft":"0","paddtop":"0"}', '', 1),
(22, 0, '版位列表', 'imagelist', 0, 'space_js/22.js', 250, 220, '{"paddleft":"0","paddtop":"0"}', '', 1),
(23, 0, '版位代码', 'code', 0, NULL, 0, 0, '{"paddleft":"0","paddtop":"0"}', '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `album`
--

CREATE TABLE IF NOT EXISTS `album` (
  `id` int(11) unsigned NOT NULL COMMENT '相册ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  `thumb` varchar(255) NOT NULL COMMENT '封面',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `author_id` int(11) unsigned NOT NULL COMMENT '作者ID',
  `author_name` varchar(30) NOT NULL COMMENT '作者姓名',
  `no_comment` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '禁止评论',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '修改时间'
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='相册表';

--
-- 转存表中的数据 `album`
--

INSERT INTO `album` (`id`, `channel_id`, `title`, `intro`, `thumb`, `keywords`, `author_id`, `author_name`, `no_comment`, `created_at`, `updated_at`) VALUES
(5, 0, '西湖印象', '相册相册相册相册相册相册相册', 'thumbnails/2015/10/19/ff3dc9cb739fc7c7834df1c7db605623.jpg', NULL, 84, '张海盼', 1, 1443513663, 1447926833),
(8, 0, '乐之章2', '乐之章', 'thumbnails/2015/10/28/680d2aba2689745f79fb7bfa3c0f6fd0.png', NULL, 81, '薛炜', 1, 1446014282, 1446450980),
(9, 0, '乐之华', '乐之章', 'thumbnails/2015/10/28/1f0ef7442f609b846b0e2e2b15130919.png', NULL, 81, '薛炜', 1, 1446014335, 1446014524),
(10, 0, '乐之章', '乐之章', 'thumbnails/2015/10/28/eb1188325f77e3cde1d2b6e2c92bef1c.png', NULL, 81, '薛炜', 1, 1446014361, 1446014361),
(11, 0, '伊丽莎白二世', '伊丽莎白二世', 'thumbnails/2015/10/28/bebe03c92ebc92c5c6dad7c87a03a922.jpg', NULL, 81, '薛炜', 0, 1446014710, 1446014710),
(12, 0, '暖暖游世界摄影大赛', '暖暖游世界摄影大赛', 'thumbnails/2015/10/28/2329d960145ae4bf4edbf84c7a6ed903.png', NULL, 81, '薛炜', 0, 1446017064, 1446017064),
(13, 0, '真心话大冒险 - 炫迈', '真心话大冒险真心话大冒险', 'thumbnails/2015/10/28/e34008569bd77c36329768bb26e50094.png', NULL, 81, '薛炜', 0, 1446017454, 1446017885),
(15, 0, '真心话大冒险', '真心话大冒险真心话大冒险', 'thumbnails/2015/10/28/e5ef966230ad39ad0ae11b7c4ee55509.png', NULL, 81, '薛炜', 0, 1446017809, 1446024846),
(16, 0, '三人行必有我师', '拉拉拉拉拉拉拉拉拉...', 'thumbnails/2015/11/02/c4366bde320b99f8987ee22f48379072.jpg', NULL, 81, '薛炜', 0, 1446453439, 1446453439);

-- --------------------------------------------------------

--
-- 表的结构 `album_image`
--

CREATE TABLE IF NOT EXISTS `album_image` (
  `id` int(11) unsigned NOT NULL COMMENT '图片ID',
  `album_id` int(11) unsigned NOT NULL COMMENT '相册ID',
  `path` varchar(255) NOT NULL COMMENT '路径',
  `intro` varchar(255) DEFAULT NULL COMMENT '简介',
  `author_id` int(11) unsigned NOT NULL COMMENT '作者ID',
  `author_name` varchar(30) NOT NULL COMMENT '作者姓名',
  `uploaded_time` int(11) unsigned NOT NULL COMMENT '上传时间',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='图片表'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;

--
-- 转存表中的数据 `album_image`
--

INSERT INTO `album_image` (`id`, `album_id`, `path`, `intro`, `author_id`, `author_name`, `uploaded_time`, `sort`, `partition_by`) VALUES
(6, 15, 'albums/2015/10/28/9fe60a41aea5a220698a0d8c829326ce.png', '', 81, '薛炜', 1446024810, 0, 2015),
(7, 15, 'albums/2015/10/28/5047c6ae2cae279a1243d9ca31527570.png', '', 81, '薛炜', 1446024814, 0, 2015),
(8, 15, 'albums/2015/10/28/63d1c706d79db372ee3be3854f98f557.png', '', 81, '薛炜', 1446024823, 0, 2015),
(9, 15, 'albums/2015/10/28/654b47c58e9ac183bbaedce414161d0d.jpg', '', 81, '薛炜', 1446024828, 0, 2015),
(10, 5, 'albums/2015/10/29/8afa6abf5272311c1a23616f93d184ff.jpg', '', 82, '匡高峰', 1446086187, 0, 2015),
(11, 5, 'albums/2015/10/29/8577c199cc0855876f1712000a14ed95.jpg', '', 82, '匡高峰', 1446086190, 0, 2015),
(12, 5, 'albums/2015/10/29/7a4986ce40960eeaae63e0c45475e1d5.jpg', '', 82, '匡高峰', 1446086191, 0, 2015),
(13, 5, 'albums/2015/10/29/216826514b0da9d5a55aad907d155300.jpg', '', 82, '匡高峰', 1446086191, 0, 2015),
(14, 9, 'albums/2015/11/06/f38566bb3f353784d831d1040c34e1a8.png', '', 111, '章海泉', 1446804449, 0, 2015),
(15, 9, 'albums/2015/11/06/e01db7aaa861b54aba46afa2b3c46a78.png', '', 111, '章海泉', 1446804456, 0, 2015);

-- --------------------------------------------------------

--
-- 表的结构 `album_tmp`
--

CREATE TABLE IF NOT EXISTS `album_tmp` (
  `id` int(11) unsigned NOT NULL COMMENT '主ID',
  `author_id` int(11) unsigned NOT NULL COMMENT '作者ID',
  `code` varchar(50) NOT NULL COMMENT '识别码',
  `path` varchar(255) NOT NULL COMMENT '路径',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB AUTO_INCREMENT=304 DEFAULT CHARSET=utf8 COMMENT='相册临时表';

--
-- 转存表中的数据 `album_tmp`
--

INSERT INTO `album_tmp` (`id`, `author_id`, `code`, `path`, `created_at`) VALUES
(6, 81, '16c666a3a666d819cad05b93813683d3da18cd71', 'albums/2015/10/28/ebda189a22d60038597ee6adc1f23266.png', 1446014125),
(21, 81, 'a520c0e27b890a991e7225756060d008', 'albums/2015/10/28/8291b56e1e1235627f91dfed7e3437c7.jpg', 1446016913),
(22, 81, 'a520c0e27b890a991e7225756060d008', 'albums/2015/10/28/be06b8ab5a6065f405a5dcef74668b67.png', 1446016919),
(31, 82, 'ce711ccdc9e9418d670e52d64a29df46', 'albums/2015/11/02/dfa2cb19875c5f74927646c2f42f941d.jpg', 1446450750),
(33, 82, '7fa074cd06a15497912784da0d56f3a8', 'albums/2015/11/02/2ac5cf146357ff98a9fa76a5dfc1c144.png', 1446454366),
(34, 82, '7fa074cd06a15497912784da0d56f3a8', 'albums/2015/11/02/e70f31a4debf7f1f3ee0866b95ac64a1.jpg', 1446454367),
(35, 82, '4b97979627519404677e0a013eacb90b', 'albums/2015/11/02/f7c610863f550cf5f3ecf6e3338ee3f6.png', 1446454467),
(37, 82, '4b97979627519404677e0a013eacb90b', 'albums/2015/11/02/e610694fd7be90a5b848335645f4325e.png', 1446454476),
(41, 82, '4b97979627519404677e0a013eacb90b', 'albums/2015/11/02/51ad0f8ebde85487a5bb44b1154175c2.png', 1446454494),
(42, 82, '4b97979627519404677e0a013eacb90b', 'albums/2015/11/02/9fb64b2fd481e48af953d99fe5ac59ac.jpg', 1446454494),
(51, 82, 'f842e337fbb92176fd57380f8a4734c1', 'albums/2015/11/02/0f35f8396d592cba23feef446f9ae847.png', 1446455387),
(52, 82, 'f842e337fbb92176fd57380f8a4734c1', 'albums/2015/11/02/eb752a6ecb7d9afca88fad57fbd3bf4f.png', 1446455396),
(53, 82, 'f842e337fbb92176fd57380f8a4734c1', 'albums/2015/11/02/641a4fc68cede986b9a1d326ade5e0e9.jpg', 1446455408),
(54, 82, 'a07e5f1c6dd9a4e9e70c3187756a726e', 'albums/2015/11/02/ae697404d22826e022063b65bcbcc315.jpg', 1446455432),
(55, 82, 'b66fc82d5edec95415689a4b63a4e829', 'albums/2015/11/02/21d5c636af497adc56873838afe817cc.png', 1446455459),
(56, 82, '2f23865e59fd864cb863984197e6277a', 'albums/2015/11/02/ff43789b4e4bc47685fd16cefa915d10.png', 1446455508),
(58, 82, 'f1d922525fe7fabec510c47ebe9e0188', 'albums/2015/11/02/ccc1caa0a9bceb2acfe84d2a93362fb5.png', 1446455659),
(60, 82, '3a960343ac0ea214fdbe2205fcc15e23', 'albums/2015/11/02/1252227fdb07f3fd926078dc9f20e588.jpg', 1446455821),
(61, 82, 'b966feac4e3fa7bc1d88607226b80343', 'albums/2015/11/02/f3b5c0e1cf63c214a3bd38de1354615d.png', 1446455884),
(62, 82, '812267becbe0e596fb427a12c3f1e03e', 'albums/2015/11/02/97e963dc8f0e65f41146da1d30a48df8.png', 1446455910),
(63, 82, '812267becbe0e596fb427a12c3f1e03e', 'albums/2015/11/02/4f85770c35dbb139354fdf21a322bf79.jpg', 1446455911),
(64, 82, 'edd8bf8442d4c4a84f125c3f058348a6', 'albums/2015/11/02/e851e0fb88c224f8ece7f995afc838e2.png', 1446455941),
(65, 82, 'edd8bf8442d4c4a84f125c3f058348a6', 'albums/2015/11/02/c0f1f3b1ad1c09f31e894a203134ef2e.jpg', 1446455942),
(66, 82, 'da3dba51730c211135959cbd4b194185', 'albums/2015/11/02/fc41bb74cb3d93e4187f176c76eb4492.png', 1446456367),
(67, 82, '0d0fab7b09fcec972cd79767b6a8175f', 'albums/2015/11/02/5cbb9aa49e92ffa314da3514a9772d03.png', 1446456401),
(75, 82, 'acd1bad27c07c81fb23c1bf0314d7b72', 'albums/2015/11/02/4c3108bf88f4faff42a4bf8b8b55a57b.png', 1446457037),
(76, 82, 'acd1bad27c07c81fb23c1bf0314d7b72', 'albums/2015/11/02/82de0d2620de5833e97a5488af479737.jpg', 1446457037),
(79, 82, '1aebe25cd91a88beb602ebed80a497b2', 'albums/2015/11/02/d5024071a8ad3bd967d1e32f6d0d362f.jpg', 1446457284),
(80, 82, '1aebe25cd91a88beb602ebed80a497b2', 'albums/2015/11/02/bdbfcd8fcb081a90e08680ee3dc6f062.png', 1446457289),
(81, 82, '94a9ef95ace4c28e1e4248be6585634e', 'albums/2015/11/03/c7842ac8a9ddf782ef88785ba2eb7b25.png', 1446511836),
(82, 82, 'ad2dc3a1c8ed6e533ded08b57bc2138e', 'albums/2015/11/03/6ebdec366673f99c1b2937a64e967093.png', 1446512104),
(83, 82, '97ab803c66f9eed67f39ff5d00f02ebc', 'albums/2015/11/03/07beac40cefc3c2f675f830a029ba7ff.png', 1446512259),
(86, 82, 'd2d1c5cceffb195706a04cb6e22d10fb', 'albums/2015/11/03/419e5a308f834e9856df5b4692fe059f.jpg', 1446514438),
(88, 82, '82d988609bab4071a46f5744adeb7059', 'albums/2015/11/03/9d446f4e8543b6f416babcc5ed2146c5.jpg', 1446515188),
(89, 82, 'bd629ab87a2b3f0c7aabac678f850461', 'albums/2015/11/03/17a587945e582effdf5962906c58be83.png', 1446515419),
(90, 82, 'bd629ab87a2b3f0c7aabac678f850461', 'albums/2015/11/03/c075a9bbe608b7fa98661a1ddafb0a0b.jpg', 1446515435),
(91, 82, '34da8a2f2e7b1e10037fe07175bbb9c6', 'albums/2015/11/03/d5566073dcf8ba1d50373319d2d75b1b.png', 1446515559),
(92, 82, '34da8a2f2e7b1e10037fe07175bbb9c6', 'albums/2015/11/03/ef4be53ca4c096dfb562042c8453909b.png', 1446515562),
(93, 82, '34da8a2f2e7b1e10037fe07175bbb9c6', 'albums/2015/11/03/4ffe5c4aeec16770c5c39a6c4cdfe86d.png', 1446515562),
(94, 82, '34da8a2f2e7b1e10037fe07175bbb9c6', 'albums/2015/11/03/cf1ba8ecf6eac0ae3b49cd4c74eea892.png', 1446515562),
(95, 82, '7d7099f569ba20a63fdb6b8a1b14c6ef', 'albums/2015/11/03/d12637172f6b21dcc2654d0bf100f8b2.png', 1446515581),
(96, 82, 'bb631a2ee0e990e58f87bbd9627a33b2', 'albums/2015/11/03/fc99dd12661a67e020a7aa46db6d285a.png', 1446515639),
(97, 82, '64563b271c2f5f3cb944d4d40f3ee26e', 'albums/2015/11/03/ce3bff897da6c236f7a28d8c171b9946.png', 1446515702),
(102, 82, 'f180d9bb37d0afc8b0a4f8af9cbc26a4', 'albums/2015/11/03/2a5bf946c03776129f8d325860422b7a.png', 1446515998),
(103, 82, 'd54328f3bc4dcfaf40d69e6c9466e545', 'albums/2015/11/03/fc2870e0754bcf322777d5388920361e.jpg', 1446516656),
(111, 82, 'fdbfcb70ded02184215c24a487af0749', 'albums/2015/11/03/0c8d6460b5ce6fff43d8e75cdef2f81c.png', 1446519285),
(112, 82, 'fdbfcb70ded02184215c24a487af0749', 'albums/2015/11/03/6c78cbe453ee804487f4fa9dadfbe784.jpg', 1446519293),
(113, 82, 'fdbfcb70ded02184215c24a487af0749', 'albums/2015/11/03/fb9bfb65f2d609a875c2a61f632ea2ef.png', 1446519298),
(114, 82, '1e9653b49485d5a23326c00a0f6a092c', 'albums/2015/11/03/ef713bc5b120bb1cb26808c2edca748d.png', 1446519399),
(115, 82, '8b9339488ecd8f31342a895bbe91f3a0', 'albums/2015/11/03/1414c27defd7fc85572c4b31849fe781.png', 1446519428),
(116, 82, '8b9339488ecd8f31342a895bbe91f3a0', 'albums/2015/11/03/b76d10195137ee763fdfa444279ee4d3.jpg', 1446519439),
(117, 82, '2a165ca96bb8e5cb54fc1d70194a2b2a', 'albums/2015/11/03/bf867c3a9b5e71c093f2503214fe946c.png', 1446519467),
(118, 82, '2425cd3826aa8a5bf2bd4b4c95ae3a18', 'albums/2015/11/03/9328fe9d76eddee254671e1c31c524ec.png', 1446519509),
(119, 82, '2425cd3826aa8a5bf2bd4b4c95ae3a18', 'albums/2015/11/03/363f4e1711b14ae46f9df61d4e47d698.jpg', 1446519514),
(120, 82, '2425cd3826aa8a5bf2bd4b4c95ae3a18', 'albums/2015/11/03/f80200a187e35757f12d3ee03f14901c.png', 1446519524),
(121, 82, '2425cd3826aa8a5bf2bd4b4c95ae3a18', 'albums/2015/11/03/1a9124d565041c9ae0fab707bc02f719.jpg', 1446519534),
(123, 82, '4c523c3c1c89aabab39964c01687327f', 'albums/2015/11/03/af3968260e531812c6bef2d3e47f24cc.jpg', 1446519600),
(124, 82, '4c523c3c1c89aabab39964c01687327f', 'albums/2015/11/03/1155b9a13ec49836086a5eb8448ca4fa.png', 1446519612),
(125, 82, '4c523c3c1c89aabab39964c01687327f', 'albums/2015/11/03/2552706eeab9f12fa33deadfb744adf2.jpg', 1446519640),
(132, 82, 'dff643e84fbd16b0d812fde7ae65ad31', 'albums/2015/11/03/3541c0bc599be15402cbc6353f3b05ca.png', 1446531222),
(133, 82, '3d0ffb2e70d4ddb878f3112ea0745151', 'albums/2015/11/03/c09c6f80fd656e0c5d15bb035c6c5322.png', 1446531245),
(158, 82, 'e55da7128f8987e8f41478549f1e34ef', 'albums/2015/11/03/6642dc87a22f022986d9df25d5925747.png', 1446533330),
(159, 82, 'a1f55739f75157b4fe304e5687aca47f', 'albums/2015/11/03/c0accc03903f6fc877ffe4f2baa1f26d.png', 1446533394),
(160, 82, '202d4d57ae0b2716096711758d3073f7', 'albums/2015/11/03/5b91eae6a876447c8a4d7d78217c6d16.png', 1446533411),
(161, 82, 'a5ab761cd7b3e4e9f293dfe8cb15f8c3', 'albums/2015/11/03/9b0b1f806bba6ce29643cee9e822727f.png', 1446533424),
(162, 82, 'a5ab761cd7b3e4e9f293dfe8cb15f8c3', 'albums/2015/11/03/06ff11812dcf838bf407744182a4d986.png', 1446533432),
(165, 82, 'bdc31d4536d82897db0764c779197a74', 'albums/2015/11/03/7d0fabd8aff6b9bb4003e701ff9f62f0.png', 1446533474),
(166, 82, 'bdc31d4536d82897db0764c779197a74', 'albums/2015/11/03/4c190130c045d190424f53045e208fa6.jpg', 1446533479),
(167, 82, '3b37f8e6d9d80c4d612d357de12e724d', 'albums/2015/11/03/27c1d5fb16be5c698a3863571b1dbdb4.png', 1446533568),
(174, 82, '6aa4ad2090a12a2d425225e8644b94f5', 'albums/2015/11/03/79e50fc2d423cd7d7d22b7cd831e03c2.png', 1446533696),
(175, 82, '6aa4ad2090a12a2d425225e8644b94f5', 'albums/2015/11/03/32d8723ba613140f648e4472dd23e0e1.jpg', 1446533704),
(176, 82, '5c55e65397c5f16509ae73258f5609c4', 'albums/2015/11/03/ab07b37653ad22eefa984d32cf5ddbbb.png', 1446533725),
(177, 82, '5c55e65397c5f16509ae73258f5609c4', 'albums/2015/11/03/9f74a015a7079a4f2dc665394aefb322.jpg', 1446533728),
(189, 82, '8db2cb32d7cefde1caf45952b6e0a66d', 'albums/2015/11/03/b7815e1a1c9b40031ad4cd81358fa6aa.png', 1446536548),
(190, 82, '8db2cb32d7cefde1caf45952b6e0a66d', 'albums/2015/11/03/f0da6de54e4b3bdcca71e08d76870544.jpg', 1446536553),
(191, 82, '8db2cb32d7cefde1caf45952b6e0a66d', 'albums/2015/11/03/b9452e9190469ea3d22d09fb49246a48.png', 1446536554),
(195, 82, 'fd6a7bda145876380abcb997b9c7df69', 'albums/2015/11/03/29c9a8ea99dd34e4a7eebfa4a1e0a3c4.png', 1446536982),
(199, 82, '32b57fe447538b92bb19f135b3bbc08b', 'albums/2015/11/03/78840d0abd7452be9e39154a3e9fe3c4.png', 1446537121),
(200, 82, 'eebe8a9670d9611faa7ae4c021448a6c', 'albums/2015/11/03/98d747703df0ac4db7f5142486a38c0b.jpg', 1446537181),
(201, 82, 'e51f1ed0f22b23606a71039dcae5efef', 'albums/2015/11/03/d06eb791c0b06a67bbb1a0ee2329a3a1.png', 1446537328),
(211, 82, '618011233efb0a717f554a1fc86cc328', 'albums/2015/11/03/321a3352873e59afa5b044ed855d6cf2.png', 1446538737),
(221, 82, '4744a432697fc8307f6b27cbf6a441f1', 'albums/2015/11/11/55e3f38f0d6956fe634da7fce321e43e.jpg', 1447226843),
(222, 82, 'c7efc53ab352fdf2cddfc8c134d28cea', 'albums/2015/11/11/d1cb0505b73703901873545eef7837ac.jpg', 1447228002),
(223, 82, '3ad4f86aae4c3a3bb57d382fcc9663bc', 'albums/2015/11/11/0553d7012b488775b17f42b32c580bf6.jpg', 1447228094),
(224, 82, '58270d72dbd61fff141897bc00b38082', 'albums/2015/11/11/3bd55c49151255aac26b8975a02f003f.png', 1447228243),
(226, 82, '89d8d43a46c6c4f10578bff98dcdceef', 'albums/2015/11/11/55291280e4c901615eefdda0ed112ee3.png', 1447230318),
(227, 82, '60295f381e2aa6a16193990b10576bf4', 'albums/2015/11/11/21644b0421635e6d2f4b574d41bab91e.jpg', 1447230335),
(228, 82, 'd5e7b057c2a2af407765b504f2c1af95', 'albums/2015/11/11/92f03826e9f0065f478d50f59aa02e6f.jpg', 1447230421),
(229, 82, 'a27455e2a2b1644c0f8f8ea52c2727ac', 'albums/2015/11/11/8404dfb65d1fe30e291473f3f4c35a86.png', 1447230454),
(230, 82, 'f69d6de603ac849eef50a1b2017bde4a', 'albums/2015/11/11/90f697a2db6b7a3d0f74348d9409b899.png', 1447230479),
(231, 82, 'a16dcbd12a1cd3bdb19fd780e5cc43be', 'albums/2015/11/11/36519be078a44200d3ae3dca98ebd7de.png', 1447230531),
(232, 82, 'a22e3e9bbc780b564c6e43eb8a7e99da', 'albums/2015/11/11/902a20d0466e4b6673e77291ab497a7a.png', 1447230706),
(233, 82, 'bd50fe76b1acda3217b0f4872ab95020', 'albums/2015/11/11/88ee3c2efefacca9584a0f105d91590a.png', 1447230765),
(234, 82, 'ba8dfa42d23e7db39ccc288076be36de', 'albums/2015/11/11/9605ce36b7562f3bb5b765dda9ce9ec9.png', 1447230820),
(235, 82, 'e506192b4b735fa5a1ec07c807c2c230', 'albums/2015/11/11/d885b1ac3db2d34fee00f96e0398e8ab.png', 1447230887),
(236, 82, 'f7a52c8219e003306a4e540bbdefeb5e', 'albums/2015/11/11/1e0a9c67878f597bb88b12e69be929d7.png', 1447230940),
(237, 82, 'f3bbe36094a1037e136d6358d7bde50d', 'albums/2015/11/11/adb906028fba09b19688dcaa232388cb.png', 1447230954),
(238, 82, 'b88cc4ec463b662de59304abbb1d8298', 'albums/2015/11/11/d5fac029a5e1c134e070d7789067c5b7.png', 1447230994),
(239, 82, '2168b239e7cc28d4a27786328185f9f2', 'albums/2015/11/11/a3204ecbeff107bf9a74de317a545b94.png', 1447231089),
(240, 82, '61eef62925853a52952cc38673a39200', 'albums/2015/11/11/e206bf425d2a65aeeb838126760ee4fb.png', 1447231109),
(241, 82, 'a85c48c23a134edf3ec15ff832c54889', 'albums/2015/11/11/7d029145ea35cb3f1b8f16114054fece.png', 1447231161),
(242, 82, 'bed2aa841359cb9ea8f1eb9f606908e8', 'albums/2015/11/11/ca4524c0f9d1d778b7a01893fb5afc58.png', 1447231180),
(243, 82, '7fc9cb3d620c66f370f3b34dc6319b8c', 'albums/2015/11/11/800b411d4a82fb5bf9251d66cef81d76.png', 1447231201),
(244, 82, 'c1b5043b8a63685fd7aa002e1c408155', 'albums/2015/11/11/8ea367444bbfb4ac25ba113590488e33.png', 1447231325),
(247, 82, '4226d08db3737ad1b4afb7700c33a927', 'albums/2015/11/11/59e2e3bbdbb152fe6da54e4a3a0ee282.png', 1447231889),
(248, 82, '4226d08db3737ad1b4afb7700c33a927', 'albums/2015/11/11/6c72e91afe399b3d474bdb087b4b63f0.jpg', 1447231889),
(251, 82, '986413c10c30dba6cd319bcf95224b63', 'albums/2015/11/17/2065f03110fe7f3c9c4b8e41758c4412.png', 1447741900),
(252, 82, 'bd883a456666bececab37f40d8bfb701', 'albums/2015/11/17/1d88d9d72994b8faac626219df41b50e.png', 1447741938),
(255, 82, 'bab10bb7545983d34d60da48d454aac4', 'albums/2015/11/17/ca4029b8f03c0d4d37dadcc8bdbc9714.png', 1447742329),
(256, 82, '3802bddaa83ab5604b187016ccd91553', 'albums/2015/11/17/67e412406d6b87a98ce8d07bf46d0c4c.png', 1447742380),
(257, 82, '0a383d20ecc29e0135d408ac2b9c9f18', 'albums/2015/11/17/6052a8fbf3b27863d12d9cb3470a4de9.png', 1447742633),
(261, 82, '0cda40b4b3fefc6e0fb3345607f4e7f0', 'albums/2015/11/17/c45a03e3e9e24eeb7ca9563cd9ed8a92.png', 1447742827),
(262, 82, '26203fa0218f9462971d59fa22f487d8', 'albums/2015/11/17/a8d396deb95831e747d73f726d8c9a03.png', 1447742948),
(263, 82, '3ad24906063a9505c475e76bee1df4ca', 'albums/2015/11/17/14381c7d286627d71c8002a44179719e.png', 1447742961),
(264, 82, '74430d59d59a73f7cfc688d46ac67bcf', 'albums/2015/11/17/a8bab06669e91f4d2508641287c008c7.png', 1447743021),
(265, 82, 'c7e3f0e782158a89cdedca52ed14ba7c', 'albums/2015/11/17/874677b963d51350b967c9b4a5c3d8bb.png', 1447743045),
(266, 82, 'f8ac9fa0f782bbb07df36e0a6fb8b236', 'albums/2015/11/17/6135c9cc67312be176e1362b3edf76fe.png', 1447743079),
(268, 82, '6545d3d2a7f4a39855e9d956d4cdad95', 'albums/2015/11/17/f004c73b10b12adf6cf73a595cc73c82.png', 1447743181),
(269, 82, '6c16138353271491d5849c99c34c093f', 'albums/2015/11/17/de64afce79511d0fee5011abbd13fcc1.png', 1447743269),
(270, 82, 'aebe0a20131a2388780f9b6e69adb76b', 'albums/2015/11/17/93b39fab269e3bc008223743caa38591.jpg', 1447743282),
(272, 82, '3ff6b95ef181f69eb70cd8356cf1e635', 'albums/2015/11/17/8dda33a31321562217e053066b9cd4f1.png', 1447743319),
(273, 82, '5cb1c0c0a0fb5501ae5c655c8aad3b9f', 'albums/2015/11/17/9111c142c046b123a83062d02b6d213a.png', 1447743334),
(274, 82, '69ebe583aac8c30e16dfc37d0a934a29', 'albums/2015/11/17/e4d0e3fb8e2ac52379a1816ad43e2114.png', 1447743376),
(276, 82, '9bc84d308f20d5dcc7c77832b517b511', 'albums/2015/11/17/58a875ba294724a8cd41ade0998c9920.png', 1447743517),
(277, 82, 'a4be102debb139f7695939aaee6d1945', 'albums/2015/11/17/e38ad35feb4407ac3ad8f9d556605f34.png', 1447743553),
(278, 82, '507209290313ca3f0803e47fb732ab7b', 'albums/2015/11/17/0873866bd859309c7ce22788d3478ee3.png', 1447743568),
(279, 82, '4c52ec44f22c7fc246d41afc2484a6cd', 'albums/2015/11/17/42d308453636fc5868ea6d15729e57f0.png', 1447743591),
(280, 82, '9cb13936e4cf5f76ad55886519c7396d', 'albums/2015/11/17/249097b289096d7205f72bbe88da0235.jpg', 1447743606),
(281, 82, '1b019b1d178a01aae8af7dd509a7d486', 'albums/2015/11/17/4e06e3fab0e4a027990a4e8276fc93a8.png', 1447743737),
(282, 82, '88d2ec92aa37b7e309b11338b3b17c16', 'albums/2015/11/17/724a3b811f2eeb2e402213b888e099bb.png', 1447743752),
(283, 82, '4bce9080e1ac303a8b709fd5df293880', 'albums/2015/11/17/2efb3f74fb24c5a161979b72650f722e.png', 1447743762),
(285, 82, '55c11c41911034c92d460b65882629bc', 'albums/2015/11/17/85c68a45873bb30bb7da084f659c1392.png', 1447743982),
(286, 82, '4512e01691b8b93f022c736fa48fc778', 'albums/2015/11/17/679d475f42a3152fd146ad5e023d48d6.png', 1447744004),
(288, 82, '96f8035fed753c9f1b3f08edce11a106', 'albums/2015/11/17/01f04a75de9a10c9d32a4da3d2dda70c.png', 1447744168),
(290, 82, 'ec9d98a7f86103ab2fd254fd3a0fd5cf', 'albums/2015/11/17/7307af17c6499707e1bb576eebd7e519.png', 1447744238),
(291, 82, '6b0a087c82e74f42017c537e95a19054', 'albums/2015/11/17/1c91ac2580e41456098428a977837259.png', 1447744282),
(292, 82, '36904a2e5046c8f293e3f269515996b4', 'albums/2015/11/17/a5a443c71d4e4b2af7c02a37680e0438.png', 1447744303),
(293, 82, '00723564bdbf0a0bc823198d22bd4231', 'albums/2015/11/17/d53f325ce8e8875027135dfcdd909e46.png', 1447744340),
(294, 82, '40e44216b96c0b184b0b22f62f118bca', 'albums/2015/11/17/733e1624da86b6f6eda750ed39fbd9d5.png', 1447744629),
(295, 82, '079fc20590d4c4608a308a66a87c9635', 'albums/2015/11/17/0292ac108fc751fa1eeda059c2444154.png', 1447744689),
(296, 82, '381802f8fc12bee6a9875f5a9fefa305', 'albums/2015/11/17/f08cf0306d974557485515b5f027400c.png', 1447744714),
(298, 82, 'fcb0b68e80d72e7fbfb965b044d9ae2e', 'albums/2015/11/17/0a8f6e95dfe6fe8c8fd452e453993811.png', 1447746855),
(299, 82, '3d3a344f695b458f825b78fc5d209c9e', 'albums/2015/11/17/8d57ed1a44823de82c7b21c213b9b147.png', 1447746879),
(300, 82, '972f734c5136b36d322fd2d1777e6c5b', 'albums/2015/11/17/ad854aade6a0071e34a06baea3d6e806.png', 1447746966),
(301, 82, '40cd8f47e00c956f707c3d3aa0c8cc6e', 'albums/2015/11/17/782f6cf8920c5bbc1644fd18e68d365b.png', 1447746982),
(303, 82, '3bc6e8af15697c546febdb6612c9f3dd', 'albums/2015/11/17/45def906f83e4b94491d0dea57e0183a.png', 1447748328);

-- --------------------------------------------------------

--
-- 表的结构 `announ`
--

CREATE TABLE IF NOT EXISTS `announ` (
  `id` int(9) unsigned NOT NULL,
  `content` varchar(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '公告内容',
  `time` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '发布时间',
  `return` longtext CHARACTER SET utf8 COLLATE utf8_bin COMMENT '留言消息',
  `user` int(9) NOT NULL COMMENT '发布者ID',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '公告标题',
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '发布名字',
  `rednum` int(9) NOT NULL COMMENT '已读人数',
  `pic` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `announ`
--

INSERT INTO `announ` (`id`, `content`, `time`, `return`, `user`, `title`, `name`, `rednum`, `pic`) VALUES
(1, '5465461232', '1112222', '[{"r_time":1448265405,"r_mess":"SADASDAS","r_name":"\\u5f20\\u4ea6\\u5f1b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","id":"87"},{"r_time":1448265890,"r_mess":"565614","r_name":"\\u65bc\\u6d9b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/11\\/23\\/bfd1649e7f5de5523894f039aac19c83.gif","id":"85"}]', 87, 'hjhgjghjhg', 'hjhjh', 44, NULL),
(2, '5465461232', '1112222', NULL, 87, 'hjhgjghjhg', 'hjhjh', 44, NULL),
(3, '9+25156125612', '1448266020', '[{"r_time":1448266133,"r_mess":"59652323","r_name":"\\u65bc\\u6d9b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/11\\/23\\/bfd1649e7f5de5523894f039aac19c83.gif","id":"85"},{"r_time":1448267320,"r_mess":"45522333","r_name":"\\u65bc\\u6d9b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/11\\/23\\/bfd1649e7f5de5523894f039aac19c83.gif","id":"85"},{"r_time":1448267324,"r_mess":"4553322244","r_name":"\\u65bc\\u6d9b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/11\\/23\\/bfd1649e7f5de5523894f039aac19c83.gif","id":"85"},{"r_time":1448267330,"r_mess":"5566644466","r_name":"\\u65bc\\u6d9b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/11\\/23\\/bfd1649e7f5de5523894f039aac19c83.gif","id":"85"},{"r_time":1448267338,"r_mess":"48876636","r_name":"\\u65bc\\u6d9b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/11\\/23\\/bfd1649e7f5de5523894f039aac19c83.gif","id":"85"},{"r_time":1448268056,"r_mess":"\\u6b4c\\u529f\\u9882\\u5fb7\\u7684\\u7535\\u98ce\\u6247","r_name":"\\u65bc\\u6d9b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/11\\/23\\/bfd1649e7f5de5523894f039aac19c83.gif","id":"85"},{"r_time":1448268092,"r_mess":"fghhjmmmmgdssfcv","r_name":"\\u65bc\\u6d9b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/11\\/23\\/bfd1649e7f5de5523894f039aac19c83.gif","id":"85"},{"r_time":1448269943,"r_mess":"\\u6492\\u5927\\u58f0\\u5730","r_name":"\\u5f20\\u4ea6\\u5f1b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","id":"87"},{"r_time":1448270020,"r_mess":"\\u6211\\u53d6\\u4f60\\u59b9","r_name":"\\u5f20\\u4ea6\\u5f1b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","id":"87"},{"r_time":1448270806,"r_mess":"\\u5176\\u5473\\u65e0\\u7a77","r_name":"\\u5f20\\u4ea6\\u5f1b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","id":"87"},{"r_time":1448274405,"r_mess":"\\u5176\\u4e50\\u65e0\\u7a77","r_name":"\\u65bc\\u6d9b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/11\\/23\\/bfd1649e7f5de5523894f039aac19c83.gif","id":"85"},{"r_time":1448274419,"r_mess":"\\u5927\\u5bb6\\u597d\\u624d\\u662f\\u771f\\u7684\\u597d\\uff01","r_name":"\\u65bc\\u6d9b","img":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/11\\/23\\/bfd1649e7f5de5523894f039aac19c83.gif","id":"85"}]', 85, 'polkopk,po,o', '於涛', 0, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/avatars/2015/11/23/bfd1649e7f5de5523894f039aac19c83.gif');

-- --------------------------------------------------------

--
-- 表的结构 `announ_status`
--

CREATE TABLE IF NOT EXISTS `announ_status` (
  `id` int(8) unsigned NOT NULL,
  `notice_id` int(8) NOT NULL COMMENT '公告ID',
  `admin_id` int(8) NOT NULL COMMENT '用户ID',
  `status` tinyint(1) NOT NULL COMMENT '状态'
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `announ_status`
--

INSERT INTO `announ_status` (`id`, `notice_id`, `admin_id`, `status`) VALUES
(1, 1, 85, 2),
(2, 3, 160, 1),
(3, 3, 162, 1),
(4, 3, 161, 1),
(5, 3, 164, 1),
(6, 3, 188, 1),
(7, 3, 192, 1),
(8, 3, 158, 1),
(9, 3, 165, 1),
(10, 3, 111, 1),
(11, 3, 168, 1),
(12, 3, 159, 1),
(13, 3, 90, 1),
(14, 3, 191, 1),
(15, 3, 83, 2),
(16, 3, 82, 1),
(17, 3, 81, 1),
(18, 3, 87, 2),
(19, 3, 152, 1),
(20, 3, 85, 2);

-- --------------------------------------------------------

--
-- 表的结构 `api_doc`
--

CREATE TABLE IF NOT EXISTS `api_doc` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `name` int(99) NOT NULL COMMENT 'API名字',
  `url` varchar(255) NOT NULL COMMENT '请求URL',
  `method` varchar(9) NOT NULL COMMENT '请求方式',
  `params` text NOT NULL COMMENT '请求参数',
  `feedback` text NOT NULL COMMENT '返回值',
  `demo` varchar(255) NOT NULL COMMENT '示例URL',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='API';

-- --------------------------------------------------------

--
-- 表的结构 `app_list`
--

CREATE TABLE IF NOT EXISTS `app_list` (
  `id` int(11) unsigned NOT NULL,
  `bundleid` varchar(32) NOT NULL,
  `channel_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(32) NOT NULL,
  `intro` varchar(255) DEFAULT NULL,
  `sku` varchar(32) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `copyright` varchar(127) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `app_list`
--

INSERT INTO `app_list` (`id`, `bundleid`, `channel_id`, `name`, `intro`, `sku`, `logo`, `copyright`) VALUES
(3, '43343', 3, '中国蓝TVbeta', '421', '21321', NULL, '12323'),
(5, '2', 2, '萧山app', '2', '2', NULL, '2');

-- --------------------------------------------------------

--
-- 表的结构 `app_version`
--

CREATE TABLE IF NOT EXISTS `app_version` (
  `id` int(11) unsigned NOT NULL,
  `app_id` int(11) unsigned NOT NULL,
  `newfeature` varchar(255) DEFAULT NULL,
  `version` varchar(32) NOT NULL,
  `apk` varchar(32) DEFAULT NULL,
  `iosuri` varchar(32) DEFAULT NULL,
  `iostesturi` varchar(32) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `app_version`
--

INSERT INTO `app_version` (`id`, `app_id`, `newfeature`, `version`, `apk`, `iosuri`, `iostesturi`, `status`, `timestamp`) VALUES
(1, 3, 'new2', '1.0.0', '1', '1', '1', 1, 1442992437),
(4, 3, 'new1', '1.0.1', '1', '1', '1', 0, 1442992467),
(7, 5, 'new1', '1.0.2', '1', '1', '1', 0, 1442991556);

-- --------------------------------------------------------

--
-- 表的结构 `asset`
--

CREATE TABLE IF NOT EXISTS `asset` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(50) NOT NULL COMMENT '资产名字',
  `number` varchar(50) NOT NULL COMMENT '资产编号',
  `time` varchar(50) NOT NULL COMMENT '时间',
  `status` tinyint(4) NOT NULL COMMENT '状态'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `asset`
--

INSERT INTO `asset` (`id`, `admin_id`, `name`, `number`, `time`, `status`) VALUES
(1, 87, '手机', '201511111001', '1447175580', 1),
(2, 87, '电脑2222', '201111111', '1446516300', 1);

-- --------------------------------------------------------

--
-- 表的结构 `attachment_common`
--

CREATE TABLE IF NOT EXISTS `attachment_common` (
  `id` int(11) NOT NULL,
  `origin_name` varchar(256) DEFAULT NULL COMMENT '原始附件名',
  `name` varchar(256) DEFAULT NULL COMMENT '上传后的保存名',
  `created` int(11) DEFAULT NULL COMMENT '附件上传时间',
  `type` varchar(20) DEFAULT NULL COMMENT '附件类型 1-视频 2-图片 0-未知',
  `path` varchar(1024) DEFAULT NULL COMMENT '存储相对路径',
  `ext` varchar(255) DEFAULT NULL COMMENT '附件后缀',
  `usertype` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:manager, 1:member',
  `u_id` int(11) DEFAULT '0' COMMENT '上传者id default：-1'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用于头图，介绍视频，图片，logo等介绍性附件的存放';

--
-- 转存表中的数据 `attachment_common`
--

INSERT INTO `attachment_common` (`id`, `origin_name`, `name`, `created`, `type`, `path`, `ext`, `usertype`, `u_id`) VALUES
(1, '键盘操作.png', 'b666e1de6b1c89addd1632ae453e0ab4.png', 1442494262, '0', 'task/attach_b666e1de6b1c89addd1632ae453e0ab4.png', 'image/png', 0, 1),
(2, '20150706131505.jpg', '2015070613150.jpg', 1442569408, '0', 'task/attach_2015070613150.jpg', 'image/jpeg', 0, 1),
(3, '20150706131505.jpg', '2015070613150.jpg', 1442569430, '0', 'task/attach_2015070613150.jpg', 'image/jpeg', 0, 1),
(4, '20150706131505.jpg', '2015070613150.jpg', 1442569461, '0', 'task/attach_2015070613150.jpg', 'image/jpeg', 0, 1),
(5, '20150706131505.jpg', '2015070613150.jpg', 1442569461, '0', 'task/attach_2015070613150.jpg', 'image/jpeg', 0, 1),
(6, 'Penguins.jpg', 'Penguin.jpg', 1442990474, '0', 'admin/avatar_Penguin.jpg', 'image/jpeg', 0, 1),
(7, 'Koala.jpg', 'Koal.jpg', 1442990504, '0', 'admin/avatar_Koal.jpg', 'image/jpeg', 0, 1),
(8, 'viewphoto.action.jpg', 'viewphoto.actio.jpg', 1444386962, '0', 'admin/avatar/viewphoto.actio.jpg', 'image/jpeg', 0, 111),
(9, 'f7acc13cc1a59698635ec6a62e337ce1.png', 'f7acc13cc1a59698635ec6a62e337ce.png', 1444387079, '0', 'admin/avatar/f7acc13cc1a59698635ec6a62e337ce.png', 'image/png', 0, 111),
(10, 'Desert.jpg', 'Deser.jpg', 1444703403, '0', 'task/attach/Deser.jpg', 'image/jpeg', 0, 111),
(11, '伊丽莎白.jpg', '伊丽莎白.jpg', 1445390854, '2', 'logos/2015/10/21/11add285c181b82e8af96b23e8a12ccd.jpg', 'image/jpeg', 0, 84),
(12, '伊丽莎白.jpg', '伊丽莎白.jpg', NULL, '2', 'logos/2015/10/21/2efd3a5296c5aa69d288b7bcd84c9b8f.jpg', 'image/jpeg', 0, 0),
(13, '20150706131505.jpg', '2015070613150.jpg', 1445851043, '0', 'task/attach/2015070613150.jpg', 'image/jpeg', 0, 82),
(14, 'Hydrangeas.jpg', 'Hydrangeas.jpg', 1448265653, '2', 'logos/2015/11/23/3b6191b1690ba2c869efdca74ea3376e.jpg', 'image/jpeg', 0, 90),
(15, 'Hydrangeas.jpg', 'Hydrangeas.jpg', NULL, '2', 'logos/2015/11/23/7554ba71630f3d3a47f6c4f50632d1b0.jpg', 'image/jpeg', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `auth_assign`
--

CREATE TABLE IF NOT EXISTS `auth_assign` (
  `id` int(11) unsigned NOT NULL COMMENT '授权ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `element_id` int(11) unsigned NOT NULL COMMENT '原子ID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:原子/1:角色'
) ENGINE=InnoDB AUTO_INCREMENT=1108 DEFAULT CHARSET=utf8 COMMENT='权限授权表';

--
-- 转存表中的数据 `auth_assign`
--

INSERT INTO `auth_assign` (`id`, `channel_id`, `user_id`, `element_id`, `type`) VALUES
(290, 0, 84, 5, 1),
(615, 0, 85, 5, 0),
(614, 0, 85, 6, 0),
(633, 0, 85, 7, 0),
(634, 0, 85, 8, 0),
(635, 0, 85, 9, 0),
(636, 0, 85, 10, 0),
(637, 0, 85, 11, 0),
(638, 0, 85, 12, 0),
(639, 0, 85, 13, 0),
(596, 0, 85, 14, 0),
(597, 0, 85, 15, 0),
(599, 0, 85, 18, 0),
(600, 0, 85, 19, 0),
(605, 0, 85, 22, 0),
(606, 0, 85, 23, 0),
(607, 0, 85, 24, 0),
(623, 0, 85, 25, 0),
(624, 0, 85, 26, 0),
(625, 0, 85, 27, 0),
(626, 0, 85, 28, 0),
(640, 0, 85, 29, 0),
(641, 0, 85, 33, 0),
(642, 0, 85, 37, 0),
(627, 0, 85, 41, 0),
(628, 0, 85, 42, 0),
(629, 0, 85, 43, 0),
(630, 0, 85, 44, 0),
(643, 0, 85, 45, 0),
(594, 0, 85, 46, 0),
(595, 0, 85, 47, 0),
(865, 0, 85, 48, 0),
(616, 0, 85, 56, 0),
(617, 0, 85, 57, 0),
(618, 0, 85, 58, 0),
(619, 0, 85, 59, 0),
(620, 0, 85, 60, 0),
(621, 0, 85, 61, 0),
(622, 0, 85, 63, 0),
(598, 0, 85, 64, 0),
(601, 0, 85, 65, 0),
(602, 0, 85, 66, 0),
(603, 0, 85, 67, 0),
(604, 0, 85, 68, 0),
(608, 0, 85, 71, 0),
(609, 0, 85, 72, 0),
(610, 0, 85, 73, 0),
(611, 0, 85, 74, 0),
(612, 0, 85, 75, 0),
(613, 0, 85, 76, 0),
(631, 0, 85, 78, 0),
(632, 0, 85, 79, 0),
(644, 0, 85, 80, 0),
(645, 0, 85, 81, 0),
(646, 0, 85, 82, 0),
(647, 0, 85, 83, 0),
(648, 0, 85, 84, 0),
(649, 0, 85, 85, 0),
(650, 0, 85, 86, 0),
(651, 0, 85, 87, 0),
(652, 0, 85, 88, 0),
(653, 0, 85, 89, 0),
(654, 0, 85, 90, 0),
(655, 0, 85, 91, 0),
(656, 0, 85, 92, 0),
(657, 0, 85, 93, 0),
(658, 0, 85, 96, 0),
(659, 0, 85, 97, 0),
(660, 0, 85, 98, 0),
(1102, 0, 85, 100, 0),
(1104, 0, 85, 110, 0),
(1103, 0, 85, 114, 0),
(1090, 0, 85, 118, 0),
(1091, 0, 85, 119, 0),
(1092, 0, 85, 120, 0),
(1093, 0, 85, 121, 0),
(1094, 0, 85, 122, 0),
(1095, 0, 85, 123, 0),
(1096, 0, 85, 124, 0),
(1097, 0, 85, 125, 0),
(1098, 0, 85, 126, 0),
(1099, 0, 85, 127, 0),
(1100, 0, 85, 128, 0),
(1101, 0, 85, 129, 0),
(1105, 0, 85, 139, 0),
(430, 0, 90, 5, 0),
(429, 0, 90, 6, 0),
(448, 0, 90, 7, 0),
(449, 0, 90, 8, 0),
(450, 0, 90, 9, 0),
(451, 0, 90, 10, 0),
(452, 0, 90, 11, 0),
(453, 0, 90, 12, 0),
(454, 0, 90, 13, 0),
(411, 0, 90, 14, 0),
(412, 0, 90, 15, 0),
(414, 0, 90, 18, 0),
(415, 0, 90, 19, 0),
(420, 0, 90, 22, 0),
(421, 0, 90, 23, 0),
(422, 0, 90, 24, 0),
(438, 0, 90, 25, 0),
(439, 0, 90, 26, 0),
(440, 0, 90, 27, 0),
(441, 0, 90, 28, 0),
(455, 0, 90, 29, 0),
(456, 0, 90, 33, 0),
(457, 0, 90, 37, 0),
(442, 0, 90, 41, 0),
(443, 0, 90, 42, 0),
(444, 0, 90, 43, 0),
(445, 0, 90, 44, 0),
(458, 0, 90, 45, 0),
(409, 0, 90, 46, 0),
(410, 0, 90, 47, 0),
(431, 0, 90, 56, 0),
(432, 0, 90, 57, 0),
(433, 0, 90, 58, 0),
(434, 0, 90, 59, 0),
(435, 0, 90, 60, 0),
(436, 0, 90, 61, 0),
(437, 0, 90, 63, 0),
(413, 0, 90, 64, 0),
(416, 0, 90, 65, 0),
(417, 0, 90, 66, 0),
(418, 0, 90, 67, 0),
(419, 0, 90, 68, 0),
(423, 0, 90, 71, 0),
(424, 0, 90, 72, 0),
(425, 0, 90, 73, 0),
(426, 0, 90, 74, 0),
(427, 0, 90, 75, 0),
(428, 0, 90, 76, 0),
(446, 0, 90, 78, 0),
(447, 0, 90, 79, 0),
(459, 0, 90, 80, 0),
(460, 0, 90, 81, 0),
(461, 0, 90, 82, 0),
(462, 0, 90, 83, 0),
(463, 0, 90, 84, 0),
(464, 0, 90, 85, 0),
(465, 0, 90, 86, 0),
(466, 0, 90, 87, 0),
(467, 0, 90, 88, 0),
(468, 0, 90, 89, 0),
(469, 0, 90, 90, 0),
(470, 0, 90, 91, 0),
(471, 0, 90, 92, 0),
(472, 0, 90, 93, 0),
(473, 0, 90, 96, 0),
(2, 0, 111, 2, 0),
(144, 0, 111, 5, 0),
(145, 0, 111, 6, 0),
(7, 0, 111, 7, 0),
(8, 0, 111, 8, 0),
(153, 0, 111, 14, 0),
(154, 0, 111, 15, 0),
(155, 0, 111, 18, 0),
(156, 0, 111, 19, 0),
(22, 0, 111, 23, 0),
(226, 0, 111, 24, 0),
(23, 0, 111, 25, 0),
(24, 0, 111, 26, 0),
(25, 0, 111, 27, 0),
(26, 0, 111, 28, 0),
(159, 0, 111, 29, 0),
(39, 0, 111, 41, 0),
(45, 0, 111, 45, 0),
(46, 0, 111, 46, 0),
(146, 0, 111, 56, 0),
(147, 0, 111, 57, 0),
(148, 0, 111, 58, 0),
(149, 0, 111, 59, 0),
(150, 0, 111, 60, 0),
(151, 0, 111, 61, 0),
(152, 0, 111, 63, 0),
(157, 0, 111, 64, 0),
(158, 0, 111, 65, 0),
(160, 0, 111, 69, 0),
(225, 0, 111, 222, 0),
(251, 0, 159, 5, 0),
(276, 0, 159, 5, 1),
(250, 0, 159, 6, 0),
(264, 0, 159, 14, 0),
(265, 0, 159, 15, 0),
(267, 0, 159, 18, 0),
(268, 0, 159, 19, 0),
(252, 0, 159, 56, 0),
(266, 0, 159, 64, 0),
(269, 0, 159, 65, 0),
(279, 0, 160, 5, 0),
(278, 0, 160, 6, 0),
(277, 0, 160, 46, 0),
(280, 0, 160, 56, 0),
(281, 0, 160, 57, 0),
(282, 0, 160, 58, 0),
(283, 0, 160, 59, 0),
(284, 0, 160, 60, 0),
(285, 0, 160, 61, 0),
(286, 0, 160, 63, 0),
(296, 0, 161, 6, 1),
(297, 0, 161, 14, 0),
(298, 0, 162, 6, 1),
(301, 0, 162, 47, 0),
(331, 0, 164, 22, 0),
(332, 0, 164, 23, 0),
(333, 0, 164, 24, 0),
(303, 0, 164, 46, 0),
(304, 0, 164, 47, 0),
(334, 0, 164, 71, 0),
(335, 0, 164, 72, 0),
(336, 0, 164, 73, 0),
(337, 0, 164, 74, 0),
(338, 0, 164, 75, 0),
(307, 0, 165, 6, 1),
(341, 0, 165, 29, 0),
(343, 0, 165, 37, 0),
(328, 0, 165, 47, 0),
(330, 0, 165, 76, 0),
(486, 0, 170, 5, 0),
(474, 0, 170, 5, 1),
(500, 0, 170, 7, 0),
(501, 0, 170, 8, 0),
(502, 0, 170, 9, 0),
(503, 0, 170, 10, 0),
(504, 0, 170, 11, 0),
(505, 0, 170, 12, 0),
(506, 0, 170, 13, 0),
(477, 0, 170, 14, 0),
(478, 0, 170, 22, 0),
(479, 0, 170, 24, 0),
(492, 0, 170, 27, 0),
(493, 0, 170, 28, 0),
(507, 0, 170, 29, 0),
(508, 0, 170, 33, 0),
(509, 0, 170, 37, 0),
(494, 0, 170, 41, 0),
(495, 0, 170, 42, 0),
(496, 0, 170, 43, 0),
(497, 0, 170, 44, 0),
(510, 0, 170, 45, 0),
(475, 0, 170, 46, 0),
(476, 0, 170, 47, 0),
(487, 0, 170, 56, 0),
(488, 0, 170, 57, 0),
(489, 0, 170, 58, 0),
(490, 0, 170, 59, 0),
(491, 0, 170, 60, 0),
(480, 0, 170, 71, 0),
(481, 0, 170, 72, 0),
(482, 0, 170, 73, 0),
(483, 0, 170, 74, 0),
(484, 0, 170, 75, 0),
(485, 0, 170, 76, 0),
(498, 0, 170, 78, 0),
(499, 0, 170, 79, 0),
(511, 0, 170, 80, 0),
(512, 0, 170, 81, 0),
(513, 0, 170, 82, 0),
(514, 0, 170, 83, 0),
(515, 0, 170, 84, 0),
(516, 0, 170, 85, 0),
(517, 0, 170, 86, 0),
(518, 0, 170, 87, 0),
(519, 0, 170, 88, 0),
(520, 0, 170, 89, 0),
(521, 0, 170, 90, 0),
(522, 0, 170, 91, 0),
(523, 0, 170, 92, 0),
(524, 0, 170, 93, 0),
(525, 0, 170, 96, 0),
(526, 0, 170, 97, 0),
(548, 0, 171, 5, 0),
(547, 0, 171, 6, 0),
(566, 0, 171, 7, 0),
(567, 0, 171, 8, 0),
(568, 0, 171, 9, 0),
(569, 0, 171, 10, 0),
(570, 0, 171, 11, 0),
(571, 0, 171, 12, 0),
(572, 0, 171, 13, 0),
(529, 0, 171, 14, 0),
(530, 0, 171, 15, 0),
(532, 0, 171, 18, 0),
(533, 0, 171, 19, 0),
(538, 0, 171, 22, 0),
(539, 0, 171, 23, 0),
(540, 0, 171, 24, 0),
(556, 0, 171, 25, 0),
(557, 0, 171, 26, 0),
(558, 0, 171, 27, 0),
(559, 0, 171, 28, 0),
(573, 0, 171, 29, 0),
(574, 0, 171, 33, 0),
(575, 0, 171, 37, 0),
(560, 0, 171, 41, 0),
(561, 0, 171, 42, 0),
(562, 0, 171, 43, 0),
(563, 0, 171, 44, 0),
(576, 0, 171, 45, 0),
(527, 0, 171, 46, 0),
(528, 0, 171, 47, 0),
(549, 0, 171, 56, 0),
(550, 0, 171, 57, 0),
(551, 0, 171, 58, 0),
(552, 0, 171, 59, 0),
(553, 0, 171, 60, 0),
(554, 0, 171, 61, 0),
(555, 0, 171, 63, 0),
(531, 0, 171, 64, 0),
(534, 0, 171, 65, 0),
(535, 0, 171, 66, 0),
(536, 0, 171, 67, 0),
(537, 0, 171, 68, 0),
(541, 0, 171, 71, 0),
(542, 0, 171, 72, 0),
(543, 0, 171, 73, 0),
(544, 0, 171, 74, 0),
(545, 0, 171, 75, 0),
(546, 0, 171, 76, 0),
(564, 0, 171, 78, 0),
(565, 0, 171, 79, 0),
(577, 0, 171, 80, 0),
(578, 0, 171, 81, 0),
(579, 0, 171, 82, 0),
(580, 0, 171, 83, 0),
(581, 0, 171, 84, 0),
(582, 0, 171, 85, 0),
(583, 0, 171, 86, 0),
(584, 0, 171, 87, 0),
(585, 0, 171, 88, 0),
(586, 0, 171, 89, 0),
(587, 0, 171, 90, 0),
(588, 0, 171, 91, 0),
(589, 0, 171, 92, 0),
(590, 0, 171, 93, 0),
(591, 0, 171, 96, 0),
(592, 0, 171, 97, 0),
(593, 0, 171, 98, 0),
(1039, 0, 188, 5, 0),
(1017, 0, 188, 5, 1),
(1061, 0, 188, 7, 0),
(1062, 0, 188, 8, 0),
(1063, 0, 188, 9, 0),
(1064, 0, 188, 10, 0),
(1065, 0, 188, 11, 0),
(1066, 0, 188, 12, 0),
(1067, 0, 188, 13, 0),
(1021, 0, 188, 14, 0),
(1022, 0, 188, 22, 0),
(1023, 0, 188, 24, 0),
(1045, 0, 188, 27, 0),
(1046, 0, 188, 28, 0),
(1068, 0, 188, 29, 0),
(1069, 0, 188, 33, 0),
(1070, 0, 188, 37, 0),
(1047, 0, 188, 41, 0),
(1048, 0, 188, 42, 0),
(1049, 0, 188, 43, 0),
(1050, 0, 188, 44, 0),
(1071, 0, 188, 45, 0),
(1018, 0, 188, 46, 0),
(1019, 0, 188, 47, 0),
(1051, 0, 188, 48, 0),
(1052, 0, 188, 49, 0),
(1053, 0, 188, 50, 0),
(1054, 0, 188, 51, 0),
(1055, 0, 188, 52, 0),
(1056, 0, 188, 53, 0),
(1057, 0, 188, 54, 0),
(1058, 0, 188, 55, 0),
(1040, 0, 188, 56, 0),
(1041, 0, 188, 57, 0),
(1042, 0, 188, 58, 0),
(1043, 0, 188, 59, 0),
(1044, 0, 188, 60, 0),
(1024, 0, 188, 71, 0),
(1025, 0, 188, 72, 0),
(1026, 0, 188, 73, 0),
(1027, 0, 188, 74, 0),
(1028, 0, 188, 75, 0),
(1038, 0, 188, 76, 0),
(1059, 0, 188, 78, 0),
(1060, 0, 188, 79, 0),
(1072, 0, 188, 80, 0),
(1073, 0, 188, 81, 0),
(1074, 0, 188, 82, 0),
(1075, 0, 188, 83, 0),
(1076, 0, 188, 84, 0),
(1077, 0, 188, 85, 0),
(1078, 0, 188, 86, 0),
(1079, 0, 188, 87, 0),
(1080, 0, 188, 88, 0),
(1081, 0, 188, 89, 0),
(1082, 0, 188, 90, 0),
(1083, 0, 188, 91, 0),
(1084, 0, 188, 92, 0),
(1085, 0, 188, 93, 0),
(1086, 0, 188, 96, 0),
(1087, 0, 188, 97, 0),
(1088, 0, 188, 98, 0),
(1089, 0, 188, 99, 0),
(1020, 0, 188, 100, 0),
(1029, 0, 188, 101, 0),
(1030, 0, 188, 102, 0),
(1031, 0, 188, 103, 0),
(1032, 0, 188, 104, 0),
(1033, 0, 188, 105, 0),
(1034, 0, 188, 106, 0),
(1035, 0, 188, 107, 0),
(1037, 0, 188, 108, 0),
(1036, 0, 188, 109, 0),
(1106, 0, 191, 5, 1),
(1107, 0, 191, 6, 1),
(795, 1, 83, 1, 1),
(796, 1, 83, 2, 1),
(797, 1, 83, 3, 1),
(769, 1, 83, 9, 0),
(770, 1, 83, 10, 0),
(771, 1, 83, 11, 0),
(772, 1, 83, 12, 0),
(773, 1, 83, 13, 0),
(739, 1, 83, 22, 0),
(741, 1, 83, 24, 0),
(757, 1, 83, 25, 0),
(758, 1, 83, 26, 0),
(759, 1, 83, 27, 0),
(760, 1, 83, 28, 0),
(774, 1, 83, 29, 0),
(775, 1, 83, 33, 0),
(776, 1, 83, 37, 0),
(761, 1, 83, 41, 0),
(762, 1, 83, 42, 0),
(763, 1, 83, 43, 0),
(764, 1, 83, 44, 0),
(777, 1, 83, 45, 0),
(904, 1, 83, 48, 0),
(750, 1, 83, 56, 0),
(751, 1, 83, 57, 0),
(752, 1, 83, 58, 0),
(753, 1, 83, 59, 0),
(754, 1, 83, 60, 0),
(755, 1, 83, 61, 0),
(756, 1, 83, 63, 0),
(736, 1, 83, 66, 0),
(742, 1, 83, 71, 0),
(743, 1, 83, 72, 0),
(745, 1, 83, 74, 0),
(746, 1, 83, 75, 0),
(747, 1, 83, 76, 0),
(765, 1, 83, 78, 0),
(766, 1, 83, 79, 0),
(778, 1, 83, 80, 0),
(779, 1, 83, 81, 0),
(780, 1, 83, 82, 0),
(781, 1, 83, 83, 0),
(782, 1, 83, 84, 0),
(783, 1, 83, 85, 0),
(784, 1, 83, 86, 0),
(785, 1, 83, 87, 0),
(786, 1, 83, 88, 0),
(787, 1, 83, 89, 0),
(788, 1, 83, 90, 0),
(789, 1, 83, 91, 0),
(790, 1, 83, 92, 0),
(791, 1, 83, 93, 0),
(792, 1, 83, 96, 0),
(793, 1, 83, 97, 0),
(794, 1, 83, 98, 0),
(224, 1, 84, 3, 1),
(162, 1, 87, 2, 0),
(174, 1, 87, 5, 0),
(175, 1, 87, 6, 0),
(188, 1, 87, 7, 0),
(189, 1, 87, 8, 0),
(164, 1, 87, 14, 0),
(165, 1, 87, 15, 0),
(166, 1, 87, 18, 0),
(167, 1, 87, 19, 0),
(173, 1, 87, 23, 0),
(183, 1, 87, 25, 0),
(184, 1, 87, 26, 0),
(185, 1, 87, 27, 0),
(186, 1, 87, 28, 0),
(190, 1, 87, 29, 0),
(187, 1, 87, 41, 0),
(163, 1, 87, 46, 0),
(176, 1, 87, 56, 0),
(177, 1, 87, 57, 0),
(178, 1, 87, 58, 0),
(179, 1, 87, 59, 0),
(180, 1, 87, 60, 0),
(181, 1, 87, 61, 0),
(182, 1, 87, 63, 0),
(168, 1, 87, 64, 0),
(169, 1, 87, 65, 0),
(170, 1, 87, 66, 0),
(171, 1, 87, 67, 0),
(172, 1, 87, 68, 0),
(191, 1, 87, 69, 0),
(192, 1, 87, 70, 0),
(365, 1, 90, 5, 0),
(364, 1, 90, 6, 0),
(383, 1, 90, 7, 0),
(384, 1, 90, 8, 0),
(385, 1, 90, 9, 0),
(386, 1, 90, 10, 0),
(387, 1, 90, 11, 0),
(388, 1, 90, 12, 0),
(389, 1, 90, 13, 0),
(346, 1, 90, 14, 0),
(347, 1, 90, 15, 0),
(349, 1, 90, 18, 0),
(350, 1, 90, 19, 0),
(355, 1, 90, 22, 0),
(356, 1, 90, 23, 0),
(357, 1, 90, 24, 0),
(373, 1, 90, 25, 0),
(374, 1, 90, 26, 0),
(375, 1, 90, 27, 0),
(376, 1, 90, 28, 0),
(390, 1, 90, 29, 0),
(391, 1, 90, 33, 0),
(392, 1, 90, 37, 0),
(377, 1, 90, 41, 0),
(378, 1, 90, 42, 0),
(379, 1, 90, 43, 0),
(380, 1, 90, 44, 0),
(393, 1, 90, 45, 0),
(344, 1, 90, 46, 0),
(345, 1, 90, 47, 0),
(366, 1, 90, 56, 0),
(367, 1, 90, 57, 0),
(368, 1, 90, 58, 0),
(369, 1, 90, 59, 0),
(370, 1, 90, 60, 0),
(371, 1, 90, 61, 0),
(372, 1, 90, 63, 0),
(348, 1, 90, 64, 0),
(351, 1, 90, 65, 0),
(352, 1, 90, 66, 0),
(353, 1, 90, 67, 0),
(354, 1, 90, 68, 0),
(358, 1, 90, 71, 0),
(359, 1, 90, 72, 0),
(360, 1, 90, 73, 0),
(361, 1, 90, 74, 0),
(362, 1, 90, 75, 0),
(363, 1, 90, 76, 0),
(381, 1, 90, 78, 0),
(382, 1, 90, 79, 0),
(394, 1, 90, 80, 0),
(395, 1, 90, 81, 0),
(396, 1, 90, 82, 0),
(397, 1, 90, 83, 0),
(398, 1, 90, 84, 0),
(399, 1, 90, 85, 0),
(400, 1, 90, 86, 0),
(401, 1, 90, 87, 0),
(402, 1, 90, 88, 0),
(403, 1, 90, 89, 0),
(404, 1, 90, 90, 0),
(405, 1, 90, 91, 0),
(406, 1, 90, 92, 0),
(407, 1, 90, 93, 0),
(408, 1, 90, 96, 0),
(93, 1, 106, 2, 0),
(94, 1, 106, 4, 0),
(95, 1, 106, 5, 0),
(96, 1, 106, 6, 0),
(97, 1, 106, 7, 0),
(98, 1, 106, 8, 0),
(99, 1, 106, 9, 0),
(100, 1, 106, 10, 0),
(101, 1, 106, 11, 0),
(102, 1, 106, 12, 0),
(103, 1, 106, 13, 0),
(110, 1, 106, 14, 0),
(111, 1, 106, 15, 0),
(104, 1, 106, 16, 0),
(105, 1, 106, 17, 0),
(106, 1, 106, 18, 0),
(107, 1, 106, 19, 0),
(108, 1, 106, 20, 0),
(109, 1, 106, 21, 0),
(133, 1, 106, 22, 0),
(112, 1, 106, 23, 0),
(134, 1, 106, 24, 0),
(113, 1, 106, 25, 0),
(114, 1, 106, 26, 0),
(115, 1, 106, 27, 0),
(116, 1, 106, 28, 0),
(117, 1, 106, 29, 0),
(118, 1, 106, 30, 0),
(119, 1, 106, 31, 0),
(120, 1, 106, 32, 0),
(121, 1, 106, 33, 0),
(122, 1, 106, 34, 0),
(123, 1, 106, 35, 0),
(124, 1, 106, 36, 0),
(125, 1, 106, 37, 0),
(126, 1, 106, 38, 0),
(127, 1, 106, 39, 0),
(128, 1, 106, 40, 0),
(129, 1, 106, 41, 0),
(130, 1, 106, 42, 0),
(131, 1, 106, 43, 0),
(132, 1, 106, 44, 0),
(135, 1, 106, 45, 0),
(136, 1, 106, 46, 0),
(137, 1, 106, 47, 0),
(193, 1, 146, 2, 0),
(205, 1, 146, 5, 0),
(206, 1, 146, 6, 0),
(219, 1, 146, 7, 0),
(220, 1, 146, 8, 0),
(195, 1, 146, 14, 0),
(196, 1, 146, 15, 0),
(197, 1, 146, 18, 0),
(198, 1, 146, 19, 0),
(204, 1, 146, 23, 0),
(214, 1, 146, 25, 0),
(215, 1, 146, 26, 0),
(216, 1, 146, 27, 0),
(217, 1, 146, 28, 0),
(221, 1, 146, 29, 0),
(218, 1, 146, 41, 0),
(194, 1, 146, 46, 0),
(207, 1, 146, 56, 0),
(208, 1, 146, 57, 0),
(209, 1, 146, 58, 0),
(210, 1, 146, 59, 0),
(211, 1, 146, 60, 0),
(212, 1, 146, 61, 0),
(213, 1, 146, 63, 0),
(199, 1, 146, 64, 0),
(200, 1, 146, 65, 0),
(201, 1, 146, 66, 0),
(202, 1, 146, 67, 0),
(203, 1, 146, 68, 0),
(222, 1, 146, 69, 0),
(223, 1, 146, 70, 0),
(869, 1, 169, 1, 1),
(876, 1, 169, 22, 0),
(877, 1, 169, 24, 0),
(895, 1, 169, 25, 0),
(896, 1, 169, 26, 0),
(897, 1, 169, 27, 0),
(898, 1, 169, 28, 0),
(899, 1, 169, 41, 0),
(900, 1, 169, 42, 0),
(901, 1, 169, 43, 0),
(902, 1, 169, 44, 0),
(886, 1, 169, 48, 0),
(888, 1, 169, 56, 0),
(889, 1, 169, 57, 0),
(890, 1, 169, 58, 0),
(891, 1, 169, 59, 0),
(892, 1, 169, 60, 0),
(893, 1, 169, 61, 0),
(894, 1, 169, 63, 0),
(871, 1, 169, 64, 0),
(872, 1, 169, 65, 0),
(873, 1, 169, 66, 0),
(874, 1, 169, 67, 0),
(875, 1, 169, 68, 0),
(878, 1, 169, 71, 0),
(879, 1, 169, 72, 0),
(880, 1, 169, 74, 0),
(881, 1, 169, 75, 0),
(887, 1, 169, 76, 0),
(870, 1, 169, 100, 0),
(882, 1, 169, 101, 0),
(883, 1, 169, 102, 0),
(884, 1, 169, 103, 0),
(885, 1, 169, 104, 0),
(819, 1, 174, 5, 0),
(818, 1, 174, 6, 0),
(837, 1, 174, 7, 0),
(838, 1, 174, 8, 0),
(839, 1, 174, 9, 0),
(840, 1, 174, 10, 0),
(841, 1, 174, 11, 0),
(842, 1, 174, 12, 0),
(843, 1, 174, 13, 0),
(800, 1, 174, 14, 0),
(801, 1, 174, 15, 0),
(803, 1, 174, 18, 0),
(804, 1, 174, 19, 0),
(809, 1, 174, 22, 0),
(810, 1, 174, 23, 0),
(811, 1, 174, 24, 0),
(827, 1, 174, 25, 0),
(828, 1, 174, 26, 0),
(829, 1, 174, 27, 0),
(830, 1, 174, 28, 0),
(844, 1, 174, 29, 0),
(845, 1, 174, 33, 0),
(846, 1, 174, 37, 0),
(831, 1, 174, 41, 0),
(832, 1, 174, 42, 0),
(833, 1, 174, 43, 0),
(834, 1, 174, 44, 0),
(847, 1, 174, 45, 0),
(798, 1, 174, 46, 0),
(799, 1, 174, 47, 0),
(820, 1, 174, 56, 0),
(821, 1, 174, 57, 0),
(822, 1, 174, 58, 0),
(823, 1, 174, 59, 0),
(824, 1, 174, 60, 0),
(825, 1, 174, 61, 0),
(826, 1, 174, 63, 0),
(802, 1, 174, 64, 0),
(805, 1, 174, 65, 0),
(806, 1, 174, 66, 0),
(807, 1, 174, 67, 0),
(808, 1, 174, 68, 0),
(812, 1, 174, 71, 0),
(813, 1, 174, 72, 0),
(814, 1, 174, 73, 0),
(815, 1, 174, 74, 0),
(816, 1, 174, 75, 0),
(817, 1, 174, 76, 0),
(835, 1, 174, 78, 0),
(836, 1, 174, 79, 0),
(848, 1, 174, 80, 0),
(849, 1, 174, 81, 0),
(850, 1, 174, 82, 0),
(851, 1, 174, 83, 0),
(852, 1, 174, 84, 0),
(853, 1, 174, 85, 0),
(854, 1, 174, 86, 0),
(855, 1, 174, 87, 0),
(856, 1, 174, 88, 0),
(857, 1, 174, 89, 0),
(858, 1, 174, 90, 0),
(859, 1, 174, 91, 0),
(860, 1, 174, 92, 0),
(861, 1, 174, 93, 0),
(862, 1, 174, 96, 0),
(863, 1, 174, 97, 0),
(864, 1, 174, 98, 0),
(940, 1, 177, 1, 1),
(942, 1, 177, 64, 0),
(943, 1, 177, 65, 0),
(944, 1, 177, 66, 0),
(945, 1, 177, 67, 0),
(946, 1, 177, 68, 0),
(941, 1, 177, 100, 0),
(905, 1, 179, 1, 1),
(912, 1, 179, 22, 0),
(913, 1, 179, 24, 0),
(931, 1, 179, 25, 0),
(932, 1, 179, 26, 0),
(933, 1, 179, 27, 0),
(934, 1, 179, 28, 0),
(935, 1, 179, 41, 0),
(936, 1, 179, 42, 0),
(937, 1, 179, 43, 0),
(938, 1, 179, 44, 0),
(922, 1, 179, 48, 0),
(924, 1, 179, 56, 0),
(925, 1, 179, 57, 0),
(926, 1, 179, 58, 0),
(927, 1, 179, 59, 0),
(928, 1, 179, 60, 0),
(929, 1, 179, 61, 0),
(930, 1, 179, 63, 0),
(907, 1, 179, 64, 0),
(908, 1, 179, 65, 0),
(909, 1, 179, 66, 0),
(910, 1, 179, 67, 0),
(911, 1, 179, 68, 0),
(914, 1, 179, 71, 0),
(915, 1, 179, 72, 0),
(916, 1, 179, 74, 0),
(917, 1, 179, 75, 0),
(923, 1, 179, 76, 0),
(906, 1, 179, 100, 0),
(918, 1, 179, 101, 0),
(919, 1, 179, 102, 0),
(920, 1, 179, 103, 0),
(921, 1, 179, 104, 0),
(310, 30, 167, 80, 0),
(311, 30, 167, 81, 0),
(312, 30, 167, 82, 0),
(313, 30, 167, 83, 0),
(314, 30, 167, 84, 0),
(315, 30, 167, 85, 0),
(316, 30, 167, 86, 0),
(317, 30, 167, 87, 0),
(318, 30, 167, 88, 0),
(319, 30, 167, 89, 0),
(321, 30, 167, 90, 0),
(322, 30, 167, 91, 0),
(323, 30, 167, 92, 0),
(339, 30, 167, 93, 0),
(340, 30, 167, 96, 0),
(682, 33, 83, 5, 0),
(681, 33, 83, 6, 0),
(700, 33, 83, 7, 0),
(701, 33, 83, 8, 0),
(702, 33, 83, 9, 0),
(703, 33, 83, 10, 0),
(704, 33, 83, 11, 0),
(705, 33, 83, 12, 0),
(706, 33, 83, 13, 0),
(663, 33, 83, 14, 0),
(664, 33, 83, 15, 0),
(666, 33, 83, 18, 0),
(667, 33, 83, 19, 0),
(672, 33, 83, 22, 0),
(673, 33, 83, 23, 0),
(674, 33, 83, 24, 0),
(690, 33, 83, 25, 0),
(691, 33, 83, 26, 0),
(692, 33, 83, 27, 0),
(693, 33, 83, 28, 0),
(707, 33, 83, 29, 0),
(708, 33, 83, 33, 0),
(709, 33, 83, 37, 0),
(694, 33, 83, 41, 0),
(695, 33, 83, 42, 0),
(696, 33, 83, 43, 0),
(697, 33, 83, 44, 0),
(710, 33, 83, 45, 0),
(661, 33, 83, 46, 0),
(662, 33, 83, 47, 0),
(683, 33, 83, 56, 0),
(684, 33, 83, 57, 0),
(685, 33, 83, 58, 0),
(686, 33, 83, 59, 0),
(687, 33, 83, 60, 0),
(688, 33, 83, 61, 0),
(689, 33, 83, 63, 0),
(665, 33, 83, 64, 0),
(668, 33, 83, 65, 0),
(669, 33, 83, 66, 0),
(670, 33, 83, 67, 0),
(671, 33, 83, 68, 0),
(675, 33, 83, 71, 0),
(676, 33, 83, 72, 0),
(677, 33, 83, 73, 0),
(678, 33, 83, 74, 0),
(679, 33, 83, 75, 0),
(680, 33, 83, 76, 0),
(698, 33, 83, 78, 0),
(699, 33, 83, 79, 0),
(711, 33, 83, 80, 0),
(712, 33, 83, 81, 0),
(713, 33, 83, 82, 0),
(714, 33, 83, 83, 0),
(715, 33, 83, 84, 0),
(716, 33, 83, 85, 0),
(717, 33, 83, 86, 0),
(718, 33, 83, 87, 0),
(719, 33, 83, 88, 0),
(720, 33, 83, 89, 0),
(721, 33, 83, 90, 0),
(722, 33, 83, 91, 0),
(723, 33, 83, 92, 0),
(724, 33, 83, 93, 0),
(725, 33, 83, 96, 0),
(726, 33, 83, 97, 0),
(727, 33, 83, 98, 0),
(971, 33, 172, 5, 0),
(970, 33, 172, 6, 0),
(988, 33, 172, 7, 0),
(989, 33, 172, 8, 0),
(990, 33, 172, 9, 0),
(991, 33, 172, 10, 0),
(992, 33, 172, 11, 0),
(993, 33, 172, 12, 0),
(994, 33, 172, 13, 0),
(947, 33, 172, 14, 0),
(948, 33, 172, 15, 0),
(950, 33, 172, 18, 0),
(951, 33, 172, 19, 0),
(956, 33, 172, 22, 0),
(957, 33, 172, 23, 0),
(958, 33, 172, 24, 0),
(979, 33, 172, 25, 0),
(980, 33, 172, 26, 0),
(981, 33, 172, 27, 0),
(982, 33, 172, 28, 0),
(995, 33, 172, 29, 0),
(996, 33, 172, 33, 0),
(997, 33, 172, 37, 0),
(983, 33, 172, 41, 0),
(984, 33, 172, 42, 0),
(985, 33, 172, 43, 0),
(986, 33, 172, 44, 0),
(998, 33, 172, 45, 0),
(866, 33, 172, 46, 0),
(867, 33, 172, 47, 0),
(968, 33, 172, 48, 0),
(972, 33, 172, 56, 0),
(973, 33, 172, 57, 0),
(974, 33, 172, 58, 0),
(975, 33, 172, 59, 0),
(976, 33, 172, 60, 0),
(977, 33, 172, 61, 0),
(978, 33, 172, 63, 0),
(949, 33, 172, 64, 0),
(952, 33, 172, 65, 0),
(953, 33, 172, 66, 0),
(954, 33, 172, 67, 0),
(955, 33, 172, 68, 0),
(959, 33, 172, 71, 0),
(960, 33, 172, 72, 0),
(961, 33, 172, 73, 0),
(962, 33, 172, 74, 0),
(963, 33, 172, 75, 0),
(969, 33, 172, 76, 0),
(999, 33, 172, 80, 0),
(1000, 33, 172, 81, 0),
(1001, 33, 172, 82, 0),
(1002, 33, 172, 83, 0),
(1003, 33, 172, 84, 0),
(1004, 33, 172, 85, 0),
(1005, 33, 172, 86, 0),
(1006, 33, 172, 87, 0),
(1007, 33, 172, 88, 0),
(1008, 33, 172, 89, 0),
(1009, 33, 172, 90, 0),
(1010, 33, 172, 91, 0),
(1011, 33, 172, 92, 0),
(1012, 33, 172, 93, 0),
(1013, 33, 172, 96, 0),
(1014, 33, 172, 97, 0),
(1015, 33, 172, 98, 0),
(1016, 33, 172, 99, 0),
(868, 33, 172, 100, 0),
(964, 33, 172, 101, 0),
(965, 33, 172, 102, 0),
(966, 33, 172, 103, 0),
(967, 33, 172, 104, 0);

-- --------------------------------------------------------

--
-- 表的结构 `auth_element`
--

CREATE TABLE IF NOT EXISTS `auth_element` (
  `id` int(11) unsigned NOT NULL COMMENT '原子ID',
  `controller` varchar(50) NOT NULL DEFAULT '' COMMENT '控制器原子',
  `action` varchar(50) NOT NULL DEFAULT '' COMMENT '动作原子',
  `action_name` varchar(50) NOT NULL DEFAULT '' COMMENT '原子名',
  `is_hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统功能'
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8 COMMENT='权限原子表';

--
-- 转存表中的数据 `auth_element`
--

INSERT INTO `auth_element` (`id`, `controller`, `action`, `action_name`, `is_hide`, `is_system`) VALUES
(2, 'index', 'index', '欢迎页', 0, 0),
(4, 'auth', 'role', '角色列表', 0, 0),
(5, 'admin', 'add', '添加员工', 1, 0),
(6, 'admin', 'index', '员工列表', 0, 1),
(7, 'task', 'index', '任务列表', 0, 0),
(8, 'task', 'new', '添加任务', 1, 0),
(9, 'task', 'newbranch', '分拆', 1, 0),
(10, 'task', 'edit', '修改任务', 1, 0),
(11, 'task', 'save', '保存任务', 1, 0),
(12, 'task', 'detail', '详情', 1, 0),
(13, 'task', 'changetask', '状态转换', 1, 0),
(14, 'module', 'index', '模块管理', 0, 0),
(15, 'module', 'add', '添加模块', 1, 0),
(16, 'element', 'index', '原子列表', 0, 0),
(17, 'element', 'add', '添加原子', 1, 0),
(18, 'role', 'index', '角色列表', 0, 0),
(19, 'role', 'add', '添加角色', 1, 0),
(20, 'assign', 'index', '授权列表', 0, 0),
(21, 'assign', 'add', '添加授权', 1, 0),
(22, 'media_posts', 'add', '添加媒资', 1, 0),
(23, 'media_posts', 'index', '媒资列表', 0, 0),
(24, 'media_posts', 'edit', '编辑媒资', 1, 0),
(25, 'channel', 'create', '创建频道', 1, 0),
(26, 'channel', 'index', '频道信息', 0, 0),
(27, 'channel', 'modify', '修改频道', 1, 0),
(28, 'channel', 'delete', '删除频道', 1, 0),
(29, 'stations', 'index', '电台列表', 0, 0),
(30, 'stations', 'create', '电台创建', 0, 0),
(31, 'stations', 'delete', '电台删除', 0, 0),
(32, 'stations', 'modify', '电台修改', 0, 0),
(33, 'stations_epg', 'index', '节目流列表', 0, 0),
(34, 'stations_epg', 'modify', '节目流修改', 0, 0),
(35, 'stations_epg', 'create', '节目流创建', 0, 0),
(36, 'stations_epg', 'delete', '节目流删除', 0, 0),
(37, 'stations_program', 'index', '节目单列表', 0, 0),
(38, 'stations_program', 'create', '节目单创建', 0, 0),
(39, 'stations_program', 'modify', '节目单修改', 0, 0),
(40, 'stations_program', 'delete', '节目单删除', 0, 0),
(41, 'site', 'index', '站点列表', 0, 0),
(42, 'site', 'add', '添加站点', 1, 0),
(43, 'site', 'udp', '修改站点', 1, 0),
(44, 'site', 'del', '删除站点', 1, 0),
(45, 'comment', 'index', '评论列表', 0, 0),
(46, 'personal_data', 'index', '个人信息', 0, 0),
(47, 'personal_data', 'modify', '个人资料修改', 1, 0),
(48, 'applist', 'index', 'APP列表', 0, 0),
(49, 'applist', 'add', '添加APP', 1, 0),
(50, 'applist', 'udp', '修改APP', 1, 0),
(51, 'applist', 'del', '删除APP', 1, 0),
(52, 'applist', 'version', '版本列表', 0, 0),
(53, 'applist', 'addver', '添加版本', 1, 0),
(54, 'applist', 'udpver', '修改版本', 1, 0),
(55, 'applist', 'delver', '删除版本', 1, 0),
(56, 'department', 'index', '部门管理', 0, 0),
(57, 'department', 'delete', '添加部门', 1, 0),
(58, 'department', 'edit', '部门修改', 1, 0),
(59, 'duty', 'index', '岗位管理', 0, 0),
(60, 'duty', 'add', '添加岗位', 1, 0),
(61, 'duty', 'edit', '岗位修改', 1, 0),
(63, 'admin', 'edit', '员工修改', 1, 0),
(64, 'module', 'edit', '模块修改', 1, 0),
(65, 'role', 'edit', '角色修改', 1, 0),
(66, 'module', 'elementlist', '原子列表', 1, 0),
(67, 'module', 'elementadd', '添加原子', 1, 0),
(68, 'module', 'elementedit', '原子修改', 1, 0),
(69, 'stations', 'add', '添加电台', 1, 0),
(70, 'stations', 'edit', '电台修改', 1, 0),
(71, 'media_albums', 'add', '添加相册', 1, 0),
(72, 'media_albums', 'edit', '编辑相册', 1, 0),
(73, 'media_albums', 'index', '相册列表', 0, 0),
(74, 'category', 'index', '栏目管理', 0, 0),
(75, 'category', 'add', '添加栏目', 1, 0),
(76, 'users', 'index', '用户列表', 0, 0),
(79, 'channel', 'addmaster', '管理员列表', 1, 0),
(80, 'lotteries', 'add', '添加抽奖', 1, 0),
(81, 'lotteries', 'edit', '抽奖修改', 1, 0),
(82, 'lotteries', 'index', '活动列表', 0, 0),
(83, 'lottery_channels', 'add', '添加频道', 1, 0),
(84, 'lottery_channels', 'edit', '编辑频道', 1, 0),
(85, 'lottery_channels', 'index', '频道列表', 0, 0),
(86, 'lottery_channels', 'delete', '删除频道', 1, 0),
(87, 'lottery_prizes', 'index', '奖品列表', 0, 0),
(88, 'lottery_prizes', 'add', '添加奖品', 1, 0),
(89, 'lottery_prizes', 'edit', '修改奖品', 1, 0),
(90, 'lottery_prizes', 'delete', '删除奖品', 1, 0),
(91, 'lottery_winnings', 'index', '中奖列表', 0, 0),
(92, 'lottery_winnings', 'edit', '修改中奖', 1, 0),
(93, 'lotteries', 'delete', '删除抽奖活动', 1, 0),
(96, 'lottery_winnings', 'delete', '删除奖品', 1, 0),
(97, 'lottery_cache', 'index', '缓存管理', 0, 0),
(98, 'lottery_cache', 'clearcdn', '清理CDN', 1, 0),
(99, 'lottery_cache', 'clearlist', '刷新活动列表', 1, 0),
(100, 'switching_channel', 'index', '角色切换', 0, 0),
(101, 'media_albums', 'upload', '图片上传', 1, 0),
(102, 'media_albums', 'tmpupload', '临时图片上传', 1, 0),
(103, 'media_albums', 'removeimage', '删除相册图片', 1, 0),
(104, 'media_albums', 'removetmpimage', '删除临时文件', 1, 0),
(105, 'media_posts', ' listmedia', '媒资引用', 1, 0),
(108, 'publish', 'index', '发布列表', 0, 0),
(109, 'media_editor', 'do', '媒体编辑器操作', 1, 0),
(110, 'salary', 'index', '个人薪水', 1, 0),
(111, 'salary', 'modify', '薪水修改', 1, 0),
(112, 'salary', 'delete', '薪水删除', 1, 0),
(113, 'salary', 'add', '薪水增加', 1, 0),
(114, 'asset', 'index', '个人资产', 1, 0),
(115, 'asset', 'add', '增加资产', 1, 0),
(116, 'asset', 'modify', '修改资产', 1, 0),
(117, 'asset', 'delete', '删除资产', 1, 0),
(118, 'advert_space', 'index', '广告位列表', 0, 0),
(119, 'advert_space', 'add', '新增版位', 1, 0),
(120, 'advert_space', 'edit', '编辑版位', 1, 0),
(121, 'advert', 'list', '广告列表', 1, 0),
(122, 'advert', 'add', '新增广告', 1, 0),
(123, 'advert', 'edit', '编辑广告', 1, 0),
(124, 'advert', 'delete', '删除广告', 1, 0),
(125, 'advert_space', 'lock', '审核版位', 1, 0),
(126, 'advert', 'lock', '审核广告', 1, 0),
(127, 'advert_space', 'spacepreview', '广告预览', 1, 0),
(128, 'advert_space', 'createjs', '更新js', 1, 0),
(129, 'advert', 'listorder', '广告排序', 1, 0),
(133, 'media_signals', 'index', '直播信号源列表', 0, 0),
(134, 'media_videos', 'index', '视频列表', 0, 0),
(135, 'media_video_collections', 'index', '视频集列表', 0, 0),
(136, 'media_video_collections', 'add', '添加视频集', 1, 0),
(137, 'media_video_collections', 'edit', '编辑视频集', 1, 0),
(139, 'message_center', 'index', '消息中心', 0, 0),
(141, 'publish', 'top', '媒资置顶', 1, 0),
(142, 'publish', 'approve', '媒资审核', 1, 0),
(143, 'publish', 'sort', '媒资排序', 1, 0),
(144, 'tpl', 'list', '模板管理', 0, 0),
(145, 'tpl', 'add', '新增模板', 1, 0),
(146, 'tpl', 'edit', '编辑模板', 1, 0),
(147, 'domains', 'index', '域名列表', 0, 0),
(148, 'domains', 'add', '新增域名', 1, 0),
(149, 'domains', 'edit', '编辑域名', 1, 0),
(150, 'tpl', 'delete', '删除模板', 1, 0),
(151, 'setting', 'index', '系统设置列表', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `auth_module`
--

CREATE TABLE IF NOT EXISTS `auth_module` (
  `id` int(11) unsigned NOT NULL COMMENT '模块ID',
  `name` varchar(20) NOT NULL COMMENT '模块名',
  `channel_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '频道ID/0为系统/其他为私有',
  `child` text NOT NULL COMMENT '原子集',
  `css` varchar(20) NOT NULL COMMENT '样式',
  `sort` smallint(2) unsigned NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='权限模块表';

--
-- 转存表中的数据 `auth_module`
--

INSERT INTO `auth_module` (`id`, `name`, `channel_id`, `child`, `css`, `sort`) VALUES
(1, '发布管理', 0, '108', 'icon-globe', 0),
(2, '静态媒资', 0, '23,73,134,135,118,48,133', 'icon-grid', 0),
(3, '动态媒资', 0, '45,87', 'icon-bar-chart', 0),
(4, '互动管理', 0, '91', 'icon-support', 0),
(5, '会员管理', 0, '76', ' icon-users', 0),
(6, '员工管理', 0, '6', 'icon-user', 0),
(7, '管理中心', 0, '46,100,26,74,144,56,59,14,6', 'icon-settings', 0);

-- --------------------------------------------------------

--
-- 表的结构 `auth_role`
--

CREATE TABLE IF NOT EXISTS `auth_role` (
  `id` int(11) unsigned NOT NULL COMMENT '角色ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `name` varchar(30) NOT NULL COMMENT '角色名',
  `element` text NOT NULL COMMENT '原子集'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='角色表';

--
-- 转存表中的数据 `auth_role`
--

INSERT INTO `auth_role` (`id`, `channel_id`, `name`, `element`) VALUES
(1, 1, '系统管理员', '46,47,14,15,18,19,23,73,6,5,7,8'),
(2, 1, 'test', '0'),
(3, 1, 'test one', '2,46,65,15,64,65,67,68,23,73,7,8'),
(4, 30, 'test two', '65,15,19,23,73,75,7,8'),
(5, 0, 'test three', '15,64,18,19,65,66,67,68,23,76,6,61,63,25,26'),
(6, 0, '阿萨德', '46,23,73'),
(7, 31, '想不出 大法师', '0');

-- --------------------------------------------------------

--
-- 表的结构 `baoliao`
--

CREATE TABLE IF NOT EXISTS `baoliao` (
  `id` int(11) unsigned NOT NULL,
  `channel_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `username` varchar(30) NOT NULL,
  `create_at` int(11) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `client` tinyint(1) NOT NULL,
  `ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `baoliao_reply`
--

CREATE TABLE IF NOT EXISTS `baoliao_reply` (
  `id` int(10) unsigned NOT NULL,
  `baoliao_id` int(10) unsigned NOT NULL,
  `reply` text NOT NULL COMMENT '回复正文',
  `author_id` int(10) unsigned NOT NULL COMMENT '作者id',
  `author_name` varchar(30) NOT NULL COMMENT '作者姓名',
  `create_at` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `board`
--

CREATE TABLE IF NOT EXISTS `board` (
  `id` int(10) unsigned NOT NULL COMMENT '留言板ID',
  `time` int(11) NOT NULL COMMENT '创建时间',
  `contents` text NOT NULL COMMENT '内容',
  `user_group` text NOT NULL COMMENT '用户组'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='留言板';

--
-- 转存表中的数据 `board`
--

INSERT INTO `board` (`id`, `time`, `contents`, `user_group`) VALUES
(1, 123, '[{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":"12","admin_id":"87","message":"\\u597d\\u53ef\\u6015"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":"213","admin_id":"172","message":"\\u597d\\u53ef\\u6015"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1447751720,"admin_id":"87","message":"1"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/22\\/276cbc771c0fabb98a53c3bfb93e65e2.jpg","time":1447809811,"admin_id":"172","message":"\\u6d4b\\u8bd5"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/22\\/276cbc771c0fabb98a53c3bfb93e65e2.jpg","time":1447809843,"admin_id":"172","message":"\\u8bd5\\u8bd5"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1447817665,"admin_id":"87","message":"87\\u53f7\\u6d4b\\u8bd5"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1447918222,"admin_id":"87","message":"1080p"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1447918343,"admin_id":"87","message":"\\u540c\\u6b65\\u6d4b\\u8bd5"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1447918404,"admin_id":"87","message":"\\u5e94\\u8be5\\u5b8c\\u6210\\u4e86"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1447918689,"admin_id":"87","message":"\\u6362\\u79cd\\u65b9\\u5f0f"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1447919374,"admin_id":"87","message":"\\u5c11\\u65f6\\u8bf5\\u8bd7\\u4e66"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1447919469,"admin_id":"87","message":"\\u5730\\u5bf9\\u5730\\u5bfc\\u5f39"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1447919873,"admin_id":"87","message":"\\u543e\\u95ee\\u65e0\\u4e3a\\u8c13"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1447919947,"admin_id":"87","message":"li\\u5df2\\u5b8c\\u6210"},{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/signuphdcztv.oss-cn-hangzhou.aliyuncs.com\\/cztv\\/1\\/avatar_file_9b137611d5e39c46ba74011f7cc5198d.jpg","time":1447983323,"admin_id":"85","message":"\\u6d4b\\u8bd5\\u5b8c\\u6210\\uff01"},{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/signuphdcztv.oss-cn-hangzhou.aliyuncs.com\\/cztv\\/1\\/avatar_file_9b137611d5e39c46ba74011f7cc5198d.jpg","time":1447986134,"admin_id":"85","message":"452632154"},{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/signuphdcztv.oss-cn-hangzhou.aliyuncs.com\\/cztv\\/1\\/avatar_file_9b137611d5e39c46ba74011f7cc5198d.jpg","time":1447986140,"admin_id":"85","message":"77884254251"},{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/signuphdcztv.oss-cn-hangzhou.aliyuncs.com\\/cztv\\/1\\/avatar_file_9b137611d5e39c46ba74011f7cc5198d.jpg","time":1447987338,"admin_id":"85","message":"\\u6362\\u8bdd\\u8d39\\u5361\\u5149\\u68cd\\u4e86\\u4e86\\u4e48\\u4e48\\u4e48\\uff0c\\u7ecf\\u8d39\\u5357\\u7b19\\u59d1\\u5a18\\u90a3\\u4e48\\u62b9\\u80f8\\u5973\\u795e\\u6ca1\\u660e\\u767d\\u660e\\u767d\\u7684\\u3002"},{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/signuphdcztv.oss-cn-hangzhou.aliyuncs.com\\/cztv\\/1\\/avatar_file_9b137611d5e39c46ba74011f7cc5198d.jpg","time":1448001044,"admin_id":"85","message":"\\u9504\\u79be\\u65e5\\u5f53\\u5348\\uff0c\\u6c57\\u6ef4\\u79be\\u4e0b\\u571f\\uff0c\\u8c01\\u77e5\\u76d8\\u4e2d\\u9910\\uff0c\\u7c92\\u7c92\\u7686\\u8f9b\\u82e6\\uff01"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1448010324,"admin_id":"87","message":"\\u521b\\u5efa\\u6d4b\\u8bd51\\uff1a\\u662f\\u5426\\u6dfb\\u52a0"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1448010378,"admin_id":"87","message":"\\u6d4b\\u8bd52\\uff1a\\u662f\\u5426\\u53d8\\u4e3a\\u5df2\\u8bfb"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1448269889,"admin_id":"87","message":"resd"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1448269893,"admin_id":"87","message":"\\u7684\\u6211\\u5f3a\\u5927\\u7684"}]', '85,87'),
(2, 11111, '[{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":"12","admin_id":"87","message":"\\u597d\\u53ef\\u6015"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":"213","admin_id":"172","message":"\\u597d\\u53ef\\u6015"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1447751720,"admin_id":"87","message":"1"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/22\\/276cbc771c0fabb98a53c3bfb93e65e2.jpg","time":1447809811,"admin_id":"172","message":"\\u6d4b\\u8bd5"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/22\\/276cbc771c0fabb98a53c3bfb93e65e2.jpg","time":1447809843,"admin_id":"172","message":"\\u8bd5\\u8bd5"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1447817665,"admin_id":"87","message":"87\\u53f7\\u6d4b\\u8bd5"},{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/signuphdcztv.oss-cn-hangzhou.aliyuncs.com\\/cztv\\/1\\/avatar_file_9b137611d5e39c46ba74011f7cc5198d.jpg","time":1447920079,"admin_id":"85","message":"4563322"},{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/signuphdcztv.oss-cn-hangzhou.aliyuncs.com\\/cztv\\/1\\/avatar_file_9b137611d5e39c46ba74011f7cc5198d.jpg","time":1448001882,"admin_id":"85","message":"5632145"},{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/signuphdcztv.oss-cn-hangzhou.aliyuncs.com\\/cztv\\/1\\/avatar_file_9b137611d5e39c46ba74011f7cc5198d.jpg","time":1448001886,"admin_id":"85","message":"2632145212"},{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/signuphdcztv.oss-cn-hangzhou.aliyuncs.com\\/cztv\\/1\\/avatar_file_9b137611d5e39c46ba74011f7cc5198d.jpg","time":1448001891,"admin_id":"85","message":"4884112523"},{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/signuphdcztv.oss-cn-hangzhou.aliyuncs.com\\/cztv\\/1\\/avatar_file_9b137611d5e39c46ba74011f7cc5198d.jpg","time":1448001896,"admin_id":"85","message":"896321521"},{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/signuphdcztv.oss-cn-hangzhou.aliyuncs.com\\/cztv\\/1\\/avatar_file_9b137611d5e39c46ba74011f7cc5198d.jpg","time":1448002076,"admin_id":"85","message":"87425631"}]', '85,87,172'),
(5, 1123, '[{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":"12","admin_id":"87","message":"\\u597d\\u53ef\\u6015"},{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/11\\/23\\/bfd1649e7f5de5523894f039aac19c83.gif","time":1448247074,"admin_id":"85","message":"\\u6015\\u5565\\u5440\\uff1f"}]', '85'),
(7, 1448011221, '[{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1448011221,"admin_id":"87","message":"\\u6d4b\\u8bd5\\u521b\\u5efa"},{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1448269999,"admin_id":"87","message":"\\u5927\\u5e08\\u5927\\u5e08"}]', '83,87'),
(8, 1448011324, '{"name":"\\u5f20\\u4ea6\\u5f1b","avatar":"http:\\/\\/cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com\\/avatars\\/2015\\/10\\/23\\/ba3afb7bc9b187ddf138c99eb1792dc1.jpg","time":1448011324,"admin_id":"87","message":"\\u9875\\u9762\\u4e0a\\u6d4b\\u8bd5\\u4e00\\u6b21"}', '87,90'),
(9, 1448012488, '{"name":"\\u65bc\\u6d9b","avatar":"http:\\/\\/signuphdcztv.oss-cn-hangzhou.aliyuncs.com\\/cztv\\/1\\/avatar_file_9b137611d5e39c46ba74011f7cc5198d.jpg","time":1448012488,"admin_id":"85","message":"\\u5927\\u5bb6\\u4e00\\u8d77\\u6765\\uff01"}', '85,87,90');

-- --------------------------------------------------------

--
-- 表的结构 `board_status`
--

CREATE TABLE IF NOT EXISTS `board_status` (
  `id` int(10) unsigned NOT NULL COMMENT '状态ID',
  `board_id` int(10) unsigned NOT NULL COMMENT '留言板ID',
  `admin_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `status` tinyint(4) NOT NULL COMMENT '状态'
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='留言板状态';

--
-- 转存表中的数据 `board_status`
--

INSERT INTO `board_status` (`id`, `board_id`, `admin_id`, `status`) VALUES
(1, 1, 87, 2),
(2, 2, 87, 2),
(4, 2, 172, 1),
(5, 2, 85, 2),
(6, 1, 85, 2),
(8, 5, 85, 2),
(9, 7, 83, 2),
(10, 7, 87, 2),
(11, 8, 87, 1),
(12, 8, 90, 1),
(13, 9, 85, 2),
(14, 9, 87, 2),
(15, 9, 90, 1);

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) unsigned NOT NULL COMMENT '栏目ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `name` varchar(50) NOT NULL COMMENT '栏目名',
  `code` varchar(50) NOT NULL COMMENT '别名代码',
  `father_id` int(10) unsigned NOT NULL COMMENT '父ID',
  `terminal` enum('web','app','wap','wechat') NOT NULL COMMENT '终端类型'
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='栏目表';

--
-- 转存表中的数据 `category`
--

INSERT INTO `category` (`id`, `channel_id`, `name`, `code`, `father_id`, `terminal`) VALUES
(1, 1, '新闻', '1', 0, 'web'),
(2, 1, '国内', '2', 1, 'web'),
(3, 1, '国际', '3', 1, 'web'),
(4, 1, '社会', '4', 1, 'web'),
(5, 2, '新闻', '5', 0, 'web'),
(14, 0, '防晒霜', '14', 0, 'web'),
(16, 0, 'WQADSF ', '16', 13, 'web'),
(19, 0, 'a', '19', 0, 'web'),
(21, 0, '1', '21', 14, 'web'),
(23, 1, '安卓国内', '23', 1, 'web'),
(24, 1, 'app新闻', '24', 0, 'app'),
(25, 1, 'wap新闻', '25', 0, 'wap'),
(26, 1, '微信新闻', '26', 0, 'wechat'),
(33, 1, '国内APP新闻', '33', 24, 'app'),
(34, 1, '国际APP新闻', '34', 24, 'app'),
(35, 1, '社会APP新闻', '35', 24, 'app'),
(36, 1, '伊朗新闻', '36', 34, 'app'),
(37, 1, '阿富汗新闻', '37', 34, 'app'),
(38, 0, '撒旦', '38', 0, 'web');

-- --------------------------------------------------------

--
-- 表的结构 `category_auth`
--

CREATE TABLE IF NOT EXISTS `category_auth` (
  `id` int(11) unsigned NOT NULL COMMENT '栏目授权ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `category_id` int(11) unsigned NOT NULL COMMENT '栏目ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栏目权限表';

-- --------------------------------------------------------

--
-- 表的结构 `category_data`
--

CREATE TABLE IF NOT EXISTS `category_data` (
  `id` int(11) unsigned NOT NULL COMMENT '关联ID',
  `data_id` int(11) unsigned NOT NULL COMMENT '容器ID',
  `category_id` int(11) unsigned NOT NULL COMMENT '栏目ID',
  `sort` smallint(2) unsigned NOT NULL DEFAULT '0' COMMENT '基于栏目的排序',
  `weight` smallint(2) unsigned NOT NULL DEFAULT '0' COMMENT '权重，锁贴位置',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年'
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8 COMMENT='栏目容器关联表'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;

--
-- 转存表中的数据 `category_data`
--

INSERT INTO `category_data` (`id`, `data_id`, `category_id`, `sort`, `weight`, `partition_by`) VALUES
(1, 19, 1, 0, 0, 2015),
(2, 19, 2, 0, 0, 2015),
(3, 19, 3, 0, 0, 2015),
(15, 19, 23, 0, 0, 2015),
(60, 30, 2, 2, 0, 2015),
(61, 30, 33, 0, 0, 2015),
(83, 29, 2, 0, 0, 2015),
(85, 29, 23, 0, 0, 2015),
(86, 29, 36, 0, 0, 2015),
(96, 31, 3, 1, 0, 2015),
(101, 4, 2, 1, 1, 2015),
(102, 4, 33, 0, 0, 2015),
(107, 3, 21, 0, 0, 2015),
(108, 3, 19, 0, 0, 2015),
(109, 3, 38, 0, 0, 2015);

-- --------------------------------------------------------

--
-- 表的结构 `channel`
--

CREATE TABLE IF NOT EXISTS `channel` (
  `id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `name` varchar(255) NOT NULL COMMENT '频道名',
  `tag` varchar(10) NOT NULL COMMENT '频道短标识',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `channel_logo` varchar(255) NOT NULL COMMENT '频道logo',
  `channel_url` varchar(255) NOT NULL COMMENT '频道URL',
  `channel_instr` varchar(255) NOT NULL COMMENT '频道说明'
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='频道表';

--
-- 转存表中的数据 `channel`
--

INSERT INTO `channel` (`id`, `name`, `tag`, `status`, `channel_logo`, `channel_url`, `channel_instr`) VALUES
(1, 'cztv2', 'cztv2', 0, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/09/1878763a17b99078f8a7d26f92ad6a62.jpg', 'www.cztv.com', '简介2'),
(30, '新蓝TV', 'tvcztv', 0, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/09/435fa29875c23610872071f8e349e704.jpg', 'tv.cztv.com', '新蓝TV'),
(31, 'ceshi', 'ceshi', 0, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/12/9fc1446a9f399754f86164170e18d56e.jpg', 'tv.cztv.com', 'ceshi'),
(32, '新蓝TV', 'tv2', 0, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/13/2a01a81ae519a2233dcaf285b5e4d276.png', 'tv.cztv.com', 'tv.cztv.com'),
(33, '专属通道', 'zhuanshu', 1, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/21/2f29c2634dab709107194e64abd00df1.jpg', 'http://www.cztv.com', '测试专属通道');

-- --------------------------------------------------------

--
-- 表的结构 `channel_share`
--

CREATE TABLE IF NOT EXISTS `channel_share` (
  `id` int(11) unsigned NOT NULL COMMENT '认证ID',
  `origin_id` int(11) unsigned NOT NULL COMMENT '原始频道ID',
  `auth_id` int(1) unsigned NOT NULL COMMENT '授权频道ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='频道数据共享认证表';

-- --------------------------------------------------------

--
-- 表的结构 `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) unsigned NOT NULL,
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `data_id` int(11) unsigned NOT NULL COMMENT '容器ID',
  `father_id` int(11) unsigned NOT NULL COMMENT '父ID',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `create_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `likes` int(10) unsigned DEFAULT NULL,
  `down` int(11) unsigned NOT NULL COMMENT '底',
  `domain` varchar(30) NOT NULL COMMENT '域名',
  `client` tinyint(1) unsigned NOT NULL COMMENT '终端',
  `ip` varchar(15) NOT NULL COMMENT 'IP',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='评论表'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;

--
-- 转存表中的数据 `comment`
--

INSERT INTO `comment` (`id`, `channel_id`, `user_id`, `username`, `data_id`, `father_id`, `content`, `create_at`, `status`, `likes`, `down`, `domain`, `client`, `ip`, `partition_by`) VALUES
(1, 1, 1, 'aa', 1, 2, 'aaaaa', 22222, 1, 22, 22, 'wwww', 1, 'aaaaaa', 2015);

-- --------------------------------------------------------

--
-- 表的结构 `data`
--

CREATE TABLE IF NOT EXISTS `data` (
  `id` int(11) unsigned NOT NULL,
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `type` enum('news','album','video','topic','live','news_collection','album_collection','video_collection') NOT NULL DEFAULT 'news' COMMENT '类型',
  `source_id` int(11) unsigned NOT NULL COMMENT '源ID',
  `country_id` int(8) unsigned NOT NULL COMMENT '国家ID',
  `province_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '省ID',
  `city_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '市ID',
  `county_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '县区ID',
  `village_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '村ID',
  `title` varchar(255) NOT NULL COMMENT '源标题',
  `intro` varchar(255) DEFAULT NULL COMMENT '源简介',
  `thumb` varchar(255) DEFAULT NULL COMMENT '源缩略图',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '修改时间',
  `author_id` int(11) unsigned NOT NULL COMMENT '作者ID',
  `author_name` varchar(30) NOT NULL COMMENT '作者名字',
  `hits` int(11) unsigned NOT NULL COMMENT '点击',
  `comments` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论数量',
  `data_data` text NOT NULL COMMENT '外部资源ID',
  `status` tinyint(1) unsigned NOT NULL COMMENT '发布状态',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '年分区'
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COMMENT='容器表'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;

--
-- 转存表中的数据 `data`
--

INSERT INTO `data` (`id`, `channel_id`, `type`, `source_id`, `country_id`, `province_id`, `city_id`, `county_id`, `village_id`, `title`, `intro`, `thumb`, `created_at`, `updated_at`, `author_id`, `author_name`, `hits`, `comments`, `data_data`, `status`, `partition_by`) VALUES
(3, 0, 'news', 5, 0, 0, 0, 0, 0, 'commit ''''''''', 'commit ''''''''', 'thumbnails/2015/09/29/963ea7a1ad1994a91aa5361feaedf575.png', 1442910956, 1446539067, 1, '张三', 0, 0, '[39,41]', 1, 2015),
(4, 1, 'news', 6, 0, 0, 0, 0, 0, '*必填*必填*必填', ' *必填', 'thumbnails/2015/09/29/0e3fd418df5a15d9284e527947893fe5.png', 1442911058, 1445397013, 1, '张三', 0, 0, '{}', 1, 2015),
(5, 0, 'news', 7, 0, 0, 0, 0, 0, 'me test', 'me test', 'thumbnails/2015/09/29/e0c2a15883111aff1c3d3095b3a0d94d.png', 1443000443, 1446797584, 1, '张三', 0, 0, '[]', 1, 2015),
(6, 0, 'news', 8, 0, 0, 0, 0, 0, '3213123123', '123123123123', 'thumbnails/2015/09/29/5aad82bfd2dca7725a1c8ef168fa699d.png', 1443421481, 1443498004, 111, '章海泉', 0, 0, '{}', 1, 2015),
(7, 0, 'news', 9, 0, 0, 0, 0, 0, '发个新闻稿来个测试看看可以不可以', '发个新闻稿来个测试看看可以不可以', 'thumbnails/2015/09/29/4618baf67e950bd8479bee953f1f623b.jpg', 1443425000, 1446539010, 111, '章海泉', 0, 0, '[9]', 1, 2015),
(8, 0, 'news', 10, 0, 0, 0, 0, 0, '11111', '111', 'thumbnails/2015/09/28/c84b2be6513ed81702e11824229aa749.jpg', 1443428162, 1446099115, 111, '章海泉', 0, 0, '{}', 1, 2015),
(9, 0, 'album', 5, 0, 0, 0, 0, 0, '西湖印象', '相册相册相册相册相册相册相册', 'thumbnails/2015/10/19/ff3dc9cb739fc7c7834df1c7db605623.jpg', 1443513663, 1447926833, 84, '张海盼', 0, 0, '{}', 1, 2015),
(11, 0, 'news', 12, 0, 0, 0, 0, 0, '1,2,3', '1,2,31,2,31,2,31,2,31,2,3', 'thumbnails/2015/10/08/3e21e160a85c06002e56cccc8712f543.jpg', 1444291115, 1444291115, 111, '章海泉', 0, 0, '{}', 1, 2015),
(19, 0, 'news', 20, 0, 0, 0, 0, 0, 'category_ids', 'category_ids category_ids category_ids category_idscategory_ids', 'thumbnails/2015/10/08/febd882beecfa5d317440d973836f5d5.png', 1444292061, 1447126030, 111, '章海泉', 0, 0, '[]', 1, 2015),
(20, 1, 'news', 21, 0, 0, 0, 0, 0, '1,2,3,4,5,6', '1,2,3,4,5,6', 'thumbnails/2015/10/09/d55b08aed0f1f38d9fa99d59acd4db37.jpg', 1444353633, 1444353633, 111, '章海泉', 0, 0, '{}', 1, 2015),
(29, 1, 'news', 30, 0, 0, 0, 0, 0, '123123', '123', 'thumbnails/2015/09/29/0e3fd418df5a15d9284e527947893fe5.png', 1444360927, 1444360927, 111, '章海泉', 0, 0, '{}', 1, 2015),
(30, 1, 'news', 31, 0, 0, 0, 0, 0, 'test111', 'test', 'thumbnails/2015/10/09/a6e2f310a831dbb0b9a8ebd9134bd311.png', 1444383639, 1444438277, 111, '章海泉', 0, 0, '{}', 1, 2015),
(31, 1, 'news', 32, 0, 0, 0, 0, 0, '浙江新闻', '中央委员会中的“老资格”们\r\n2\r\n\r\n　　1人6次出席五中全', 'thumbnails/2015/10/28/ae1f22a56b2e7722fb02388adcfe956e.png', 1445999926, 1445999926, 169, '章数问', 0, 0, '{}', 1, 2015),
(34, 0, 'album', 8, 0, 0, 0, 0, 0, '乐之章2', '乐之章', 'thumbnails/2015/10/28/680d2aba2689745f79fb7bfa3c0f6fd0.png', 1446014282, 1446450980, 81, '薛炜', 0, 0, '{}', 1, 2015),
(35, 0, 'album', 9, 0, 0, 0, 0, 0, '乐之华', '乐之章', 'thumbnails/2015/10/28/1f0ef7442f609b846b0e2e2b15130919.png', 1446014335, 1446014524, 81, '薛炜', 0, 0, '{}', 1, 2015),
(36, 0, 'album', 10, 0, 0, 0, 0, 0, '乐之章', '乐之章', 'thumbnails/2015/10/28/eb1188325f77e3cde1d2b6e2c92bef1c.png', 1446014361, 1446914361, 81, '薛炜', 0, 0, '{}', 1, 2015),
(37, 0, 'album', 11, 0, 0, 0, 0, 0, '伊丽莎白二世', '伊丽莎白二世', 'thumbnails/2015/10/28/bebe03c92ebc92c5c6dad7c87a03a922.jpg', 1446014710, 1446014710, 81, '薛炜', 0, 0, '{}', 1, 2015),
(38, 0, 'album', 12, 0, 0, 0, 0, 0, '暖暖游世界摄影大赛', '暖暖游世界摄影大赛', 'thumbnails/2015/10/28/2329d960145ae4bf4edbf84c7a6ed903.png', 1446017064, 1446017064, 81, '薛炜', 0, 0, '{}', 1, 2015),
(39, 0, 'album', 13, 0, 0, 0, 0, 0, '真心话大冒险 - 炫迈', '真心话大冒险真心话大冒险', 'thumbnails/2015/10/28/e34008569bd77c36329768bb26e50094.png', 1446017454, 1446017885, 81, '薛炜', 0, 0, '{}', 1, 2015),
(41, 0, 'album', 15, 0, 0, 0, 0, 0, '真心话大冒险', '真心话大冒险真心话大冒险', 'thumbnails/2015/10/28/e5ef966230ad39ad0ae11b7c4ee55509.png', 1446017809, 1446024846, 81, '薛炜', 0, 0, '{}', 1, 2015),
(43, 0, 'news', 34, 0, 0, 0, 0, 0, '好好好好好好好', '好好好, 好好好!好好好, 好好好!好好好, 好好好!好好好, 好好好!好好好, 好好好!', 'thumbnails/2015/10/30/e727a2233046feaf1d5091229d4d868d.jpg', 1446194316, 1446194316, 81, '薛炜', 0, 0, '[9,34]', 1, 2015),
(44, 0, 'album', 16, 0, 0, 0, 0, 0, '三人行必有我师', '拉拉拉拉拉拉拉拉拉...', 'thumbnails/2015/11/02/c4366bde320b99f8987ee22f48379072.jpg', 1446453439, 1446453439, 81, '薛炜', 0, 0, '[]', 1, 2015),
(45, 0, 'news', 35, 0, 0, 0, 0, 0, '测试文章上传', '测试文章上传测试文章上传测试文章上传测试文章上传测试文章上传', 'thumbnails/2015/11/03/74b4bedc7d805b09793bb218da54a81a.png', 1446540995, 1446541039, 81, '薛炜', 0, 0, '[]', 1, 2015),
(46, 0, 'news', 36, 0, 0, 0, 0, 0, 'ceshi', 'ceshi', 'thumbnails/2015/11/04/830ba0e5da80feb53e90715deb648958.png', 1446622734, 1447138812, 82, '匡高峰', 0, 0, '[]', 1, 2015),
(47, 0, 'video_collection', 1, 0, 0, 0, 0, 0, '奔跑吧兄弟 2015', '奔跑奔跑', 'thumbnails/2015/09/29/963ea7a1ad1994a91aa5361feaedf575.png', 1447138812, 1447138812, 81, '薛炜', 0, 0, '[]', 1, 2015),
(48, 0, 'video', 1, 0, 0, 0, 0, 0, '奔跑吧兄弟 2015 第1集', '奔跑奔跑', 'thumbnails/2015/09/29/963ea7a1ad1994a91aa5361feaedf575.png', 1447138812, 1447138812, 81, '薛炜', 0, 0, '[]', 1, 2015),
(50, 1, 'news', 38, 0, 0, 0, 0, 0, '111212', '111212', 'thumbnails/2015/11/19/f54518b4f0ce814751079e76748381d7.png', 1447896612, 1447896612, 84, '张海盼', 0, 0, '[]', 1, 2015);

-- --------------------------------------------------------

--
-- 表的结构 `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `id` int(11) unsigned NOT NULL COMMENT '部门ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `name` varchar(255) NOT NULL COMMENT '部门名',
  `father_id` int(10) unsigned NOT NULL COMMENT '上级部门ID',
  `depth` int(11) NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='部门表';

--
-- 转存表中的数据 `department`
--

INSERT INTO `department` (`id`, `channel_id`, `name`, `father_id`, `depth`, `sort`) VALUES
(1, 1, '0', 0, 1, 1),
(2, 1, '技术中心', 1, 1, 1),
(3, 1, '新闻中心', 1, 1, 1),
(4, 1, '视频中心', 1, 1, 1),
(5, 1, '营销中心', 1, 1, 1),
(6, 1, '管理中心', 1, 1, 1),
(7, 1, '网站领导', 1, 1, 1),
(8, 1, '研发中心', 2, 1, 1),
(9, 1, '运维中心', 2, 1, 1),
(10, 1, '1', 1, 1, 1),
(11, 0, '部门顶级', 0, 1, 1),
(12, 0, '部门1级', 20, 1, 1),
(13, 0, '部门1级B', 12, 1, 1),
(16, 0, '部门2级B', 13, 1, 1),
(17, 0, '部门3级A', 13, 1, 1),
(18, 0, '顶级部门哈哈', 17, 1, 1),
(19, 0, '顶级部门哈哈', 11, 1, 1),
(20, 0, '顶级部门哈哈', 19, 1, 1),
(21, 0, 'jkhhgg', 0, 1, 1),
(22, 0, '1', 0, 1, 1),
(23, 0, '12', 0, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `domains`
--

CREATE TABLE IF NOT EXISTS `domains` (
  `id` int(11) unsigned NOT NULL COMMENT '域名iD',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '域名',
  `category_id` int(11) unsigned NOT NULL COMMENT '分类ID',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '修改时间',
  `status` smallint(2) unsigned NOT NULL COMMENT '状态'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `domains`
--

INSERT INTO `domains` (`id`, `channel_id`, `name`, `category_id`, `created_at`, `updated_at`, `status`) VALUES
(1, 0, 'frontend.cztv.app', 0, 1447728607, 1447728607, 1),
(2, 1, 'frontend.king.cztv.com', 0, 1447728607, 1447728607, 1);

-- --------------------------------------------------------

--
-- 表的结构 `duty`
--

CREATE TABLE IF NOT EXISTS `duty` (
  `id` int(11) unsigned NOT NULL COMMENT '岗位ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `name` varchar(255) NOT NULL COMMENT '岗位名',
  `sort` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='岗位表';

--
-- 转存表中的数据 `duty`
--

INSERT INTO `duty` (`id`, `channel_id`, `name`, `sort`) VALUES
(1, 1, 'afsdf', 1),
(2, 1, '程序员', 1),
(3, 1, '开', 1),
(4, 0, '测试', 1),
(5, 1, ' 我问问', 1),
(6, 0, '房间突然人突然', 1),
(7, 30, '维尔维尔', 1),
(8, 0, '2去青蛙的算法的', 1),
(9, 1, '的发生热油', 1),
(10, 1, '6urllo88', 1),
(11, 0, '同意让他', 1),
(12, 1, '56', 1),
(13, 1, '阿士大夫反复反复反复', 1),
(14, 0, '112', 1),
(15, 0, '112', 1),
(16, 1, 'ASD ', 1),
(17, 0, '  阿萨德  方撒大声地', 1),
(18, 0, 'cehsi 1', 1),
(19, 0, '测试22', 1),
(20, 0, 'aaa', 1),
(21, 0, '22', 1),
(22, 0, '23201323232', 1);

-- --------------------------------------------------------

--
-- 表的结构 `exprience`
--

CREATE TABLE IF NOT EXISTS `exprience` (
  `id` int(11) NOT NULL COMMENT '个人经历',
  `start_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `description` varchar(30) DEFAULT NULL COMMENT '简述',
  `location` varchar(30) NOT NULL COMMENT '地点'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `hotwords`
--

CREATE TABLE IF NOT EXISTS `hotwords` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `user_name` varchar(32) NOT NULL,
  `createtime` int(11) NOT NULL,
  `weight` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `hotwords`
--

INSERT INTO `hotwords` (`id`, `name`, `user_id`, `user_name`, `createtime`, `weight`, `status`) VALUES
(3, '中国好声音', 111, '章海泉', 1443084403, 5000, 1),
(4, '一克拉恋人', 111, '章海泉', 1443081390, 500, 1),
(5, '汪峰', 111, '章海泉', 1443081595, 9999, 1),
(6, '十二道锋味', 111, '章海泉', 1443084386, 2000, 1);

-- --------------------------------------------------------

--
-- 表的结构 `lotteries`
--

CREATE TABLE IF NOT EXISTS `lotteries` (
  `id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `name` varchar(50) NOT NULL COMMENT '活动标识',
  `lottery_channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `open_time` int(11) unsigned NOT NULL COMMENT '开启时间',
  `close_time` int(11) unsigned NOT NULL COMMENT '结束时间',
  `estimated_people` int(11) unsigned NOT NULL COMMENT '预计人数',
  `times_limit` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '  次数限制',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='摇奖活动';

--
-- 转存表中的数据 `lotteries`
--

INSERT INTO `lotteries` (`id`, `name`, `lottery_channel_id`, `open_time`, `close_time`, `estimated_people`, `times_limit`, `created_at`, `updated_at`) VALUES
(1, '15日测试摇奖1', 4, 1444874400, 1444892400, 100, 100, 1444874957, 1444874957),
(2, '15日测试民生1', 1, 1444838400, 1444899600, 1, 1, 1444890127, 1444890127),
(3, '15日测试 公共1', 2, 1444838400, 1444921200, 1000, 20, 1444891054, 1444891054),
(4, '16日浙声测试1', 4, 1444924800, 1445007600, 1000, 10, 1444958981, 1444958981),
(5, '18日浙声测试1', 4, 1445097600, 1445220000, 1000, 1000, 1445168867, 1445168867),
(6, '19浙声演示1', 4, 1445218800, 1445270340, 10000, 10, 1445218923, 1445218923),
(7, '11月2test', 9, 1446516600, 1448897100, 10000, 10, 1445221926, 1445481296),
(8, '活动缓存测试4', 9, 1446084600, 1504325540, 1000, 500, 1445481970, 1446099889);

-- --------------------------------------------------------

--
-- 表的结构 `lottery_channels`
--

CREATE TABLE IF NOT EXISTS `lottery_channels` (
  `id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `name` varchar(50) NOT NULL COMMENT '频道名称',
  `background` varchar(255) NOT NULL COMMENT '背景图片',
  `style` varchar(50) NOT NULL COMMENT '样式',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `type` enum('tv','radio') NOT NULL COMMENT '频道类型',
  `sort` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='摇奖活动频道';

--
-- 转存表中的数据 `lottery_channels`
--

INSERT INTO `lottery_channels` (`id`, `name`, `background`, `style`, `created_at`, `updated_at`, `type`, `sort`) VALUES
(1, '民生休闲', 'yao/2015/10/15/b25eed5f0c91d1c7854c81c1e945e603.png', 'minsheng', 1123, 1444870886, 'tv', 1),
(2, '公共.新农村', 'yao/2015/10/15/8c3cd8afa1e2806464debe7933b5fcef.png', 'new_country', 1444553999, 1444870918, 'tv', 0),
(4, '浙江之声', 'yao/2015/10/15/cd59d2279cdd65c3246a59445ff58485.png', 'voice_zj', 1444556010, 1444977073, 'radio', 100),
(9, '浙江之声1', 'yao/2015/10/29/f3fec3f16cb90309353b70653b24d249.jpg', 'z89', 1444978325, 1446104045, 'radio', 1000);

-- --------------------------------------------------------

--
-- 表的结构 `lottery_contacts`
--

CREATE TABLE IF NOT EXISTS `lottery_contacts` (
  `id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `token` varchar(64) NOT NULL COMMENT '中奖标识',
  `prize_is_real` tinyint(1) unsigned NOT NULL COMMENT '奖品是否实物',
  `mobile` varchar(20) DEFAULT NULL COMMENT '中奖者手机号',
  `name` varchar(50) DEFAULT NULL COMMENT '中奖者姓名',
  `province` varchar(20) DEFAULT NULL COMMENT '省份',
  `city` varchar(20) DEFAULT NULL COMMENT '城市',
  `area` varchar(20) DEFAULT NULL COMMENT '地区',
  `address` varchar(500) DEFAULT NULL COMMENT '联系地址',
  `address_modify_admin` varchar(128) DEFAULT NULL COMMENT '修改人',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态:'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='摇奖中奖联系人表';

--
-- 转存表中的数据 `lottery_contacts`
--

INSERT INTO `lottery_contacts` (`id`, `token`, `prize_is_real`, `mobile`, `name`, `province`, `city`, `area`, `address`, `address_modify_admin`, `created_at`, `updated_at`, `status`) VALUES
(1, '67e82a578ccd9961c5db9f74473492c2', 1, '18058702686', '徐辉', '浙江省', '金华市', '武义县', 'vv 吃豆腐放入反复听', '', 1444890435, 1444890507, 2),
(2, '4a378fcc2258f33027a2932e28c3e3fe', 1, '18058702686', '徐辉', '浙江省', '杭州市', '西湖区', '哈哈哈哈哈路', '15968885105', 1444891271, 1445418770, 2),
(3, '5fb980c079cddc22d779385cec29d3e4', 0, '18958026900', '', '', '', '', '', '', 1444894134, 1444894157, 2),
(4, '6b3ffff10a3dbf1aa870ddc7ed113a08', 1, '15968885105', '薛炜', '浙江省', '杭州市', '西湖区', '溜达溜达', '', 1444897445, 1445495140, 2),
(5, '4914313866e015bb9943e1c01d297e97', 1, '', '', '', '', '', '', '', 1444897486, 1444897486, 0),
(6, '06723bc912829869ddfe21309f56260a', 1, '', '', '', '', '', '', '', 1444911708, 1444911708, 0),
(7, '36c6c508b653adb228c607843a26d9e1', 0, '15968885105', '', '', '', '', '', '', 1444959170, 1445495549, 2),
(8, 'a787a285ae3ff6c887437efdbc95ed5b', 0, '15968885105', '', '', '', '', '', '', 1444960717, 1445496071, 2),
(9, 'f56ded7f8febe86d472e2867b5b821e8', 0, '', '', '', '', '', '', '', 1444961035, 1444961035, 0),
(11, '98c7eb99f4dfa56b634fd86f79189095', 0, '18058702686', '', '', '', '', '', '', 1444964550, 1444964576, 2),
(12, 'ba8b8989eb24f3f0f8a11ec8da363d25', 0, '15968871212', '', '', '', '', '', '', 1444978414, 1444978834, 2),
(13, '6e1bde6fbd190b0f056189d42a3d48da', 0, '', '', '', '', '', '', '', 1445257422, 1445257422, 0),
(14, '214c93a5422991772d41c5449d0d6f1b', 1, '', '', '', '', '', '', '', 1445501077, 1445501077, 0),
(15, 'e7602c3ae39accc9b61b9a4b2b0411b4', 0, '', '', '', '', '', '', '', 1445501079, 1445501079, 0),
(16, '9b28e4ff48d92dde5c8fddb74bf830e3', 0, '', '', '', '', '', '', '', 1445501081, 1445501081, 0),
(17, 'fbe085c42b62ed127c45daacbfaf66f6', 0, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(18, '977a88e2ba1bde4559cf7b8bf71314a1', 1, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(19, 'a3ba664f272bbcd50328293001554d71', 0, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(20, 'b2a4f0256cf31337101ace0fc062b3ec', 1, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(21, 'f3997a30c7c79069e443e17f6265b588', 1, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(22, 'cfd068a35e70530b0d7f2b1f3802f00c', 1, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(23, '148db2fe61c5810236e98e992288db2f', 1, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(24, 'e101c426473fdfac3e2658b0208928ae', 1, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(25, 'd89fd8c86820ceabaa4902c8db0b9043', 1, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(26, 'b341157ec924ca73e3a1264b105d58c5', 1, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(27, '89f2548f55a26bfae5a0da5b3d373282', 0, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(28, '1d0223ede352da1927a621afc4474f25', 0, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(29, 'f1917d5b05183e7e5a592ba5293fcaf4', 1, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(30, '4a6e049bc6ac32d0b013c781ab175de4', 1, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(31, '84707ce63f52362e1f479c90b5752225', 1, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(32, '85438eb0cc4eae04bbc8ea18a8fed2cf', 0, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(33, '7f6e0ec62d33a98d607ef37690cf4415', 0, '', '', '', '', '', '', '', 1445501082, 1445501082, 0),
(34, 'e38c96c5f649bc1fede3c7535868224e', 0, '', '', '', '', '', '', '', 1445501083, 1445501083, 0),
(35, '6178dbf4f539faca7b04e873e2c5e1a8', 0, '', '', '', '', '', '', '', 1445501083, 1445501083, 0),
(36, '87d7aecda8181f5c0d03bdae97e483fd', 0, '', '', '', '', '', '', '', 1445501083, 1445501083, 0),
(37, 'daef84ae5e11aad285ff5ad70ad2ac88', 0, '', '', '', '', '', '', '', 1445501083, 1445501083, 0),
(38, 'bd38ae0326d4fe0078fefb3fabb823e1', 1, '', '', '', '', '', '', '', 1445501083, 1445501083, 0),
(39, '6009705346507d6cf5ee5699cbefc5cb', 1, '', '', '', '', '', '', '', 1445501083, 1445501083, 0),
(40, '4ef99a44032e8f1beac5687e803fa8f1', 1, '', '', '', '', '', '', '', 1445501083, 1445501083, 0),
(41, '3b06f6a64562e425efbf86fcc7bbb0d5', 1, '', '', '', '', '', '', '', 1445501084, 1445501084, 0),
(42, 'a4123e4093aa94a51621b85e016f284b', 1, '', '', '', '', '', '', '', 1445501084, 1445501084, 0),
(43, '0ea9b3f840ede3fcc2cc931783e5154d', 1, '', '', '', '', '', '', '', 1445501084, 1445501084, 0),
(44, '9b5a18cce42a6614a272287a3eaa3ab3', 1, '', '', '', '', '', '', '', 1445501084, 1445501084, 0),
(45, '3c33e213eb8b45cae5d3ab2c03c63fef', 0, '', '', '', '', '', '', '', 1445501084, 1445501084, 0),
(46, '9678e0dce157d1cb1f6caedcd140ee2c', 0, '', '', '', '', '', '', '', 1445501084, 1445501084, 0),
(47, '0820e7fdf26f93ef63b57a7d33ec68af', 0, '', '', '', '', '', '', '', 1445501084, 1445501084, 0),
(48, '5d18cf4e2a544a7701b268f7aa52fa44', 1, '', '', '', '', '', '', '', 1445501084, 1445501084, 0),
(49, '0ebacdf01f305299f07f96b49329494c', 1, '', '', '', '', '', '', '', 1445501085, 1445501085, 0),
(50, 'bdf8609f281672000dca1250d8da6111', 1, '', '', '', '', '', '', '', 1445501085, 1445501085, 0),
(51, '9c741d12209b762b6d33c316394ba513', 0, '', '', '', '', '', '', '', 1445501085, 1445501085, 0),
(52, '083bc78f1966992957b2793f29745840', 0, '', '', '', '', '', '', '', 1445501085, 1445501085, 0),
(53, '039bfc3e284d09b75520ef2f6c5aa913', 0, '', '', '', '', '', '', '', 1445501085, 1445501085, 0),
(54, '663fd55e0fa8e7243712cb5e331443a7', 1, '', '', '', '', '', '', '', 1445501085, 1445501085, 0),
(55, '97d0fc92d98a50d8db33c2b3e56dd38c', 0, '', '', '', '', '', '', '', 1445501086, 1445501086, 0),
(56, 'c55e5dc20db7dd737dfd587e506a0d4f', 0, '', '', '', '', '', '', '', 1445501086, 1445501086, 0),
(57, 'aa862d37365254577f3bcef3dd466c8e', 1, '', '', '', '', '', '', '', 1445501086, 1445501086, 0),
(58, '1054a4b85b467b65c5793c9a426b7e3e', 0, '', '', '', '', '', '', '', 1445501086, 1445501086, 0),
(59, '34423483746934fd3182ad17cf3bda9c', 1, '', '', '', '', '', '', '', 1445501086, 1445501086, 0),
(60, '2f2e575ee5f92a4bd080a5a0cb3ca3b3', 1, '', '', '', '', '', '', '', 1445501086, 1445501086, 0),
(61, '98dc4e2f23500583a182df1608205a38', 1, '', '', '', '', '', '', '', 1445501086, 1445501086, 0),
(62, '05391bbd075dfd958061333c36dc85e4', 1, '', '', '', '', '', '', '', 1445501087, 1445501087, 0),
(63, '8242b56e338531a16321e71ece1fb2af', 1, '', '', '', '', '', '', '', 1445501087, 1445501087, 0),
(64, '5357a4bd9dd8fae5d9f67152cede6ffb', 0, '', '', '', '', '', '', '', 1445501087, 1445501087, 0),
(65, '07f4b63b37aded9f00588ac18211a4f9', 0, '', '', '', '', '', '', '', 1445501087, 1445501087, 0),
(66, '89b472ab2cc3ee309ea967eeb3c5b74e', 0, '', '', '', '', '', '', '', 1445501087, 1445501087, 0),
(67, 'e63236c7ef61004e24f1ec4f2e115256', 0, '', '', '', '', '', '', '', 1445501087, 1445501087, 0),
(68, '2a28a209b78b1843040bfc2bda94ac7a', 0, '', '', '', '', '', '', '', 1445501087, 1445501087, 0),
(69, '2d2ffc52e97b11c801e3d6e16ce40d73', 1, '', '', '', '', '', '', '', 1445501088, 1445501088, 0),
(70, 'a324bfc86c5e7e21a82ea869ad12915d', 1, '', '', '', '', '', '', '', 1445501088, 1445501088, 0),
(71, 'e34b720496f51d345b7e9c7c8e89e2aa', 0, '', '', '', '', '', '', '', 1445501090, 1445501090, 0),
(72, 'd371db3241f7423ebf7f349aec430fc9', 0, '', '', '', '', '', '', '', 1445501090, 1445501090, 0),
(73, 'e67c768ce6bfeedd897ac593b9205580', 1, '', '', '', '', '', '', '', 1445501091, 1445501091, 0),
(74, '5d2a3b81fd03ec84e9cdcc809e062081', 0, '', '', '', '', '', '', '', 1445501091, 1445501091, 0),
(75, '7b39263973f1898a6bf9253fc1a520d8', 0, '', '', '', '', '', '', '', 1445501091, 1445501091, 0),
(76, '5d5fd47083b4a5ced2f46eada9c5066b', 1, '', '', '', '', '', '', '', 1445501091, 1445501091, 0),
(77, '3201c326027f91ecf07ed8ed31052cca', 1, '', '', '', '', '', '', '', 1445501091, 1445501091, 0),
(78, '13260f9489a7abee5cbf48d00a0ca579', 0, '', '', '', '', '', '', '', 1445501091, 1445501091, 0),
(79, 'b56baa8ec8677313a6cd649f134511b2', 0, '', '', '', '', '', '', '', 1445501091, 1445501091, 0),
(80, '8b0a022c41905a568b6e9a6b76eacaa8', 0, '', '', '', '', '', '', '', 1445501092, 1445501092, 0),
(81, '6c6291c212cb244e62ca2daa6eca2175', 0, '', '', '', '', '', '', '', 1445501092, 1445501092, 0),
(82, '90e872416837406f41f5a5282a2c82b7', 1, '', '', '', '', '', '', '', 1445501092, 1445501092, 0),
(83, '5e219d47f604dd345fbe069f4167c969', 1, '', '', '', '', '', '', '', 1445501092, 1445501092, 0),
(84, 'dda9f28b44ce8fa4ce2eae06a79d4bcd', 0, '', '', '', '', '', '', '', 1445501172, 1445501172, 0),
(85, '482981775637b465b294e5ec338ac7f3', 1, '15988888888', '好人', '浙江省', '杭州市', '西湖区', '大好人', '', 1445501183, 1445501206, 2),
(86, '6e8aa2ac11d55d2112d2cdcc1510231e', 1, '15988888888', '好人', '浙江省', '杭州市', '西湖区', '大好人', '', 1445501209, 1445501212, 2),
(87, 'c2b86bda3e757a48d7e066ae01c61e6a', 1, '15988888888', '好人', '浙江省', '杭州市', '西湖区', '大好人', '', 1445501215, 1445501218, 2),
(88, '4605caa9454182a9bcdf4f228db6ac73', 1, '15988888888', '好人', '浙江省', '杭州市', '西湖区', '大好人', '', 1445501221, 1445501223, 2),
(89, '5f6f918a78b861291967b0ce823d4cc5', 0, '15988888888', '', '', '', '', '', '', 1445501226, 1445501229, 2),
(90, 'a01c2c2af2ce86902ee0412695bcf7bc', 1, '', '', '', '', '', '', '', 1445501302, 1445501302, 0),
(91, '0b7dac96728fe0d1e3dba57a7edd4f33', 1, '', '', '', '', '', '', '', 1445501302, 1445501302, 0),
(92, 'c738e288b4bb826cb77247042249154c', 0, '', '', '', '', '', '', '', 1445501302, 1445501302, 0),
(93, '055e6a7efc5c36b1648188d689b9a3c0', 0, '', '', '', '', '', '', '', 1445501303, 1445501303, 0),
(94, 'fa83778034f46561f443c132bb0a43ad', 0, '', '', '', '', '', '', '', 1445501303, 1445501303, 0),
(95, '95a770c5758f0fc3aabc7fe95af1c29d', 0, '', '', '', '', '', '', '', 1445501303, 1445501303, 0),
(96, '9b188852a20f63874c1b99864d0ebfb5', 1, '', '', '', '', '', '', '', 1445501303, 1445501303, 0),
(97, '76be50c97ec66fb9c015fb663c52f350', 0, '', '', '', '', '', '', '', 1445501303, 1445501303, 0),
(98, '2abe488cdf8dd8993f010a36c92c4306', 1, '', '', '', '', '', '', '', 1445501303, 1445501303, 0),
(99, 'd1b66b45edd9d421d5fae49b5291feae', 0, '', '', '', '', '', '', '', 1445501303, 1445501303, 0),
(100, 'c74d40fae4ac58c01dcdabc2e805dfa9', 1, '', '', '', '', '', '', '', 1445501309, 1445501309, 0),
(101, '630c4c9f46a04a6065993ba1cfba5527', 1, '15988888888', '好人', '浙江省', '杭州市', '西湖区', '大好人', '', 1445501339, 1445501341, 2),
(102, '81b1197efc126a4a33f7a97d7aed28bc', 1, '15988888888', '好人', '浙江省', '杭州市', '西湖区', '大好人', '', 1445501344, 1445501346, 2),
(103, 'c3805b1f1295a1d448848aca05dc5bc3', 0, '15988888888', '', '', '', '', '', '', 1445501350, 1445501351, 2),
(104, '2922b90484bd3abbbfb091c735203941', 0, '15988888888', '', '', '', '', '', '', 1445501393, 1445501395, 2),
(105, 'a94f0a4989e033c5a82dee31a49ace5a', 1, '', '', '', '', '', '', '', 1445501398, 1445501398, 0),
(106, 'd90fad1747723de8df2d355090b4f2cf', 1, '15966666666', '坏人', '浙江省', '杭州市', '西湖区', '坏坏', '', 1445501589, 1445501604, 2),
(107, 'f32f8efe71e56a34b6e248ae6c55e52c', 0, '15966666666', '', '', '', '', '', '', 1445501607, 1445501609, 2),
(108, '49bfcdd6e77b879a1fc0385a936fbe57', 0, '15966666666', '', '', '', '', '', '', 1445501612, 1445501614, 2),
(109, 'f4a340976036caaaa1b06db1f5d0309c', 1, '15966666666', '坏人', '浙江省', '杭州市', '西湖区', '坏坏', '', 1445501616, 1445501622, 2),
(110, 'aa7b1a9367902f26fe3c4ccc8df62167', 0, '', '', '', '', '', '', '', 1445503018, 1445503018, 0),
(111, '408ebab6f75b00507b4bdb600d729b37', 0, '15968885105', '', '', '', '', '', '', 1445503047, 1445503054, 2),
(112, 'cb0460e8215a4bb1e57469d1069581fd', 1, '', '', '', '', '', '', '', 1445503059, 1445503059, 0),
(113, '2c65416692f52cbf867c6da11f1ce417', 0, '', '', '', '', '', '', '', 1445503101, 1445503101, 0),
(114, '94ad05cd0fd5036b4f50e3f2d5c3039b', 0, '', '', '', '', '', '', '', 1445505270, 1445505270, 0),
(115, '0a6e4244ecc1bec6f4be44c9a2e93c36', 1, '', '', '', '', '', '', '', 1445506056, 1445506056, 0),
(116, '4a2190a7f04754943da46426c7294762', 1, '', '', '', '', '', '', '', 1445506074, 1445506074, 0),
(117, 'feff84d6475ce5c5dae491bd92629b57', 1, '', '', '', '', '', '', '', 1445506139, 1445506139, 0),
(118, '07369847e487b1c014390b30e7216baf', 0, '', '', '', '', '', '', '', 1445506153, 1445506153, 0),
(119, '27908944bd0add13e63c0e2e470d08ee', 0, '', '', '', '', '', '', '', 1445506167, 1445506167, 0),
(120, 'c4e2e985100f7f723cd783074af8d933', 1, '', '', '', '', '', '', '', 1445506190, 1445506190, 0),
(121, 'fe93150b9e9c8fd2e339e3044aa24481', 1, '', '', '', '', '', '', '', 1445506506, 1445506506, 0),
(122, 'b5035d1c975026d68325eb27c28c837f', 0, '', '', '', '', '', '', '', 1445506519, 1445506519, 0),
(123, 'b787223fc6377ab6061df59006aba35a', 0, '', '', '', '', '', '', '', 1445506771, 1445506771, 0),
(124, 'f24a2995d76c760d43563df73a52e634', 0, '', '', '', '', '', '', '', 1445506843, 1445506843, 0),
(125, '14ac24cfd4f5eea3d14e34a9af7fce34', 0, '', '', '', '', '', '', '', 1445506860, 1445506860, 0),
(126, 'a22cc8831691cccfd5c370bf8f47e2bf', 1, '', '', '', '', '', '', '', 1446100120, 1446100120, 0),
(127, '1d355d7b479c1b56fd6028953e5e9f65', 0, '', '', '', '', '', '', '', 1446100130, 1446100130, 0),
(128, '3edcbf1f25562d7ffa61c1fe54614981', 1, '', '', '', '', '', '', '', 1446100139, 1446100139, 0),
(129, 'c053f02764e722874c48bd3c90fdf916', 0, '', '', '', '', '', '', '', 1446100147, 1446100147, 0),
(130, '7ecd29c27e4fd9cc9c0a38ee77072c20', 0, '', '', '', '', '', '', '', 1446100195, 1446100195, 0),
(131, '26c475c4b223ea1384833350a8ffa987', 0, '', '', '', '', '', '', '', 1446100582, 1446100582, 0),
(132, '56d3621c9d557b681715870ade5a271c', 0, '', '', '', '', '', '', '', 1446100786, 1446100786, 0),
(133, 'f12a21bc9b4aff7da4f2aefcf75473f4', 1, '', '', '', '', '', '', '', 1446100918, 1446100918, 0),
(134, '1c018c2fecb0785f1b3d901a546bc36e', 1, '', '', '', '', '', '', '', 1446100934, 1446100934, 0),
(135, '8d66adc487aa42bb196479885e86b5ba', 0, '', '', '', '', '', '', '', 1446100942, 1446100942, 0),
(136, '1a485919ecbdb4eb26af8f2b0fb66daa', 0, '', '', '', '', '', '', '', 1446100967, 1446100967, 0),
(137, '5b63ed70001185390bd37fa29005f862', 1, '12345678901', '看见了', '浙江省', '杭州市', '西湖区', '225588', '', 1446101038, 1446101080, 2),
(138, '6e94166314685ef63e8afd4f89c6fb6c', 0, '', '', '', '', '', '', '', 1446101196, 1446101196, 0),
(139, '95b872a2e565ff7945ab86daa6d2f5f2', 1, '', '', '', '', '', '', '', 1446101231, 1446101231, 0),
(140, '1af911ac5c7e427837a8931d88381343', 0, '', '', '', '', '', '', '', 1446101454, 1446101454, 0),
(141, 'f2f4f5ea2f93a54f003e47086b3f0328', 0, '', '', '', '', '', '', '', 1446101476, 1446101476, 0),
(142, 'ce106c9b4cab2f1833b4fe5d278379e6', 0, '', '', '', '', '', '', '', 1446101512, 1446101512, 0),
(143, '891bf9b84df2d171eb38076ec2c46390', 0, '', '', '', '', '', '', '', 1446101550, 1446101550, 0),
(144, 'd0309a613dfb3275cde32845568436f4', 1, '', '', '', '', '', '', '', 1446101555, 1446101555, 0),
(145, '3361b1391c0aab1fb991161a8374d8ab', 0, '', '', '', '', '', '', '', 1446101971, 1446101971, 0),
(146, '5a3100b3ce41196ca90e556241a9d263', 0, '13888888888', '', '', '', '', '', '', 1446102170, 1446102179, 2),
(147, 'cd7a1e9ff1decdd4645659d53ca294c4', 0, '', '', '', '', '', '', '', 1446104193, 1446104193, 0),
(148, '12daa2bb40f3e8b948e467496715fc89', 1, '', '', '', '', '', '', '', 1446105479, 1446105479, 0),
(149, '4facb1a308b302428576b815a137472b', 1, '15958985105', '颜腾威', '浙江省', '杭州市', '西湖区', '新蓝网409', '', 1446175251, 1446175349, 2),
(150, 'f13d6fb4bd94ac2fe558d905a1c54797', 1, '', '', '', '', '', '', '', 1447382252, 1447382252, 0),
(151, '583a8b2a8a168112d260e1f74955849d', 1, '', '', '', '', '', '', '', 1447400796, 1447400796, 0);

-- --------------------------------------------------------

--
-- 表的结构 `lottery_prizes`
--

CREATE TABLE IF NOT EXISTS `lottery_prizes` (
  `id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `lottery_id` int(11) unsigned NOT NULL COMMENT '摇奖活动ID',
  `name` varchar(128) NOT NULL COMMENT '奖品名',
  `level` smallint(4) unsigned NOT NULL COMMENT '奖品级别',
  `number` int(8) unsigned NOT NULL COMMENT '数量',
  `rest_number` int(8) unsigned NOT NULL COMMENT '剩余数量',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `is_real` tinyint(1) unsigned NOT NULL COMMENT '是否真实产品'
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='摇奖活动奖品';

--
-- 转存表中的数据 `lottery_prizes`
--

INSERT INTO `lottery_prizes` (`id`, `lottery_id`, `name`, `level`, `number`, `rest_number`, `created_at`, `updated_at`, `is_real`) VALUES
(1, 1, '手机', 1, 10, 10, 1444874994, 1444874994, 1),
(2, 1, '话费', 2, 90, 84, 1444875010, 1444875010, 0),
(3, 2, '手机', 1, 1, 0, 1444890149, 1444890149, 1),
(4, 3, 'iPhone 6S 16G', 1, 100, 97, 1444891097, 1444891097, 1),
(5, 3, '小米 摄像头', 2, 200, 199, 1444891114, 1444891114, 1),
(6, 3, '话费5元', 3, 200, 199, 1444891138, 1444891138, 0),
(7, 3, '流量50M', 4, 200, 200, 1444891151, 1444891151, 0),
(8, 4, 'iPhone 6s 16G手机', 1, 10, 10, 1444959023, 1444959023, 1),
(9, 4, '小米充电宝', 2, 100, 100, 1444959037, 1444959037, 1),
(10, 4, '手机话费5元', 3, 200, 195, 1444959051, 1444959051, 0),
(11, 4, '手机流量50M', 4, 200, 199, 1444959060, 1444959060, 0),
(12, 5, '手机', 1, 300, 300, 1445168891, 1445168891, 1),
(13, 6, '10M流量', 3, 1000, 999, 1445219022, 1445219022, 0),
(14, 6, '360奇酷手机', 2, 100, 100, 1445219047, 1445219047, 1),
(15, 7, 'iphone6s', 1, 10, 10, 1445221991, 1445221991, 1),
(16, 7, '10M流量', 4, 100, 100, 1445222008, 1445222008, 0),
(17, 8, '人头马', 1, 100, 9933, 1445501064, 1446100334, 1),
(18, 8, '人头马2', 1, 100, 9929, 1445501073, 1446100314, 0);

-- --------------------------------------------------------

--
-- 表的结构 `lottery_winnings`
--

CREATE TABLE IF NOT EXISTS `lottery_winnings` (
  `id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `client_id` varchar(128) NOT NULL COMMENT '中奖客户',
  `prize_id` int(11) unsigned NOT NULL COMMENT '奖品ID',
  `prize_name` varchar(128) NOT NULL COMMENT '奖品名',
  `prize_level` smallint(4) unsigned NOT NULL COMMENT '奖品等级',
  `prize_is_real` tinyint(1) unsigned NOT NULL COMMENT '奖品是否实物',
  `lottery_id` int(11) unsigned NOT NULL COMMENT '摇奖活动ID',
  `lottery_channel_id` int(11) unsigned NOT NULL COMMENT '抽奖频道ID',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8 COMMENT='摇奖活动中奖';

--
-- 转存表中的数据 `lottery_winnings`
--

INSERT INTO `lottery_winnings` (`id`, `client_id`, `prize_id`, `prize_name`, `prize_level`, `prize_is_real`, `lottery_id`, `lottery_channel_id`, `created_at`) VALUES
(1, 'aa4170937b1198034b6e826940f0fa91', 3, '手机', 1, 1, 2, 1, 1444890435),
(2, 'E9D42177-A0C1-44CB-A101-525D2A458F75', 4, 'iPhone 6S 16G', 1, 1, 3, 2, 1444891271),
(3, 'f93fa7ce6470eab8', 6, '话费5元', 3, 0, 3, 2, 1444894134),
(4, '3974a04e5df4add2', 4, 'iPhone 6S 16G', 1, 1, 3, 2, 1444897445),
(5, '0fcf56c75104d5e0f99ae64551b7ed72', 5, '小米 摄像头', 2, 1, 3, 2, 1444897486),
(6, '285110136d01d6916f236a0aa1dc184f', 4, 'iPhone 6S 16G', 1, 1, 3, 2, 1444911708),
(7, '4009fdbd72415d90328ca4410da4d000', 10, '手机话费5元', 3, 0, 4, 4, 1444959170),
(8, '08bf0629925679808b648063601c35ad', 10, '手机话费5元', 3, 0, 4, 4, 1444960717),
(9, 'c0834904b44491de791c3f9d2fca753e', 11, '手机流量50M', 4, 0, 4, 4, 1444961035),
(11, '5ECFCFBC-5496-4308-B132-B35BC627C844', 10, '手机话费5元', 3, 0, 4, 4, 1444964550),
(12, '2f939746302c08aa', 10, '手机话费5元', 3, 0, 4, 4, 1444978414),
(13, '31d06ce0706afb25', 13, '10M流量', 3, 0, 6, 4, 1445257422),
(14, 'dd6498c08b774af1', 17, '人头马', 1, 1, 8, 9, 1445501077),
(15, '25e5346507db3c54', 18, '人头马2', 1, 0, 8, 9, 1445501079),
(16, 'f5d402516bfbe98d', 18, '人头马2', 1, 0, 8, 9, 1445501081),
(17, '50c58850e05e85a9', 18, '人头马2', 1, 0, 8, 9, 1445501082),
(18, '90b42160eb961227', 17, '人头马', 1, 1, 8, 9, 1445501082),
(19, 'd3ba130689a65a45', 18, '人头马2', 1, 0, 8, 9, 1445501082),
(20, '28656ea3ba2aeceb', 17, '人头马', 1, 1, 8, 9, 1445501082),
(21, 'e098f2fd7587ce67', 17, '人头马', 1, 1, 8, 9, 1445501082),
(22, 'ddfc3c66de9b7ac9', 17, '人头马', 1, 1, 8, 9, 1445501082),
(23, 'c8331f5c93169e1c', 17, '人头马', 1, 1, 8, 9, 1445501082),
(24, '46c506a2124955b7', 17, '人头马', 1, 1, 8, 9, 1445501082),
(25, '26333c9f5c446573', 17, '人头马', 1, 1, 8, 9, 1445501082),
(26, '72c7c5846c5680b3', 17, '人头马', 1, 1, 8, 9, 1445501082),
(27, '2b9c118e6f79be97', 18, '人头马2', 1, 0, 8, 9, 1445501082),
(28, '86c517974a311b3d', 18, '人头马2', 1, 0, 8, 9, 1445501082),
(29, '4ed4903499598749', 17, '人头马', 1, 1, 8, 9, 1445501082),
(30, '3f64e3685fa5cfbc', 17, '人头马', 1, 1, 8, 9, 1445501082),
(31, '26ac3a1a14cb1029', 17, '人头马', 1, 1, 8, 9, 1445501082),
(32, '2c5770c686d1d72c', 18, '人头马2', 1, 0, 8, 9, 1445501082),
(33, 'a7a6e7cf57a693bd', 18, '人头马2', 1, 0, 8, 9, 1445501082),
(34, '08d0b04dd3458c30', 18, '人头马2', 1, 0, 8, 9, 1445501083),
(35, '0e557133eb9a0e66', 18, '人头马2', 1, 0, 8, 9, 1445501083),
(36, 'dbdb095f923f42b7', 18, '人头马2', 1, 0, 8, 9, 1445501083),
(37, '52709ed24e4bf25e', 18, '人头马2', 1, 0, 8, 9, 1445501083),
(38, 'ebc2f580909fd4f1', 17, '人头马', 1, 1, 8, 9, 1445501083),
(39, '469cdb97016b403d', 17, '人头马', 1, 1, 8, 9, 1445501083),
(40, '14624442337cc0dd', 17, '人头马', 1, 1, 8, 9, 1445501083),
(41, 'ac28b121c7cedb58', 17, '人头马', 1, 1, 8, 9, 1445501084),
(42, '632c1f88b48dd2a3', 17, '人头马', 1, 1, 8, 9, 1445501084),
(43, '77165a4ee5d67971', 17, '人头马', 1, 1, 8, 9, 1445501084),
(44, '06ad69861b6ac00a', 17, '人头马', 1, 1, 8, 9, 1445501084),
(45, 'f0662e9067499e2c', 18, '人头马2', 1, 0, 8, 9, 1445501084),
(46, '8d7cd1d1fb8ac546', 18, '人头马2', 1, 0, 8, 9, 1445501084),
(47, '487a456551681126', 18, '人头马2', 1, 0, 8, 9, 1445501084),
(48, '53797bb46d2a950c', 17, '人头马', 1, 1, 8, 9, 1445501084),
(49, 'b9378174b0f47205', 17, '人头马', 1, 1, 8, 9, 1445501085),
(50, 'b0811c3734f31445', 17, '人头马', 1, 1, 8, 9, 1445501085),
(51, 'c9ef0db9db83a775', 18, '人头马2', 1, 0, 8, 9, 1445501085),
(52, 'ba29a2acaadf2dd4', 18, '人头马2', 1, 0, 8, 9, 1445501085),
(53, '2e122346a7079733', 18, '人头马2', 1, 0, 8, 9, 1445501085),
(54, 'a4916df3a24c84b3', 17, '人头马', 1, 1, 8, 9, 1445501085),
(55, 'ce0165ce9d02a9e6', 18, '人头马2', 1, 0, 8, 9, 1445501086),
(56, '07dc8398e1d38c8b', 18, '人头马2', 1, 0, 8, 9, 1445501086),
(57, '2aaed336273a4604', 17, '人头马', 1, 1, 8, 9, 1445501086),
(58, '2125dd352fa22400', 18, '人头马2', 1, 0, 8, 9, 1445501086),
(59, '4c884c36a3e298d0', 17, '人头马', 1, 1, 8, 9, 1445501086),
(60, 'a6a83917511cbc1f', 17, '人头马', 1, 1, 8, 9, 1445501086),
(61, '7a210c60492f82b2', 17, '人头马', 1, 1, 8, 9, 1445501086),
(62, '31c4e5f1625c586a', 17, '人头马', 1, 1, 8, 9, 1445501087),
(63, '0d12f9ff32f90f04', 17, '人头马', 1, 1, 8, 9, 1445501087),
(64, '8913e057e3875c7e', 18, '人头马2', 1, 0, 8, 9, 1445501087),
(65, '5239e05a80988a87', 18, '人头马2', 1, 0, 8, 9, 1445501087),
(66, 'f23300c6187c5f23', 18, '人头马2', 1, 0, 8, 9, 1445501087),
(67, '236b91d297722d01', 18, '人头马2', 1, 0, 8, 9, 1445501087),
(68, 'a24aec4fb8ea54d1', 18, '人头马2', 1, 0, 8, 9, 1445501087),
(69, 'e5fb209afc549232', 17, '人头马', 1, 1, 8, 9, 1445501088),
(70, '364bb6357caf8263', 17, '人头马', 1, 1, 8, 9, 1445501088),
(71, 'a886d827bed08982', 18, '人头马2', 1, 0, 8, 9, 1445501090),
(72, '0384109cb59fc610', 18, '人头马2', 1, 0, 8, 9, 1445501090),
(73, '05a00de6d563623e', 17, '人头马', 1, 1, 8, 9, 1445501091),
(74, '810253e0f47dcaba', 18, '人头马2', 1, 0, 8, 9, 1445501091),
(75, '4429f927b81f37c8', 18, '人头马2', 1, 0, 8, 9, 1445501091),
(76, '5ca3df45e8f5afc1', 17, '人头马', 1, 1, 8, 9, 1445501091),
(77, '67434dd96f44485c', 17, '人头马', 1, 1, 8, 9, 1445501091),
(78, '1519ef6fee046e5f', 18, '人头马2', 1, 0, 8, 9, 1445501091),
(79, '05c34219b358bf7a', 18, '人头马2', 1, 0, 8, 9, 1445501091),
(80, '4097c87e7d2dbff0', 18, '人头马2', 1, 0, 8, 9, 1445501092),
(81, '42c48c5b27e96fbe', 18, '人头马2', 1, 0, 8, 9, 1445501092),
(82, 'dd2afaf0315fb3d7', 17, '人头马', 1, 1, 8, 9, 1445501092),
(83, '11e69d551fe24946', 17, '人头马', 1, 1, 8, 9, 1445501092),
(84, '2add744d97cfa4ae', 18, '人头马2', 1, 0, 8, 9, 1445501172),
(85, 'aae8fff9eb44fb22', 17, '人头马', 1, 1, 8, 9, 1445501183),
(86, 'b0d028ce9ae93423', 17, '人头马', 1, 1, 8, 9, 1445501209),
(87, '835fb6c84807aa75', 17, '人头马', 1, 1, 8, 9, 1445501215),
(88, '91bc0e08b948cedc', 17, '人头马', 1, 1, 8, 9, 1445501221),
(89, '8e2c3acabb9d10b0', 18, '人头马2', 1, 0, 8, 9, 1445501226),
(90, '5bad460077a39322', 17, '人头马', 1, 1, 8, 9, 1445501302),
(91, 'a96098e48ead74c0', 17, '人头马', 1, 1, 8, 9, 1445501302),
(92, 'cb8396eb53d067bc', 18, '人头马2', 1, 0, 8, 9, 1445501302),
(93, '0dd356852aca0edd', 18, '人头马2', 1, 0, 8, 9, 1445501303),
(94, 'd9984729bc041552', 18, '人头马2', 1, 0, 8, 9, 1445501303),
(95, '0ed8a2f41dd9aca9', 18, '人头马2', 1, 0, 8, 9, 1445501303),
(96, 'a16c686c7122e15f', 17, '人头马', 1, 1, 8, 9, 1445501303),
(97, 'd4115876dcdc981d', 18, '人头马2', 1, 0, 8, 9, 1445501303),
(98, '6c6ba94b08bba8f8', 17, '人头马', 1, 1, 8, 9, 1445501303),
(99, '822e694bd5c7327d', 18, '人头马2', 1, 0, 8, 9, 1445501303),
(100, '515f65d8a2ace6e2', 17, '人头马', 1, 1, 8, 9, 1445501309),
(101, '156b7bbddd198800', 17, '人头马', 1, 1, 8, 9, 1445501339),
(102, '98fa71515f143bcf', 17, '人头马', 1, 1, 8, 9, 1445501344),
(103, '056b6e1934a761cb', 18, '人头马2', 1, 0, 8, 9, 1445501350),
(104, '096fe1de2882115e', 18, '人头马2', 1, 0, 8, 9, 1445501393),
(105, 'b453ff1af0504238', 17, '人头马', 1, 1, 8, 9, 1445501398),
(106, 'b474191c53ba7444', 17, '人头马', 1, 1, 8, 9, 1445501589),
(107, '3e7e3493093c371f', 18, '人头马2', 1, 0, 8, 9, 1445501607),
(108, 'e5216d43f5c2cdd7', 18, '人头马2', 1, 0, 8, 9, 1445501612),
(109, '453cb2afe25d889c', 17, '人头马', 1, 1, 8, 9, 1445501616),
(110, '8d9fd914c38e8a3d', 18, '人头马2', 1, 0, 8, 9, 1445503018),
(111, 'f611fbcdb4e45b29', 18, '人头马2', 1, 0, 8, 9, 1445503047),
(112, '66d652a8d90f14fa', 17, '人头马', 1, 1, 8, 9, 1445503059),
(113, 'a5bc23270634042f', 18, '人头马2', 1, 0, 8, 9, 1445503101),
(114, 'f1aacd174c4ff8c3', 18, '人头马2', 1, 0, 8, 9, 1445505270),
(115, '94e58638eea53d77', 17, '人头马', 1, 1, 8, 9, 1445506056),
(116, '401d9c3a751881d5', 17, '人头马', 1, 1, 8, 9, 1445506074),
(117, '878b61937efac636', 17, '人头马', 1, 1, 8, 9, 1445506139),
(118, 'e8c6a5514d3ce1f6', 18, '人头马2', 1, 0, 8, 9, 1445506153),
(119, '8e566119d37b0723', 18, '人头马2', 1, 0, 8, 9, 1445506167),
(120, '045fca1e0648793d', 17, '人头马', 1, 1, 8, 9, 1445506190),
(121, '108a67a8d1c217f9', 17, '人头马', 1, 1, 8, 9, 1445506506),
(122, 'a63a61b34ad22adb', 18, '人头马2', 1, 0, 8, 9, 1445506519),
(123, '75668c8e0c372d76', 18, '人头马2', 1, 0, 8, 9, 1445506771),
(124, '6a80c5399cbbfb90', 18, '人头马2', 1, 0, 8, 9, 1445506843),
(125, '029e6d04cd6518da', 18, '人头马2', 1, 0, 8, 9, 1445506860),
(126, 'b54dd67b2251e1cc', 17, '人头马', 1, 1, 8, 9, 1446100120),
(127, '3c5912e67b875ed5', 18, '人头马2', 1, 0, 8, 9, 1446100130),
(128, '45a9fabb18285e8b', 17, '人头马', 1, 1, 8, 9, 1446100139),
(129, 'aa40676e004462f5', 18, '人头马2', 1, 0, 8, 9, 1446100147),
(130, 'b1433409e114e1fe', 18, '人头马2', 1, 0, 8, 9, 1446100195),
(131, '5ef5427565fb6f90', 18, '人头马2', 1, 0, 8, 9, 1446100582),
(132, 'a024caa67c716d84', 18, '人头马2', 1, 0, 8, 9, 1446100786),
(133, '43653d0ced06afec', 17, '人头马', 1, 1, 8, 9, 1446100918),
(134, 'd37939a949eaac11', 17, '人头马', 1, 1, 8, 9, 1446100934),
(135, '182d38960e34bf08', 18, '人头马2', 1, 0, 8, 9, 1446100942),
(136, '56be09958b41a489', 18, '人头马2', 1, 0, 8, 9, 1446100967),
(137, '7586dd8d1ce50419', 17, '人头马', 1, 1, 8, 9, 1446101038),
(138, '144b60f00c2e4f14', 18, '人头马2', 1, 0, 8, 9, 1446101196),
(139, '1', 17, '人头马', 1, 1, 8, 9, 1446101231),
(140, '52165b168af5667c', 18, '人头马2', 1, 0, 8, 9, 1446101454),
(141, 'f37893c2832def87', 18, '人头马2', 1, 0, 8, 9, 1446101476),
(142, 'a865b462c22b5ebf', 18, '人头马2', 1, 0, 8, 9, 1446101512),
(143, 'e3a3788923aa37e7', 18, '人头马2', 1, 0, 8, 9, 1446101550),
(144, '31b50dc171a8aa18', 17, '人头马', 1, 1, 8, 9, 1446101555),
(145, '2c856b5a1bd19eeb', 18, '人头马2', 1, 0, 8, 9, 1446101971),
(146, 'd44f5ab64618e350', 18, '人头马2', 1, 0, 8, 9, 1446102170),
(147, '5A271137-99B5-493B-8AEC-77ECAD047E3C', 18, '人头马2', 1, 0, 8, 9, 1446104193),
(148, '7545c5cfa32496f208426f648f96d23a', 17, '人头马', 1, 1, 8, 9, 1446105479),
(149, 'df59615aea959373462d98fa75d8cd73', 17, '人头马', 1, 1, 8, 9, 1446175251),
(150, 'a:1', 17, '人头马', 1, 1, 8, 9, 1447382252),
(151, 'a:2f939746302c08aa', 17, '人头马', 1, 1, 8, 9, 1447400796);

-- --------------------------------------------------------

--
-- 表的结构 `message_task`
--

CREATE TABLE IF NOT EXISTS `message_task` (
  `id` int(11) unsigned NOT NULL,
  `send_id` int(11) unsigned NOT NULL COMMENT '发送者id',
  `rec_id` int(11) unsigned NOT NULL COMMENT '接收者id',
  `task_id` int(11) unsigned NOT NULL COMMENT '任务id',
  `message` varchar(255) NOT NULL COMMENT '消息',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态0未读；1已读',
  `timestamp` int(11) NOT NULL COMMENT '时间戳'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `message_task`
--

INSERT INTO `message_task` (`id`, `send_id`, `rec_id`, `task_id`, `message`, `status`, `timestamp`) VALUES
(1, 110, 86, 329, '65tfgfhj', 0, 1448267642),
(2, 84, 104, 234, 'ghkoykty', 0, 1448267662),
(3, 86, 110, 329, '23213', 0, 1448268265),
(4, 110, 86, 370, '方法法兰克福的了快了地方该地块浪费了', 0, 1448268573),
(5, 86, 110, 370, '付款了到生看了看', 0, 1448268599),
(6, 86, 110, 329, '23123213', 0, 1448327761),
(7, 86, 110, 370, '2312321321', 0, 1448327777);

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) unsigned NOT NULL COMMENT '新闻ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  `thumb` varchar(255) DEFAULT NULL COMMENT '源缩略图',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '修改时间',
  `author_id` int(11) unsigned NOT NULL COMMENT '作者ID',
  `author_name` varchar(30) NOT NULL COMMENT '作者名字',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `content` text NOT NULL COMMENT '新闻内容',
  `source` varchar(255) DEFAULT NULL COMMENT '来源',
  `no_comment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '禁止评论',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年'
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;

--
-- 转存表中的数据 `news`
--

INSERT INTO `news` (`id`, `channel_id`, `title`, `intro`, `thumb`, `created_at`, `updated_at`, `author_id`, `author_name`, `keywords`, `content`, `source`, `no_comment`, `partition_by`) VALUES
(5, 0, 'commit ''''''''', 'commit ''''''''', 'thumbnails/2015/09/29/963ea7a1ad1994a91aa5361feaedf575.png', 1442910956, 1446539067, 1, '张三', NULL, '<pre style="background-color:#2b2b2b;color:#e6e1dc;font-family:&#39;Menlo&#39;;font-size:12pt;">commit</pre><p><br/></p><p>&#39;&#39;&#39;&#39;&#39;&#39;&#39;&#39;</p>', NULL, 0, 2015),
(6, 1, '*必填*必填*必填', ' *必填', 'thumbnails/2015/09/29/0e3fd418df5a15d9284e527947893fe5.png', 1442911058, 1445397013, 1, '张三', NULL, '<pre style="background-color:#2b2b2b;color:#e6e1dc;font-family:&#39;Menlo&#39;;font-size:12pt;">&nbsp;&lt;span&nbsp;class=&quot;font-red&quot;&gt;*必填&lt;/span&gt;</pre><p><br/></p>', NULL, 0, 2015),
(7, 0, 'me test', 'me test', 'thumbnails/2015/09/29/e0c2a15883111aff1c3d3095b3a0d94d.png', 1443000443, 1446797584, 1, '张三', NULL, '<p>me test</p>', NULL, 0, 2015),
(8, 0, '3213123123', '123123123123', 'thumbnails/2015/09/29/5aad82bfd2dca7725a1c8ef168fa699d.png', 1443421481, 1443498004, 111, '章海泉', NULL, '<p>3123123123123</p>', NULL, 0, 2015),
(9, 0, '发个新闻稿来个测试看看可以不可以', '发个新闻稿来个测试看看可以不可以', 'thumbnails/2015/09/29/4618baf67e950bd8479bee953f1f623b.jpg', 1443425000, 1446539010, 111, '章海泉', NULL, '<p><input class="quote-item" data-id="9" data-type="album" disabled=""/>发个新闻稿来个测试看看可以不可以</p>', NULL, 0, 2015),
(10, 0, '11111', '111', 'thumbnails/2015/09/28/c84b2be6513ed81702e11824229aa749.jpg', 1443428162, 1446099115, 111, '章海泉', NULL, '<p>2222222222</p>', NULL, 0, 2015),
(12, 0, '1,2,3', '1,2,31,2,31,2,31,2,31,2,3', 'thumbnails/2015/10/08/3e21e160a85c06002e56cccc8712f543.jpg', 1444291115, 1444291115, 111, '章海泉', NULL, '<p>1,2,31,2,31,2,31,2,31,2,31,2,31,2,31,2,31,2,31,2,3</p>', NULL, 0, 2015),
(20, 0, 'category_ids', 'category_ids category_ids category_ids category_idscategory_ids', 'thumbnails/2015/10/08/febd882beecfa5d317440d973836f5d5.png', 1444292061, 1447126030, 111, '章海泉', NULL, '<p>a1a`</p>', NULL, 0, 2015),
(21, 0, '1,2,3,4,5,6', '1,2,3,4,5,6', 'thumbnails/2015/10/09/d55b08aed0f1f38d9fa99d59acd4db37.jpg', 1444353633, 1444353633, 111, '章海泉', NULL, '<p>1,2,3,4,5,6</p>', NULL, 0, 2015),
(30, 0, '123123', '123', 'thumbnails/2015/09/29/0e3fd418df5a15d9284e527947893fe5.png', 1444360927, 1444360927, 111, '章海泉', NULL, '<p>123</p>', NULL, 0, 2015),
(31, 0, 'test111', 'test', 'thumbnails/2015/10/09/a6e2f310a831dbb0b9a8ebd9134bd311.png', 1444383639, 1444438277, 111, '章海泉', NULL, '<p>$messages<br/>$messages<br/>$messages<br/></p>', NULL, 0, 2015),
(32, 1, '浙江新闻', '中央委员会中的“老资格”们\r\n2\r\n\r\n　　1人6次出席五中全', 'thumbnails/2015/10/28/ae1f22a56b2e7722fb02388adcfe956e.png', 1445999926, 1445999926, 169, '章数问', NULL, '中央委员会中的“老资格”们<br/>2<br/><br/>　　1人6次出席五中全', NULL, 0, 2015),
(34, 0, '好好好好好好好', '好好好, 好好好!好好好, 好好好!好好好, 好好好!好好好, 好好好!好好好, 好好好!', 'thumbnails/2015/10/30/e727a2233046feaf1d5091229d4d868d.jpg', 1446194316, 1446194316, 81, '薛炜', NULL, '<p>好好好, 好好好!好好好, 好好好!好好好, 好好好!好好好, 好好好!好好好, 好好好!好好好, 好好好!好好好, 好好好!好好好, 好好好!好好好, 好好好!好好好, 好好好!</p>', NULL, 0, 2015),
(35, 0, '测试文章上传', '测试文章上传测试文章上传测试文章上传测试文章上传测试文章上传', 'thumbnails/2015/11/03/74b4bedc7d805b09793bb218da54a81a.png', 1446540995, 1446541039, 81, '薛炜', NULL, '<p><img src="http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/posts/2015/11/03/8eaa79b7f7a6622f41d2f5e8706a1b8c.png" title="8eaa79b7f7a6622f41d2f5e8706a1b8c.png" alt="Elements.png"/></p><p><br/></p><p>啦啦啦啦</p><p><br/></p><p style="line-height: 16px;"><img style="vertical-align: middle; margin-right: 2px;" src="/assets/global/plugins/ueditor/dialogs/attachment/fileTypeImages/icon_pdf.gif"/><a style="font-size:12px; color:#0066cc;" href="http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/attachments/2015/11/03/947ccf31aedf64e8996d1276e440e72c.pdf" title="Yii快速入门教程.pdf">Yii快速入门教程.pdf</a></p><p><br/></p>', NULL, 0, 2015),
(36, 0, 'ceshi', 'ceshi', 'thumbnails/2015/11/04/830ba0e5da80feb53e90715deb648958.png', 1446622734, 1447138812, 82, '匡高峰', NULL, '<p>ceshi</p>', NULL, 0, 2015),
(38, 1, '111212', '111212', 'thumbnails/2015/11/19/f54518b4f0ce814751079e76748381d7.png', 1447896612, 1447896612, 84, '张海盼', NULL, '<p>1111212121</p>', NULL, 0, 2015);

-- --------------------------------------------------------

--
-- 表的结构 `news_group`
--

CREATE TABLE IF NOT EXISTS `news_group` (
  `id` int(11) NOT NULL COMMENT '新闻集ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `keyword` varchar(255) NOT NULL COMMENT '关键词',
  `intro` varchar(255) NOT NULL COMMENT '简介'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新闻集表';

-- --------------------------------------------------------

--
-- 表的结构 `private_category`
--

CREATE TABLE IF NOT EXISTS `private_category` (
  `id` int(11) unsigned NOT NULL COMMENT '栏目ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `name` varchar(50) NOT NULL COMMENT '栏目名'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='栏目表';

--
-- 转存表中的数据 `private_category`
--

INSERT INTO `private_category` (`id`, `channel_id`, `name`) VALUES
(1, 1, '新闻'),
(2, 1, '国内'),
(3, 1, '国际'),
(4, 1, '社会'),
(5, 2, '新闻'),
(7, 0, '新闻'),
(8, 0, '国内'),
(9, 0, '国际'),
(10, 0, '社会');

-- --------------------------------------------------------

--
-- 表的结构 `private_category_data`
--

CREATE TABLE IF NOT EXISTS `private_category_data` (
  `id` int(11) unsigned NOT NULL COMMENT '关联ID',
  `data_id` int(11) unsigned NOT NULL COMMENT '容器ID',
  `category_id` int(11) unsigned NOT NULL COMMENT '栏目ID',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年'
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='栏目容器关联表'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;

--
-- 转存表中的数据 `private_category_data`
--

INSERT INTO `private_category_data` (`id`, `data_id`, `category_id`, `partition_by`) VALUES
(20, 3, 7, 2015),
(21, 31, 4, 2015),
(22, 5, 8, 2015),
(23, 8, 10, 2015),
(24, 43, 8, 2015),
(25, 9, 9, 2015),
(26, 34, 9, 2015),
(27, 44, 8, 2015),
(28, 7, 7, 2015),
(29, 45, 8, 2015),
(30, 46, 7, 2015),
(31, 19, 7, 2015),
(32, 50, 1, 2015);

-- --------------------------------------------------------

--
-- 表的结构 `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(8) unsigned NOT NULL COMMENT '地区ID',
  `father_id` int(8) unsigned NOT NULL COMMENT '父ID',
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '名称',
  `pinyin` varchar(256) NOT NULL DEFAULT '' COMMENT '全拼',
  `pinyin_short` varchar(50) NOT NULL DEFAULT '' COMMENT '首字母',
  `level` enum('country','province','city','county','town','village') NOT NULL DEFAULT 'city' COMMENT '级别: 国, 省(自治区,直辖市,特别行政区), 市, 县(区), 镇(乡, 街道), 村'
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='地区表';

--
-- 转存表中的数据 `regions`
--

INSERT INTO `regions` (`id`, `father_id`, `name`, `pinyin`, `pinyin_short`, `level`) VALUES
(1, 0, '中国', 'zhongguo', 'zg', 'country'),
(2, 1, '浙江', 'zhejiang', 'zj', 'province'),
(3, 2, '杭州', 'hangzhou', 'hz', 'city'),
(4, 3, '西湖区', 'xihuqu', 'xhq', 'county'),
(5, 3, '上城区', 'shangchengqu', 'scq', 'county'),
(6, 3, '下城区', 'xiachengqu', 'xcq', 'county'),
(7, 3, '拱墅区', 'gongshuqu', 'gsq', 'county'),
(8, 3, '江干区', 'jiangganqu', 'jgq', 'county'),
(9, 3, '滨江区', 'binjiangqu', 'bjq', 'county'),
(10, 3, '萧山区', 'xiaoshanqu', 'xsq', 'county'),
(11, 10, '瓜沥镇', 'gualizhen', 'glz', 'town'),
(12, 11, '航民村', 'hangmincun', 'hmc', 'village'),
(13, 11, '民朗村', 'minlangcun', 'mlc', 'village'),
(14, 11, '东恩村', 'dongencun', 'dec', 'village');

-- --------------------------------------------------------

--
-- 表的结构 `salary`
--

CREATE TABLE IF NOT EXISTS `salary` (
  `id` int(11) unsigned NOT NULL,
  `admin_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `number` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '薪水编号',
  `salary` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '薪水'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `salary`
--

INSERT INTO `salary` (`id`, `admin_id`, `number`, `salary`) VALUES
(6, 87, '201511666', '55555'),
(7, 111, '123', '11111');

-- --------------------------------------------------------

--
-- 表的结构 `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(10) NOT NULL COMMENT '配置名',
  `key` varchar(30) NOT NULL,
  `value` text NOT NULL,
  `channel_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='频道基础配置表';

--
-- 转存表中的数据 `setting`
--

INSERT INTO `setting` (`id`, `name`, `key`, `value`, `channel_id`) VALUES
(1, '登录短信验证', 'is.login.message', '0', 0);

-- --------------------------------------------------------

--
-- 表的结构 `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `id` int(11) unsigned NOT NULL COMMENT '站点ID',
  `name` varchar(99) NOT NULL COMMENT '站点名',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `app_id` varchar(32) NOT NULL COMMENT '终端ID',
  `app_secret` varchar(32) NOT NULL COMMENT '密钥',
  `logo` varchar(255) NOT NULL COMMENT '站点logo',
  `domain` varchar(50) NOT NULL COMMENT '域名',
  `stations` text NOT NULL COMMENT '授权电台ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='站点表';

--
-- 转存表中的数据 `site`
--

INSERT INTO `site` (`id`, `name`, `channel_id`, `app_id`, `app_secret`, `logo`, `domain`, `stations`, `status`) VALUES
(13, '32', 0, 'e4da3b7fbbce2345d7772b0674a318d5', 'e4da3b7fbbce2345d7772b0674a318d5', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/12/f86925d487a66c9e59c32717ba70d775.jpg', 'http://a.com', '2', 0),
(21, '101', 0, '1', '1', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/12/a98ba544c263a3219953f214c9bdc620.jpg', 'http://a.com', '2', 0),
(22, '11', 0, '8ef5dbbcdcf48b6119c13efd090280d0', 'a938c97d7240bfce1eb99c44f002f623', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/12/c391f7c54aa4913b85d651e8d467a1a2.jpg', 'http://a.com', '1,2', 1),
(23, '随机测试', 0, '24b9fd20a474dced5e4fe6784a49938e', '15c34bb8dd5ba592dc725b44ef32d694', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/12/2f57f0ddbb4cb1c733e2ce7ffdda5d41.jpg', 'http://a.com', '7', 0),
(24, '测是', 0, 'c3c8f32dd3c923781c04f9ce1b7b4c44', '12b8954eb5d4994e232ad061358b6392', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/12/9e57bad83d712f52ecdfd13b6194ba6e.jpg', 'http://a.com', '3', 0),
(25, '1236', 0, '74aae45030fa23de498c99ac2340e2a2', '0726d20997daa10b186ca05c8b1c2b77', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/12/8ba3d2c024efa3570b60f2ea1919611a.jpg', 'http://a.com', '2', 0),
(26, 'SOOOOI服饰', 0, 'd6ebe065c208cc9061e98e4db0f5a01d', '71113f758c01a4643cfc38bd6c756b01', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/12/8ef4b77f8beb62d24f57f3eec7a6f5c8.jpg', 'http://a.com', '7', 1),
(27, '测试站', 0, 'f5430ff3b2129886aa2ed2aeff48499b', 'e89738d3ab9e6566c3ecdb05f24adc9f', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/12/cc3f7c0cd206f856a6cb6e989ae3ae31.jpg', 'http://a.com', '17,18', 1),
(28, '修改', 0, '42619b49e0c4679209c1840ab49c0fc2', 'b6a70cee84a9f6f440dcf60747b051aa', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/13/a9706a428b90f3a426f01193ae089ac5.jpg', 'http://a.com', '6', 1),
(29, '12344', 0, '8b4da7bef398e4a5bb09d60a9697325e', 'da68e57c9ffd225aae5e404b69a5498a', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/13/960750d2daad329e658fdc33f4b493b6.jpg', 'http://a.com', '7,16', 0),
(30, '正式问题', 0, '96470acc253da2f9d9d01885d389564c', 'e51a9998cab7777e171baade8d555620', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/12/cd52ff3816b44ea73226509ee9458953.jpg', 'http://a.com', '6,7', 1),
(31, '问问', 0, '8c206890ace11ecfedfe5e7536f77699', '2e5a343754f865d1c18a9e71ed917286', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/13/a1b7a49286418c4619035f0f1b340610.jpg', 'http://a.com', '7', 1),
(32, 'adsa', 0, '7eb3cedd7757b1389855d15167cc6924', '6237c9c3610dab69dbc9e68b043cd9c9', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/21/a20de6b09a7501903c4f2a78f1eb115a.png', 'http://backend.a.com/', '28', 1),
(33, 'adsasd', 0, 'a556d8cad5e50ff54282ae355f1c6094', '2943f22f39e8a6fcd62ddb7179678cb9', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/21/8c03dd3fe655fd87de9ea16e2004ea0e.png', 'http://backend.a.com/', '18,28', 0),
(34, 'jkijjuju', 0, '771610e81fb90d07443234176d7dadd1', '927cedb874478e6934b0b48f52c6fb69', 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/21/7452169e10c37f03bcde9041b8d9bada.png', 'http://backend.a.com/site/inde', '28', 0),
(35, 'cztv-test', 0, 'a19fa98936e2108c2da170fbcbedef9f', 'e1e3ed86ca2108af06cc1f41b47bdf3e', 'http://yaoxueren.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/26/11657f22550d61f2358b74b510c9edaf.jpg', 'http://www.xianghunet.com', '18,28', 0);

-- --------------------------------------------------------

--
-- 表的结构 `stations`
--

CREATE TABLE IF NOT EXISTS `stations` (
  `id` int(11) unsigned NOT NULL COMMENT '电台ID',
  `is_system` tinyint(1) unsigned NOT NULL COMMENT '是否系统级',
  `channel_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所属频道',
  `code` int(11) unsigned NOT NULL COMMENT '电台编号',
  `name` varchar(99) NOT NULL COMMENT '电台名',
  `type` int(11) NOT NULL COMMENT '电台类型 1：电视台 2：广播台',
  `logo` varchar(255) DEFAULT NULL COMMENT 'logo',
  `channel_name` varchar(99) NOT NULL COMMENT '直播流相关字段1',
  `customer_name` varchar(99) NOT NULL COMMENT '直播流相关字段2',
  `epg_path` varchar(255) NOT NULL COMMENT '直播流相关字段3'
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 COMMENT='电视台表';

--
-- 转存表中的数据 `stations`
--

INSERT INTO `stations` (`id`, `is_system`, `channel_id`, `code`, `name`, `type`, `logo`, `channel_name`, `customer_name`, `epg_path`) VALUES
(18, 1, 0, 105, '浙江少儿', 1, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/13/4a5eeeb2f397570e6cc8915a2ed77da5.jpg', '123', '321', '123'),
(28, 1, 0, 101, '浙江卫视', 1, 'http://cztv-backend-beta.oss-cn-hangzhou.aliyuncs.com/logos/2015/10/16/b704a7f5c51b640b55e1e6586ed6c2ca.jpg', 'channel11', 'lantian', 'channels/lantian/channel03');

-- --------------------------------------------------------

--
-- 表的结构 `stations_epg`
--

CREATE TABLE IF NOT EXISTS `stations_epg` (
  `id` int(11) unsigned NOT NULL COMMENT '直播流ID',
  `stations_id` int(11) unsigned NOT NULL COMMENT '电视台ID',
  `name` varchar(99) NOT NULL COMMENT '直播流名',
  `width` smallint(4) unsigned NOT NULL COMMENT '宽度',
  `height` smallint(4) unsigned NOT NULL COMMENT '高度',
  `cdn` text NOT NULL COMMENT 'cdn',
  `percent` text NOT NULL,
  `kpbs` smallint(4) unsigned NOT NULL COMMENT '码率',
  `audiokpbs` smallint(11) NOT NULL COMMENT '音频码率',
  `drm` tinyint(4) NOT NULL COMMENT '防盗链'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='电视节目流';

--
-- 转存表中的数据 `stations_epg`
--

INSERT INTO `stations_epg` (`id`, `stations_id`, `name`, `width`, `height`, `cdn`, `percent`, `kpbs`, `audiokpbs`, `drm`) VALUES
(5, 103, '540p', 720, 540, 'http://hls4.l.cztv.com', '100', 781, 60, 0),
(7, 103, '540p', 720, 540, 'http://hls4.l.cztv.com', '100', 781, 60, 1);

-- --------------------------------------------------------

--
-- 表的结构 `stations_program`
--

CREATE TABLE IF NOT EXISTS `stations_program` (
  `id` int(11) unsigned NOT NULL COMMENT '节目ID',
  `stations_id` int(11) unsigned NOT NULL COMMENT '电视ID',
  `title` varchar(99) NOT NULL COMMENT '标题',
  `start` int(11) unsigned NOT NULL COMMENT '开始时间',
  `duration` int(11) unsigned NOT NULL COMMENT '时长',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年'
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='电视节目单'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;

--
-- 转存表中的数据 `stations_program`
--

INSERT INTO `stations_program` (`id`, `stations_id`, `title`, `start`, `duration`, `partition_by`) VALUES
(3, 123, '123', 1437012600, 82, 2015),
(4, 104, '112', 1443089880, 732, 2015),
(5, 7, 'Test ID', 1443074340, 130, 2015),
(10, 121, '1212', 1445841060, 90, 2015),
(11, 123321, '123321', 1445842740, 61, 2015),
(12, 3333, '3333', 1445937420, 122, 2015),
(13, 4444, '4444', 1445862000, 122, 2015),
(14, 55555, '55555', 1445862000, 245, 2015);

-- --------------------------------------------------------

--
-- 表的结构 `supplies`
--

CREATE TABLE IF NOT EXISTS `supplies` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID',
  `source_id` int(11) unsigned NOT NULL COMMENT '数据源',
  `supply_category_id` int(11) unsigned NOT NULL COMMENT '供应分类ID',
  `origin_content` text COMMENT '原始数据',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '年分区'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='外部供应数据表'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;

-- --------------------------------------------------------

--
-- 表的结构 `supply_categories`
--

CREATE TABLE IF NOT EXISTS `supply_categories` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID',
  `source_id` int(11) unsigned NOT NULL COMMENT '源ID',
  `origin_id` int(11) unsigned NOT NULL COMMENT '原始数据分类ID',
  `origin_name` varchar(128) NOT NULL DEFAULT '' COMMENT '原始数据分类名'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='外部供应数据 - 分类表';

-- --------------------------------------------------------

--
-- 表的结构 `supply_sources`
--

CREATE TABLE IF NOT EXISTS `supply_sources` (
  `id` int(11) unsigned NOT NULL COMMENT '供应源ID',
  `type` enum('video','news') NOT NULL DEFAULT 'video' COMMENT '供应源类型',
  `name` varchar(256) NOT NULL DEFAULT '' COMMENT '供应源名称',
  `eshort` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='外部供应数据 - 源数据';

-- --------------------------------------------------------

--
-- 表的结构 `supply_to_category`
--

CREATE TABLE IF NOT EXISTS `supply_to_category` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID',
  `supply_category_id` int(11) unsigned NOT NULL COMMENT '供应数据分类ID',
  `category_id` int(11) unsigned NOT NULL COMMENT '分类数据'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='外部供应数据与发布渠道的对应关系';

-- --------------------------------------------------------

--
-- 表的结构 `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属频道id',
  `title` varchar(1024) NOT NULL COMMENT '任务名称',
  `content_id` int(11) NOT NULL,
  `attachnum` int(11) NOT NULL COMMENT '附件数量',
  `receiver` int(11) NOT NULL DEFAULT '0' COMMENT '接收者',
  `receiver_name` varchar(99) NOT NULL COMMENT '存放姓名拼音 排序用',
  `creator` int(11) NOT NULL DEFAULT '0' COMMENT '创建者',
  `creator_name` varchar(99) NOT NULL,
  `progress` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0:新建(未分配) 1:未接收 2:拒绝 3:重新打开 4:进行中 5:审核驳回 6:提交审核 7:同意完成 8:已评分',
  `subs_complete` smallint(11) DEFAULT '1' COMMENT ' 0: 无分拆 1：未完成 2：全部完成',
  `notify` tinyint(4) NOT NULL COMMENT '是否在通知栏展示',
  `score` varchar(255) DEFAULT NULL,
  `priority` smallint(6) DEFAULT NULL COMMENT '优先级',
  `start` int(11) DEFAULT NULL COMMENT '预计开始',
  `end` int(11) DEFAULT NULL,
  `actual_start` int(11) DEFAULT NULL COMMENT '实际开始',
  `actual_end` int(11) DEFAULT NULL,
  `is_main` tinyint(4) NOT NULL DEFAULT '1' COMMENT '主任务',
  `isolate_code` int(11) NOT NULL COMMENT '任务树编号',
  `Lft` int(11) NOT NULL COMMENT '任务树左值',
  `Rgt` int(11) NOT NULL COMMENT '任务树右值',
  `depth` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1:审核 2:未审核 3:删除',
  `created` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=399 DEFAULT CHARSET=utf8 COMMENT='任务表';

--
-- 转存表中的数据 `tasks`
--

INSERT INTO `tasks` (`id`, `channel_id`, `title`, `content_id`, `attachnum`, `receiver`, `receiver_name`, `creator`, `creator_name`, `progress`, `subs_complete`, `notify`, `score`, `priority`, `start`, `end`, `actual_start`, `actual_end`, `is_main`, `isolate_code`, `Lft`, `Rgt`, `depth`, `status`, `created`) VALUES
(1, 0, '【长期任务】了解市县云平台构架与模板', 37, 0, 111, '1', 111, '1', 7, 1, 1, NULL, 0, 1437470220, 1438334220, 1442996964, 0, 1, 1, 1, 14, 1, 1, 1442996964),
(2, 0, '中国蓝互动研究与开发', 0, 0, 111, '1', 111, '1', 9, 1, 0, NULL, 0, 1437472440, 1441446840, 1442996964, 0, 1, 2, 1, 18, 1, 1, 1442996964),
(3, 0, 'Python研究', 0, 0, 0, '1', 0, '1', 9, 1, 3, NULL, 0, 1438052700, 1439003100, 1442996964, 0, 1, 3, 1, 14, 1, 1, 1442996964),
(4, 0, '节目单接口优化', 0, 0, 106, '1', 106, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438575660, 1439785260, 1442996964, 0, 1, 4, 1, 4, 1, 1, 1442996964),
(5, 0, '任务管理系统新建任务优化需求', 0, 0, 106, '1', 106, '1', 7, 1, 0, NULL, 0, 1438584600, 1438671000, 1442996964, 0, 1, 5, 1, 4, 1, 1, 1442996964),
(6, 0, '创建任务时，即可进行任务分配', 0, 0, 106, '1', 106, '1', 7, 1, 0, NULL, 0, 1438586160, 1438845360, 1442996964, 0, 1, 6, 1, 8, 1, 1, 1442996964),
(7, 0, '象山影视基地方案', 0, 0, 106, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438606440, 1438692840, 1442996964, 0, 1, 7, 1, 2, 1, 1, 1442996964),
(8, 0, '明天早晨开会', 0, 0, 106, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438682940, 1438736400, 1442996964, 0, 1, 8, 1, 2, 1, 1, 1442996964),
(9, 0, '自己给自己设置任务的需求修改。', 0, 0, 111, '1', 106, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438683060, 1438769460, 1442996964, 0, 1, 9, 1, 6, 1, 1, 1442996964),
(10, 0, '员工管理菜单优化', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["9"],"2":["10"],"3":["10"]}', 0, 1438750380, 1438836780, 1442996964, 0, 1, 10, 1, 2, 1, 1, 1442996964),
(11, 0, '频道管理菜单优化', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["9"],"2":["10"],"3":["10"]}', 0, 1438751760, 1438838160, 1442996964, 0, 1, 11, 1, 2, 1, 1, 1442996964),
(12, 0, '系统管理内，二级菜单，接口管理菜单优化。', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438752000, 1438838400, 1442996964, 0, 1, 12, 1, 2, 1, 1, 1442996964),
(13, 0, '好好', 0, 0, 90, '1', 90, '1', 9, 1, 0, NULL, 0, 1438756740, 1441867140, 1442996964, 0, 1, 13, 1, 6, 1, 1, 1442996964),
(14, 0, '将达到近爱上了就打算考劳动局傻了', 0, 0, 136, '1', 90, '1', 9, 0, 0, NULL, 0, 1438757520, 1438843920, 1442996964, 0, 1, 14, 1, 2, 1, 1, 1442996964),
(15, 0, '给自己一个任务测试', 0, 0, 106, '1', 106, '1', 7, 0, 0, NULL, 0, 1438771740, 1438858140, 1442996965, 0, 1, 15, 1, 2, 1, 1, 1442996965),
(16, 0, '我的第一个任务', 0, 0, 84, '1', 84, '1', 9, 1, 0, NULL, 0, 1438834860, 1438921260, 1442996965, 0, 1, 16, 1, 4, 1, 1, 1442996965),
(17, 0, '任务管理模块增加“撤回任务”状态', 0, 0, 111, '1', 106, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438840440, 1438855200, 1442996965, 0, 1, 17, 1, 6, 1, 1, 1442996965),
(18, 0, '任务模块之“任务过期”的需求', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["1"],"2":["1"],"3":["1"]}', 0, 1438841520, 1438927920, 1442996965, 0, 1, 18, 1, 2, 1, 1, 1442996965),
(19, 0, '新蓝网开发的APP的检查汇总工作', 0, 0, 110, '1', 106, '1', 8, 1, 0, '{"1":["9"],"2":["9"],"3":["9"]}', 0, 1438913580, 1439518380, 1442996965, 0, 1, 19, 1, 8, 1, 1, 1442996965),
(20, 0, '查找快网DNS8.8.8.8比较慢的问题原因', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438924500, 1439183700, 1442996965, 0, 1, 20, 1, 2, 1, 1, 1442996965),
(21, 0, '技术群里一份绿盟关于我们网站的扫描报告', 0, 0, 110, '1', 106, '1', 8, 1, 0, '{"1":["9"],"2":["9"],"3":["10"]}', 0, 1438927980, 1439548200, 1442996965, 0, 1, 21, 1, 8, 1, 1, 1442996965),
(22, 0, '互动任务系统BUG修改', 0, 0, 110, '1', 110, '1', 7, 1, 0, NULL, 0, 1438928220, 1440950400, 1442996965, 0, 1, 22, 1, 4, 1, 1, 1442996965),
(23, 0, '中国蓝TV的H5页面播播放M3U8的可行性分析', 0, 0, 107, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438931580, 1439536380, 1442996965, 0, 1, 23, 1, 2, 1, 1, 1442996965),
(24, 0, '上传的附件（如world,excel）可以在线预览', 0, 0, 106, '1', 97, '1', 9, 0, 0, NULL, 0, 1438931820, 1439018220, 1442996965, 0, 1, 24, 1, 2, 1, 1, 1442996965),
(25, 0, '任务指派到人时可以手动输入名字或输入时可以自动名字匹配提示', 0, 0, 106, '1', 97, '1', 8, 1, 0, '{"1":"1","2":"1","3":"1"}', 0, 1439137260, 1441005660, 1442996965, 0, 1, 25, 1, 6, 1, 1, 1442996965),
(26, 0, '任务分类归属', 0, 0, 106, '1', 84, '1', 9, 0, 0, NULL, 0, 1438933320, 1439019720, 1442996965, 0, 1, 26, 1, 2, 1, 1, 1442996965),
(27, 0, '截屏工具在mac下无法使用', 0, 0, 106, '1', 86, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439195280, 1439540880, 1442996965, 0, 1, 27, 1, 6, 1, 1, 1442996965),
(28, 0, '建议', 0, 0, 99, '1', 99, '1', 7, 0, 0, NULL, 0, 1438936680, 1439023080, 1442996965, 0, 1, 28, 1, 2, 1, 1, 1442996965),
(29, 0, '完成详情可见度，所有人可见', 0, 0, 106, '1', 111, '1', 9, 0, 0, NULL, 0, 1438936680, 1439023080, 1442996965, 0, 1, 29, 1, 2, 1, 1, 1442996965),
(30, 0, '任务开始时间不能设置为当前以前的日期', 0, 0, 97, '1', 97, '1', 7, 0, 0, NULL, 0, 1438418280, 1439023080, 1442996965, 0, 1, 30, 1, 2, 1, 1, 1442996965),
(31, 0, '建议', 0, 0, 106, '1', 99, '1', 9, 0, 0, NULL, 0, 1438936860, 1439023260, 1442996965, 0, 1, 31, 1, 2, 1, 1, 1442996965),
(32, 0, '开始时间与结束时间显示方式太low', 0, 0, 106, '1', 84, '1', 9, 1, 0, NULL, 0, 1438936680, 1439199000, 1442996965, 0, 1, 32, 1, 4, 1, 1, 1442996965),
(33, 0, '任务列表建议加入筛选', 0, 0, 106, '1', 90, '1', 9, 0, 0, NULL, 0, 1438936920, 1440146520, 1442996965, 0, 1, 33, 1, 2, 1, 1, 1442996965),
(34, 0, '出差金额备忘', 0, 0, 106, '1', 106, '1', 7, 0, 0, NULL, 0, 1438937040, 1439541840, 1442996965, 0, 1, 34, 1, 2, 1, 1, 1442996965),
(35, 0, '任务系统建议', 0, 0, 106, '1', 87, '1', 9, 0, 0, NULL, 0, 1438937100, 1439541900, 1442996965, 0, 1, 35, 1, 2, 1, 1, 1442996965),
(36, 0, '任务指派', 0, 0, 106, '1', 110, '1', 9, 0, 0, NULL, 0, 1438937460, 1439542260, 1442996965, 0, 1, 36, 1, 2, 1, 1, 1442996965),
(37, 0, '建议', 0, 0, 106, '1', 99, '1', 9, 1, 0, NULL, 0, 1438937640, 1441011240, 1442996965, 0, 1, 37, 1, 6, 1, 1, 1442996965),
(38, 0, '个人中心个人资料建议', 0, 0, 106, '1', 136, '1', 9, 0, 0, NULL, 0, 1438938120, 1439542920, 1442996965, 0, 1, 38, 1, 2, 1, 1, 1442996965),
(39, 0, '任务系统建议', 0, 0, 106, '1', 87, '1', 9, 0, 0, NULL, 0, 1438938300, 1439543100, 1442996965, 0, 1, 39, 1, 2, 1, 1, 1442996965),
(40, 0, '我的任务中的子任务，旁边显示主任务', 0, 0, 106, '1', 111, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438938360, 1439024760, 1442996965, 0, 1, 40, 1, 4, 1, 1, 1442996965),
(41, 0, '建议', 0, 0, 106, '1', 85, '1', 9, 0, 0, NULL, 0, 1438938120, 1439197320, 1442996965, 0, 1, 41, 1, 2, 1, 1, 1442996965),
(42, 0, '对任务管理的建议', 0, 0, 106, '1', 83, '1', 9, 0, 0, NULL, 0, 1438938360, 1439197560, 1442996965, 0, 1, 42, 1, 2, 1, 1, 1442996965),
(43, 0, '当前页面可批量添加任务', 0, 0, 106, '1', 97, '1', 9, 0, 0, NULL, 0, 1438938840, 1439025240, 1442996966, 0, 1, 43, 1, 2, 1, 1, 1442996966),
(44, 0, '在会员列表中希望也能加入搜索功能', 0, 0, 106, '1', 136, '1', 9, 0, 0, NULL, 0, 1438938960, 1439543760, 1442996966, 0, 1, 44, 1, 2, 1, 1, 1442996966),
(45, 0, '测试重新打开', 0, 0, 0, '1', 0, '1', 8, 0, 3, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438939500, 1439025900, 1442996966, 0, 1, 45, 1, 2, 1, 1, 1442996966),
(46, 0, '中国蓝TV优化事项', 0, 0, 106, '1', 106, '1', 7, 1, 0, NULL, 0, 1438940040, 1441013640, 1442996966, 0, 1, 46, 1, 48, 1, 1, 1442996966),
(47, 0, '建议', 0, 0, 106, '1', 85, '1', 9, 0, 0, NULL, 0, 1438940100, 1439199300, 1442996966, 0, 1, 47, 1, 2, 1, 1, 1442996966),
(48, 0, '设置任务优先级详细', 0, 0, 106, '1', 83, '1', 9, 0, 0, NULL, 0, 1438940280, 1439199480, 1442996966, 0, 1, 48, 1, 2, 1, 1, 1442996966),
(49, 0, '蓝天云听版本显示异常', 0, 0, 86, '1', 86, '1', 7, 0, 0, NULL, 0, 1439168280, 1439281800, 1442996966, 0, 1, 49, 1, 2, 1, 1, 1442996966),
(50, 0, '余姚手机台版本更新', 0, 0, 86, '1', 86, '1', 7, 0, 0, NULL, 0, 1439168400, 1439800200, 1442996966, 0, 1, 50, 1, 2, 1, 1, 1442996966),
(51, 0, '中国蓝TV：修复定时刷新直播页面评论列表', 0, 0, 86, '1', 86, '1', 7, 0, 0, NULL, 0, 1439254920, 1439281800, 1442996966, 0, 1, 51, 1, 2, 1, 1, 1442996966),
(52, 0, '中国蓝TV_iOS版个推推送方式', 0, 0, 86, '1', 86, '1', 7, 0, 0, NULL, 0, 1439261580, 1439265600, 1442996966, 0, 1, 52, 1, 2, 1, 1, 1442996966),
(53, 0, '中国蓝TV_iOS端广告数据不上报BUG', 0, 0, 86, '1', 86, '1', 7, 0, 0, NULL, 0, 1439274780, 1439281800, 1442996966, 0, 1, 53, 1, 2, 1, 1, 1442996966),
(54, 0, '中国蓝TV安卓版百度渠道包9点半活动', 0, 0, 110, '1', 110, '1', 7, 1, 0, NULL, 0, 1439285940, 1440149400, 1442996966, 0, 1, 54, 1, 8, 1, 1, 1442996966),
(55, 0, '新蓝网开发的APP的上架情况及更新情况', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439346900, 1439519700, 1442996966, 0, 1, 55, 1, 2, 1, 1, 1442996966),
(56, 0, '任务管理意见', 0, 0, 106, '1', 96, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439368680, 1441010280, 1442996966, 0, 1, 56, 1, 2, 1, 1, 1442996966),
(57, 0, '我的任务的子菜单修改及默认结束时间', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439428740, 1439515140, 1442996966, 0, 1, 57, 1, 2, 1, 1, 1442996966),
(58, 0, '中国蓝tv测试用例编写', 0, 0, 83, '1', 109, '1', 8, 0, 0, '{"1":["6"],"2":["7"],"3":["8"]}', 0, 1439431440, 1439784000, 1442996966, 0, 1, 58, 1, 2, 1, 1, 1442996966),
(59, 0, '中国蓝TV测试用例编写', 0, 0, 85, '1', 109, '1', 8, 0, 0, '{"1":["7"],"2":["9"],"3":["9"]}', 0, 1439431560, 1439784000, 1442996966, 0, 1, 59, 1, 2, 1, 1, 1442996966),
(60, 0, '​中国蓝TV测试用例编写', 0, 0, 87, '1', 109, '1', 8, 0, 0, '{"1":["9"],"2":["8"],"3":["8"]}', 0, 1439431620, 1439784000, 1442996966, 0, 1, 60, 1, 2, 1, 1, 1442996966),
(61, 0, '​中国蓝TV测试用例编写', 0, 0, 90, '1', 109, '1', 8, 0, 0, '{"1":["8"],"2":["8"],"3":["8"]}', 0, 1439431620, 1439784000, 1442996966, 0, 1, 61, 1, 2, 1, 1, 1442996966),
(62, 0, '测试文件', 0, 0, 105, '1', 105, '1', 9, 0, 0, NULL, 0, 1439434320, 1440039120, 1442996966, 0, 1, 62, 1, 2, 1, 1, 1442996966),
(63, 0, '沃尔沃热', 0, 0, 105, '1', 105, '1', 9, 0, 0, NULL, 0, 1439434740, 1440039540, 1442996966, 0, 1, 63, 1, 2, 1, 1, 1442996966),
(64, 0, '任务系统建议1', 0, 0, 106, '1', 105, '1', 8, 1, 0, '{"1":["9"],"2":["9"],"3":["9"]}', 0, 1439435400, 1440126600, 1442996966, 0, 1, 64, 1, 8, 1, 1, 1442996966),
(65, 0, '任务系统建议2', 0, 0, 106, '1', 105, '1', 9, 0, 0, NULL, 0, 1439436240, 1440041040, 1442996966, 0, 1, 65, 1, 2, 1, 1, 1442996966),
(66, 0, '中国蓝TV app安卓版的好声音皮肤修改', 0, 0, 97, '1', 96, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439532660, 1439544600, 1442996966, 0, 1, 66, 1, 2, 1, 1, 1442996966),
(67, 0, '点击完成任务后，在自己的任务列表没有显示', 0, 0, 106, '1', 97, '1', 9, 0, 0, NULL, 0, 1439538900, 1440143700, 1442996966, 0, 1, 67, 1, 2, 1, 1, 1442996966),
(68, 0, '任务列表可以按各种状态进行排序', 0, 0, 106, '1', 97, '1', 8, 1, 0, '{"1":["10"],"2":["9"],"3":["10"]}', 0, 1439539320, 1440144120, 1442996966, 0, 1, 68, 1, 10, 1, 1, 1442996966),
(69, 0, '附件上传bug错误测试', 0, 0, 90, '1', 111, '1', 9, 0, 0, NULL, 0, 1439540880, 1440145680, 1442996966, 0, 1, 69, 1, 2, 1, 1, 1442996966),
(70, 0, '[任务系统优化建议] 增加任务优先级', 0, 0, 106, '1', 84, '1', 9, 0, 0, NULL, 0, 1439697420, 1440151200, 1442996966, 0, 1, 70, 1, 2, 1, 1, 1442996966),
(71, 0, '[任务系统优化建议] 任务列表【主】任务标识显示相关', 0, 0, 106, '1', 84, '1', 9, 0, 0, NULL, 0, 1439697900, 1440151200, 1442996966, 0, 1, 71, 1, 2, 1, 1, 1442996966),
(72, 0, '修复中国蓝互动iOS版通讯录', 0, 0, 86, '1', 86, '1', 7, 0, 0, NULL, 0, 1439774700, 1440379500, 1442996966, 0, 1, 72, 1, 2, 1, 1, 1442996966),
(73, 0, '浙江手机台联通定制版的回源地址修改', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1439776680, 1443578280, 1442996967, 0, 1, 73, 1, 2, 1, 1, 1442996967),
(74, 0, '浙江手机台联通用户退费', 0, 0, 106, '1', 106, '1', 7, 0, 0, NULL, 0, 1439778240, 1442802240, 1442996967, 0, 1, 74, 1, 2, 1, 1, 1442996967),
(75, 0, '湘湖网高负载问题', 0, 0, 111, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439779860, 1439956800, 1442996967, 0, 1, 75, 1, 2, 1, 1, 1442996967),
(76, 0, '绿盟漏洞报告文档整理', 0, 0, 83, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439779980, 1440043200, 1442996967, 0, 1, 76, 1, 2, 1, 1, 1442996967),
(77, 0, '手机客户端收费推送可行性方案', 0, 0, 106, '1', 106, '1', 7, 0, 0, NULL, 0, 1439781480, 1441163880, 1442996967, 0, 1, 77, 1, 2, 1, 1, 1442996967),
(78, 0, '互动平台开放任务的API', 0, 0, 106, '1', 86, '1', 8, 1, 0, '{"1":"10","2":"10","3":"10"}', 0, 1439800560, 1440750960, 1442996967, 0, 1, 78, 1, 8, 1, 1, 1442996967),
(79, 0, '中国蓝互动增加离线功能', 0, 0, 86, '1', 86, '1', 7, 0, 0, NULL, 0, 1439800980, 1440751380, 1442996967, 0, 1, 79, 1, 2, 1, 1, 1442996967),
(80, 0, '各市县云平台合作广电手机客户端在互动平台管理', 0, 0, 109, '1', 106, '1', 4, 1, 0, NULL, 0, 1439875680, 1441015200, 1442996967, 0, 1, 80, 1, 12, 1, 1, 1442996967),
(81, 0, '无效的任务或已经完成的任务在自己的后台可以删除', 0, 0, 106, '1', 97, '1', 9, 0, 0, NULL, 0, 1439946240, 1440983040, 1442996967, 0, 1, 81, 1, 2, 1, 1, 1442996967),
(82, 0, '特殊任务处理的设想', 0, 0, 106, '1', 111, '1', 9, 0, 0, NULL, 0, 1439950200, 1440555000, 1442996967, 0, 1, 82, 1, 2, 1, 1, 1442996967),
(83, 0, '中国蓝TV的APP中礼包隐藏的需求', 0, 0, 109, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1439963460, 1440568260, 1442996967, 0, 1, 83, 1, 2, 1, 1, 1442996967),
(84, 0, '修改任务时，被拒绝的原因也应该显示的在修改的编辑界面里', 0, 0, 106, '1', 97, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439969040, 1440573840, 1442996967, 0, 1, 84, 1, 2, 1, 1, 1442996967),
(85, 0, '中国蓝TV的BUG修改', 0, 0, 86, '1', 86, '1', 7, 0, 0, NULL, 0, 1440033600, 1440638400, 1442996967, 0, 1, 85, 1, 2, 1, 1, 1442996967),
(86, 0, '互动平台，增加推送功能', 0, 0, 86, '1', 86, '1', 7, 0, 0, NULL, 0, 1440034500, 1442885700, 1442996967, 0, 1, 86, 1, 2, 1, 1, 1442996967),
(87, 0, '余姚台APP优化更新', 0, 0, 86, '1', 109, '1', 9, 0, 0, NULL, 0, 1440117720, 1440636120, 1442996967, 0, 1, 87, 1, 2, 1, 1, 1442996967),
(88, 0, '余姚台手机app更新优化', 0, 0, 97, '1', 109, '1', 8, 1, 0, '{"1":"10","2":"10","3":"10"}', 0, 1440117840, 1440636240, 1442996967, 0, 1, 88, 1, 6, 1, 1, 1442996967),
(89, 0, '测试', 0, 0, 106, '1', 106, '1', 7, 0, 0, NULL, 0, 1440119700, 1440724500, 1442996967, 0, 1, 89, 1, 2, 1, 1, 1442996967),
(90, 0, '自己给自己创建的任务的状态', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440120600, 1440725400, 1442996967, 0, 1, 90, 1, 2, 1, 1, 1442996967),
(91, 0, '个推帐户中，各市县广电APP的建立', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1440120720, 1440725520, 1442996967, 0, 1, 91, 1, 2, 1, 1, 1442996967),
(92, 0, '互动平台，基于频道下各产品的个推的推送服务。', 0, 0, 110, '1', 106, '1', 8, 0, 0, '{"1":["8"],"2":["8"],"3":["8"]}', 0, 1440121080, 1440725880, 1442996967, 0, 1, 92, 1, 2, 1, 1, 1442996967),
(93, 0, '现在live1和mala的直播页，广告还没结束，直播画面就进来了，广告的声音还在。', 0, 0, 109, '1', 106, '1', 8, 1, 0, '{"1":"10","2":"10","3":"10"}', 0, 1440164460, 1440769260, 1442996967, 0, 1, 93, 1, 4, 1, 1, 1442996967),
(94, 0, '余姚广电的收录情况观察', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":["9"],"2":["9"],"3":["9"]}', 0, 1440165480, 1441029480, 1442996967, 0, 1, 94, 1, 2, 1, 1, 1442996967),
(95, 0, 'APP推送通知的检测功能', 0, 0, 106, '1', 106, '1', 7, 1, 0, NULL, 0, 1440165780, 1442844180, 1442996967, 0, 1, 95, 1, 8, 1, 1, 1442996967),
(96, 0, '米秀业务CDN换到快网', 0, 0, 112, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440386400, 1440991200, 1442996967, 0, 1, 96, 1, 2, 1, 1, 1442996967),
(97, 0, '短信帐户的使用情况统计分析', 0, 0, 112, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440404940, 1440466200, 1442996967, 0, 1, 97, 1, 2, 1, 1, 1442996967),
(98, 0, '任务完成但还在等待审核时还可以再编辑任务的完成详情', 0, 0, 106, '1', 97, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440479280, 1441084080, 1442996967, 0, 1, 98, 1, 6, 1, 1, 1442996967),
(99, 0, '【任务系统】程序+数据库重新设计', 0, 0, 111, '1', 84, '1', 8, 1, 0, '{"1":"10","2":"10","3":"10"}', 0, 1440480060, 1441360800, 1442996967, 0, 1, 99, 1, 6, 1, 1, 1442996967),
(100, 0, '新蓝网原有架构上存在的缺陷，或者继续做而没有做的', 0, 0, 112, '1', 84, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440482820, 1440756000, 1442996968, 0, 1, 100, 1, 2, 1, 1, 1442996968),
(101, 0, '萧山广电平台，前台增加两台服务器，务必今天完成。', 0, 0, 106, '1', 106, '1', 9, 0, 0, NULL, 0, 1440550260, 1440583200, 1442996968, 0, 1, 101, 1, 2, 1, 1, 1442996968),
(102, 0, '萧山广电 平台，前台扩容两台服务器，务必今天完成', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["9"],"2":["10"],"3":["10"]}', 0, 1440550380, 1440583200, 1442996968, 0, 1, 102, 1, 2, 1, 1, 1442996968),
(103, 0, 'BUG-自己给自己创建了任务后，修改任务时，无法选择其它人', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["6"],"2":["6"],"3":["6"]}', 0, 1440550440, 1440579600, 1442996968, 0, 1, 103, 1, 2, 1, 1, 1442996968),
(104, 0, '我自己“等待处理”的任务，只有两个，为什么在画圈的地方，有6个？\r\n\r\n点击6个后，就是这个页面！', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440556020, 1440669600, 1442996968, 0, 1, 104, 1, 2, 1, 1, 1442996968),
(105, 0, '做一个图片，具体要求：PNG格式、大小1280*720 ，左上角为“中国蓝TV”的LOGO，右下角为“大华技术支持”', 0, 0, 96, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["9"],"3":["10"]}', 0, 1440559380, 1440572400, 1442996968, 0, 1, 105, 1, 2, 1, 1, 1442996968),
(106, 0, '网络直播的流程整理成文档，并进行内部人员培训。', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1440582900, 1441274100, 1442996968, 0, 1, 106, 1, 2, 1, 1, 1442996968),
(107, 0, '帮助向叶补一张请假单', 0, 0, 90, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["8"],"3":["10"]}', 0, 1440583920, 1440670320, 1442996968, 0, 1, 107, 1, 2, 1, 1, 1442996968),
(108, 0, '给自己的任务', 0, 0, 111, '1', 111, '1', 7, 0, 1, NULL, 0, 1440588960, 1441193760, 1442996968, 0, 1, 108, 1, 2, 1, 1, 1442996968),
(109, 0, '我的任务页面，修改需求！', 0, 0, 82, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440662580, 1441353780, 1442996968, 0, 1, 109, 1, 2, 1, 1, 1442996968),
(110, 0, '根据专辑，定制播放页和播放器皮肤。\r\n根据专辑、频道，使用不同的播放器。', 0, 0, 106, '1', 106, '1', 7, 0, 0, NULL, 0, 1440671220, 1442485620, 1442996968, 0, 1, 110, 1, 2, 1, 1, 1442996968),
(111, 0, '视频外链播放器', 0, 0, 106, '1', 106, '1', 7, 1, 0, NULL, 0, 1440739020, 1441343820, 1442996968, 0, 1, 111, 1, 4, 1, 1, 1442996968),
(112, 0, '动画频道，不能够下载的开发评估', 0, 0, 110, '1', 106, '1', 5, 0, 0, NULL, 0, 1440742560, 1441088160, 1442996968, 0, 1, 112, 1, 2, 1, 1, 1442996968),
(113, 0, '余姚节目录制问题，现在的情况是 成功录制后，视频留在了wawaz 观止没有提取过去到vms处理，你联系协调解决一下', 0, 0, 105, '1', 111, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440743100, 1441185900, 1442996968, 0, 1, 113, 1, 2, 1, 1, 1442996968),
(114, 0, '给浙大单老师推一路1.5M中央一套的高清流，具体跟观止对接好！', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440747600, 1441006800, 1442996968, 0, 1, 114, 1, 2, 1, 1, 1442996968),
(115, 0, '“八大主播爱朋友”：动听968eric助力寒门学子,ios端无法分享，1.2.3有提示，1.2.4，无提示。原因查找！', 0, 0, 109, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1440752580, 1441011780, 1442996968, 0, 1, 115, 1, 2, 1, 1, 1442996968),
(116, 0, '国双优先建议的评估', 0, 0, 110, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1440753240, 1441098840, 1442996968, 0, 1, 116, 1, 2, 1, 1, 1442996968),
(117, 0, '前台资源的ftp位置，与乐视沟通拿到，给从林', 0, 0, 110, '1', 106, '1', 8, 0, 0, '{"1":["8"],"2":["9"],"3":["9"]}', 0, 1440753540, 1441012740, 1442996968, 0, 1, 117, 1, 2, 1, 1, 1442996968),
(118, 0, '【徐辉】的cms账号为新的超管账号，向乐视拿到。', 0, 0, 109, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440753600, 1441012800, 1442996968, 0, 1, 118, 1, 2, 1, 1, 1442996968),
(119, 0, '主任务和子任务能有收缩功能，鼠标放在任务名称上面 能显示详情框', 0, 0, 106, '1', 136, '1', 9, 0, 0, NULL, 0, 0, 1441324800, 1442996968, 0, 1, 119, 1, 2, 1, 1, 1442996968),
(120, 0, '按照霞总的要求，帮忙协调设计和技术进行布局修改。', 0, 0, 96, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440763620, 1441109220, 1442996968, 0, 1, 120, 1, 2, 1, 1, 1442996968),
(121, 0, 'CMS后台招标需求\r\n招人需求', 0, 0, 106, '1', 106, '1', 7, 0, 0, NULL, 0, 1440768240, 1441200240, 1442996968, 0, 1, 121, 1, 2, 1, 1, 1442996968),
(122, 0, 'php程序中，phalcon框架下，解决定时任务的方法。\r\nPHP是短链接，在后台设置了某个时间点执行任务时，现有的解决方案是每隔一定时间进行查询，有没有办法可以有个时间进程通知功能，只有到了时间才去触发？', 0, 0, 84, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440772140, 1441031340, 1442996968, 0, 1, 122, 1, 2, 1, 1, 1442996968),
(123, 0, '阿里云搭转码服务平台，浪弯', 0, 0, 105, '1', 106, '1', 4, 0, 0, NULL, 0, 1440902940, 1442198940, 1442996968, 0, 1, 123, 1, 2, 1, 1, 1442996968),
(124, 0, '【教学】阅读thinkphp框架源码，学习其实现原理和核心机制 任务完成时需讲述考核', 0, 0, 83, '1', 84, '1', 8, 0, 0, '{"1":"5","2":"6","3":"8"}', 0, 1440982320, 1442203200, 1442996968, 0, 1, 124, 1, 2, 1, 1, 1442996968),
(125, 0, '【教学】阅读thinkphp框架源码，学习其实现原理和核心机制 任务完成时需讲述考核', 0, 0, 136, '1', 84, '1', 8, 0, 0, '{"1":"5","2":"6","3":"8"}', 0, 1440982500, 1442203200, 1442996969, 0, 1, 125, 1, 2, 1, 1, 1442996969),
(126, 0, '【教学】阅读thinkphp框架源码，学习其实现原理和核心机制 任务完成时需讲述考核', 0, 0, 87, '1', 84, '1', 8, 0, 0, '{"1":"5","2":"8","3":"8"}', 0, 1440982560, 1442203200, 1442996969, 0, 1, 126, 1, 2, 1, 1, 1442996969),
(127, 0, '柯桥直播的解决方案', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":["9"],"2":["8"],"3":["9"]}', 0, 1440991020, 1441017000, 1442996969, 0, 1, 127, 1, 2, 1, 1, 1442996969),
(128, 0, 'PHP的视频学习，学习完进行总结汇报', 0, 0, 90, '1', 106, '1', 4, 0, 0, NULL, 0, 1440996900, 1444712100, 1442996969, 0, 1, 128, 1, 2, 1, 1, 1442996969),
(129, 0, 'PHP框架学习  QEE框架的学习体会，进行汇报总结！', 0, 0, 87, '1', 106, '1', 8, 0, 0, '{"1":"7","2":"7","3":"7"}', 0, 1440997080, 1442206680, 1442996969, 0, 1, 129, 1, 2, 1, 1, 1442996969),
(130, 0, 'PHP框架学习  thinkphp框架的学习体会，进行汇报总结！', 0, 0, 83, '1', 106, '1', 8, 0, 0, '{"1":"9","2":"9","3":"9"}', 0, 1440997200, 1442206800, 1442996969, 0, 1, 130, 1, 2, 1, 1, 1442996969),
(131, 0, 'PHP框架学习  QEE框架的学习体会，进行汇报总结！', 0, 0, 136, '1', 106, '1', 8, 0, 0, '{"1":"8","2":"8","3":"8"}', 0, 1440997200, 1442206800, 1442996969, 0, 1, 131, 1, 2, 1, 1, 1442996969),
(132, 0, '202.107.195.195，这个IP地址，直播业务的优化', 0, 0, 105, '1', 106, '1', 9, 0, 0, NULL, 0, 1440998040, 1441017000, 1442996969, 0, 1, 132, 1, 2, 1, 1, 1442996969),
(133, 0, '中央一套直播的在线转码，1.5M，到CDN厂商', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["9"],"3":["10"]}', 0, 1440998220, 1441017000, 1442996969, 0, 1, 133, 1, 2, 1, 1, 1442996969),
(134, 0, '中国蓝TV的APP的反馈功能', 0, 0, 96, '1', 106, '1', 4, 0, 0, NULL, 0, 1441087680, 1441692480, 1442996969, 0, 1, 134, 1, 2, 1, 1, 1442996969),
(135, 0, '林总反馈的问题的查实', 0, 0, 109, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1441090020, 1441103400, 1442996969, 0, 1, 135, 1, 2, 1, 1, 1442996969),
(136, 0, '好运！', 0, 0, 85, '1', 85, '1', 9, 0, 0, NULL, 0, 1441094760, 1441699560, 1442996969, 0, 1, 136, 1, 2, 1, 1, 1442996969),
(137, 0, '萧山台电视直播页面无法全屏，希望全屏按钮能点击。', 0, 0, 110, '1', 109, '1', 8, 1, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441096440, 1441701240, 1442996969, 0, 1, 137, 1, 4, 1, 1, 1442996969),
(138, 0, '湘湖网 新闻列表页 下面分页点击错误，http://www.xianghunet.com/news/list/301.html', 0, 0, 111, '1', 109, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441096800, 1441701600, 1442996969, 0, 1, 138, 1, 2, 1, 1, 1442996969),
(139, 0, '请给中国蓝TV 的各端搭建测试环境，优先级8', 0, 0, 110, '1', 96, '1', 5, 0, 0, NULL, 0, 1441096260, 1441614660, 1442996969, 0, 1, 139, 1, 2, 1, 1, 1442996969),
(140, 0, '发现板块的视频增加全屏功能，全屏后需要有时间进度条拖拽。\r\n视频播放参考腾讯视频默认做消音播放，用户点击可以打开声音，可根据网络环境判断是否自动播放，wifi环境下下拉自动播放，非wifi环境下需要手动点击播放（默认有声音，可以关闭声音）优先级7', 0, 0, 86, '1', 96, '1', 5, 0, 0, NULL, 0, 1441098180, 1442998980, 1442996969, 0, 1, 140, 1, 2, 1, 1, 1442996969),
(141, 0, '限制评论内容的重复发送，30秒内限制相同评论内容的重复发送，1天评论条数上限50条，用户id拉黑后前端评论同步不显示。优先级9', 0, 0, 86, '1', 96, '1', 5, 0, 0, NULL, 0, 1441098300, 1442999100, 1442996969, 0, 1, 141, 1, 2, 1, 1, 1442996969),
(142, 0, '电视剧需增加预告片的剧集字段，优先级7', 0, 0, 110, '1', 96, '1', 5, 1, 0, NULL, 0, 1441098360, 1441703160, 1442996969, 0, 1, 142, 1, 4, 1, 1, 1442996969),
(143, 0, '需新增友盟大数据统计，两套统计有利于对比数据的真实性', 0, 0, 86, '1', 96, '1', 6, 0, 0, NULL, 0, 1441098420, 1442999220, 1442996969, 0, 1, 143, 1, 2, 1, 1, 1442996969),
(144, 0, 'WAP分享页视频试看功能，根据视频的时长限制不同的观看时间，优先级7', 0, 0, 110, '1', 96, '1', 5, 0, 0, NULL, 0, 1441098480, 1441703280, 1442996969, 0, 1, 144, 1, 2, 1, 1, 1442996969),
(145, 0, '频道系统/频道API/频道增删改查', 0, 0, 136, '1', 84, '1', 8, 0, 0, '{"1":"7","2":"8","3":"8"}', 0, 1441154760, 1442651400, 1442996969, 0, 1, 145, 1, 2, 1, 1, 1442996969),
(146, 0, '电台资源/电台的增删改查/直播流/节目单/电台相关资源API（张亦弛 跟进）', 0, 0, 111, '1', 84, '1', 9, 1, 0, NULL, 0, 1441155000, 1442219400, 1442996969, 0, 1, 146, 1, 4, 1, 1, 1442996969),
(147, 0, '站点系统/站点API/站点增删改查/站点对外授权的增删', 0, 0, 99, '1', 84, '1', 9, 1, 0, NULL, 0, 1441155180, 1442392200, 1442996969, 0, 1, 147, 1, 4, 1, 1, 1442996969),
(148, 0, '栏目系统/栏目系统API/栏目增删改查/栏目授权媒资类型/栏目权限管理（颜腾威 跟进）', 0, 0, 112, '1', 84, '1', 9, 0, 0, NULL, 0, 1441155240, 1442219400, 1442996969, 0, 1, 148, 1, 2, 1, 1, 1442996969),
(149, 0, '后端员工系统/后台权限系统API/后台管理用户的增删改查/后台权限管理', 0, 0, 84, '1', 84, '1', 9, 0, 0, NULL, 0, 1441155360, 1442565000, 1442996970, 0, 1, 149, 1, 2, 1, 1, 1442996970),
(150, 0, '媒资管理系统/文集/图集/视频集/专题-手机端/图文直播 （9.15后所有人跟进）', 0, 0, 81, '1', 84, '1', 4, 0, 0, NULL, 0, 1441155540, 1443688200, 1442996970, 0, 1, 150, 1, 2, 1, 1, 1442996970),
(151, 0, '中国蓝TV索引图问题解决，进度安排及项目跟进，完成后，让测试组测试。', 0, 0, 105, '1', 106, '1', 4, 0, 0, NULL, 0, 1441170960, 1444972560, 1442996970, 0, 1, 151, 1, 2, 1, 1, 1442996970),
(152, 0, '新版播放器的需求文档。', 0, 0, 110, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1441172460, 1441777260, 1442996970, 0, 1, 152, 1, 2, 1, 1, 1442996970),
(153, 0, '调研乐视、华数等公司，形成我们自己点播视频MD5的可行性方案', 0, 0, 105, '1', 106, '1', 4, 1, 0, NULL, 0, 1441173480, 1442555880, 1442996970, 0, 1, 153, 1, 4, 1, 1, 1442996970),
(154, 0, '百度推广活动的相关开发及推广效果的数据', 0, 0, 110, '1', 106, '1', 8, 0, 0, '{"1":["8"],"2":["8"],"3":["9"]}', 0, 1441173540, 1441778340, 1442996970, 0, 1, 154, 1, 2, 1, 1, 1442996970),
(155, 0, '视频前插广告需要增加消音的按钮，UI稿见9月2日的UED邮件', 0, 0, 86, '1', 96, '1', 6, 0, 0, NULL, 0, 1441173720, 1442988120, 1442996970, 0, 1, 155, 1, 2, 1, 1, 1442996970),
(156, 0, '发现板块的视频播放器需要增加消音的按钮，UI稿见9月2日UED的邮件', 0, 0, 86, '1', 96, '1', 9, 0, 0, NULL, 0, 1441173840, 1441778640, 1442996970, 0, 1, 156, 1, 2, 1, 1, 1442996970),
(157, 0, '直播频道增加视频直播窗，把频道列表下移，可上下滑动切换频道。直播窗有消音和全屏的功能。UI稿见9月2日UED邮件。优先级8', 0, 0, 86, '1', 96, '1', 6, 0, 0, NULL, 0, 1441173840, 1442988240, 1442996970, 0, 1, 157, 1, 2, 1, 1, 1442996970),
(158, 0, '打开APP 提示用户上一次观看至什么片源以及具体时间，UI稿见9月2日UED邮件，优先级5', 0, 0, 86, '1', 96, '1', 6, 0, 0, NULL, 0, 1441174020, 1442988420, 1442996970, 0, 1, 158, 1, 2, 1, 1, 1442996970),
(159, 0, 'PC端全站缩略图鼠标经过图片时不出现ALT注释', 0, 0, 99, '1', 96, '1', 5, 0, 0, NULL, 0, 1441184820, 1441789620, 1442996970, 0, 1, 159, 1, 2, 1, 1, 1442996970),
(160, 0, '中国蓝TV PC端全站增加三级列表页，展示频道首页中单个栏目视频的集合，权重设置修改为不填时按时间排序', 0, 0, 99, '1', 96, '1', 4, 0, 0, NULL, 0, 1441185120, 1441789920, 1442996970, 0, 1, 160, 1, 2, 1, 1, 1442996970),
(161, 0, '中国蓝TV PC端视频播放页的面包屑改到标题的下一行，与国双进行可行性评估', 0, 0, 110, '1', 96, '1', 8, 1, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441185180, 1441789980, 1442996970, 0, 1, 161, 1, 4, 1, 1, 1442996970),
(162, 0, '中国蓝TV PC端首页缩略图加载、显示图片的位置定位在页面底部，现在是必须将图片拖动到页面中间的位置才能加载', 0, 0, 99, '1', 96, '1', 5, 0, 0, NULL, 0, 1441185240, 1441790040, 1442996970, 0, 1, 162, 1, 2, 1, 1, 1442996970),
(163, 0, '中国蓝TV PC端频道检索区块的按类型和按地区改成按栏目，栏目由编辑手动管理', 0, 0, 99, '1', 96, '1', 4, 0, 0, NULL, 0, 1441185240, 1441790040, 1442996970, 0, 1, 163, 1, 2, 1, 1, 1442996970),
(164, 0, '余姚新版APP 后台修改\r\n “记者关注”放到“看栏目” 子菜单首个，“姚江桥头”第二，“姚江田野”第三，“姚江光影”第四，“快乐碰碰车”第五，“放眼看天下”第六', 0, 0, 111, '1', 109, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1441502100, 1441588500, 1442996970, 0, 1, 164, 1, 2, 1, 1, 1442996970),
(165, 0, '好声音直播中，我们自己的直播播放器标题改成“正在直播中”', 0, 0, 110, '1', 105, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1441504440, 1442127600, 1442996970, 0, 1, 165, 1, 2, 1, 1, 1442996970),
(166, 0, '安卓V1,2.8 测试任务', 0, 0, 87, '1', 109, '1', 8, 0, 0, '{"1":"9","2":"10","3":"10"}', 0, 1441508640, 1442113440, 1442996970, 0, 1, 166, 1, 2, 1, 1, 1442996970),
(167, 0, '易传媒保密协议拟定', 0, 0, 105, '1', 105, '1', 7, 0, 0, NULL, 0, 1441509300, 1441854900, 1442996970, 0, 1, 167, 1, 2, 1, 1, 1442996970),
(168, 0, '易传媒开发备忘录拟定（我方定制需求开发部分）', 0, 0, 110, '1', 105, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441509360, 1441768560, 1442996970, 0, 1, 168, 1, 2, 1, 1, 1442996970),
(169, 0, '易传媒部署服务器需求确认（包括预期性能报告，以免资源浪费）', 0, 0, 105, '1', 105, '1', 9, 0, 0, NULL, 0, 1441509480, 1442114280, 1442996970, 0, 1, 169, 1, 2, 1, 1, 1442996970),
(170, 0, '萧山台小主播投票  开个栏目，具体操作联系钱琛做', 0, 0, 111, '1', 109, '1', 8, 0, 0, '{"1":"9","2":"10","3":"10"}', 0, 1441520700, 1442125500, 1442996970, 0, 1, 170, 1, 2, 1, 1, 1442996970),
(171, 0, '中国蓝TV服务器清单，含测试环境和正式环境。', 0, 0, 110, '1', 106, '1', 5, 0, 0, NULL, 0, 1441521840, 1441781040, 1442996970, 0, 1, 171, 1, 2, 1, 1, 1442996970),
(172, 0, '中国蓝新闻更换为个推推送', 0, 0, 110, '1', 110, '1', 4, 1, 0, NULL, 0, 1441524780, 1442136600, 1442996970, 0, 1, 172, 1, 8, 1, 1, 1442996970),
(173, 0, 'APP定向到指定栏目不成功，今天出现广告全无', 0, 0, 110, '1', 109, '1', 8, 1, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441526520, 1442131320, 1442996970, 0, 1, 173, 1, 6, 1, 1, 1442996970),
(174, 0, 'PHP从入门到精通的视频学习资料的下载转码并上传到米秀的平台，单独建立一个栏目。视频地址：http://video.1kejian.com/computer/programming/24482/', 0, 0, 136, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441528680, 1442133480, 1442996970, 0, 1, 174, 1, 2, 1, 1, 1442996970),
(175, 0, '应知应会百问百答事宜', 0, 0, 106, '1', 106, '1', 7, 1, 0, NULL, 0, 1441592280, 1442197080, 1442996971, 0, 1, 175, 1, 10, 1, 1, 1442996971),
(176, 0, 'APPstore中，对中国蓝TV评论差评中，出现的问题的复测试报告。发给罗总，抄送给我和史伟强。下周一上班前完成。', 0, 0, 109, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441597200, 1442190600, 1442996971, 0, 1, 176, 1, 2, 1, 1, 1442996971),
(177, 0, '庭审直播网bug修改', 0, 0, 109, '1', 111, '1', 4, 1, 0, NULL, 0, 1441613640, 1441872840, 1442996971, 0, 1, 177, 1, 4, 1, 1, 1442996971),
(178, 0, '中国蓝新闻对接国双大数据统计', 0, 0, 110, '1', 110, '1', 4, 1, 0, NULL, 0, 1441615440, 1442226600, 1442996971, 0, 1, 178, 1, 8, 1, 1, 1442996971),
(179, 0, '新互动后台开发', 0, 0, 110, '1', 110, '1', 4, 1, 0, NULL, 0, 1441675140, 1446307200, 1442996971, 0, 1, 179, 1, 4, 1, 1, 1442996971),
(180, 0, '中国蓝TV 安卓端 视频ID传值错误 造成国双系统里 一个视频ID对应多个视频且错误的标题', 0, 0, 109, '1', 107, '1', 4, 1, 0, NULL, 0, 1441680600, 1442285400, 1442996971, 0, 1, 180, 1, 6, 1, 1, 1442996971),
(181, 0, '测试任务详情', 0, 0, 111, '1', 111, '1', 7, 0, 1, NULL, 0, 1441690020, 1442294820, 1442996971, 0, 1, 181, 1, 2, 1, 1, 1442996971),
(182, 0, '测试同意完成', 0, 0, 105, '1', 111, '1', 9, 0, 0, NULL, 0, 1441699500, 1442304300, 1442996971, 0, 1, 182, 1, 2, 1, 1, 1442996971),
(183, 0, '中国蓝新闻 更新所需要准备的资源文件', 0, 0, 96, '1', 86, '1', 4, 0, 0, NULL, 0, 1441765740, 1442394000, 1442996971, 0, 1, 183, 1, 2, 1, 1, 1442996971),
(184, 0, '对拒绝的任务也可以修改拒绝的理由', 0, 0, 111, '1', 97, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441766340, 1442371140, 1442996971, 0, 1, 184, 1, 2, 1, 1, 1442996971),
(185, 0, '礼包隐藏功能开发', 0, 0, 96, '1', 106, '1', 4, 0, 0, NULL, 0, 1441802100, 1443616500, 1442996971, 0, 1, 185, 1, 2, 1, 1, 1442996971),
(186, 0, '中国蓝TV短信发票三张报销', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1441932480, 1444438080, 1442996971, 0, 1, 186, 1, 2, 1, 1, 1442996971),
(187, 0, '任务系统转移到新版cms', 0, 0, 111, '1', 84, '1', 8, 0, 0, '{"1":"6","2":"8","3":"8"}', 0, 1441933620, 1442538420, 1442996971, 0, 1, 187, 1, 2, 1, 1, 1442996971),
(188, 0, '市县台APP推送到详情页的需求IOS', 0, 0, 86, '1', 109, '1', 4, 0, 0, NULL, 0, 1441933740, 1444957740, 1442996971, 0, 1, 188, 1, 2, 1, 1, 1442996971),
(189, 0, '市县台APP推送到详情页的需求安卓', 0, 0, 97, '1', 109, '1', 4, 1, 0, NULL, 0, 1441933800, 1442538600, 1442996971, 0, 1, 189, 1, 4, 1, 1, 1442996971),
(190, 0, '调研申请报告', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441955880, 1441980000, 1442996971, 0, 1, 190, 1, 2, 1, 1, 1442996971),
(191, 0, '整理CMS、VMS流媒体、大数据、广告系统的百问百答，最后邮件发给郑磊。', 0, 0, 90, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441955880, 1441969200, 1442996971, 0, 1, 191, 1, 2, 1, 1, 1442996971),
(192, 0, '整理钱江潮直播的方案及应急预案，发给浪弯、云视链，要求其安排专职人员进行对接。', 0, 0, 105, '1', 106, '1', 5, 0, 0, NULL, 0, 1441957140, 1442993940, 1442996971, 0, 1, 192, 1, 2, 1, 1, 1442996971),
(193, 0, '储秀在WAP和APP端投放广告，需要支持VAST投放的邮件，烦请尽快协助落实。详细见QQ邮箱', 0, 0, 110, '1', 109, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441964340, 1442223540, 1442996971, 0, 1, 193, 1, 2, 1, 1, 1442996971),
(194, 0, 'FM998直播信号嵌入到新蓝网“广播直播”版块。', 0, 0, 96, '1', 106, '1', 9, 0, 0, NULL, 0, 1442200080, 1442311200, 1442996971, 0, 1, 194, 1, 2, 1, 1, 1442996971),
(195, 0, '中国蓝TV 国双乐视统计问题汇总（详情见附件）', 0, 0, 109, '1', 107, '1', 9, 0, 0, NULL, 0, 1442214960, 1442819760, 1442996971, 0, 1, 195, 1, 2, 1, 1, 1442996971),
(196, 0, 'sdfaf333', 0, 0, 111, '1', 111, '1', 7, 0, 1, NULL, 0, 1442299920, 1442904720, 1442996971, 0, 1, 196, 1, 2, 1, 1, 1442996971),
(197, 0, 'Ipad端基本功能测试', 0, 0, 83, '1', 109, '1', 6, 0, 0, NULL, 0, 1442309400, 1442482200, 1442996971, 0, 1, 197, 1, 2, 1, 1, 1442996971),
(198, 0, '乐视直播合同确认走流程', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442368440, 1442800440, 1442996971, 0, 1, 198, 1, 2, 1, 1, 1442996971),
(199, 0, '监控萧山问题/脚本已经在运行', 0, 0, 84, '1', 84, '1', 4, 1, 0, NULL, 0, 1442374200, 1444879800, 1442996971, 0, 1, 199, 1, 4, 1, 1, 1442996971),
(200, 0, 'API对接规范', 0, 0, 84, '1', 84, '1', 7, 1, 0, NULL, 0, 1442385060, 1442570400, 1442996971, 0, 1, 200, 1, 4, 1, 1, 1442996971),
(201, 0, '与乐视对接中国蓝TV 项目第一阶段完成的功能和第二阶段需要完成的功能', 0, 0, 96, '1', 96, '1', 4, 0, 0, NULL, 0, 1442386860, 1442473260, 1442996971, 0, 1, 201, 1, 2, 1, 1, 1442996971),
(202, 0, '易传媒系统部署相关，请易传媒给出相关的评估', 0, 0, 110, '1', 110, '1', 4, 0, 0, NULL, 0, 1442387760, 1442992560, 1442996971, 0, 1, 202, 1, 2, 1, 1, 1442996971),
(203, 0, '【浪湾】市县云平台直播转码迁移', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442391000, 1444983000, 1442996971, 0, 1, 203, 1, 2, 1, 1, 1442996971),
(204, 0, '【虹软】视频监控：盒子多节点滚屏监看', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442391300, 1444896900, 1442996971, 0, 1, 204, 1, 2, 1, 1, 1442996971),
(205, 0, '【虹软】离线转码抽帧（十秒一张）', 0, 0, 105, '1', 105, '1', 4, 1, 0, NULL, 0, 1442391420, 1443601020, 1442996971, 0, 1, 205, 1, 6, 1, 1, 1442996971),
(206, 0, '【虹软】索贝无水印转码有问题不成功', 0, 0, 105, '1', 105, '1', 7, 0, 0, NULL, 0, 1442392380, 1442997180, 1442996971, 0, 1, 206, 1, 2, 1, 1, 1442996971),
(207, 0, '【浪湾】权限管理，流媒体系统版本升级', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442392380, 1444897980, 1442996971, 0, 1, 207, 1, 2, 1, 1, 1442996971),
(208, 0, '【索贝】VMS数据库的主备切换', 0, 0, 105, '1', 105, '1', 7, 0, 0, NULL, 0, 1442392680, 1442911080, 1442996972, 0, 1, 208, 1, 2, 1, 1, 1442996972),
(209, 0, '【索贝】点播文件多增加单独音频文件转码以及纯音频的标识', 0, 0, 105, '1', 105, '1', 7, 1, 0, NULL, 0, 1442392740, 1442911140, 1442996972, 0, 1, 209, 1, 4, 1, 1, 1442996972),
(210, 0, '【索贝】编辑对vms 用户体验上的优化意见，联系人：蔡景伟', 0, 0, 105, '1', 105, '1', 4, 1, 0, NULL, 0, 1442392860, 1446194460, 1442996972, 0, 1, 210, 1, 4, 1, 1, 1442996972),
(211, 0, '【乐视】国双统计问题，找范志勇', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442393580, 1442566380, 1442996972, 0, 1, 211, 1, 2, 1, 1, 1442996972),
(212, 0, '云转码软件评测资料填写', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442394420, 1442567220, 1442996972, 0, 1, 212, 1, 2, 1, 1, 1442996972),
(213, 0, '【乐视】播放器针对5分钟防盗链是否支持预加载', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442452020, 1443056820, 1442996972, 0, 1, 213, 1, 2, 1, 1, 1442996972),
(214, 0, '国双问题汇总。', 0, 0, 109, '1', 107, '1', 1, 0, 0, NULL, 0, 1442454420, 1444873620, 1442996972, 0, 1, 214, 1, 2, 1, 1, 1442996972),
(215, 0, '熟悉cztv cms结构和框架', 0, 0, 79, '1', 84, '1', 8, 0, 0, '{"1":"6","2":"8","3":"7"}', 0, 1442454480, 1442484000, 1442996972, 0, 1, 215, 1, 2, 1, 1, 1442996972),
(216, 0, '熟悉cztv cms结构和框架', 0, 0, 80, '1', 84, '1', 8, 0, 0, '{"1":"6","2":"8","3":"8"}', 0, 1442454600, 1442484000, 1442996972, 0, 1, 216, 1, 2, 1, 1, 1442996972),
(217, 0, '幸福摇电视节目准备及开发测试', 0, 0, 106, '1', 106, '1', 4, 0, 0, NULL, 0, 1442456640, 1443579840, 1442996972, 0, 1, 217, 1, 2, 1, 1, 1442996972),
(218, 0, '钱江潮直播', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442459460, 1443628500, 1442996972, 0, 1, 218, 1, 2, 1, 1, 1442996972),
(219, 0, 'TEST_Hello_World', 0, 0, 104, '1', 104, '1', 9, 0, 0, NULL, 0, 1442465700, 1442552100, 1442996972, 0, 1, 219, 1, 2, 1, 1, 1442996972),
(220, 0, 'TEST_2_FOR_THE_HORDE', 0, 0, 104, '1', 104, '1', 7, 0, 0, NULL, 0, 1442465880, 1442466300, 1442996972, 0, 1, 220, 1, 2, 1, 1, 1442996972),
(221, 0, 'TEST_3_FOR_THE_DIN', 0, 0, 104, '1', 104, '1', 7, 0, 0, NULL, 0, 1442466000, 1443074400, 1442996972, 0, 1, 221, 1, 2, 1, 1, 1442996972),
(222, 0, 'TEST', 0, 0, 80, '1', 104, '1', 9, 0, 0, NULL, 0, 1442466060, 1442469660, 1442996972, 0, 1, 222, 1, 2, 1, 1, 1442996972),
(223, 0, '1、修改完善调整CMS系统中的任务系统页面。\r\n2、无限级联动组件', 0, 0, 85, '1', 82, '1', 4, 0, 0, NULL, 0, 1442481840, 1443086640, 1442996972, 0, 1, 223, 1, 2, 1, 1, 1442996972),
(224, 0, '1、列表页的排序效果。\r\n2、框架编辑器的函数定义，调用http://ueditor.baidu.com/website/的编辑器。', 0, 0, 104, '1', 82, '1', 8, 0, 0, '{"1":"9","2":"9","3":"9"}', 0, 1442537340, 1442827800, 1442996972, 0, 1, 224, 1, 2, 1, 1, 1442996972),
(225, 0, '中国蓝TV去掉中国好声音的皮肤，设计稿。', 0, 0, 96, '1', 106, '1', 4, 0, 0, NULL, 0, 1442537760, 1442969760, 1442996972, 0, 1, 225, 1, 2, 1, 1, 1442996972),
(226, 0, '浙江手机台联通整体修改完全', 0, 0, 105, '1', 105, '1', 7, 0, 0, NULL, 0, 1442553300, 1442830500, 1442996972, 0, 1, 226, 1, 2, 1, 1, 1442996972),
(227, 0, '栏目模块，栏目权限模块 涉及表 category/category_auth', 0, 0, 80, '1', 84, '1', 8, 0, 0, '{"1":"7","2":"8","3":"8"}', 0, 1442563440, 1442916000, 1442996972, 0, 1, 227, 1, 2, 1, 1, 1442996972),
(228, 0, '评论模块 涉及表comment', 0, 0, 79, '1', 84, '1', 6, 0, 0, NULL, 0, 1442563560, 1442916000, 1442996972, 0, 1, 228, 1, 2, 1, 1, 1442996972),
(229, 0, '市县使用存储升级至5T', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442566020, 1442911620, 1442996972, 0, 1, 229, 1, 2, 1, 1, 1442996972),
(230, 0, '摇一摇活动', 0, 0, 84, '1', 84, '1', 4, 0, 0, NULL, 0, 1442566980, 1444471200, 1442996972, 0, 1, 230, 1, 2, 1, 1, 1442996972),
(231, 0, '投票系统', 0, 0, 84, '1', 84, '1', 4, 0, 0, NULL, 0, 1442567100, 1445763900, 1442996972, 0, 1, 231, 1, 2, 1, 1, 1442996972),
(232, 0, '协助完成权限系统', 0, 0, 111, '1', 84, '1', 4, 0, 0, NULL, 0, 1442569200, 1443001200, 1442996972, 0, 1, 232, 1, 2, 1, 1, 1442996972),
(233, 0, '中国蓝TV客户端增加友盟统计', 0, 0, 110, '1', 110, '1', 4, 1, 0, NULL, 0, 1442579820, 1443184620, 1442996972, 0, 1, 233, 1, 6, 1, 1, 1442996972),
(234, 0, 'https证书购买', 0, 0, 104, '1', 84, '1', 9, 0, 0, NULL, 0, 1442796480, 1442829600, 1442996972, 0, 1, 234, 1, 2, 1, 1, 1442996972),
(235, 0, '幸福摇电视活动产品需求文档', 0, 0, 96, '1', 96, '1', 4, 0, 0, NULL, 0, 1442797620, 1442884020, 1442996972, 0, 1, 235, 1, 2, 1, 1, 1442996972),
(236, 0, '一起商榷下中国蓝TV 9月底迭代版本，更新哪些内容点。\r\n我这边整理的有：中国好声音导师皮肤更新成跑男皮肤；用户注册协议客服电话更新；首页小礼包图标增加摇一摇图标，做成后台可控制切换两套图标。', 0, 0, 106, '1', 96, '1', 6, 0, 0, NULL, 0, 1442797680, 1442884080, 1442996972, 0, 1, 236, 1, 2, 1, 1, 1442996972),
(237, 0, '云视链播放器对接，先对接用在钱江潮直播的WAP端，后续用于中国蓝新闻端H5播放器', 0, 0, 112, '1', 110, '1', 1, 0, 0, NULL, 0, 1442801220, 1443406020, 1442996972, 0, 1, 237, 1, 2, 1, 1, 1442996972),
(238, 0, '个人中心，密码修改，头像上传，资料修改等', 0, 0, 87, '1', 84, '1', 6, 0, 0, NULL, 0, 1442885460, 1443081600, 1442996972, 0, 1, 238, 1, 2, 1, 1, 1442996972),
(239, 0, 'APP版本库，参照a.hd.cztv.com', 0, 0, 83, '1', 84, '1', 4, 0, 0, NULL, 0, 1442885520, 1443081600, 1442996972, 0, 1, 239, 1, 2, 1, 1, 1442996972),
(240, 0, '文章，图集，视频列表读取', 0, 0, 136, '1', 84, '1', 4, 0, 0, NULL, 0, 1442885580, 1444464000, 1442996972, 0, 1, 240, 1, 2, 1, 1, 1442996972),
(241, 0, '任务系统和会员数据转移', 0, 0, 111, '1', 84, '1', 4, 0, 0, NULL, 0, 1442885700, 1443067200, 1442996972, 0, 1, 241, 1, 2, 1, 1, 1442996972),
(242, 0, '直播页面，在播放广告时，点击下面的节目条，时移回看时，提示语及弹窗的优化。\r\n调皮一点的：耐心欣赏下广告嘛\r\n正式一点的：广告后才可以切换节目，请稍候\r\n\r\n与视频中心沟通优化方案，出设计稿，给到史伟强，让其安排开发上线。', 0, 0, 96, '1', 106, '1', 1, 0, 0, NULL, 0, 1442906700, 1443511500, 1442996972, 0, 1, 242, 1, 2, 1, 1, 1442996972),
(243, 0, '活动模块 具体需求询问谢东或者孟老师', 0, 0, 80, '1', 84, '1', 1, 0, 0, NULL, 0, 1442975100, 1443499200, 1442996972, 0, 1, 243, 1, 2, 1, 1, 1442996972),
(244, 0, '前端会员中心', 0, 0, 79, '1', 84, '1', 1, 0, 0, NULL, 0, 1442975640, 1443499200, 1442996972, 0, 1, 244, 1, 2, 1, 1, 1442996972),
(245, 0, '1、CMS系统的用户中心页面，用户登录页面，密码重置，忘记密码页面完善。', 0, 0, 104, '1', 82, '1', 4, 0, 0, NULL, 0, 1442976300, 1443130200, 1442996972, 0, 1, 245, 1, 2, 1, 1, 1442996972),
(246, 0, '诸暨、余姚、庭审直播', 38, 0, 83, '1', 111, '1', 8, 0, 0, '{"1":["8"],"2":["8"],"3":["8"]}', 0, 1437470220, 1438334220, 1442996988, 0, 0, 1, 2, 3, 2, 1, 1442996988),
(247, 0, '上虞、桐乡、嵊州', 0, 0, 85, '1', 111, '1', 8, 1, 0, '{"1":["8"],"2":["8"],"3":["8"]}', 0, 1437470220, 1438334220, 1442996988, 0, 0, 1, 4, 9, 2, 1, 1442996988),
(248, 0, '萧山（单独部署）', 0, 0, 87, '1', 111, '1', 8, 0, 0, '{"1":["8"],"2":["8"],"3":["8"]}', 0, 1437470220, 1438334220, 1442996988, 0, 0, 1, 10, 11, 2, 1, 1442996988),
(249, 0, '嵊州首页漂浮广告设计', 0, 0, 85, '1', 111, '1', 8, 0, 0, '{"1":["10"],"2":["8"],"3":["10"]}', 0, 1437470220, 1438334220, 1442996988, 0, 0, 1, 12, 13, 2, 1, 1442996988),
(250, 0, '中国蓝互动研究与开发 需求分析及任务分配', 0, 0, 106, '1', 111, '1', 9, 0, 0, NULL, 0, 1437472440, 1441446840, 1442996989, 0, 0, 2, 2, 3, 2, 1, 1442996989),
(251, 0, '节目单获取流程简化，中间环节取消', 0, 0, 111, '1', 111, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1437472440, 1437645240, 1442996989, 0, 0, 2, 4, 5, 2, 1, 1442996989),
(252, 0, '分拆级别测试', 0, 0, 111, '1', 111, '1', 9, 1, 0, NULL, 0, 1438842000, 1438928400, 1442996989, 0, 0, 2, 6, 15, 2, 1, 1442996989),
(253, 0, '分拆级别测试', 0, 0, 111, '1', 111, '1', 9, 0, 0, NULL, 0, 1438842120, 1438928520, 1442996989, 0, 0, 2, 16, 17, 2, 1, 1442996989),
(254, 0, '使用python设计辅助型系统，利用moinmoin开发一个我们研发中心自己wiki系统', 0, 0, 0, '1', 0, '1', 9, 0, 3, NULL, 0, 1438052700, 1439003100, 1442996989, 0, 0, 3, 2, 3, 2, 1, 1442996989),
(255, 0, '一级子任务一级子任务', 0, 0, 0, '1', 0, '1', 9, 1, 3, NULL, 0, 1438677960, 1438764360, 1442996989, 0, 0, 3, 4, 13, 2, 1, 1442996989),
(256, 0, '节目单优化可行性分析', 0, 0, 110, '1', 106, '1', 8, 0, 0, '{"1":["8"],"2":["8"],"3":["8"]}', 0, 1438575660, 1439785260, 1442996989, 0, 0, 4, 2, 3, 2, 1, 1442996989),
(257, 0, '新建任务界面优化', 0, 0, 90, '1', 106, '1', 8, 0, 0, '{"1":["1"],"2":["1"],"3":["1"]}', 0, 1438584600, 1438671000, 1442996989, 0, 0, 5, 2, 3, 2, 1, 1442996989),
(258, 0, '任务模块增加功能需求', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438586160, 1438758960, 1442996990, 0, 0, 6, 2, 3, 2, 1, 1442996990),
(259, 0, '完成任务时，需有任务详情回复', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438586160, 1438758960, 1442996990, 0, 0, 6, 4, 5, 2, 1, 1442996990),
(260, 0, '子任务未全部完成时，主任务不能够显示“完成详情”，也不要有完成按键', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438681920, 1438768500, 1442996990, 0, 0, 6, 6, 7, 2, 1, 1442996990),
(261, 0, '自己给自己设置任务', 0, 0, 111, '1', 111, '1', 7, 0, 1, NULL, 0, 1438766520, 1438768500, 1442996990, 0, 0, 9, 2, 3, 2, 1, 1442996990),
(262, 0, '自己给自己任务2222222222222', 0, 0, 111, '1', 111, '1', 7, 0, 1, NULL, 0, 1438766940, 1438767000, 1442996990, 0, 0, 9, 4, 5, 2, 1, 1442996990),
(263, 0, '啊啊啊啊啊', 0, 0, 0, '1', 0, '1', 9, 0, 3, NULL, 0, 1438756860, 1438843260, 1442996990, 0, 0, 13, 2, 3, 2, 1, 1442996990),
(264, 0, '呵呵呵呵呵呵', 0, 0, 103, '1', 90, '1', 9, 0, 0, NULL, 0, 1438770600, 1438874700, 1442996990, 0, 0, 13, 4, 5, 2, 1, 1442996990),
(265, 0, '子任务', 0, 0, 84, '1', 84, '1', 9, 0, 0, NULL, 0, 1438834860, 1438921320, 1442996991, 0, 0, 16, 2, 3, 2, 1, 1442996991),
(266, 0, '测试', 0, 0, 109, '1', 111, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438846740, 1438850400, 1442996991, 0, 0, 17, 2, 5, 2, 1, 1442996991),
(267, 0, '第一部分', 0, 0, 97, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438913760, 1439000160, 1442996991, 0, 0, 19, 2, 3, 2, 1, 1442996991),
(268, 0, '第二部分', 0, 0, 107, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438913820, 1439000220, 1442996991, 0, 0, 19, 4, 5, 2, 1, 1442996991),
(269, 0, '第三部分', 0, 0, 86, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438934820, 1439021220, 1442996991, 0, 0, 19, 6, 7, 2, 1, 1442996991),
(270, 0, '请检查验证', 0, 0, 111, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438934880, 1439366880, 1442996991, 0, 0, 21, 2, 3, 2, 1, 1442996991),
(271, 0, '请检查验证', 0, 0, 99, '1', 110, '1', 9, 0, 0, NULL, 0, 1438935060, 1439367060, 1442996992, 0, 0, 21, 4, 5, 2, 1, 1442996992),
(272, 0, '请检查验证', 0, 0, 112, '1', 110, '1', 9, 0, 0, NULL, 0, 1438935120, 1439367120, 1442996992, 0, 0, 21, 6, 7, 2, 1, 1442996992),
(273, 0, '我的任务中，已完成与未完成任务可筛选', 0, 0, 111, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438928340, 1439014740, 1442996992, 0, 0, 22, 2, 3, 2, 1, 1442996992),
(274, 0, '手动输入人员名字且能够自动查找本频道人员搜素', 0, 0, 111, '1', 106, '1', 9, 0, 0, NULL, 0, 1439191920, 1439274600, 1442996992, 0, 0, 25, 2, 3, 2, 1, 1442996992),
(275, 0, '任务指派到人时可以手动输入名字或输入时可以自动名字匹配提示', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1439891880, 1440496680, 1442996992, 0, 0, 25, 4, 5, 2, 1, 1442996992),
(276, 0, '附件功能开发', 0, 0, 111, '1', 106, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439286360, 1439459160, 1442996992, 0, 0, 27, 2, 5, 2, 1, 1442996992),
(277, 0, '你提出要求的需求，由你来协调完成', 0, 0, 84, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438940940, 1439195400, 1442996993, 0, 0, 32, 2, 3, 2, 1, 1442996993),
(278, 0, '任务列表展示方式优化', 0, 0, 99, '1', 106, '1', 9, 0, 0, NULL, 0, 1438937940, 1439024340, 1442996993, 0, 0, 37, 2, 3, 2, 1, 1442996993),
(279, 0, '请尽快完成任务', 0, 0, 99, '1', 106, '1', 9, 0, 0, NULL, 0, 1440549120, 1441011240, 1442996993, 0, 0, 37, 4, 5, 2, 1, 1442996993),
(280, 0, '我的任务中的子任务，旁边显示主任务', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438938720, 1438948800, 1442996993, 0, 0, 40, 2, 3, 2, 1, 1442996993),
(281, 0, 'PC直播页面BUG', 0, 0, 109, '1', 106, '1', 8, 0, 0, '{"1":["8"],"2":["8"],"3":["9"]}', 0, 1438940160, 1439544960, 1442996994, 0, 0, 46, 2, 3, 2, 1, 1442996994),
(282, 0, '视音频文件的MD5码方案', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":["5"],"2":["5"],"3":["6"]}', 0, 1439345340, 1439950140, 1442996994, 0, 0, 46, 4, 5, 2, 1, 1442996994),
(283, 0, '中国好声音专题页面头部样式的确认', 0, 0, 96, '1', 106, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439773440, 1440378240, 1442996994, 0, 0, 46, 6, 11, 2, 1, 1442996994),
(284, 0, '中国好声音专题，播放页对联广告样式的检查', 0, 0, 109, '1', 106, '1', 8, 0, 0, '{"1":["8"],"2":["9"],"3":["10"]}', 0, 1439773860, 1440119460, 1442996994, 0, 0, 46, 12, 13, 2, 1, 1442996994),
(285, 0, 'PC端点播视频码率的调整', 0, 0, 109, '1', 106, '1', 8, 1, 0, NULL, 0, 1439773980, 1440378780, 1442996994, 0, 0, 46, 14, 17, 2, 1, 1442996994),
(286, 0, '中国蓝TV 的H5端，直播页面的样式修改。', 0, 0, 109, '1', 106, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439774100, 1440378900, 1442996995, 0, 0, 46, 18, 21, 2, 1, 1442996995),
(287, 0, '关于直播和点播视频的LOGO，更改为在播放器中控制的可行性方案评估。', 0, 0, 105, '1', 106, '1', 8, 0, 0, NULL, 0, 1439774280, 1440379080, 1442996995, 0, 0, 46, 22, 23, 2, 1, 1442996995),
(288, 0, 'inapp的XML文件的制作', 0, 0, 109, '1', 106, '1', 8, 1, 0, '{"1":"10","2":"10","3":"10"}', 0, 1439774700, 1440465900, 1442996995, 0, 0, 46, 24, 27, 2, 1, 1442996995),
(289, 0, 'VIP会员去三个月免看广告功能', 0, 0, 109, '1', 106, '1', 8, 1, 0, '{"1":["9"],"2":["9"],"3":["9"]}', 0, 1439774760, 1440379560, 1442996995, 0, 0, 46, 28, 31, 2, 1, 1442996995),
(290, 0, '专辑的索引图问题', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439774880, 1440984480, 1442996995, 0, 0, 46, 32, 33, 2, 1, 1442996995),
(291, 0, '直播视频前播广告功能的开发', 0, 0, 109, '1', 106, '1', 8, 1, 0, NULL, 0, 1439775060, 1440984660, 1442996995, 0, 0, 46, 34, 43, 2, 1, 1442996995),
(292, 0, '根据专辑进行单独的广告投放的评估', 0, 0, 110, '1', 106, '1', 8, 0, 0, '{"1":["6"],"2":["6"],"3":["6"]}', 0, 1440404460, 1441009260, 1442996995, 0, 0, 46, 44, 45, 2, 1, 1442996995),
(293, 0, '测试测试', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["9"],"2":["9"],"3":["9"]}', 0, 1440549060, 1441013640, 1442996995, 0, 0, 46, 46, 47, 2, 1, 1442996995),
(294, 0, '【后台】接口提供', 0, 0, 111, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439286000, 1439803800, 1442996996, 0, 0, 54, 2, 3, 2, 1, 1442996996),
(295, 0, '【客户端】读取活动接口', 0, 0, 97, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439286120, 1440063000, 1442996996, 0, 0, 54, 4, 5, 2, 1, 1442996996),
(296, 0, '【前端】H5页面制作', 0, 0, 99, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439286600, 1439803800, 1442996996, 0, 0, 54, 6, 7, 2, 1, 1442996996),
(297, 0, '在我的任务栏内增加子菜单', 0, 0, 111, '1', 106, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439440320, 1440045120, 1442996996, 0, 0, 64, 2, 7, 2, 1, 1442996996),
(298, 0, '任务列表的可按分类目，进行排序', 0, 0, 111, '1', 106, '1', 9, 0, 0, NULL, 0, 1439773140, 1439773140, 1442996997, 0, 0, 68, 2, 3, 2, 1, 1442996997),
(299, 0, '关于任务排序的需求，请与章海泉再仔细沟通一下', 0, 0, 97, '1', 106, '1', 8, 0, 0, '{"1":["9"],"2":["9"],"3":["10"]}', 0, 1439792880, 1439803800, 1442996997, 0, 0, 68, 4, 5, 2, 1, 1442996997),
(300, 0, '根据蓝新华要求，排序功能', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439875980, 1439962380, 1442996997, 0, 0, 68, 6, 7, 2, 1, 1442996997),
(301, 0, '任务排序优化', 0, 0, 111, '1', 106, '1', 9, 0, 0, NULL, 0, 1440119520, 1440119520, 1442996997, 0, 0, 68, 8, 9, 2, 1, 1442996997),
(302, 0, '互动平台任务管理的API规范制定', 0, 0, 110, '1', 106, '1', 9, 0, 0, NULL, 0, 1439858580, 1440463380, 1442996998, 0, 0, 78, 2, 3, 2, 1, 1442996998),
(303, 0, '任务管理手机APP的界面制作', 0, 0, 86, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1439858880, 1440463680, 1442996998, 0, 0, 78, 4, 5, 2, 1, 1442996998),
(304, 0, '测试一下！', 0, 0, 86, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440549360, 1440750960, 1442996998, 0, 0, 78, 6, 7, 2, 1, 1442996998),
(305, 0, '收集目前所有市县平台的ios安装包正式版', 0, 0, 85, '1', 109, '1', 6, 0, 0, NULL, 0, 1440474480, 1440647280, 1442996998, 0, 0, 80, 2, 3, 2, 1, 1442996998),
(306, 0, '收集目前所有市县平台的ios安装包测试版', 0, 0, 83, '1', 109, '1', 6, 0, 0, NULL, 0, 1440474540, 1440647340, 1442996998, 0, 0, 80, 4, 5, 2, 1, 1442996998),
(307, 0, '收集目前所有市县平台的安卓包', 0, 0, 90, '1', 109, '1', 6, 0, 0, NULL, 0, 1440474660, 1440647460, 1442996998, 0, 0, 80, 6, 7, 2, 1, 1442996998),
(308, 0, '帮忙提供各市县app正式版logo和测试版logo', 0, 0, 96, '1', 109, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440474780, 1440647580, 1442996998, 0, 0, 80, 8, 9, 2, 1, 1442996998),
(309, 0, '向章海泉学习如何上传app生成二维码，并上传所有市县的安卓客户端到互动平台', 0, 0, 87, '1', 109, '1', 6, 0, 0, NULL, 0, 1440474960, 1440734160, 1442996998, 0, 0, 80, 10, 11, 2, 1, 1442996998),
(310, 0, '余姚台手机app部分更新', 0, 0, 107, '1', 97, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["9"]}', 0, 1440118800, 1440550800, 1442996999, 0, 0, 88, 2, 5, 2, 1, 1442996999),
(311, 0, '直播信号live1和mala直播页广告bug', 0, 0, 110, '1', 109, '1', 8, 0, 0, '{"1":["9"],"2":["9"],"3":["9"]}', 0, 1440474300, 1440733500, 1442997000, 0, 0, 93, 2, 3, 2, 1, 1442997000),
(312, 0, '根据APP通知检测的产品需求，协调设计师，出设计稿，并与运营确认此功能是否可行。', 0, 0, 96, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1440166200, 1440771000, 1442997000, 0, 0, 95, 2, 3, 2, 1, 1442997000),
(313, 0, '根据主任务，论证安卓和iOS端的可行性', 0, 0, 110, '1', 106, '1', 8, 0, 0, '{"1":"9","2":"9","3":"9"}', 0, 1440166320, 1440771120, 1442997000, 0, 0, 95, 4, 5, 2, 1, 1442997000),
(314, 0, '测试测试', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440549480, 1442844180, 1442997000, 0, 0, 95, 6, 7, 2, 1, 1442997000),
(315, 0, '提交完成任务后，未审核前，可以修改完成的内容', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440481500, 1440498600, 1442997000, 0, 0, 98, 2, 3, 2, 1, 1442997000);
INSERT INTO `tasks` (`id`, `channel_id`, `title`, `content_id`, `attachnum`, `receiver`, `receiver_name`, `creator`, `creator_name`, `progress`, `subs_complete`, `notify`, `score`, `priority`, `start`, `end`, `actual_start`, `actual_end`, `is_main`, `isolate_code`, `Lft`, `Rgt`, `depth`, `status`, `created`) VALUES
(316, 0, '测试', 0, 0, 111, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440487560, 1440573960, 1442997000, 0, 0, 98, 4, 5, 2, 1, 1442997000),
(317, 0, '测试测试', 0, 0, 0, '1', 0, '1', 9, 0, 3, NULL, 0, 1440491460, 1441360800, 1442997001, 0, 0, 99, 2, 3, 2, 1, 1442997001),
(318, 0, '倒萨发送到分', 0, 0, 111, '1', 111, '1', 7, 0, 1, NULL, 0, 1440768240, 1441360800, 1442997001, 0, 0, 99, 4, 5, 2, 1, 1442997001),
(319, 0, 'http://www.1905.com/special/dianbo/liveD5/?bdfrom=hideheadfoot，这个地址的直播，嵌套到我们自己的专题上或乐视的平台上。', 0, 0, 110, '1', 106, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440753720, 1440998220, 1442997001, 0, 0, 111, 2, 3, 2, 1, 1442997001),
(320, 0, '萧山台直播修改为非iframe嵌入', 0, 0, 111, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1441522440, 1441701240, 1442997003, 0, 0, 137, 2, 3, 2, 1, 1442997003),
(321, 0, '电视剧需增加预告片的剧集字段，这个需求你评估下看', 0, 0, 112, '1', 110, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441596360, 1441703160, 1442997003, 0, 0, 142, 2, 3, 2, 1, 1442997003),
(322, 0, '电台资源/电台的增删改查/直播流/节目单/电台相关资源API（张亦弛 跟进）', 0, 0, 87, '1', 111, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441763400, 1442219400, 1442997004, 0, 0, 146, 2, 3, 2, 1, 1442997004),
(323, 0, '站点系统/站点API/站点增删改查/站点对外授权的增删', 0, 0, 83, '1', 99, '1', 7, 0, 0, NULL, 0, 1441765440, 1442392200, 1442997004, 0, 0, 147, 2, 3, 2, 1, 1442997004),
(324, 0, '【乐视】给出md5解决方案意见', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442393640, 1442469480, 1442997004, 0, 0, 153, 2, 3, 2, 1, 1442997004),
(325, 0, '跟国双确认下这个改动符不符合SEO优化需求', 0, 0, 107, '1', 110, '1', 9, 0, 0, NULL, 0, 1441596300, 1441789980, 1442997005, 0, 0, 161, 2, 3, 2, 1, 1442997005),
(326, 0, '中国蓝新闻个推推送后台对接', 0, 0, 112, '1', 110, '1', 4, 0, 0, NULL, 0, 1441524840, 1442136600, 1442997005, 0, 0, 172, 2, 3, 2, 1, 1442997005),
(327, 0, '中国蓝新闻个推推送android对接', 0, 0, 97, '1', 110, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441524840, 1442136600, 1442997005, 0, 0, 172, 4, 5, 2, 1, 1442997005),
(328, 0, '中国蓝新闻个推推送ios对接', 0, 0, 86, '1', 110, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441524840, 1442136600, 1442997006, 0, 0, 172, 6, 7, 2, 1, 1442997006),
(329, 0, 'APP广告定向问题，查一下专辑ID有没有正确上报给广告系统，截图到附件', 0, 0, 86, '1', 110, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441531200, 1441699320, 1442997006, 0, 0, 173, 2, 3, 2, 1, 1442997006),
(330, 0, 'APP广告定向问题，查一下专辑ID有没有正确上报给广告系统，截图到附件', 0, 0, 97, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1441531320, 1441699320, 1442997006, 0, 0, 173, 4, 5, 2, 1, 1442997006),
(331, 0, '直播、点播（含编码器、流媒体、CDN分发、VMS入库）的应知应会，百问百答版块。\r\n周二下班前把整理出的问题以邮件的形式发给我，抄送给罗总。\r\n周五之前，把相应问题的答案整理出来，以邮件形式发出。', 0, 0, 105, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441592340, 1441937880, 1442997006, 0, 0, 175, 2, 3, 2, 1, 1442997006),
(332, 0, '广告系统的应知应会，百问百答整理。\r\n周二下班前把整理出的问题以邮件的形式发给我，抄送给罗总。\r\n周五之前，把相应问题的答案整理出来，以邮件形式发出。', 0, 0, 110, '1', 106, '1', 8, 0, 0, '{"1":"8","2":"8","3":"9"}', 0, 1441592820, 1441937880, 1442997006, 0, 0, 175, 4, 5, 2, 1, 1442997006),
(333, 0, '大数据系统的应知应会，百问百答的整理。\r\n周二下班前把整理出的问题以邮件的形式发给我，抄送给罗总。\r\n周五之前，把相应问题的答案整理出来，以邮件形式发出。', 0, 0, 107, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441592880, 1441937880, 1442997006, 0, 0, 175, 6, 7, 2, 1, 1442997006),
(334, 0, 'VRS、CMS的应知应会，百问百答的整理。\r\n周二下班前把整理出的问题以邮件的形式发给我，抄送给罗总。\r\n周五之前，把相应问题的答案整理出来，以邮件形式发出。', 0, 0, 109, '1', 106, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441593000, 1441937880, 1442997006, 0, 0, 175, 8, 9, 2, 1, 1442997006),
(335, 0, '庭审直播测试2222', 0, 0, 109, '1', 111, '1', 8, 0, 0, '{"1":"9","2":"10","3":"10"}', 0, 1441674600, 1441872840, 1442997007, 0, 0, 177, 2, 3, 2, 1, 1442997007),
(336, 0, '中国蓝新闻对接国双大数据统计-Android端', 0, 0, 97, '1', 110, '1', 2, 0, 0, NULL, 0, 1441615500, 1442226600, 1442997007, 0, 0, 178, 2, 3, 2, 1, 1442997007),
(337, 0, '中国蓝新闻对接国双大数据统计-IOS端', 0, 0, 86, '1', 110, '1', 2, 0, 0, NULL, 0, 1441615500, 1442226600, 1442997007, 0, 0, 178, 4, 5, 2, 1, 1442997007),
(338, 0, '鲍洲问国双名硕要一下中国蓝新闻APP端统计的相关资料', 0, 0, 107, '1', 110, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1442194320, 1442226600, 1442997007, 0, 0, 178, 6, 7, 2, 1, 1442997007),
(339, 0, '短信验证接口问题\r\n1，同一台手机只能发送1次\r\n2，同一个号码只能发送1次', 0, 0, 110, '1', 110, '1', 7, 0, 0, NULL, 0, 1441675200, 1446307200, 1442997007, 0, 0, 179, 2, 3, 2, 1, 1442997007),
(340, 0, '中国蓝TV 安卓端 视频ID传值错误 造成国双系统里 一个视频ID对应多个视频且错误的标题', 0, 0, 110, '1', 109, '1', 6, 1, 0, NULL, 0, 1441681080, 1442285400, 1442997009, 0, 0, 180, 2, 5, 2, 1, 1442997009),
(341, 0, '市县台APP推送到详情页', 0, 0, 107, '1', 97, '1', 4, 0, 0, NULL, 0, 1442194140, 1442538600, 1442997010, 0, 0, 189, 2, 3, 2, 1, 1442997010),
(342, 0, '监控萧山问题', 0, 0, 111, '1', 84, '1', 4, 0, 0, NULL, 0, 1442374260, 1444879800, 1442997010, 0, 0, 199, 2, 3, 2, 1, 1442997010),
(343, 0, '辅助制定API规范', 0, 0, 106, '1', 84, '1', 9, 0, 0, NULL, 0, 1442385060, 1442570400, 1442997011, 0, 0, 200, 2, 3, 2, 1, 1442997011),
(344, 0, '【乐视】播放器进度条，视频截图提示功能', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442391480, 1443601020, 1442997011, 0, 0, 205, 2, 3, 2, 1, 1442997011),
(345, 0, '【索贝】视频多维度索引图选择功能', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442392200, 1443601020, 1442997011, 0, 0, 205, 4, 5, 2, 1, 1442997011),
(346, 0, '【索贝】点播文件多增加单独音频文件转码 功能在测试环境测试', 0, 0, 109, '1', 105, '1', 9, 0, 0, NULL, 0, 1442393100, 1442479140, 1442997011, 0, 0, 209, 2, 3, 2, 1, 1442997011),
(347, 0, 'vms字段优化部分', 0, 0, 105, '1', 105, '1', 4, 0, 0, NULL, 0, 1442993820, 1444380060, 1442997011, 0, 0, 210, 2, 3, 2, 1, 1442997011),
(348, 0, '中国蓝TV客户端增加友盟统计', 0, 0, 86, '1', 110, '1', 6, 0, 0, NULL, 0, 1442579880, 1443184620, 1442997012, 0, 0, 233, 2, 3, 2, 1, 1442997012),
(349, 0, '中国蓝TV客户端增加友盟统计', 0, 0, 97, '1', 110, '1', 4, 0, 0, NULL, 0, 1442579880, 1443184620, 1442997013, 0, 0, 233, 4, 5, 2, 1, 1442997013),
(350, 0, '学习模板与数据源——上虞', 39, 0, 85, '1', 85, '1', 8, 0, 0, '{"1":["8"],"2":["8"],"3":["8"]}', 0, 1437470220, 1437556620, 1442997029, 0, 0, 1, 5, 6, 3, 1, 1442997029),
(351, 0, '学习模板与数据源——上虞', 0, 0, 85, '1', 85, '1', 8, 0, 0, '{"1":["8"],"2":["8"],"3":["8"]}', 0, 1437525000, 1437557400, 1442997029, 0, 0, 1, 7, 8, 3, 1, 1442997029),
(352, 0, '分拆级别测试', 0, 0, 111, '1', 111, '1', 9, 1, 0, NULL, 0, 1438842060, 1438928460, 1442997030, 0, 0, 2, 7, 14, 3, 1, 1442997030),
(353, 0, '一级任务', 0, 0, 111, '1', 85, '1', 9, 1, 0, NULL, 0, 1438678020, 1438764420, 1442997030, 0, 0, 3, 5, 12, 3, 1, 1442997030),
(354, 0, '分拆测试', 0, 0, 85, '1', 109, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1438847100, 1438847940, 1442997031, 0, 0, 17, 3, 4, 3, 1, 1442997031),
(355, 0, '附件功能测试', 0, 0, 111, '1', 111, '1', 7, 0, 1, NULL, 0, 1439288040, 1439352840, 1442997031, 0, 0, 27, 3, 4, 3, 1, 1442997031),
(356, 0, '中国好声音专题首页顶部增加蓝TV统一导航', 0, 0, 110, '1', 96, '1', 8, 1, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439796120, 1440063000, 1442997032, 0, 0, 46, 7, 10, 3, 1, 1442997032),
(357, 0, 'PC端点播视频码率的调整【修改任务】', 0, 0, 110, '1', 109, '1', 8, 0, 0, '{"1":["9"],"2":["9"],"3":["9"]}', 0, 1439862780, 1440035580, 1442997032, 0, 0, 46, 15, 16, 3, 1, 1442997032),
(358, 0, '中国蓝TV 的H5端，直播页面的样式修改', 0, 0, 99, '1', 109, '1', 8, 0, 0, '{"1":["10"],"2":["8"],"3":["10"]}', 0, 1439862720, 1440035520, 1442997032, 0, 0, 46, 19, 20, 3, 1, 1442997032),
(359, 0, 'inapp的XML文件的制作【开发任务】', 0, 0, 99, '1', 109, '1', 8, 0, 0, NULL, 0, 1439779080, 1440383880, 1442997032, 0, 0, 46, 25, 26, 3, 1, 1442997032),
(360, 0, 'VIP会员去三个月免看广告功能【开发任务】', 0, 0, 110, '1', 109, '1', 8, 0, 0, '{"1":["9"],"2":["9"],"3":["9"]}', 0, 1439778900, 1440124500, 1442997032, 0, 0, 46, 29, 30, 3, 1, 1442997032),
(361, 0, '直播视频前播广告功能的开发【开发任务】', 0, 0, 110, '1', 109, '1', 8, 1, 0, '{"1":["9"],"2":["9"],"3":["9"]}', 0, 1439779020, 1440729420, 1442997033, 0, 0, 46, 35, 40, 3, 1, 1442997033),
(362, 0, '直播视频前播广告功能的开发', 0, 0, 110, '1', 109, '1', 8, 0, 0, '{"1":["9"],"2":["9"],"3":["9"]}', 0, 1440117900, 1440722700, 1442997033, 0, 0, 46, 41, 42, 3, 1, 1442997033),
(363, 0, '子任务1', 0, 0, 111, '1', 111, '1', 7, 0, 1, NULL, 0, 1439450940, 1439969340, 1442997034, 0, 0, 64, 3, 4, 3, 1, 1442997034),
(364, 0, '子任务2', 0, 0, 111, '1', 111, '1', 7, 0, 1, NULL, 0, 1439451000, 1439969400, 1442997034, 0, 0, 64, 5, 6, 3, 1, 1442997034),
(365, 0, '接口更改', 0, 0, 111, '1', 107, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1440148980, 1440550800, 1442997034, 0, 0, 88, 3, 4, 3, 1, 1442997034),
(366, 0, '看主任务', 0, 0, 97, '1', 110, '1', 8, 0, 0, '{"1":"10","2":"10","3":"10"}', 0, 1441695420, 1442285400, 1442997036, 0, 0, 180, 3, 4, 3, 1, 1442997036),
(367, 0, '分拆级别测试', 40, 0, 111, '1', 111, '1', 9, 1, 0, NULL, 0, 1438842120, 1438928520, 1442997044, 0, 0, 2, 8, 13, 4, 1, 1442997044),
(368, 0, '碎片碎片碎片', 0, 0, 111, '1', 111, '1', 9, 1, 0, NULL, 0, 1438695300, 1438745700, 1442997044, 0, 0, 3, 6, 11, 4, 1, 1442997044),
(369, 0, '协调乐视在好声音专题上部加上通栏', 0, 0, 99, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1439797080, 1440056280, 1442997044, 0, 0, 46, 8, 9, 4, 1, 1442997044),
(370, 0, '直播视频前播广告功能', 0, 0, 86, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440035640, 1440640440, 1442997045, 0, 0, 46, 36, 37, 4, 1, 1442997045),
(371, 0, '直播视频前播广告功能的开发', 0, 0, 97, '1', 110, '1', 8, 0, 0, '{"1":["10"],"2":["10"],"3":["10"]}', 0, 1440036000, 1440640800, 1442997045, 0, 0, 46, 38, 39, 4, 1, 1442997045),
(372, 0, '分拆级别测试', 41, 0, 111, '1', 111, '1', 9, 1, 0, NULL, 0, 1438842120, 1438928520, 1442997048, 0, 0, 2, 9, 12, 5, 1, 1442997048),
(373, 0, '碎片碎片碎片', 0, 0, 111, '1', 111, '1', 9, 1, 0, NULL, 0, 1438695360, 1438745760, 1442997048, 0, 0, 3, 7, 10, 5, 1, 1442997048),
(374, 0, '分拆级别测试5', 42, 0, 111, '1', 111, '1', 9, 0, 0, NULL, 0, 1438842180, 1438928580, 1442997056, 0, 0, 2, 10, 11, 6, 1, 1442997056),
(375, 0, '碎片碎片碎片', 0, 0, 111, '1', 111, '1', 9, 0, 0, NULL, 0, 1438695480, 1438743600, 1442997056, 0, 0, 3, 8, 9, 6, 1, 1442997056),
(376, 0, 'test', 0, 0, 0, '1', 111, '1', 4, 0, 3, '', 0, 1443063180, 1443667980, 1443063221, 0, 1, 0, 1, 2, 1, 1, 1443063221),
(377, 0, '1221212', 0, 0, 0, '1', 111, '1', 4, 0, 3, '', 0, 1443063240, 1443668040, 1443063262, 0, 1, 0, 1, 2, 1, 1, 1443063262),
(378, 0, '1111', 3, 0, 82, '1', 111, '1', 6, 0, 0, '', 0, 1443063300, 1443668100, 1443063369, 1445936121, 1, 378, 1, 2, 1, 1, 1443063369),
(379, 0, 'test1100111', 4, 0, 82, '1', 111, '1', 6, 0, 0, '', 0, 1443063420, 1443668220, 1443063490, 1445936104, 1, 379, 1, 2, 1, 1, 1443063490),
(380, 0, '真的在测试啊', 5, 0, 152, '1', 111, '1', 4, 0, 3, '', 0, 1443063540, 1443668340, 1443063592, 0, 1, 380, 1, 2, 1, 1, 1443063592),
(381, 0, 'ces', 6, 0, 145, '1', 111, '1', 4, 0, 3, '', 0, 1443064200, 1443669000, 1443064232, 0, 1, 381, 1, 2, 1, 1, 1443064232),
(382, 0, 'test', 7, 0, 159, '1', 111, '1', 1, 0, 3, '', 0, 1444455480, 1445060280, 0, 0, 1, 382, 1, 2, 1, 1, 1444455528),
(383, 0, '大公会', 8, 0, 159, '1', 111, '1', 1, 0, 3, '', 0, 1444455720, 1445060520, 0, 0, 1, 383, 1, 2, 1, 1, 1444455736),
(384, 0, '速度', 9, 0, 159, '1', 111, '1', 1, 0, 3, '', 0, 1444455720, 1445060520, 0, 0, 1, 384, 1, 2, 1, 1, 1444455741),
(385, 0, '的随时随地', 10, 0, 159, '1', 111, '1', 1, 0, 3, '', 0, 1444455720, 1445060520, 0, 0, 1, 385, 1, 2, 1, 1, 1444455747),
(386, 0, '十大S DFS FD FS FS', 11, 0, 159, '1', 111, '1', 1, 0, 3, '', 0, 1444455720, 1445060520, 0, 0, 1, 386, 1, 2, 1, 1, 1444455753),
(387, 0, '啊啊啊2是', 12, 0, 111, '1', 111, '1', 4, 0, 3, '', 0, 1444455780, 1449713400, 0, 0, 1, 387, 1, 2, 1, 1, 1444455815),
(388, 0, '啊啊而威尔微文深诋2', 13, 0, 116, '1', 111, '1', 1, 0, 3, '', 0, 1444455900, 1445066100, 1444455928, 0, 1, 388, 1, 2, 1, 1, 1444455928),
(389, 0, '胜多负少', 14, 0, 159, '1', 111, '1', 1, 0, 3, '', 0, 1444455960, 1443330540, 0, 0, 1, 389, 1, 2, 1, 1, 1444455980),
(390, 0, '水电费', 15, 0, 159, '1', 111, '1', 1, 0, 3, '', 0, 1444455960, 1444169160, 0, 0, 1, 390, 1, 2, 1, 1, 1444455992),
(391, 0, '二二', 16, 0, 159, '1', 111, '1', 1, 0, 3, '', 0, 1444455960, 1443398880, 0, 0, 1, 391, 1, 2, 1, 1, 1444455999),
(392, 0, '亚泰集团', 17, 0, 158, '1', 111, '1', 1, 0, 3, '', 0, 1444455960, 1443650760, 0, 0, 1, 392, 1, 2, 1, 1, 1444456008),
(393, 0, '推荐软硬件有', 18, 0, 158, '1', 111, '1', 1, 0, 3, '', 0, 1444455960, 1444093740, 0, 0, 1, 393, 1, 2, 1, 1, 1444456027),
(394, 0, '可好了空间', 19, 0, 159, '1', 111, '1', 1, 0, 3, '', 0, 1444456020, 1444180140, 0, 0, 1, 394, 1, 2, 1, 1, 1444456040),
(395, 0, 'jlk', 20, 0, 162, '1', 111, '1', 1, 0, 3, '', 0, 1444466880, 1443297900, 0, 0, 1, 395, 1, 2, 1, 1, 1444466940),
(396, 0, 'as asdhdgfdfgfgd', 21, 0, 162, '1', 111, '1', 1, 0, 3, '', 0, 1444466940, 1443283200, 0, 0, 1, 396, 1, 2, 1, 1, 1444466978),
(397, 0, 'asdsdsdfsfdfds', 22, 0, 162, '1', 111, '1', 1, 0, 3, '', 0, 1444466940, 1445071740, 0, 0, 1, 397, 1, 2, 1, 1, 1444466990),
(398, 0, '啊色粉啊的士速递', 23, 0, 191, '1', 111, '1', 1, 0, 3, '', 0, 1447900320, 1448505120, 0, 0, 1, 398, 1, 2, 1, 1, 1447900345);

-- --------------------------------------------------------

--
-- 表的结构 `task_attachs_relation`
--

CREATE TABLE IF NOT EXISTS `task_attachs_relation` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL COMMENT '任务id',
  `attach_id` int(11) NOT NULL COMMENT '附件id',
  `type` tinyint(4) DEFAULT NULL COMMENT '1:任务附件 2:完成附件'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='record附件类型';

-- --------------------------------------------------------

--
-- 表的结构 `task_contents`
--

CREATE TABLE IF NOT EXISTS `task_contents` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL COMMENT '任务id',
  `isolate_code` int(11) NOT NULL COMMENT '版本树编码',
  `Lft` int(11) NOT NULL COMMENT '版本树左值',
  `Rgt` int(11) NOT NULL COMMENT '版本树右值',
  `title` varchar(1024) NOT NULL COMMENT '任务名称',
  `intro` varchar(1024) DEFAULT NULL,
  `content` text COMMENT '任务详情',
  `signature` varchar(32) DEFAULT NULL COMMENT '版本签名',
  `encrypt` tinyint(2) NOT NULL COMMENT '加密级别0,1,2',
  `status` tinyint(2) NOT NULL COMMENT '1:审核 2:未审核 3:删除',
  `updated` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='任务内容';

--
-- 转存表中的数据 `task_contents`
--

INSERT INTO `task_contents` (`id`, `task_id`, `isolate_code`, `Lft`, `Rgt`, `title`, `intro`, `content`, `signature`, `encrypt`, `status`, `updated`) VALUES
(1, 376, 376, 1, 2, 'test', 'test', '', '098f6bcd4621d373cade4e832627b4f6', 0, 1, 1443063221),
(2, 377, 377, 1, 2, '1221212', '1221212', '', '4f1cb2cce1bd665d34f21735c517cf9f', 0, 1, 1443063262),
(3, 378, 378, 1, 2, '1111', '1111', '', 'b59c67bf196a4758191e42f76670ceba', 0, 1, 1443063369),
(4, 379, 379, 1, 2, 'test1100111', 'test1100111', '', '91778e6494a9753b8578c00ad346a014', 0, 1, 1443063490),
(5, 380, 380, 1, 2, '真的在测试啊', '真的在测试啊', '', 'dca9365d86790ee6524391062dac4e24', 0, 1, 1443063592),
(6, 381, 381, 1, 2, 'ces', 'ces', '', '9d89e27badedeba14a6e13bce87c9957', 0, 1, 1443064232),
(7, 382, 382, 1, 2, 'test', 'test', '', '098f6bcd4621d373cade4e832627b4f6', 0, 1, 1444455528),
(8, 383, 383, 1, 2, '大公会', '大公会', '', '244bf96069c29072ce6ee112b3d3d0c1', 0, 1, 1444455736),
(9, 384, 384, 1, 2, '速度', '速度', '', '03f38597a66d680059d67b9822334e33', 0, 1, 1444455741),
(10, 385, 385, 1, 2, '的随时随地', '的随时随地', '', '4e6bbff727fef6c4a5f34423dded7294', 0, 1, 1444455747),
(11, 386, 386, 1, 2, '十大S DFS FD FS FS', '十大S DFS FD FS FS', '', '034e150847f08ae6d890088a802b0b24', 0, 1, 1444455753),
(12, 387, 387, 1, 2, '啊啊啊2是', '啊啊啊', '', '8cbe98d278a271d2596bda326977b58c', 0, 1, 1444455911),
(13, 388, 388, 1, 2, '啊啊而威尔微文深诋2', '啊啊而威尔微文深诋2', '', 'cebd5e7247114d0a29d3dab17f2a0f06', 0, 1, 1444467407),
(14, 389, 389, 1, 2, '胜多负少', '胜多负少', '', '375d9d83914b0b90de3e1982e8057223', 0, 1, 1444455980),
(15, 390, 390, 1, 2, '水电费', '水电费', '', '065ceddfba02c7409fb3abb88340ca60', 0, 1, 1444455992),
(16, 391, 391, 1, 2, '二二', '二二', '', 'a49b15883bc55b40022e6fbb705a008b', 0, 1, 1444455999),
(17, 392, 392, 1, 2, '亚泰集团', '亚泰集团', '', '2961e0dac0c605827722a42c0bba86f2', 0, 1, 1444456008),
(18, 393, 393, 1, 2, '推荐软硬件有', '推荐软硬件有', '', 'ad7c2f87d57f49f29b1635f86a03936f', 0, 1, 1444456027),
(19, 394, 394, 1, 2, '可好了空间', '可好了空间', '', 'd21386ed4f38643f7ca2687165a801c9', 0, 1, 1444456040),
(20, 395, 395, 1, 2, 'jlk', 'jlk', '', 'a2bf84aad6c3f75024931d9459b2a2d5', 0, 1, 1444466940),
(21, 396, 396, 1, 2, 'as asdhdgfdfgfgd', 'as asdhdgfdfgfgd', '', '7175641c368909c66a133e8cff2d81af', 0, 1, 1444466978),
(22, 397, 397, 1, 2, 'asdsdsdfsfdfds', 'asdsdsdfsfdfds', 'w4rqadsfdsf', '865409cf38361d3d55e0c57b5f130791', 0, 1, 1444467018),
(23, 398, 398, 1, 2, '啊色粉啊的士速递', '啊色粉啊的士速递', '', '9e3c3aff524adbd457af6c6dc59098dc', 0, 1, 1447900345);

-- --------------------------------------------------------

--
-- 表的结构 `task_progress`
--

CREATE TABLE IF NOT EXISTS `task_progress` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `progress` tinyint(4) NOT NULL COMMENT '0:新建(未分配) 1:未接收 2:拒绝 3:重新打开 4:进行中 5:审核驳回 6:提交审核 7:同意完成 8:已评分',
  `user_id` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  `content` text
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `task_progress`
--

INSERT INTO `task_progress` (`id`, `task_id`, `progress`, `user_id`, `updated`, `content`) VALUES
(1, 376, 4, 1443063221, 1443063221, ''),
(2, 377, 4, 1443063262, 1443063262, ''),
(3, 378, 4, 1443063369, 1443063369, ''),
(4, 379, 4, 1443063490, 1443063490, ''),
(5, 380, 4, 1443063592, 1443063592, ''),
(6, 381, 4, 1443064232, 1443064232, ''),
(7, 382, 1, 111, 1444455528, ''),
(8, 383, 1, 111, 1444455736, ''),
(9, 384, 1, 111, 1444455741, ''),
(10, 385, 1, 111, 1444455747, ''),
(11, 386, 1, 111, 1444455753, ''),
(12, 387, 1, 111, 1444455815, ''),
(13, 387, 4, 111, 1444455911, ''),
(14, 388, 4, 111, 1444455928, ''),
(15, 389, 1, 111, 1444455980, ''),
(16, 390, 1, 111, 1444455992, ''),
(17, 391, 1, 111, 1444455999, ''),
(18, 392, 1, 111, 1444456008, ''),
(19, 393, 1, 111, 1444456027, ''),
(20, 394, 1, 111, 1444456040, ''),
(21, 395, 1, 111, 1444466940, ''),
(22, 396, 1, 111, 1444466978, ''),
(23, 397, 1, 111, 1444466990, ''),
(24, 388, 1, 111, 1444467407, ''),
(25, 379, 6, 82, 1445936104, ''),
(26, 378, 6, 82, 1445936121, ''),
(27, 398, 1, 111, 1447900345, '');

-- --------------------------------------------------------

--
-- 表的结构 `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) unsigned NOT NULL COMMENT '模板ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '渠道ID',
  `domain_id` int(11) unsigned NOT NULL COMMENT '域名ID',
  `author_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `type` enum('index','category','detail','album','layout','error','static','custom') DEFAULT 'custom' COMMENT '类型',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '文件名',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `content` longtext NOT NULL COMMENT '内容',
  `url_rules` varchar(255) NOT NULL DEFAULT '' COMMENT '路径规则',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` smallint(2) unsigned NOT NULL COMMENT '状态'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='模板';

--
-- 转存表中的数据 `templates`
--

INSERT INTO `templates` (`id`, `channel_id`, `domain_id`, `author_id`, `type`, `name`, `path`, `content`, `url_rules`, `created_at`, `updated_at`, `status`) VALUES
(1, 0, 1, 81, 'index', 'index.tpl', 'index.tpl', '{extends file="mem:main.tpl"} \n{block name="title" append}\n首页\n{/block}\n{block name="body"}\n<h1>这里是首页</h1>\n{/block}', '/', 1447728607, 1447728622, 1),
(2, 0, 1, 81, 'category', 'category.tpl', 'category.tpl', '{extends file="mem:main.tpl"} \n{block name="title" append}\nID 为 {$category_id} 的分类\n{/block}\n{block name="body"}\n<h1>ID 为 {$category_id} 的分类</h1>\n{print_r(SmartyData::getCategory($category_id))}\n{print_r(SmartyData::getCategoryByCode($category_id))}\n{print_r(SmartyData::getLatest($category_id))}\n{print_r(SmartyData::getLatestWithSort($category_id))}\n{print_r(SmartyData::getLatestByCode($category_id))}\n{print_r(SmartyData::getLatestByCodeWithSort($category_id))}\n{/block}', '/cagories/{category_id}/', 1447728607, 1447728630, 1),
(3, 0, 1, 81, 'detail', 'detail.tpl', 'detail.tpl', '{extends file="mem:main.tpl"} \n{block name="title" append}\n详情 of {$data_id}\n{/block}\n{block name="body"}\n{assign var="r" value=SmartyData::getNews($data_id)}\n<h1>{$r.news.title}</h1>\n<hr />\n<div style="background-color:#2b2b2b;color:#e6e1dc;">{$r.news.intro}</div>\n<p>\n<img src="{$r.news.thumb}" />\n</p>\n<div style="background-color:#e6e1dc;">{SmartyData::getNewsContent($r.news.content, $r.data_data)}</div>\n{/block}', '/view/{data_id}/', 1447728607, 1447728636, 1),
(4, 0, 1, 81, 'custom', 'world.tpl', 'world.tpl', '{extends file="mem:main.tpl"} \n{block name="title" append}\n世界新闻\n{/block}\n{block name="body"}\n<h1>这里是新世界</h1>\n{/block}', '/world/', 1447728607, 1447728622, 1),
(5, 0, 1, 81, 'layout', 'main.tpl', 'main.tpl', '<html>\n  <head>\n    <title>{block name="title"}标题 - {/block}</title>\n  </head>\n  <body>\n	{block name="body"} <h1>Default Layout</h1>{/block}\n  </body>\n</html>', '', 1447728610, 1447728622, 1),
(6, 0, 1, 81, 'custom', 'debug.tpl', 'debug.tpl', '{extends file="mem:main.tpl"} \n{block name="title" append}\n调试\n{/block}\n{block name="body"}\n<h1>调试调试</h1>\n{/block}', '/debug/', 1447728615, 1447728622, 1),
(7, 0, 1, 81, 'error', 'error.tpl', 'error.tpl', '{extends file="mem:main.tpl"} \n{block name="title" append}\n出错啦\n{/block}\n{block name="body"}\n<h1>55555~~~真的出错啦...</h1>\n{/block}', '', 1447728622, 1447728622, 1),
(8, 0, 1, 81, 'album', 'album.tpl', 'album.tpl', '{extends file="mem:main.tpl"} \n{block name="title" append}\n相册 of {$data_id}\n{/block}\n{block name="body"}\n<h1>相册 {$data_id} 的详情</h1>\n{/block}', '/album/{data_id}/', 1447728622, 1447728622, 1),
(9, 1, 2, 84, 'layout', 'default_layout.tpl', 'default_layout.tpl', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml">\r\n<head>\r\n<link rel="icon" href="http://image.xianghunet.com/templates/imgcssjs/1/31//favicon.ico" type="image/x-icon" />\r\n<meta property="qc:admins" content="2550230425601167056546375" />\r\n<link rel="shortcut icon" href="http://image.xianghunet.com/templates/imgcssjs/1/31//favicon.ico" type="image/x-icon" />\r\n<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />\r\n<title>{block name="title"}{/block}湘湖网_萧山网络电视台_萧山第一视频门户网站</title>\r\n<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />\r\n<meta http-equiv="expires" content="0"/>\r\n<meta name="keywords" content="湘湖网,萧山电视台,萧山广播,萧山视频网,萧山新闻,萧山论坛,萧山影视,热线188,萧山天气,萧山机场航班信息"/>\r\n<meta name="description" content="湘湖网位于浙江省杭州市萧山区，是萧山广播电视台对外宣传的网络平台，是萧山的官方新闻网站，开辟了教育、房产、卫生、娱乐、财经、社区等版块，及时发布原创视频、原创新闻、政府信息。是全国首家拥有网络视听许可证的县区级广电网站；承办了全国首届大众网络剧评选活动；拍摄了《西施》等电影。" />\r\n<meta name="author" content="湘湖网" />\r\n<meta name="Copyright" content="湘湖网版权所有">\r\n</meta>\r\n<link href="http://image.xianghunet.com/templates/imgcssjs/1/31/images/style_index_xh.css?20150525150333" rel="stylesheet" type="text/css">\r\n</link>\r\n{literal}\r\n<script type="text/javascript" src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/jquery.js"></script>\r\n<script type="text/javascript" src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/tab_new.js"></script>\r\n<script type="text/javascript" src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/bxCarousel.js"></script>\r\n<script type="text/javascript" src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/cztv.public.js"></script>\r\n<script type="text/javascript">\r\n$(document).ready(function(){\r\n      var banner_li_size=$("#scroll_banner").find("ul").children().length;\r\n      var temp_ul=$("#scroll_banner").find("ul");           \r\n      banner_li_size>=2?$(temp_ul).addClass("demo6"):$(temp_ul).removeClass("demo6");     \r\n  });      \r\n$(function(){   \r\n      $(''#demo1'').bxCarousel({      display_num:4,      move:4,     auto:false,       margin:10   });   \r\n      $(''#demo2,#demo3'').bxCarousel({     display_num:4,      move:4,     auto:true,      margin:28,      auto_hover: true    });   \r\n      $(''#demo4'').bxCarousel({      display_num:1,      move:1,     auto:true,      margin:15,      auto_hover: true    });   \r\n      $(''#demo5'').bxCarousel({      display_num:5,      move:5,     auto:true,      margin:25,      auto_hover: true    });   \r\n      $(''.demo6'').bxCarousel({      display_num:1,      move:1,     auto:true,      margin:0,     auto_hover: true    });\r\n  });\r\n</script>\r\n<script language="javascript">\r\n(function(){\r\n  var res = GetRequest();                     \r\n  if(res[''a'']!=''pc''){                           \r\n    var ua=navigator.userAgent.toLowerCase();                           \r\n    var contains=function (a, b){                                 \r\n          if(a.indexOf(b)!=-1){return true;}                          \r\n      };                          \r\n    var toMobileVertion = function(){                           \r\n      window.location.href = ''http://wap.xianghunet.com'';\r\n      };                            \r\n    if(contains(ua,"ipad")||(contains(ua,"rv:1.2.3.4"))||(contains(ua,"0.0.0.0"))||(contains(ua,"8.0.552.237"))){\r\n      window.location.href = ''http://www.xianghunet.com/ipad/'';       \r\n    }\r\n    if((contains(ua,"android")&&contains(ua,"mobile"))||(contains(ua,"android") && contains(ua,"mozilla"))||(contains(ua,"android") && contains(ua,"opera"))||contains(ua,"ucweb7")||contains(ua,"iphone")){          toMobileVertion();        \r\n    }\r\n  }\r\n  })();\r\n\r\n  function GetRequest() {\r\n    var url = location.search;\r\n    var theRequest = new Object();\r\n    if (url.indexOf("?") != -1) {\r\n      var str = url.substr(1);        \r\n      strs = str.split("&");\r\n      for(var i = 0; i < strs.length; i ++) {           \r\n        theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);        \r\n      }\r\n    }\r\n    return theRequest;\r\n  }\r\n</script>\r\n<script type="text/javascript" src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/myfocus-1.1.0.min.js"></script>\r\n<script type="text/javascript" src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/mF_YSlider.js"></script>\r\n<link id="mf-css" rel="stylesheet" href="http://image.xianghunet.com/templates/imgcssjs/1/31/images/mF_YSlider.css">\r\n</link>\r\n<script type="text/javascript">/*实例化*/  myFocus.set({   id:''myFocus'',/*焦点图ID*/    pattern:''mF_YSlider'',/*风格样式*/   time:3,/*切换时间间隔(秒)*/    trigger:''click'',/*触发切换模式：''click''(点击)/''mouseover''(悬停)*/    width:725,/*设置宽度(主图区)*/   height:365,/*设置高度(主图区)*/    txtHeight:''default''/*文字层高度设置，''default''为默认高度，0为隐藏*/  });</script>\r\n\r\n{/literal}\r\n</head>\r\n<body>\r\n<div xmlns="" id="header">\r\n  <div class="top_bar clearfix">\r\n    <div class="date fl">\r\n\r\n{literal}\r\n    <script type="text/javascript">                function initArray(){                for(i=0;i<initArray.arguments.length;i++)                this[i]=initArray.arguments[i];              }                            var isnMonths=new initArray("1","2","3","4","5","6","7","8","9","10","11","12");              var isnDays=new initArray("星期日","星期一","星期二","星期三","星期四","星期五","星期六","星期日");              today=new Date();              hrs=today.getHours();              min=today.getMinutes();              sec=today.getSeconds();              clckh=""+((hrs>12)?hrs-12:hrs);              clckm=((min<10)?"0":"")+min;clcks=((sec<10)?"0":"")+sec;              clck=(hrs>=12)?"下午":"上午";              var stnr="";              var ns="0123456789";              var a="";                            function getFullYear(d){                yr=d.getYear();                if(yr<1000)                yr+=1900;                return yr;              }                      $(document).ready(function(){             $(''.channel .c1'').hover(function(){                 if($(this).find(''ul'').css(''display'')==''none''){                    $(this).find(''ul'').show();                }             },              function(){               if($(this).find(''ul'').css(''display'')==''block''){                   $(this).find(''ul'').hide();                }             });                           $(''.channel .c2'').hover(function(){         if($(this).find(''ul'').css(''display'')==''none''){                        $(this).find(''ul'').show();        }                 },function(){       if($(this).find(''ul'').css(''display'')==''block''){                         $(this).find(''ul'').hide();        }                 });                                                     });                                     </script><script type="text/javascript">              /*下面各行分别是一种风格，把不需要的删掉即可*/              document.write("今天" + " " + getFullYear(today)+"年" + isnMonths[today.getMonth()] +"月" + today.getDate() + "日" + "&nbsp;&nbsp;" + isnDays[today.getDay()]);                      </script>\r\n\r\n{/literal}</div>\r\n    <div class="weather fl">\r\n      <iframe src="http://m.weather.com.cn/m/pn5/weather.htm" width="140" height="22" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no"></iframe>\r\n    </div>\r\n    <div class="search fl">\r\n      <form action="/news/list" method="get">\r\n        <div class="bo1 fl">\r\n          <input name="keywords" onfocus="if(this.value==''输入您想搜索的内容'') this.value='''';" type="text" value="输入您想搜索的内容" class="input_txt">\r\n        </div>\r\n        <div class="bo2 fl">\r\n          <input name="" type="submit" value="" class="input_search">\r\n        </div>\r\n      </form>\r\n    </div>\r\n    <div id="authstatus02" class="register fr"><a href="http://zjnews.xianghunet.com/admin/login.asp" title="镇街传稿" target="_blank">镇街传稿</a></div>\r\n    <div id="authstatus00" class="login fr"><a href="/index.php?module=xianghunet&amp;namespace=default&amp;controller=site&amp;action=login" title="登录" target="_blank">登录</a></div>\r\n    <div id="authstatus01" class="login fr"><a href="/index.php?module=xianghunet&amp;namespace=default&amp;controller=site&amp;action=register" title="注册" target="_blank">注册</a></div>\r\n    <div id="authstatus03" class="login fr"><a href="http://2013.xianghunet.com/" title="旧版" target="_blank">旧版</a></div>\r\n    <div id="authstatus1" class="logout fr"><span><a href="http://2013.xianghunet.com/" title="旧版" target="_blank">旧版</a></span><span style="width:120px; overflow:hidden;">欢迎您，<strong>小明同学</strong></span><span><a href="/index.php?module=xianghunet&amp;namespace=default&amp;controller=user&amp;action=index" title="会员中心" target="_blank">会员中心</a></span><span><a href="http://zjnews.xianghunet.com/admin/login.asp" title="镇街传稿" target="_blank">镇街传稿</a></span><span><a href="/index.php?module=xianghunet&amp;namespace=default&amp;controller=site&amp;action=logout" title="退出" target="_blank">退出</a></span></div>\r\n\r\n{literal}\r\n    <script type="text/javascript">                       $(document).ready(function(){                        $.get( "/index.php?module=xianghunet&namespace=default&controller=site&action=authcheck",                       function(result){                         if(result.status) {                           $("#authstatus1 span strong").html(result.userinfo.nikename);                                $("#authstatus1").css("display","block");                                  $("#authstatus00").css("display","none");                                    $("#authstatus01").css("display","none");                                    $("#authstatus02").css("display","none");                                   $("#authstatus03").css("display","none");                         }              else {                $("#authstatus1").css("display","none");                              }           },''json'');      });       </script>\r\n\r\n{/literal}</div>\r\n  <div class="top_side">\r\n    <div class="logo fl"><a href="http://www.xianghunet.com/" title="湘湖网 萧山网络电视台"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/logo_xh.jpg" alt=""></a></div>\r\n    <div class="banner_01 fl"><!-- 广告位：首页顶部LOGO右侧621*71 --> \r\n      <script type="text/javascript">BAIDU_CLB_SLOT_ID = "798893";</script> \r\n      <script type="text/javascript" src="http://cbjs.baidu.com/js/o.js"></script></div>\r\n    <div class="channel fr">\r\n      <div class="c1"><a href="javascript:void(0);" title="部门频道" class="red">部门频道</a>\r\n        <ul>\r\n          <li><a href="http://www.xianghunet.com/news/list/11913.html" title="部门综合">部门综合</a></li>\r\n          <li><a href="/bumen/397.html" title="萧山区纪委">萧山区纪委</a></li>\r\n          <li><a href="/bumen/398.html" title="区委组织部">区委组织部</a></li>\r\n          <li><a href="/bumen/286.html" title="萧山区妇联">萧山区妇联</a></li>\r\n          <li><a href="/bumen/399.html" title="环境保护局">环境保护局</a></li>\r\n          <li><a href="/bumen/400.html" title="萧山团区委">萧山团区委</a></li>\r\n          <li><a href="/bumen/402.html" title="萧山老龄委">萧山老龄委</a></li>\r\n          <li><a href="http://www.xianghunet.com/hsjt" title="红色讲坛">红色讲坛</a></li>\r\n          <li><a href="/bumen/412.html" title="城市管理局">城市管理局</a></li>\r\n          <li><a href="/bumen/413.html" title="城乡一体办">城乡一体办</a></li>\r\n          <li><a href="/bumen/414.html" title="民政局">民政局</a></li>\r\n          <li><a href="/bumen/401.html" title="萧山供电局">萧山供电局</a></li>\r\n          <li><a href="http://www.xshszh.cn/" title="萧山红十字会">萧山红十字会</a></li>\r\n        </ul>\r\n      </div>\r\n      <div class="c2" style="z-index:1"><a href="javascript:void(0);" title="镇街频道" class="red">镇街频道</a>\r\n        <ul>\r\n          <li><a href="/zhenjie/294.html" title="瓜沥">瓜沥</a></li>\r\n          <li><a href="/zhenjie/513.html" title="临浦">临浦</a></li>\r\n          <li><a href="/zhenjie/522.html" title="义蓬">义蓬</a></li>\r\n          <li><a href="/zhenjie/504.html" title="楼塔">楼塔</a></li>\r\n          <li><a href="/zhenjie/500.html" title="河上">河上</a></li>\r\n          <li><a href="/zhenjie/520.html" title="戴村">戴村</a></li>\r\n          <li><a href="/zhenjie/523.html" title="浦阳">浦阳</a></li>\r\n          <li><a href="/zhenjie/519.html" title="进化">进化</a></li>\r\n          <li><a href="/zhenjie/501.html" title="所前">所前</a></li>\r\n          <li><a href="/zhenjie/518.html" title="义桥">义桥</a></li>\r\n          <li><a href="/zhenjie/502.html" title="闻堰">闻堰</a></li>\r\n          <li><a href="/zhenjie/506.html" title="宁围">宁围</a></li>\r\n          <li><a href="/zhenjie/511.html" title="新街">新街</a></li>\r\n          <li><a href="/zhenjie/515.html" title="衙前">衙前</a></li>\r\n          <li><a href="/zhenjie/510.html" title="益农">益农</a></li>\r\n          <li><a href="/zhenjie/505.html" title="靖江">靖江</a></li>\r\n          <li><a href="/zhenjie/509.html" title="南阳">南阳</a></li>\r\n          <li><a href="/zhenjie/517.html" title="河庄">河庄</a></li>\r\n          <li><a href="/zhenjie/503.html" title="党湾">党湾</a></li>\r\n          <li><a href="/zhenjie/508.html" title="红山">红山</a></li>\r\n          <li><a href="/zhenjie/521.html" title="新湾">新湾</a></li>\r\n          <li><a href="/zhenjie/514.html" title="城厢">城厢</a></li>\r\n          <li><a href="/zhenjie/516.html" title="北干">北干</a></li>\r\n          <li><a href="/zhenjie/512.html" title="蜀山">蜀山</a></li>\r\n          <li><a href="/zhenjie/507.html" title="新塘">新塘</a></li>\r\n          <li><a href="/zhenjie/525.html" title="临江">临江</a></li>\r\n          <li><a href="/zhenjie/524.html" title="前进">前进</a></li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n    <div class="cl"></div>\r\n  </div>\r\n  <div class="top_menu">\r\n    <div class="pack">\r\n      <div class="menu1 clearfix">\r\n        <div class="m1 fl">\r\n          <ul class="clearfix" id="menu1">\r\n            <li id="five1"><a href="/news/" title="新闻" target="_blank">新闻</a></li>\r\n            <li id="five2"><a href="/shijie/" title="视界" target="_blank">视界</a></li>\r\n            <li id="five3"><a href="/zhibo/" title="直播" target="_blank">直播</a></li>\r\n            <li id="five4"><a href="/guangbo/" title="广播" target="_blank">广播</a></li>\r\n            <li id="five5"><a href="/huodong/" title="专题" target="_blank">专题</a></li>\r\n            <li id="five6"><a href="http://me.xianghunet.com/" title="拍客">拍客</a></li>\r\n            <li id="five7"><a href="/photo/" title="图片" target="_blank">图片</a></li>\r\n          </ul>\r\n        </div>\r\n        <div class="m2 fr">\r\n          <ul class="clearfix">\r\n            <li><i><b><a href="/shiting/" title="湘湖视听" target="_blank">湘湖视听</a></b></i></li>\r\n            <li><i><b><a href="http://weibo.com/xiaoshantv" title="微博" target="_blank">微博</a></b></i></li>\r\n            <li><i><b><a href="/weixin" title="微信" target="_blank">微信</a></b></i></li>\r\n            <li><i><b><a href="javascript:void(0)" title="手机电视">手机电视</a></b></i></li>\r\n            <li><i><b><a href="/index.php?module=xianghunet&amp;controller=baoliao&amp;action=index" title="爆料" target="_blank">爆料</a></b></i></li>\r\n          </ul>\r\n        </div>\r\n      </div>\r\n      <div class="menu2" id="menu2"></div>\r\n      <div style="display:none">\r\n        <input type="hidden" id="defaultid" value="0">\r\n        <ul>\r\n          <li id="dsTitle_five0"><a href="http://www.qianrengou.com/" title="千人购" target="_blank">千人购</a>| <a href="http://bbs.xianghunet.com/" title="萧山论坛" target="_blank" style="color: #ff5213">萧山论坛</a>| <a href="/edu" title="萧山网络教育台" target="_blank">萧山网络教育台</a>| <a href="/health" title="萧然健康" target="_blank">萧然健康</a>| <a href="/ent" title="萧然文娱" target="_blank">萧然文娱</a>| <a href="/finance" title="萧山理财帮" target="_blank">萧山理财帮</a>| <a href="/zqmyztc" title="政情民意直通车" target="_blank">政情民意直通车</a>| <a href="/course" title="空中课堂" target="_blank">空中课堂</a>| <a href="/2015/zmjc" title="周末剧场" target="_blank">周末剧场</a>| </li>\r\n          <li id="dsTitle_five1" style=""><a href="/news/list/283.html" title="看萧山" target="_blank">看萧山</a>| <a href="/news/list/301.html" title="看周边" target="_blank">看周边</a>| <a href="/news/list/302.html" title="看中国" target="_blank">看中国</a>| <a href="/news/list/303.html" title="看世界" target="_blank">看世界</a>| <a href="/ent" title="娱乐" target="_blank">娱乐</a>| <a href="/health" title="健康" target="_blank">健康</a>| <a href="/edu" title="教育" target="_blank">教育</a>| <a href="/finance" title="理财" target="_blank">理财</a>| <a href="/lanmu/285.html" title="萧山新闻" target="_blank">萧山新闻</a>| <a href="/lanmu/307.html" title="热线188" target="_blank">热线188</a>| <a href="/lanmu/281.html" title="社会聚焦" target="_blank">社会聚焦</a>| </li>\r\n          <li id="dsTitle_five2">\r\n          <li id="dsTitle_five3">\r\n          <li id="dsTitle_five4"><a href="/news/list/348.html" title="广播品牌栏目" target="_blank">广播品牌栏目</a>| </li>\r\n          <li id="dsTitle_five5"><a href="/news/list/353.html" title="活动专题" target="_blank">活动专题</a>| <a href="/news/list/1252.html" title="新闻专题" target="_blank">新闻专题</a>| </li>\r\n          <li id="dsTitle_five6"><a href="http://me.xianghunet.com/video-list-2.html" title="娱乐动态" target="_blank">娱乐动态</a>| <a href="http://me.xianghunet.com/video-list-1.html" title="搞笑联盟" target="_blank">搞笑联盟</a>| <a href="http://me.xianghunet.com/video-list-3.html" title="生活百科" target="_blank">生活百科</a>| <a href="http://me.xianghunet.com/video-list-4.html" title="奇闻异事" target="_blank">奇闻异事</a>| <a href="http://me.xianghunet.com/video-list-5.html" title="清新一族" target="_blank">清新一族</a>| </li>\r\n          <li id="dsTitle_five7"><a href="/photo/list/1066.html" title="热点推荐" target="_blank">热点推荐</a>| <a href="/photo/list/368.html" title="微友推荐" target="_blank">微友推荐</a>| <a href="/photo/list/369.html" title="美食美拍" target="_blank">美食美拍</a>| <a href="/photo/list/370.html" title="秀色可餐" target="_blank">秀色可餐</a>| <a href="/photo/list/371.html" title="视觉漫游" target="_blank">视觉漫游</a>| <a href="/photo/list/372.html" title="美图精选" target="_blank">美图精选</a>| </li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n\r\n{literal}\r\n<script xmlns="">       \r\n  var did=parseInt($("#defaultid").val());    \r\n  var defaultid=''five''+$("#defaultid").val();   \r\n  var t;    \r\n  function initMenu(id){        \r\n    defaultid=''five''+id;        \r\n    if(id>0){         \r\n      $("#"+defaultid).addClass("on");        \r\n    }       \r\n    var submenu=$("#dsTitle_"+defaultid).html();        \r\n    $("#menu2").html(submenu);    \r\n  }  \r\n  \r\n  $(document).ready(function(){               \r\n        $("#menu1").find(''li'').bind(''mouseover'',function(){       \r\n            clearTimeout(t);        \r\n        var curid=$(this).attr("id");       \r\n        var submenu=$("#dsTitle_"+curid).html();        \r\n        $("#menu1").find(''li'').removeClass(''on'');       $(this).addClass("on");         $("#menu2").html(submenu);      });     $("#menu1").find(''li'').bind(''mouseout'',function(){                t=setTimeout(function(){          var submenu=$("#dsTitle_"+defaultid).html();          $("#menu1").find(''li'').removeClass(''on'');         $("#"+defaultid).addClass("on");          $("#menu2").html(submenu);        },500);         });         $("#menu2").bind(''mouseover'',function(){        clearTimeout(t);      });           $("#menu2").bind(''mouseleave'',function(){         var submenu=$("#dsTitle_"+defaultid).html();          $("#menu1").find(''li'').removeClass(''on'');         $("#"+defaultid).addClass("on");          $("#menu2").html(submenu);      });         });   initMenu(did);        </script>\r\n\r\n{/literal}\r\n\r\n\r\n{block name="body"}\r\n<h1>这里是首页</h1>\r\n{/block}\r\n\r\n\r\n\r\n<div xmlns="" id="footer">\r\n  <div class="footer_menu"><a href="/about" title="">关于本站</a> | <a href="/disclaimer" title="">免责声明</a> | <a href="/contact" title="">联系我们</a> | <a href="/privacy" title="">版权隐私</a> | <a href="/friendlink" title="">友情链接</a> | <a href="/law" title="">法律顾问</a> | <a href="/sitemap" title="">网站地图</a></div>\r\n  <div class="copyright">\r\n    <p>Copyright © xianghunet.com All Rights Reserved. <strong><a href="/" title="杭州市萧山广播电视台">杭州市萧山广播电视台</a></strong> 版权所有</p>\r\n    <p>浙ICP备15010528号  信息网络传播视听节目许可证号：1110489</p>\r\n    <p>浙江网络广播电视台萧山分台</p>\r\n  </div>\r\n</div>\r\n\r\n{literal}\r\n<script xmlns="" type="text/javascript">\r\n      var countsetaaa = 0;        var aaaIntervalID;        function setaaa() {         countsetaaa++;          if(countsetaaa>8)     clearInterval(aaaIntervalID);           $(''a'').each(function(){             if(!($(this).attr(''href'')==''javascript:void(0);''||$(this).attr(''href'')==''javascript:;''||$(this).attr(''href'')==''javascript:void(0)''||$(this).attr(''target''))){               $(this).attr(''target'',''_blank'');              }           });             }           aaaIntervalID = setInterval("setaaa()", 1000);            $(function(){           $(''a'').each(function(){             if(!($(this).attr(''href'')==''javascript:void(0);''||$(this).attr(''href'')==''javascript:;''||$(this).attr(''href'')==''javascript:void(0)''||$(this).attr(''target''))){               $(this).attr(''target'',''_blank'');              }           });         })\r\n    </script><script xmlns="" type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id=''cnzz_stat_icon_2998585''%3E%3C/span%3E%3Cscript src=''" + cnzz_protocol + "s4.cnzz.com/stat.php%3Fid%3D2998585%26show%3Dpic'' type=''text/javascript''%3E%3C/script%3E"));</script> \r\n&nbsp;&nbsp;&nbsp;&nbsp; \r\n<script type="text/javascript">BAIDU_CLB_fillSlot("849022");</script> \r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; \r\n<script type="text/javascript">BAIDU_CLB_fillSlot("851153");</script>\r\n\r\n{/literal}\r\n</body>\r\n</html>', '', 1447728610, 1447728625, 1);
INSERT INTO `templates` (`id`, `channel_id`, `domain_id`, `author_id`, `type`, `name`, `path`, `content`, `url_rules`, `created_at`, `updated_at`, `status`) VALUES
(10, 1, 2, 84, 'index', 'index.tpl', 'index.tpl', '{extends file="mem:default_layout.tpl"} \r\n{block name="title" append}\r\n首页\r\n{/block}\r\n{block name="body"}\r\n<h1>这里是首页</h1>\r\n\r\n\r\n<div class="ad_box_06" style="margin-top:-10px"><a href="http://www.xianghunet.com/2014/wsgz"><img src="http://ubmcmm.baidustatic.com/media/v1/0f000KY6nQdjbfFYPam6K6.jpg"></img></a></div>\r\n<div class="ad_box_06" style="margin-top:-10px"><script type="text/javascript">BAIDU_CLB_fillSlot("942348");</script></div>\r\n<div class="ad_box_06" style="margin-top:-10px"><a href="http://bbs.xianghunet.com/zhuanti/xialingying/index.html"><img src="http://image.xianghunet.com/templates/project/xianghunet/images/ad/lsds2.jpg"></img></a></div>\r\n<div class="ad_box_06" style="margin-top:-10px">\r\n  <div style="float:left; width:485px;"><a href="http://www.xianghunet.com/2015/zfjs"><img src="http://image.xianghunet.com/templates/project/xianghunet/images/ad/zfjs.jpg"></img></a></div>\r\n  <div style="float:right; width:485px;"><a href="http://www.xianghunet.com/2015/jdzz"><img src="http://image.xianghunet.com/templates/project/xianghunet/images/ad/jdzz.jpg"></img></a></div>\r\n  <div style="clear:both"></div>\r\n</div>\r\n<div id="content" class="clearfix" style="padding:0;">\r\n  <div xmlns="" class="xstv">\r\n    <div class="bx_wrap">\r\n      <div class="bx_container">\r\n        <ul id="demo1" class="xstv_list">\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/285.html" title="萧山新闻" target="_blank"><img src="http://image.xianghunet.com/201501/21/0abd95838ee502e0fd7766f5928a96b1.jpg" alt="萧山新闻"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/285.html" title="萧山新闻" target="_blank">萧山新闻</a></h2>\r\n              <p><a href="/lanmu/285.html?date=2015-11-18" title="2015年11月18日《萧山新闻》" target="_blank">2015年11月18日《萧山新闻》</a></p>\r\n              <p><a href="/lanmu/285.html?date=2015-11-17" title="2015年11月17日《萧山新闻》" target="_blank">2015年11月17日《萧山新闻》</a></p>\r\n              <p><a href="/lanmu/285.html?date=2015-11-16" title="2015年11月16日《萧山新闻》" target="_blank">2015年11月16日《萧山新闻》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/307.html" title="热线188" target="_blank"><img src="http://image.xianghunet.com/201501/21/218f01afc8b95ac3fccb0cb84c3552b9.jpg" alt="热线188"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/307.html" title="热线188" target="_blank">热线188</a></h2>\r\n              <p><a href="/lanmu/307.html?date=2015-11-18" title="2015年11月18日《热线188》" target="_blank">2015年11月18日《热线188》</a></p>\r\n              <p><a href="/lanmu/307.html?date=2015-11-17" title="2015年11月17日《热线188》" target="_blank">2015年11月17日《热线188》</a></p>\r\n              <p><a href="/lanmu/307.html?date=2015-11-16" title="2015年11月16日《热线188》" target="_blank">2015年11月16日《热线188》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/kxs" title="天天看萧山" target="_blank"><img src="/images/ttkxs.jpg" alt="天天看萧山"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/kxs" title="天天看萧山" target="_blank">天天看萧山</a></h2>\r\n              <p><a href="/kxs?id=1676967" title="2015年11月18日《金色大地》" target="_blank">2015年11月18日《金色大地》</a></p>\r\n              <p><a href="/kxs?id=1676939" title="2015年11月17日《农村大视野》" target="_blank">2015年11月17日《农村大视野》</a></p>\r\n              <p><a href="/kxs?id=1676910" title="2015年11月16日《萧山环保》" target="_blank">2015年11月16日《萧山环保》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/298.html" title="每周财经" target="_blank"><img src="/images/lanmu/%E6%AF%8F%E5%91%A8%E8%B4%A2%E7%BB%8F.jpg" alt="每周财经"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/298.html" title="每周财经" target="_blank">每周财经</a></h2>\r\n              <p><a href="/lanmu/298.html?date=2015-11-18" title="2015年11月18日《每周财经》" target="_blank">2015年11月18日《每周财经》</a></p>\r\n              <p><a href="/lanmu/298.html?date=2015-11-11" title="2015年11月11日《每周财经》" target="_blank">2015年11月11日《每周财经》</a></p>\r\n              <p><a href="/lanmu/298.html?date=2015-11-04" title="2015年11月04日《每周财经》" target="_blank">2015年11月04日《每周财经》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/281.html" title="社会聚焦" target="_blank"><img src="http://image.xianghunet.com/201501/21/823f64da1635426aa7bf75c0c719c16a.jpg" alt="社会聚焦"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/281.html" title="社会聚焦" target="_blank">社会聚焦</a></h2>\r\n              <p><a href="/lanmu/281.html?date=2015-11-15" title="2015年11月15日《社会聚焦》之细读我区工业企业效益综合评价体系" target="_blank">2015年11月15日《社会聚焦》之细读我区工业企业效益综合评价体系</a></p>\r\n              <p><a href="/lanmu/281.html?date=2015-11-08" title="2015年11月08日《社会聚焦》之两新组织强化特色党建工作 助力社会发展" target="_blank">2015年11月08日《社会聚焦》之两新组织强化特色党建工作 助力社会发展</a></p>\r\n              <p><a href="/lanmu/281.html?date=2015-11-01" title="2015年11月01日《社会聚焦》之 家庭过期药品去哪儿" target="_blank">2015年11月01日《社会聚焦》之 家庭过期药品去哪儿</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/290.html" title="农村大视野" target="_blank"><img src="/images/lanmu/%E5%86%9C%E6%9D%91%E5%A4%A7%E8%A7%86%E9%87%8E.jpg" alt="农村大视野"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/290.html" title="农村大视野" target="_blank">农村大视野</a></h2>\r\n              <p><a href="/lanmu/290.html?date=2015-11-17" title="2015年11月17日《农村大视野》" target="_blank">2015年11月17日《农村大视野》</a></p>\r\n              <p><a href="/lanmu/290.html?date=2015-11-10" title="2015年11月10日《农村大视野》" target="_blank">2015年11月10日《农村大视野》</a></p>\r\n              <p><a href="/lanmu/290.html?date=2015-11-03" title="2015年11月03日《农村大视野》" target="_blank">2015年11月03日《农村大视野》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/288.html" title="萧山党建" target="_blank"><img src="/images/lanmu/%E8%90%A7%E5%B1%B1%E5%85%9A%E5%BB%BA.jpg" alt="萧山党建"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/288.html" title="萧山党建" target="_blank">萧山党建</a></h2>\r\n              <p><a href="/lanmu/288.html?date=2015-11-14" title="2015年11月14日《萧山党建》" target="_blank">2015年11月14日《萧山党建》</a></p>\r\n              <p><a href="/lanmu/288.html?date=2015-11-07" title="2015年11月07日《萧山党建》" target="_blank">2015年11月07日《萧山党建》</a></p>\r\n              <p><a href="/lanmu/288.html?date=2015-10-31" title="2015年10月31日《萧山党建》" target="_blank">2015年10月31日《萧山党建》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/423.html" title="阿通哥说新闻" target="_blank"><img src="http://image.xianghunet.com/201405/09/0034eb2a2dcfb1e8d9038a0b4c127d7c.jpg" alt="阿通哥说新闻"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/423.html" title="阿通哥说新闻" target="_blank">阿通哥说新闻</a></h2>\r\n              <p><a href="/lanmu/423.html?date=2015-11-17" title="2015年11月17日《阿通哥说新闻》" target="_blank">2015年11月17日《阿通哥说新闻》</a></p>\r\n              <p><a href="/lanmu/423.html?date=2015-11-16" title="2015年11月16日《阿通哥说新闻》" target="_blank">2015年11月16日《阿通哥说新闻》</a></p>\r\n              <p><a href="/lanmu/423.html?date=2015-11-13" title="2015年11月13日《阿通哥说新闻》" target="_blank">2015年11月13日《阿通哥说新闻》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/289.html" title="萧山法治" target="_blank"><img src="/images/lanmu/%E8%90%A7%E5%B1%B1%E6%B3%95%E6%B2%BB.jpg" alt="萧山法治"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/289.html" title="萧山法治" target="_blank">萧山法治</a></h2>\r\n              <p><a href="/lanmu/289.html?date=2015-11-15" title="2015年11月15日《萧山法治》" target="_blank">2015年11月15日《萧山法治》</a></p>\r\n              <p><a href="/lanmu/289.html?date=2015-11-08" title="2015年11月08日《萧山法治》" target="_blank">2015年11月08日《萧山法治》</a></p>\r\n              <p><a href="/lanmu/289.html?date=2015-11-01" title="2015年11月01日《萧山法治》" target="_blank">2015年11月01日《萧山法治》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/298.html" title="每周财经" target="_blank"><img src="/images/lanmu/%E6%AF%8F%E5%91%A8%E8%B4%A2%E7%BB%8F.jpg" alt="每周财经"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/298.html" title="每周财经" target="_blank">每周财经</a></h2>\r\n              <p><a href="/lanmu/298.html?date=2015-11-18" title="2015年11月18日《每周财经》" target="_blank">2015年11月18日《每周财经》</a></p>\r\n              <p><a href="/lanmu/298.html?date=2015-11-11" title="2015年11月11日《每周财经》" target="_blank">2015年11月11日《每周财经》</a></p>\r\n              <p><a href="/lanmu/298.html?date=2015-11-04" title="2015年11月04日《每周财经》" target="_blank">2015年11月04日《每周财经》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/382.html" title="政情民意直通车" target="_blank"><img src="/images/lanmu/zqmyztc.jpg" alt="政情民意直通车"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/382.html" title="政情民意直通车" target="_blank">政情民意直通车</a></h2>\r\n              <p><a href="/lanmu/382.html?date=2015-11-18" title="区发改局旅游局卫计局广电中心负责人—“建设智慧城市”专题" target="_blank">区发改局旅游局卫计局广电中心负责人—“建设智慧城市”专题</a></p>\r\n              <p><a href="/lanmu/382.html?date=2015-11-04" title="区办事服务中心负责人—“一站式服务”专题" target="_blank">区办事服务中心负责人—“一站式服务”专题</a></p>\r\n              <p><a href="/lanmu/382.html?date=2015-10-21" title="区老龄委区民政局负责人—“加强养老为老服务”专题" target="_blank">区老龄委区民政局负责人—“加强养老为老服务”专题</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/416.html" title="房产百事通" target="_blank"><img src="http://image.xianghunet.com/201403/18/e61e686b4c1a6fa9b0d5984dc6c68e06.jpg" alt="房产百事通"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/416.html" title="房产百事通" target="_blank">房产百事通</a></h2>\r\n              <p><a href="/lanmu/416.html?date=2015-11-16" title="2015年11月16日《房产百事通》" target="_blank">2015年11月16日《房产百事通》</a></p>\r\n              <p><a href="/lanmu/416.html?date=2015-11-02" title="2015年11月02日《房产百事通》" target="_blank">2015年11月02日《房产百事通》</a></p>\r\n              <p><a href="/lanmu/416.html?date=2015-10-26" title="2015年10月26日《房产百事通》" target="_blank">2015年10月26日《房产百事通》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/420.html" title="名师风采" target="_blank"><img src="http://image.xianghunet.com/201405/12/35119404ccb2286995d5ee3ac36f51b6.jpg" alt="名师风采"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/420.html" title="名师风采" target="_blank">名师风采</a></h2>\r\n              <p><a href="/lanmu/420.html?date=2015-01-12" title="周玲莉 新街初中" target="_blank">周玲莉 新街初中</a></p>\r\n              <p><a href="/lanmu/420.html?date=2015-01-05" title="高建祥 萧山中学" target="_blank">高建祥 萧山中学</a></p>\r\n              <p><a href="/lanmu/420.html?date=2014-12-29" title="刘  燕 湘湖小学" target="_blank">刘  燕 湘湖小学</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/421.html" title="教育新天地" target="_blank"><img src="http://image.xianghunet.com/201405/12/d25417d4cc5c65f8b01b1467b2fb2c99.jpg" alt="教育新天地"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/421.html" title="教育新天地" target="_blank">教育新天地</a></h2>\r\n              <p><a href="/lanmu/421.html?date=2015-11-12" title="2015年11月12日《教育新天地》" target="_blank">2015年11月12日《教育新天地》</a></p>\r\n              <p><a href="/lanmu/421.html?date=2015-11-05" title="2015年11月05日《教育新天地》" target="_blank">2015年11月05日《教育新天地》</a></p>\r\n              <p><a href="/lanmu/421.html?date=2015-10-29" title="2015年10月29日《教育新天地》" target="_blank">2015年10月29日《教育新天地》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/422.html" title="名校展播" target="_blank"><img src="http://image.xianghunet.com/201405/12/b39841491e208887fdecc13273c814c7.jpg" alt="名校展播"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/422.html" title="名校展播" target="_blank">名校展播</a></h2>\r\n              <p><a href="/lanmu/422.html?date=2015-01-13" title="朝晖小学" target="_blank">朝晖小学</a></p>\r\n              <p><a href="/lanmu/422.html?date=2015-01-06" title="汇宇小学" target="_blank">汇宇小学</a></p>\r\n              <p><a href="/lanmu/422.html?date=2014-12-30" title="南阳初级中学" target="_blank">南阳初级中学</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/291.html" title="爱心满天" target="_blank"><img src="/images/lanmu/%E7%88%B1%E5%BF%83%E6%BB%A1%E5%A4%A9.jpg" alt="爱心满天"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/291.html" title="爱心满天" target="_blank">爱心满天</a></h2>\r\n              <p><a href="/lanmu/291.html?date=2015-03-15" title="2015年03月15日《爱心满天》" target="_blank">2015年03月15日《爱心满天》</a></p>\r\n              <p><a href="/lanmu/291.html?date=2015-03-08" title="2015年03月08日《爱心满天》" target="_blank">2015年03月08日《爱心满天》</a></p>\r\n              <p><a href="/lanmu/291.html?date=2015-03-01" title="2015年03月01日《爱心满天》" target="_blank">2015年03月01日《爱心满天》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/295.html" title="健康生活" target="_blank"><img src="/images/lanmu/%E5%81%A5%E5%BA%B7%E7%94%9F%E6%B4%BB.jpg" alt="健康生活"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/295.html" title="健康生活" target="_blank">健康生活</a></h2>\r\n              <p><a href="/lanmu/295.html?date=2015-11-14" title="2015年11月14日《健康生活》" target="_blank">2015年11月14日《健康生活》</a></p>\r\n              <p><a href="/lanmu/295.html?date=2015-11-07" title="2015年11月07日《健康生活》" target="_blank">2015年11月07日《健康生活》</a></p>\r\n              <p><a href="/lanmu/295.html?date=2015-10-31" title="2015年10月31日《健康生活》" target="_blank">2015年10月31日《健康生活》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/296.html" title="美食娱乐周刊" target="_blank"><img src="/images/lanmu/%E7%BE%8E%E9%A3%9F%E5%A8%B1%E4%B9%90%E5%91%A8%E5%88%8A.jpg" alt="美食娱乐周刊"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/296.html" title="美食娱乐周刊" target="_blank">美食娱乐周刊</a></h2>\r\n              <p><a href="/lanmu/296.html?date=2015-11-13" title="2015年11月13日《美食娱乐周刊》" target="_blank">2015年11月13日《美食娱乐周刊》</a></p>\r\n              <p><a href="/lanmu/296.html?date=2015-11-06" title="2015年11月06日《美食娱乐周刊》" target="_blank">2015年11月06日《美食娱乐周刊》</a></p>\r\n              <p><a href="/lanmu/296.html?date=2015-10-30" title="2015年10月30日《美食娱乐周刊》" target="_blank">2015年10月30日《美食娱乐周刊》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/297.html" title="房产家居" target="_blank"><img src="/images/lanmu/%E6%88%BF%E4%BA%A7%E5%AE%B6%E5%B1%85.jpg" alt="房产家居"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/297.html" title="房产家居" target="_blank">房产家居</a></h2>\r\n              <p><a href="/lanmu/297.html?date=2015-11-15" title="2015年11月15日《房产家居》" target="_blank">2015年11月15日《房产家居》</a></p>\r\n              <p><a href="/lanmu/297.html?date=2015-11-08" title="2015年11月08日《房产家居》" target="_blank">2015年11月08日《房产家居》</a></p>\r\n              <p><a href="/lanmu/297.html?date=2015-11-01" title="2015年11月01日《房产家居》" target="_blank">2015年11月01日《房产家居》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/300.html" title="车市报道" target="_blank"><img src="/images/lanmu/%E8%BD%A6%E5%B8%82%E6%8A%A5%E9%81%93.jpg" alt="车市报道"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/300.html" title="车市报道" target="_blank">车市报道</a></h2>\r\n              <p><a href="/lanmu/300.html?date=2015-11-09" title="2015年11月09日《车市报道》" target="_blank">2015年11月09日《车市报道》</a></p>\r\n              <p><a href="/lanmu/300.html?date=2015-10-26" title="2015年10月26日《车市报道》" target="_blank">2015年10月26日《车市报道》</a></p>\r\n              <p><a href="/lanmu/300.html?date=2015-10-12" title="2015年10月12日《车市报道》" target="_blank">2015年10月12日《车市报道》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/303.html" title="金色大地" target="_blank"><img src="/images/lanmu/%E9%87%91%E8%89%B2%E5%A4%A7%E5%9C%B0.jpg" alt="金色大地"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/303.html" title="金色大地" target="_blank">金色大地</a></h2>\r\n              <p><a href="/lanmu/303.html?date=2015-11-19" title="2015年11月18日《金色大地》" target="_blank">2015年11月18日《金色大地》</a></p>\r\n              <p><a href="/lanmu/303.html?date=2015-11-11" title="2015年11月11日《金色大地》" target="_blank">2015年11月11日《金色大地》</a></p>\r\n              <p><a href="/lanmu/303.html?date=2015-11-04" title="2015年11月04日《金色大地》" target="_blank">2015年11月04日《金色大地》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/304.html" title="萧然警界" target="_blank"><img src="/images/lanmu/%E8%90%A7%E7%84%B6%E8%AD%A6%E7%95%8C.jpg" alt="萧然警界"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/304.html" title="萧然警界" target="_blank">萧然警界</a></h2>\r\n              <p><a href="/lanmu/304.html?date=2015-11-13" title="2015年11月13日《萧然警界》" target="_blank">2015年11月13日《萧然警界》</a></p>\r\n              <p><a href="/lanmu/304.html?date=2015-11-06" title="2015年11月06日《萧然警界》" target="_blank">2015年11月06日《萧然警界》</a></p>\r\n              <p><a href="/lanmu/304.html?date=2015-10-30" title="2015年10月30日《萧然警界》" target="_blank">2015年10月30日《萧然警界》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/305.html" title="交通你我他" target="_blank"><img src="/images/lanmu/%E4%BA%A4%E9%80%9A%E4%BD%A0%E6%88%91%E4%BB%96.jpg" alt="交通你我他"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/305.html" title="交通你我他" target="_blank">交通你我他</a></h2>\r\n              <p><a href="/lanmu/305.html?date=2015-03-12" title="2015年03月12日《交通你我他》" target="_blank">2015年03月12日《交通你我他》</a></p>\r\n              <p><a href="/lanmu/305.html?date=2015-01-29" title="2015年01月29日《交通你我他》" target="_blank">2015年01月29日《交通你我他》</a></p>\r\n              <p><a href="/lanmu/305.html?date=2015-01-15" title="2015年01月15日《交通你我他》" target="_blank">2015年01月15日《交通你我他》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/306.html" title="萧山烟草" target="_blank"><img src="/images/lanmu/%E8%90%A7%E5%B1%B1%E7%83%9F%E8%8D%89.jpg" alt="萧山烟草"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/306.html" title="萧山烟草" target="_blank">萧山烟草</a></h2>\r\n              <p><a href="/lanmu/306.html?date=2014-12-09" title="2014年12月09日《萧山烟草》" target="_blank">2014年12月09日《萧山烟草》</a></p>\r\n              <p><a href="/lanmu/306.html?date=2014-11-11" title="2014年11月11日《萧山烟草》" target="_blank">2014年11月11日《萧山烟草》</a></p>\r\n              <p><a href="/lanmu/306.html?date=2014-10-14" title="2014年10月14日《萧山烟草》" target="_blank">2014年10月14日《萧山烟草》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/360.html" title="人力资源和社会保障" target="_blank"><img src="/images/lanmu/%E4%BA%BA%E5%8A%9B%E8%B5%84%E6%BA%90%E5%92%8C%E7%A4%BE%E4%BC%9A%E4%BF%9D%E9%9A%9C.jpg" alt="人力资源和社会保障"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/360.html" title="人力资源和社会保障" target="_blank">人力资源和社会保障</a></h2>\r\n              <p><a href="/lanmu/360.html?date=2015-11-09" title="2015年11月09日《人力资源与社会保障》" target="_blank">2015年11月09日《人力资源与社会保障》</a></p>\r\n              <p><a href="/lanmu/360.html?date=2015-11-02" title="2015年11月02日《人力资源和社会保障》" target="_blank">2015年11月02日《人力资源和社会保障》</a></p>\r\n              <p><a href="/lanmu/360.html?date=2015-10-26" title="2015年10月26日《人力资源和社会保障》" target="_blank">2015年10月26日《人力资源和社会保障》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/361.html" title="萧山环保" target="_blank"><img src="/images/lanmu/xshb.jpg" alt="萧山环保"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/361.html" title="萧山环保" target="_blank">萧山环保</a></h2>\r\n              <p><a href="/lanmu/361.html?date=2015-11-16" title="2015年11月16日《萧山环保》" target="_blank">2015年11月16日《萧山环保》</a></p>\r\n              <p><a href="/lanmu/361.html?date=2015-10-19" title="2015年10月19日《萧山环保》" target="_blank">2015年10月19日《萧山环保》</a></p>\r\n              <p><a href="/lanmu/361.html?date=2015-08-24" title="2015年08月24日《萧山环保》" target="_blank">2015年08月24日《萧山环保》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/428.html" title="生活实验室" target="_blank"><img src="http://image.xianghunet.com/201409/03/365c29cb35ae51667acf5e7f5fe36b1d.jpg" alt="生活实验室"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/428.html" title="生活实验室" target="_blank">生活实验室</a></h2>\r\n              <p><a href="/lanmu/428.html?date=2015-11-17" title="2015年11月17日《生活实验室》" target="_blank">2015年11月17日《生活实验室》</a></p>\r\n              <p><a href="/lanmu/428.html?date=2015-11-10" title="2015年11月10日《生活实验室》" target="_blank">2015年11月10日《生活实验室》</a></p>\r\n              <p><a href="/lanmu/428.html?date=2015-11-03" title="2015年11月03日《生活实验室》" target="_blank">2015年11月03日《生活实验室》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/435.html" title="市场监管之窗" target="_blank"><img src="http://image.xianghunet.com/201407/15/82c57b120a33e3d5b51a7433329c36bd.jpg" alt="市场监管之窗"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/435.html" title="市场监管之窗" target="_blank">市场监管之窗</a></h2>\r\n              <p><a href="/lanmu/435.html?date=2015-11-16" title="2015年11月16日《市场监管之窗》" target="_blank">2015年11月16日《市场监管之窗》</a></p>\r\n              <p><a href="/lanmu/435.html?date=2015-11-02" title="2015年11月02日《市场监管之窗》" target="_blank">2015年11月02日《市场监管之窗》</a></p>\r\n              <p><a href="/lanmu/435.html?date=2015-10-19" title="2015年10月19日《市场监管之窗》" target="_blank">2015年10月19日《市场监管之窗》</a></p>\r\n            </div>\r\n          </li>\r\n          <li>\r\n            <div class="pic"><a href="/lanmu/447.html" title="品质萧商" target="_blank"><img src="http://image.xianghunet.com/201510/10/1267786181d4fad40a2e573622f79e3e.jpg" alt="品质萧商"></a></div>\r\n            <div class="info">\r\n              <h2><a href="/lanmu/447.html" title="品质萧商" target="_blank">品质萧商</a></h2>\r\n              <p><a href="/lanmu/447.html?date=2015-11-12" title="2015年11月12日《品质萧商》" target="_blank">2015年11月12日《品质萧商》</a></p>\r\n              <p><a href="/lanmu/447.html?date=2015-11-05" title="2015年11月05日《品质萧商》" target="_blank">2015年11月05日《品质萧商》</a></p>\r\n              <p><a href="/lanmu/447.html?date=2015-10-29" title="2015年10月29日《品质萧商》" target="_blank">2015年10月29日《品质萧商》</a></p>\r\n            </div>\r\n          </li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <script xmlns="" type="text/javascript">          $(''#xstv_list'').bxCarousel({      display_num:4,      move:4,     auto:false,       margin:10   });   $("#xstv").show();        </script>\r\n  <div class="part_one clearfix">\r\n    <div xmlns="" class="pic_show fl">\r\n      <div id="myFocus">\r\n        <ul class="pic">\r\n          <li>\r\n          <a href="/news/view/267584.html" title="新疆打掉一境外指挥暴恐团伙 全歼28名暴徒" target="_blank"><img src="http://image.xianghunet.com/201511/20/c72e17c1d4e0272b7911bfd682edf686.jpg" alt="新疆打掉一境外指挥暴恐团伙 全歼28名暴徒"></a>\r\n                    \r\n                    <span class="i_player"></span>\r\n                    </li>\r\n          <li>\r\n          <a href="/news/view/267584.html" title="新疆打掉一境外指挥暴恐团伙 全歼28名暴徒" target="_blank"><img src="http://image.xianghunet.com/201511/20/c72e17c1d4e0272b7911bfd682edf686.jpg" alt="新疆打掉一境外指挥暴恐团伙 全歼28名暴徒"></a>\r\n                    \r\n                    <span class="i_player"></span>\r\n                    </li>\r\n          <li>\r\n          <a href="/news/view/267584.html" title="新疆打掉一境外指挥暴恐团伙 全歼28名暴徒" target="_blank"><img src="http://image.xianghunet.com/201511/20/c72e17c1d4e0272b7911bfd682edf686.jpg" alt="新疆打掉一境外指挥暴恐团伙 全歼28名暴徒"></a>\r\n                    \r\n                    <span class="i_player"></span>\r\n                    </li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n    \r\n    <div class="live_box fr">\r\n      <div xmlns="" class="live">\r\n        <h3>正在直播中</h3>\r\n        <ul>\r\n          <li class=""><span class="tv"><a href="/zhibo" target="_blank">萧山电视台综合频道</a></span></li>\r\n          <li class="on"><span class="radio"><a href="/guangbo" target="_blank">调频广播FM107.9</a></span></li>\r\n          <li class=""><span class="radio"><a href="/guangbo/?type=yx" target="_blank">萧山有线广播</a></span></li>\r\n        </ul>\r\n      </div>\r\n      <div class="new_ad_list">\r\n        <ul>\r\n          <li><script type="text/javascript">BAIDU_CLB_fillSlot("837712");</script></li>\r\n          <li><script type="text/javascript">BAIDU_CLB_fillSlot("837754");</script></li>\r\n          <li><script type="text/javascript">BAIDU_CLB_fillSlot("837784");</script></li>\r\n          <li><script type="text/javascript">BAIDU_CLB_fillSlot("837806");</script></li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div xmlns="" class="today_focus clearfix">\r\n    <div class="box1 fl"><a href="/news/list/276.html" title="今日关注" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_today.jpg" alt="今日关注"></a></div>\r\n    <div id="scroll_banner" class="box2">\r\n      <ul>\r\n        <li>\r\n          <h1><a href="/news/view/267360.html" title="90后媳妇写了封大逆不道的信，婆婆看完却服了.……" target="_blank">90后媳妇写了封大逆不道的信，婆婆看完却服了.……</a></h1>\r\n          <p><a href="/news/view/267287.html" title="继天猫魔盒后，有81款APP因违反规定将被屏蔽" target="_blank">继天猫魔盒后，有81款APP因违反规定将被屏蔽</a> | <a href="/news/view/267359.html" title="党山一男子挂盐水后死亡，家属索赔200万！" target="_blank">党山一男子挂盐水后死亡，家属索赔200万！</a></p>\r\n        </li>\r\n        <li>\r\n          <h1><a href="/news/view/267430.html" title="丈夫晚上没在家，她叫了三个男人进门……" target="_blank">丈夫晚上没在家，她叫了三个男人进门……</a></h1>\r\n          <p><a href="/news/view/267288.html" title="紧急通知！明年起，大部分医院门诊不许挂盐水！" target="_blank">紧急通知！明年起，大部分医院门诊不许挂盐水！</a> | <a href="/news/view/267432.html" title="丈母娘说了10句话，当婆婆的听完一晚上没睡着……" target="_blank">丈母娘说了10句话，当婆婆的听完一晚上没睡着……</a></p>\r\n        </li>\r\n      </ul>\r\n    </div>\r\n  </div>\r\n  <div class="part_main clearfix">\r\n    <div class="left_part fl">\r\n      <div class="news clearfix">\r\n        <div xmlns="" class="side_one fl">\r\n          <div class="s2" id="videoplayer"></div>\r\n          <div class="s3">\r\n            <ul id="video_recommend">\r\n              <li class="">\r\n                <input type="hidden" value="http://video.xianghunet.com/video/201511/18/564c6836-789b-d1a3-4242-82407c0f/transcode_4ce1a0a1-e362-8846-f232-b9db1be7.mp4" name="videourl">\r\n                <input type="hidden" value="http://video.xianghunet.com/video/201511/18/564c6836-789b-d1a3-4242-82407c0f/transcode_4db6dfca-ec4d-179a-2eb2-17325157.mp4" name="videourl1">\r\n                <input type="hidden" value="1676953" name="videoid">\r\n                <input type="hidden" value="萧山以开放姿态迎接G20" name="videotitle">\r\n                <input type="hidden" value="http://image.xianghunet.com/201511/18/859a3a103df78136885a366d7266f004.jpg" name="videoimage">\r\n                <span><a href="javascript:void(0);" title="萧山以开放姿态迎接G20">【社会】</a></span><a href="javascript:void(0);" title="萧山以开放姿态迎接G20">萧山以开放姿态迎接G20</a></li>\r\n              <li class="">\r\n                <input type="hidden" value="http://video.xianghunet.com/video/201511/18/564c6828-7897-d952-4447-b6e412e9/transcode_c347bd1c-ce22-38d8-b1b0-6b744340.mp4" name="videourl">\r\n                <input type="hidden" value="http://video.xianghunet.com/video/201511/18/564c6828-7897-d952-4447-b6e412e9/transcode_ff1ca98b-c9cc-1b66-2b31-49f82f81.mp4" name="videourl1">\r\n                <input type="hidden" value="1676957" name="videoid">\r\n                <input type="hidden" value="区委理论学习中心组召开专题学习会" name="videotitle">\r\n                <input type="hidden" value="http://image.xianghunet.com/201511/18/89de1330ee8817282860d026979f2480.jpg" name="videoimage">\r\n                <span><a href="javascript:void(0);" title="区委理论学习中心组召开专题学习会">【政务】</a></span><a href="javascript:void(0);" title="区委理论学习中心组召开专题学习会">区委理论学习中心组召开专题学习会</a></li>\r\n              <li class="">\r\n                <input type="hidden" value="http://video.xianghunet.com/video/201511/18/564c6831-7899-3a23-407c-9fa57129/transcode_5f5e387d-ac4f-638b-8b7f-27be2fd9.mp4" name="videourl">\r\n                <input type="hidden" value="http://video.xianghunet.com/video/201511/18/564c6831-7899-3a23-407c-9fa57129/transcode_81a08a7f-e4c3-b859-b4e6-1cde746b.mp4" name="videourl1">\r\n                <input type="hidden" value="1676955" name="videoid">\r\n                <input type="hidden" value="中石油与萧山民企开展战略合作" name="videotitle">\r\n                <input type="hidden" value="http://image.xianghunet.com/201511/18/a9f24966c0da9f965d687e0c945f2894.jpg" name="videoimage">\r\n                <span><a href="javascript:void(0);" title="中石油与萧山民企开展战略合作">【产业】</a></span><a href="javascript:void(0);" title="中石油与萧山民企开展战略合作">中石油与萧山民企开展战略合作</a></li>\r\n              <li class="">\r\n                <input type="hidden" value="http://video.xianghunet.com/video/201510/29/8d0b9f91-af7e-413d-ff29-ec61e277fb13/transcode_cd58b9d1-35ab-c58d-f6b2-b7549e59.mp4" name="videourl">\r\n                <input type="hidden" value="http://video.xianghunet.com/video/201510/29/8d0b9f91-af7e-413d-ff29-ec61e277fb13/transcode_6ba3af07-db24-a04e-9a5b-fe714211.mp4" name="videourl1">\r\n                <input type="hidden" value="1676409" name="videoid">\r\n                <input type="hidden" value="看看邻居家  担心自己家" name="videotitle">\r\n                <input type="hidden" value="http://image.xianghunet.com/201510/29/543c0cc94c341de342fb4934b21ccb36.jpg" name="videoimage">\r\n                <a href="javascript:void(0);" title="看看邻居家  担心自己家">看看邻居家  担心自己家</a></li>\r\n              <li class="">\r\n                <input type="hidden" value="http://video.xianghunet.com/video/201508/27/49c77cee-6092-40ce-f5f8-4a131c2dc051/transcode_00136d2d-af8a-292b-21e1-a5e0440c.mp4" name="videourl">\r\n                <input type="hidden" value="http://video.xianghunet.com/video/201508/27/49c77cee-6092-40ce-f5f8-4a131c2dc051/transcode_6d3e12aa-8f8c-86a2-c3ae-112b1106.mp4" name="videourl1">\r\n                <input type="hidden" value="1674683" name="videoid">\r\n                <input type="hidden" value="母亲想搬出小儿子家的缘由" name="videotitle">\r\n                <input type="hidden" value="http://image.xianghunet.com/201508/27/f8281ebbdd21eb2a83e4cdce21d1672e.jpg" name="videoimage">\r\n                <a href="javascript:void(0);" title="母亲想搬出小儿子家的缘由">母亲想搬出小儿子家的缘由</a></li>\r\n              <li class="">\r\n                <input type="hidden" value="http://video.xianghunet.com/video/201510/29/3410f2b5-9bb5-45c4-d49b-64ed009a357a/transcode_cb13d794-d589-1f50-d698-b2fed723.mp4" name="videourl">\r\n                <input type="hidden" value="http://video.xianghunet.com/video/201510/29/3410f2b5-9bb5-45c4-d49b-64ed009a357a/transcode_9b859e05-2823-be2b-4e25-7765923a.mp4" name="videourl1">\r\n                <input type="hidden" value="1676404" name="videoid">\r\n                <input type="hidden" value="车子经常挡门口  理发店无法营业" name="videotitle">\r\n                <input type="hidden" value="http://image.xianghunet.com/201510/29/d8f6de124aaece65c6f371226751bd3e.jpg" name="videoimage">\r\n                <a href="javascript:void(0);" title="车子经常挡门口  理发店无法营业">车子经常挡门口  理发店无法营业</a></li>\r\n            </ul>\r\n          </div>\r\n          <div class="s4">\r\n            <h3><em class="more_1"><a href="/huodong" title="">更多&gt;&gt;</a></em><span><a href="/huodong" title="">专题</a></span></h3>\r\n            <ul>\r\n              <li>\r\n                <div class="pic fl"><a href="http://www.xianghunet.com/2015/gsmp" title="2015年萧山区“公诉民评”面对面问政" target="_blank"><img src="http://image.xianghunet.com/201509/30/396ec79d78b2bac00d135695c4e18c2f.png" alt="2015年萧山区“公诉民评”面对面问政"></a></div>\r\n                <dl class="txt">\r\n                  <dt><a href="http://www.xianghunet.com/2015/gsmp" title="2015年萧山区“公诉民评”面对面问政">2015年萧山区“公诉民评”面对面问政</a></dt>\r\n                  <dd>“公述民评”活动是由区作风建设领导小组主办，区纪委（监察…<span class="more_intro"><a href="http://www.xianghunet.com/2015/gsmp" title="2015年萧山区“公诉民评”面对面问政" target="_blank">[详细]</a></span></dd>\r\n                </dl>\r\n              </li>\r\n              <li>\r\n                <div class="pic fl"><a href="http://bbs.xianghunet.com/portal.php?mod=topic&amp;topicid=2" title="萌娃靓女摄影征集 萌娃篇" target="_blank"><img src="http://image.xianghunet.com/201509/15/d40d619f7c8caf95d8e8c2819e47d830.jpg" alt="萌娃靓女摄影征集 萌娃篇"></a></div>\r\n                <dl class="txt">\r\n                  <dt><a href="http://bbs.xianghunet.com/portal.php?mod=topic&amp;topicid=2" title="萌娃靓女摄影征集 萌娃篇">萌娃靓女摄影征集 萌娃篇</a></dt>\r\n                  <dd>萌娃靓女第二季的活动开始啦。请上湘湖网上传大家的萌娃靓女…<span class="more_intro"><a href="http://bbs.xianghunet.com/portal.php?mod=topic&amp;topicid=2" title="萌娃靓女摄影征集 萌娃篇" target="_blank">[详细]</a></span></dd>\r\n                </dl>\r\n              </li>\r\n            </ul>\r\n          </div>\r\n        </div>\r\n\r\n{literal}\r\n        <script xmlns="" type="text/javascript" src="http://player.cztv.com/swfobject.js"></script><script xmlns="" type="text/javascript">         $(function(){             video_play($(''#video_recommend li:first''),1);     $(''#video_recommend li'').click(function(){        video_play($(this),0);      });   });   function video_play(elem,type){     var str = elem.find(''input[name=videourl]'').val();var str1 = elem.find(''input[name=videourl1]'').val();              var title = elem.find(''input[name=videotitle]'').val();              var id = elem.find(''input[name=videoid]'').val();              var image = parseInt(type)==0?0:elem.find(''input[name=videoimage]'').val();      if((lib.ipad||lib.iphone4)&&lib.UC){                              $(''#videoplayer'').html(''<video controls width="100%" height="100%"controls="controls" autoplay="autoplay"><source src="''+str+''" type="video/mp4" autoplay="autoplay" /></video>'');                                  }else if(lib.ipad||lib.iphone4){                          $(''#videoplayer'').html(''<object id="MP" classid="clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6"  width="100%" height="100%"><param name="autoStart" value="true" /><param name="URL" value="''+str+''" /><embed autostart="true" src="''+str+''" type="video/x-ms-wmv" width="100%" height="100%" controls="ImageWindow" console="cons"></embed></object>'');                                                           }else if(lib.android){                          $(''#videoplayer'').html(''<a href="''+str+''"><img src="''+image+''" alt="湘湖网视频"/></a>'');             }else{        $(''#videoplayer'').html(''<div id="flashContent"></div>'');        var swfVersionStr = "11.1.0";              var xiSwfUrlStr = "http://player.cztv.com/playerProductInstall.swf";              var flashvars = {path1:str1,path2:str,auto:(type==1?image:type)};              var params = {};              params.quality = "high";              params.bgcolor = "#000000";              params.allowscriptaccess = "always";              params.allowfullscreen = "true";              var attributes = {};              attributes.id = "VzPlayerLiveXH";              attributes.name = "VzPlayerLiveXH";              attributes.align = "middle";              swfobject.embedSWF(                  "http://www.xianghunet.com/player/VzPlayerXH.swf", "flashContent",                   "100%", "100%",                   swfVersionStr, xiSwfUrlStr,                   flashvars, params, attributes);              swfobject.createCSS("#flashContent", "display:block;text-align:left;");       }elem.css(''font-weight'',''bold'').siblings().css(''font-weight'',''normal'');     }           </script>\r\n\r\n{/literal}\r\n        <div xmlns="" class="side_two fr">\r\n          <div class="news_show" style="margin-bottom:15px;">\r\n            <h3><em>爆料热线：18857123188<img src="images/btn_bl.jpg" alt="我要爆料" style="cursor:pointer" onclick="window.location.href=''http://www.xianghunet.com/index.php?module=xianghunet&amp;namespace=default&amp;controller=baoliao&amp;action=index''"></em><span class="on"><a href="/news/list/283.html" title="看萧山" target="_blank">看萧山</a></span></h3>\r\n            <div class="list">\r\n              <ul>\r\n                <li class="picimg"><a href="/news/view/267360.html" style="" title="90后媳妇写了封大逆不道的信，婆婆看完却服了.……" target="_blank">90后媳妇写了封大逆不道的信，婆婆看完却服了.……</a></li>\r\n                <li class="picimg"><a href="/news/view/267287.html" style="font-weight:bold" title="继天猫魔盒后，有81款APP因违反规定将被屏蔽" target="_blank">继天猫魔盒后，有81款APP因违反规定将被屏蔽</a></li>\r\n                <li class="picimg"><a href="/news/view/267135.html" style="font-weight:bold" title="原萧山区委常委楼增明被依法逮捕" target="_blank">原萧山区委常委楼增明被依法逮捕</a></li>\r\n                <li class="picimg"><a href="/news/view/267447.html" style="" title="你感冒后这样吃药吗？调查说大部分人都吃错！" target="_blank">你感冒后这样吃药吗？调查说大部分人都吃错！</a></li>\r\n                <li class="picimg"><a href="/news/view/267446.html" style="" title="国务院昨天推出这些新政与你有关" target="_blank">国务院昨天推出这些新政与你有关</a></li>\r\n                <li class="picimg"><a href="/news/view/267432.html" style="" title="丈母娘对她亲家说了10句话，当婆婆的听完一晚上没睡着……" target="_blank">丈母娘对她亲家说了10句话，当婆婆的听完一晚上没睡着……</a></li>\r\n                <li class="picimg"><a href="/news/view/267430.html" style="" title="丈夫晚上没在家，她叫了三个男人进门……" target="_blank">丈夫晚上没在家，她叫了三个男人进门……</a></li>\r\n                <li class="picimg"><a href="/news/view/267419.html" style="" title="萧山有个“长发魔女” 近20年没剪过头发" target="_blank">萧山有个“长发魔女” 近20年没剪过头发</a></li>\r\n                <li class="picimg"><a href="/news/view/267403.html" style="" title="萧山神秘土豪花120万买大闸蟹到钱塘江放生" target="_blank">萧山神秘土豪花120万买大闸蟹到钱塘江放生</a></li>\r\n              </ul>\r\n              <ul>\r\n                <li class="picimg"><a href="/news/view/267400.html" style="" title="萧山试点村务工作者聘用制——村干部不好当了" target="_blank">萧山试点村务工作者聘用制——村干部不好当了</a></li>\r\n                <li class="picimg"><a href="/news/view/267388.html" style="" title="萧山医院开设产科专家分娩评估门诊" target="_blank">萧山医院开设产科专家分娩评估门诊</a></li>\r\n                <li class="picimg"><a href="/news/view/267374.html" style="" title="市心路人民路交叉口 汽车撞破消防栓" target="_blank">市心路人民路交叉口 汽车撞破消防栓</a></li>\r\n                <li class="picimg"><a href="/news/view/267366.html" style="" title="楼塔成立首家镇街市场化篮球经纪公司" target="_blank">楼塔成立首家镇街市场化篮球经纪公司</a></li>\r\n                <li class="picimg"><a href="/news/view/267359.html" style="" title="党山一男子挂盐水后死亡，家属索赔200万！" target="_blank">党山一男子挂盐水后死亡，家属索赔200万！</a></li>\r\n                <li class="picimg"><a href="/news/view/267303.html" style="" title="​萧山检验检疫局 关于部分进口雷克萨斯汽车的风险警示通告" target="_blank">​萧山检验检疫局 关于部分进口雷克萨斯汽车的风险警示通告</a></li>\r\n              </ul>\r\n            </div>\r\n          </div>\r\n          <div class="news_show">\r\n            <h3><span id="two1" class="on"><a onmouseover="setTab(''two'',1,3)" href="/news/list/301.html" title="">看周边</a></span><span id="two2"><a onmouseover="setTab(''two'',2,3)" href="/news/list/302.html" title="">看中国</a></span><span id="two3"><a onmouseover="setTab(''two'',3,3)" href="/news/list/303.html" title="">看世界</a></span></h3>\r\n            <div id="dsTitle_two_1" class="list">\r\n              <ul>\r\n                <li class="picimg"><a href="/news/view/267416.html" style="" title="市民在路上发现一个蛇皮袋 里面竟是……" target="_blank">市民在路上发现一个蛇皮袋 里面竟是……</a></li>\r\n                <li class="picimg"><a href="/news/view/267407.html" style="" title="“南方也要供暖” 成新热点 你怎么看？" target="_blank">“南方也要供暖” 成新热点 你怎么看？</a></li>\r\n                <li class="picimg"><a href="/news/view/267405.html" style="" title="年轻爸爸发帖投诉交警 引来上千网友批评" target="_blank">年轻爸爸发帖投诉交警 引来上千网友批评</a></li>\r\n                <li class="picimg"><a href="/news/view/267399.html" style="" title="滴滴打车1.5公里 车费竟比杭州飞成都的机票还贵" target="_blank">滴滴打车1.5公里 车费竟比杭州飞成都的机票还贵</a></li>\r\n                <li class=""><a href="/news/view/256764.html" title="萧山区文明办2015年公开选调工作人员公告" style="" target="_blank">萧山区文明办2015年公开选调工作人员公告</a></li>\r\n              </ul>\r\n            </div>\r\n            <div id="dsTitle_two_2" class="list dn">\r\n              <ul>\r\n                <li class="picimg"><a href="/news/view/267427.html" style="" title="中方确认一中国公民被IS绑架并杀害：强烈谴责暴行" target="_blank">中方确认一中国公民被IS绑架并杀害：强烈谴责暴行</a></li>\r\n                <li class="picimg"><a href="/news/view/267415.html" style="" title="什么？梅长苏没死？" target="_blank">什么？梅长苏没死？</a></li>\r\n                <li class="picimg"><a href="/news/view/267397.html" style="" title="男子4年骗父母200万元 因涉嫌诈骗罪被批捕" target="_blank">男子4年骗父母200万元 因涉嫌诈骗罪被批捕</a></li>\r\n                <li class="picimg"><a href="/news/view/267315.html" style="" title="违法驾驶：豪车、人兽、制服、捆绑，好凌乱~" target="_blank">违法驾驶：豪车、人兽、制服、捆绑，好凌乱~</a></li>\r\n                <li class=""><a href="/news/view/256764.html" title="萧山区文明办2015年公开选调工作人员公告" style="" target="_blank">萧山区文明办2015年公开选调工作人员公告</a></li>\r\n              </ul>\r\n            </div>\r\n            <div id="dsTitle_two_3" class="list dn">\r\n              <ul>\r\n                <li class="camera"><a href="/news/view/267441.html" style="" title="外国小哥裸身跳进仙人掌丛" target="_blank">外国小哥裸身跳进仙人掌丛</a></li>\r\n                <li class="picimg"><a href="/news/view/267439.html" style="" title=''巴黎恐袭"人弹"脸书照片曝光 炫耀武器金钱'' target="_blank">巴黎恐袭"人弹"脸书照片曝光 炫耀武器金钱</a></li>\r\n                <li class="picimg"><a href="/news/view/267438.html" style="" title="全球最大飞行器首次离地飞行,就是这模样有点怪" target="_blank">全球最大飞行器首次离地飞行,就是这模样有点怪</a></li>\r\n                <li class="picimg"><a href="/news/view/267437.html" style="" title="女子打911订鸡翅被拘 用这些奇葩理由报警小心被抓" target="_blank">女子打911订鸡翅被拘 用这些奇葩理由报警小心被抓</a></li>\r\n                <li class=""><a href="/news/view/256403.html" title="杭州市萧山广播电视台2014年部门决算" style="" target="_blank">杭州市萧山广播电视台2014年部门决算</a></li>\r\n              </ul>\r\n            </div>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <div xmlns="" class="channel_list">\r\n        <div class="flag"></div>\r\n        <h3><span id="three1" class="on"><a onmouseover="setTab(''three'',1,2)" href="javascript:void(0);" title="">镇街频道</a></span><span id="three2"><a onmouseover="setTab(''three'',2,2)" href="javascript:void(0);" title="">部门频道</a></span></h3>\r\n        <div id="dsTitle_three_1">\r\n          <div class="box1">\r\n            <ul class="clearfix">\r\n              <li><a href="/zhenjie/294.html" title="瓜沥">瓜沥</a></li>\r\n              <li><a href="/zhenjie/513.html" title="临浦">临浦</a></li>\r\n              <li><a href="/zhenjie/522.html" title="义蓬">义蓬</a></li>\r\n              <li><a href="/zhenjie/504.html" title="楼塔">楼塔</a></li>\r\n              <li><a href="/zhenjie/500.html" title="河上">河上</a></li>\r\n              <li><a href="/zhenjie/520.html" title="戴村">戴村</a></li>\r\n              <li><a href="/zhenjie/523.html" title="浦阳">浦阳</a></li>\r\n              <li><a href="/zhenjie/519.html" title="进化">进化</a></li>\r\n              <li><a href="/zhenjie/501.html" title="所前">所前</a></li>\r\n              <li><a href="/zhenjie/518.html" title="义桥">义桥</a></li>\r\n              <li><a href="/zhenjie/502.html" title="闻堰">闻堰</a></li>\r\n              <li><a href="/zhenjie/506.html" title="宁围">宁围</a></li>\r\n              <li><a href="/zhenjie/511.html" title="新街">新街</a></li>\r\n              <li><a href="/zhenjie/515.html" title="衙前">衙前</a></li>\r\n              <li><a href="/zhenjie/510.html" title="益农">益农</a></li>\r\n              <li><a href="/zhenjie/505.html" title="靖江">靖江</a></li>\r\n              <li><a href="/zhenjie/509.html" title="南阳">南阳</a></li>\r\n              <li><a href="/zhenjie/517.html" title="河庄">河庄</a></li>\r\n              <li><a href="/zhenjie/503.html" title="党湾">党湾</a></li>\r\n              <li><a href="/zhenjie/508.html" title="红山">红山</a></li>\r\n              <li><a href="/zhenjie/521.html" title="新湾">新湾</a></li>\r\n              <li><a href="/zhenjie/514.html" title="城厢">城厢</a></li>\r\n              <li><a href="/zhenjie/516.html" title="北干">北干</a></li>\r\n              <li><a href="/zhenjie/512.html" title="蜀山">蜀山</a></li>\r\n              <li><a href="/zhenjie/507.html" title="新塘">新塘</a></li>\r\n              <li><a href="/zhenjie/525.html" title="临江">临江</a></li>\r\n              <li><a href="/zhenjie/524.html" title="前进">前进</a></li>\r\n            </ul>\r\n          </div>\r\n          <div id="scrolldiv_one" class="box4">\r\n            <ul>\r\n              <li>\r\n                <div class=""><a href="/news/view/267099.html" title="蜀山街道：开展“三严三实”专题教育第三专题研讨" target="_blank">蜀山街道：开展“三严三实”专题教育第三专题研讨</a></div>\r\n                <div class=""><a href="/news/view/267442.html" title="文化直通车专场文艺演出走进义桥杨家村" target="_blank">文化直通车专场文艺演出走进义桥杨家村</a></div>\r\n              </li>\r\n              <li>\r\n                <div class=""><a href="/news/view/267424.html" title="浦阳评选“最美村落”、“最美庭院”" target="_blank">浦阳评选“最美村落”、“最美庭院”</a></div>\r\n                <div class=""><a href="/news/view/267418.html" title="瓜沥切实加强地质隐患排查工作" target="_blank">瓜沥切实加强地质隐患排查工作</a></div>\r\n              </li>\r\n              <li>\r\n                <div class=""><a href="/news/view/267417.html" title="瓜沥二中“沥园文学社”荣获“全国示范文学社团”称号" target="_blank">瓜沥二中“沥园文学社”荣获“全国示范文学社团”称号</a></div>\r\n                <div class=""><a href="/news/view/264176.html" title="北干街道举办“如何履行安全生产工作”专题讲座" target="_blank">北干街道举办“如何履行安全生产工作”专题讲座</a></div>\r\n              </li>\r\n              <li>\r\n                <div class=""><a href="/news/view/267410.html" title="不能忘却的纪念——社区党员参观富阳抗战胜利纪念馆" target="_blank">不能忘却的纪念——社区党员参观富阳抗战胜利纪念馆</a></div>\r\n                <div class=""><a href="/news/view/267411.html" title="蓝苑楼宇社区:便民服务走进白领身边" target="_blank">蓝苑楼宇社区:便民服务走进白领身边</a></div>\r\n              </li>\r\n              <li>\r\n                <div class=""><a href="/news/view/267392.html" title="空港经济区赴桐庐县分水镇调研学习农村基层党建工作" target="_blank">空港经济区赴桐庐县分水镇调研学习农村基层党建工作</a></div>\r\n                <div class=""><a href="/news/view/267377.html" title="所前组织收看“红色讲坛·网上公开课”" target="_blank">所前组织收看“红色讲坛·网上公开课”</a></div>\r\n              </li>\r\n              <li>\r\n                <div class=""><a href="/news/view/267321.html" title="杭州市红十字会领导走访慰问新街“失独”家庭" target="_blank">杭州市红十字会领导走访慰问新街“失独”家庭</a></div>\r\n                <div class=""><a href="/news/view/267322.html" title="新街街道“三优”指导中心接受省级验收" target="_blank">新街街道“三优”指导中心接受省级验收</a></div>\r\n              </li>\r\n            </ul>\r\n          </div>\r\n\r\n{literal}\r\n          <script type="text/javascript"> \r\n          /*滚动插件*/\r\n          (function($){\r\n            $.fn.extend({\r\n              Scroll:function(opt,callback){\r\n                  /*参数初始化*/\r\n                  if(!opt) var opt={};\r\n                  var _this=this.eq(0).find("ul:first");\r\n                  var lineH=_this.find("li:first").height(), /*获取行高*/\r\n                    line=opt.line?parseInt(opt.line,10):parseInt(this.height()/lineH,10), /*每次滚动的行数，默认为一屏，即父容器高度*/\r\n                    speed=opt.speed?parseInt(opt.speed,10):500, /*卷动速度，数值越大，速度越慢（毫秒）*/\r\n                    timer=opt.timer?parseInt(opt.timer,10):4000; /*滚动的时间间隔（毫秒）*/\r\n                  if(line==0) line=1;\r\n                  var upHeight=0-line*lineH;\r\n                  /*滚动函数*/\r\n                  scrollUp=function(){\r\n                      _this.animate({\r\n                          marginTop:upHeight\r\n                      },speed,function(){\r\n                          for(i=1;i<=line;i++){\r\n                              _this.find("li:first").appendTo(_this);\r\n                          }\r\n                          _this.css({marginTop:0});\r\n                      });\r\n                  };\r\n                  /*鼠标事件绑定*/\r\n                  _this.hover(function(){\r\n                      clearInterval(timerID);\r\n                  },function(){\r\n                      timerID=setInterval("scrollUp()",timer);\r\n                  }).mouseout();\r\n              }       \r\n            });\r\n          })(jQuery);\r\n\r\n          $(document).ready(function(){\r\n            $("#scrolldiv_one").Scroll({line:2,speed:500,timer:4000});\r\n          });\r\n          </script>\r\n\r\n{/literal}\r\n          </div>\r\n        <div id="dsTitle_three_2" class="dn">\r\n          <div class="box3">\r\n            <ul class="clearfix">\r\n              <li><a href="http://www.xianghunet.com/news/list/11913.html" title="部门综合">部门综合</a></li>\r\n              <li><a href="/bumen/397.html" title="萧山区纪委">萧山区纪委</a></li>\r\n              <li><a href="/bumen/398.html" title="区委组织部">区委组织部</a></li>\r\n              <li><a href="/bumen/286.html" title="萧山区妇联">萧山区妇联</a></li>\r\n              <li><a href="/bumen/399.html" title="环境保护局">环境保护局</a></li>\r\n              <li><a href="/bumen/400.html" title="萧山团区委">萧山团区委</a></li>\r\n              <li><a href="/bumen/402.html" title="萧山老龄委">萧山老龄委</a></li>\r\n              <li><a href="http://www.xianghunet.com/hsjt" title="红色讲坛">红色讲坛</a></li>\r\n              <li><a href="/bumen/412.html" title="城市管理局">城市管理局</a></li>\r\n              <li><a href="/bumen/413.html" title="城乡一体办">城乡一体办</a></li>\r\n              <li><a href="/bumen/414.html" title="民政局">民政局</a></li>\r\n              <li><a href="/bumen/401.html" title="萧山供电局">萧山供电局</a></li>\r\n              <li><a href="http://www.xshszh.cn/" title="萧山红十字会">萧山红十字会</a></li>\r\n            </ul>\r\n          </div>\r\n          <div id="scrolldiv_two" class="box2">\r\n            <ul>\r\n              <li>\r\n                <div class=""><a href="/news/view/267445.html" title="萧山区农民书画在第八届中国重阳书画展获奖" target="_blank">萧山区农民书画在第八届中国重阳书画展获奖</a></div>\r\n                <div class="camera"><a href="/news/view/266439.html" title="做有个性的幸福教师 衙前农小开展教师培训活动" target="_blank">做有个性的幸福教师 衙前农小开展教师培训活动</a></div>\r\n              </li>\r\n              <li>\r\n                <div class="camera"><a href="/news/view/266311.html" title="今天新人排队领证" target="_blank">今天新人排队领证</a></div>\r\n                <div class="camera"><a href="/news/view/266305.html" title="河道信息化监测提升治水实效" target="_blank">河道信息化监测提升治水实效</a></div>\r\n              </li>\r\n              <li>\r\n                <div class="camera"><a href="/news/view/265728.html" title="区社会工作协会成立仪式暨社区服务创新项目成果展举行" target="_blank">区社会工作协会成立仪式暨社区服务创新项目成果展举行</a></div>\r\n                <div class="camera"><a href="/news/view/266243.html" title="未按规定设置的户外广告牌 拆了" target="_blank">未按规定设置的户外广告牌 拆了</a></div>\r\n              </li>\r\n              <li>\r\n                <div class=""><a href="/news/view/266132.html" title="海关积极备战为“双十一”保驾护航" target="_blank">海关积极备战为“双十一”保驾护航</a></div>\r\n                <div class="camera"><a href="/news/view/265997.html" title="“翰墨抒情 水墨留痕”张吟棣中国画展开展" target="_blank">“翰墨抒情 水墨留痕”张吟棣中国画展开展</a></div>\r\n              </li>\r\n              <li>\r\n                <div class="camera"><a href="/news/view/265996.html" title="萧山百名单身青年男女在绿科秀交友联谊" target="_blank">萧山百名单身青年男女在绿科秀交友联谊</a></div>\r\n                <div class="camera"><a href="/news/view/265886.html" title="就想和你在一“企”——40名单身青年相约杭州极地海洋" target="_blank">就想和你在一“企”——40名单身青年相约杭州极地海洋</a></div>\r\n              </li>\r\n              <li>\r\n                <div class="camera"><a href="/news/view/265881.html" title="《萧山城区门牌图册》出版" target="_blank">《萧山城区门牌图册》出版</a></div>\r\n                <div class="camera"><a href="/news/view/265699.html" title="“翰墨抒情 水墨留痕”张吟棣中国画展开展" target="_blank">“翰墨抒情 水墨留痕”张吟棣中国画展开展</a></div>\r\n              </li>\r\n            </ul>\r\n          </div>\r\n\r\n{literal}\r\n          <script type="text/javascript"> \r\n          (function($){\r\n            $.fn.extend({\r\n              Scroll:function(opt,callback){\r\n                  /*参数初始化*/\r\n                  if(!opt) var opt={};\r\n                  var _btnUp = $("#"+ opt.up);/*Shawphy:向上按钮*/\r\n                  var _btnDown = $("#"+ opt.down);/*Shawphy:向下按钮*/\r\n                  var timerID;\r\n                  var _this=this.eq(0).find("ul:first");\r\n                  var lineH=_this.find("li:first").height(), /*获取行高*/\r\n                          line=opt.line?parseInt(opt.line,10):parseInt(this.height()/lineH,10), /*每次滚动的行数，默认为一屏，即父容器高度*/\r\n                      speed=opt.speed?parseInt(opt.speed,10):500; /*卷动速度，数值越大，速度越慢（毫秒）*/\r\n                      timer=opt.timer; /*parseInt(opt.timer,10):3000; //滚动的时间间隔（毫秒）*/\r\n                  if(line==0) line=1;\r\n                  var upHeight=0-line*lineH;\r\n                  /*滚动函数*/\r\n                  var scrollUp=function(){\r\n                      _btnUp.unbind("click",scrollUp); /*Shawphy:取消向上按钮的函数绑定*/\r\n                      _this.animate({\r\n                          marginTop:upHeight\r\n                      },speed,function(){\r\n                          for(i=1;i<=line;i++){\r\n                              _this.find("li:first").appendTo(_this);\r\n                          }\r\n                          _this.css({marginTop:0});\r\n                          _btnUp.bind("click",scrollUp); /*Shawphy:绑定向上按钮的点击事件*/\r\n                      });\r\n          \r\n                  };\r\n                  /*Shawphy:向下翻页函数*/\r\n                  var scrollDown=function(){\r\n                      _btnDown.unbind("click",scrollDown);\r\n                      for(i=1;i<=line;i++){\r\n                          _this.find("li:last").show().prependTo(_this);\r\n                      }\r\n                      _this.css({marginTop:upHeight});\r\n                      _this.animate({\r\n                          marginTop:0\r\n                      },speed,function(){\r\n                          _btnDown.bind("click",scrollDown);\r\n                      });\r\n                  };\r\n                   /*Shawphy:自动播放*/\r\n                  var autoPlay = function(){\r\n                      if(timer)timerID = window.setInterval(scrollUp,timer);\r\n                  };\r\n                  var autoStop = function(){\r\n                      if(timer)window.clearInterval(timerID);\r\n                  };\r\n                   /*鼠标事件绑定*/\r\n                  _this.hover(autoStop,autoPlay).mouseout();\r\n                  _btnUp.css("cursor","pointer").click( scrollUp ).hover(autoStop,autoPlay);/*Shawphy:向上向下鼠标事件绑定*/\r\n                  _btnDown.css("cursor","pointer").click( scrollDown ).hover(autoStop,autoPlay);\r\n          \r\n              }      \r\n            })\r\n          })(jQuery);\r\n          \r\n          $(document).ready(function(){\r\n            $("#scrolldiv_two").Scroll({line:2,speed:500,timer:4000});\r\n          });\r\n          </script>\r\n\r\n{/literal}\r\n          </div>\r\n      </div>\r\n      <div class="ad_box_02 clearfix"><script type="text/javascript">BAIDU_CLB_fillSlot("879756");</script></div>\r\n      <div xmlns="" class="video">\r\n        <h3><a href="/videos/list/342.html" title="" target="_blank">本网独家</a></h3>\r\n        <div class="bx_wrap">\r\n          <div class="bx_container">\r\n            <ul id="demo2" class="video_list">\r\n              <li><a href="/video/view/1676507.html" title="市国税局和市残联巾帼文明岗到萧山特康中心开展结对活动" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vMjAxNTExLzAyL2UwZDg5Yjc1MDE5NjA0ODdlZmRjYzdiNGIyMDI2YTQ0LmpwZyZheGlzPTAsMCZzaXplPTAsMCZ0c2l6ZT0xNjAsMTA4.jpg" alt="市国税局和市残联巾帼文明岗到萧山特康中心开展结对活动"><span>市国税局和市残联巾帼文明岗到萧山特康中心开展结对活动</span></a><em><i>00:01:41</i></em></li>\r\n              <li><a href="/video/view/1676940.html" title="2015年11月17日“红色讲坛”网上公开课（第十二期）" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vMjAxNTExLzE4LzlkM2EwODUyZjJiMWQyZGQzM2Q4OTA1ZTU3MmY0ZmM4LmpwZyZheGlzPTAsMCZzaXplPTAsMCZ0c2l6ZT0xNjAsMTA4.jpg" alt="2015年11月17日“红色讲坛”网上公开课（第十二期）"><span>2015年11月17日“红色讲坛”网上公开课（第十二期）</span></a><em><i>02:05:19</i></em></li>\r\n              <li><a href="/video/view/1676875.html" title="祝家桥社区举办居民乒乓球比赛" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vMjAxNTExLzE2LzYyYTM5ODcxZDZmM2M1OWNjMjVmOWEwYWZkMWUwYzUzLmpwZyZheGlzPTAsMCZzaXplPTAsMCZ0c2l6ZT0xNjAsMTA4.jpg" alt="祝家桥社区举办居民乒乓球比赛"><span>祝家桥社区举办居民乒乓球比赛</span></a><em><i>00:00:44</i></em></li>\r\n              <li><a href="/video/view/1676831.html" title="空港跨境电商园第一个“双十一” 精彩回顾" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vMjAxNTExLzE0Lzk4NWJjOWMyNmE4ZmYzNjlhM2I0MTVjMWRiM2JhNWQ1LmpwZyZheGlzPTAsMCZzaXplPTAsMCZ0c2l6ZT0xNjAsMTA4.jpg" alt="空港跨境电商园第一个“双十一” 精彩回顾"><span>空港跨境电商园第一个“双十一” 精彩回顾</span></a><em><i>00:01:50</i></em></li>\r\n              <li><a href="/video/view/1676874.html" title="瓜沥镇今日成立了基层侨联组织" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vMjAxNTExLzE2LzQ3NTQ2YWY3MDVmNzQ2MjI5ZjgzNzc2YjhlNGQ4YTIzLmpwZyZheGlzPTAsMCZzaXplPTAsMCZ0c2l6ZT0xNjAsMTA4.jpg" alt="瓜沥镇今日成立了基层侨联组织"><span>瓜沥镇今日成立了基层侨联组织</span></a><em><i>00:01:20</i></em></li>\r\n              <li><a href="/video/view/1676800.html" title="萧山实现第4例造血干细胞捐献" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vMjAxNTExLzEzLzBhNDIwMmYwYTA0YTU3MTY5NzkxZTQ0YTExOTk3MDMyLmpwZyZheGlzPTAsMCZzaXplPTAsMCZ0c2l6ZT0xNjAsMTA4.jpg" alt="萧山实现第4例造血干细胞捐献"><span>萧山实现第4例造血干细胞捐献</span></a><em><i>00:01:59</i></em></li>\r\n              <li><a href="/video/view/1676690.html" title="蜀山街道举行职工起重工、叉车工技术比武" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vMjAxNTExLzA5Lzg2NTcwZGVjNThmZjZmNmU1ODk2ZmVmZmM5NTg4NzZjLmpwZyZheGlzPTAsMCZzaXplPTAsMCZ0c2l6ZT0xNjAsMTA4.jpg" alt="蜀山街道举行职工起重工、叉车工技术比武"><span>蜀山街道举行职工起重工、叉车工技术比武</span></a><em><i>00:01:04</i></em></li>\r\n              <li><a href="/video/view/1676687.html" title="就想和你在一“企”——40名单身青年相约杭州极地海洋公园" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vMjAxNTExLzA5LzRhY2E4ZTM0YWMyOGEyMjNjZjJjMmIyOTdiYTg4Yjg5LmpwZyZheGlzPTAsMCZzaXplPTAsMCZ0c2l6ZT0xNjAsMTA4.jpg" alt="就想和你在一“企”——40名单身青年相约杭州极地海洋公园"><span>就想和你在一“企”——40名单身青年相约杭州极地海洋公园</span></a><em><i>00:02:12</i></em></li>\r\n              <li><a href="/video/view/1676631.html" title="退厂还湖 在湖光山色中体现“三美萧山”" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vMjAxNTExLzA2L2I2NDVlZmJiODA1MTQ0MjBlYmZjYTI4ZTQzMDI3MmU0LmpwZyZheGlzPTAsMCZzaXplPTAsMCZ0c2l6ZT0xNjAsMTA4.jpg" alt="退厂还湖 在湖光山色中体现“三美萧山”"><span>退厂还湖 在湖光山色中体现“三美萧山”</span></a><em><i>00:02:36</i></em></li>\r\n              <li><a href="/video/view/1676628.html" title="自编自演欢乐多 前兴村文体周活动走过十个年头" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vMjAxNTExLzA5L2EwMTMwNmRlOWU1NTQ4YmU4NWM1MTk3NzdkYTdmNWYxLmpwZyZheGlzPTAsMCZzaXplPTAsMCZ0c2l6ZT0xNjAsMTA4.jpg" alt="自编自演欢乐多 前兴村文体周活动走过十个年头"><span>自编自演欢乐多 前兴村文体周活动走过十个年头</span></a><em><i>00:01:45</i></em></li>\r\n              <li><a href="/video/view/1676547.html" title="穿越千年 萧山博物馆与您相约“寻秦之旅”" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vMjAxNTExLzAzLzU5MTcxNGFhN2Y0OTUwOWEzNDc5MzkxMTRjMjJlNmU0LmpwZyZheGlzPTAsMCZzaXplPTAsMCZ0c2l6ZT0xNjAsMTA4.jpg" alt="穿越千年 萧山博物馆与您相约“寻秦之旅”"><span>穿越千年 萧山博物馆与您相约“寻秦之旅”</span></a><em><i>00:01:15</i></em></li>\r\n              <li><a href="/video/view/1676511.html" title="“23º海鲜面杯”2015萧山健身篮球邀请赛开赛" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vMjAxNTExLzAyLzllZTQyZDMwNGUyNGFiYjkxNWI4ODU0ZGZkNzdmMjEyLmpwZyZheGlzPTAsMCZzaXplPTAsMCZ0c2l6ZT0xNjAsMTA4.jpg" alt="“23º海鲜面杯”2015萧山健身篮球邀请赛开赛"><span>“23º海鲜面杯”2015萧山健身篮球邀请赛开赛</span></a><em><i>00:01:34</i></em></li>\r\n            </ul>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <div xmlns="" class="video">\r\n        <h3><a href="/photo" title="" target="_blank">热播图片</a></h3>\r\n        <div class="bx_wrap">\r\n          <div class="bx_container">\r\n            <ul id="demo3" class="video_list">\r\n              <li><a href="/photo/view/8277.html" title="“双十一”  萧山邮政公司业务量成培增长" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vLzIwMTUxMS8xNi9mYzBiYTllYjMyOTMzZjc3OGVhZmMwNWE0ZWM3YTQwYS5qcGcmYXhpcz0wLDAmc2l6ZT0wLDAmdHNpemU9MTYwLDEwOA==.jpg" alt="“双十一”  萧山邮政公司业务量成培增长"><span>“双十一”  萧山邮政公司业务量成培增长</span></a></li>\r\n              <li><a href="/photo/view/8276.html" title="“双十一” 忙煞快递员" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vLzIwMTUxMS8xNi8yNzg2NWJjYzk2ZTA1MmIzZWM0NzRhOTUyNDFiNmY4ZC5qcGcmYXhpcz0wLDAmc2l6ZT0wLDAmdHNpemU9MTYwLDEwOA==.jpg" alt="“双十一” 忙煞快递员"><span>“双十一” 忙煞快递员</span></a></li>\r\n              <li><a href="/photo/view/8275.html" title="向日葵争奇斗艳 欲与秋色相比美" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vLzIwMTUxMS8xNi9jZmViYjZjYjkxMGIzM2ZjMDYyODk3MGIyNTIwMTRjNi5qcGcmYXhpcz0wLDAmc2l6ZT0wLDAmdHNpemU9MTYwLDEwOA==.jpg" alt="向日葵争奇斗艳 欲与秋色相比美"><span>向日葵争奇斗艳 欲与秋色相比美</span></a></li>\r\n              <li><a href="/photo/view/8274.html" title="交通治堵我助力" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vLzIwMTUxMS8xNi9kMWEwNTlhNTJlOTM1YjcyYzRiMzgyN2M2ZDNlNmNkOC5qcGcmYXhpcz0wLDAmc2l6ZT0wLDAmdHNpemU9MTYwLDEwOA==.jpg" alt="交通治堵我助力"><span>交通治堵我助力</span></a></li>\r\n              <li><a href="/photo/view/8272.html" title="消防栓  躲猫猫" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vLzIwMTUxMS8wOS80NWFhOWM4ZWQ2MjY1NTQ5NmM5ZTM3OGY1YWE5M2JmNi5qcGcmYXhpcz0wLDAmc2l6ZT0wLDAmdHNpemU9MTYwLDEwOA==.jpg" alt="消防栓  躲猫猫"><span>消防栓  躲猫猫</span></a></li>\r\n              <li><a href="/photo/view/8269.html" title="美丽萧山" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vLzIwMTUxMS8wNi9hMGJhYTRkNmEyYjljZmM5MTI5MGRmYmIzODdmYzk4Yi5qcGcmYXhpcz0wLDAmc2l6ZT0wLDAmdHNpemU9MTYwLDEwOA==.jpg" alt="美丽萧山"><span>美丽萧山</span></a></li>\r\n              <li><a href="/photo/view/8268.html" title="雨中湘湖秋色浓" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vLzIwMTUxMS8wNi9lYjVkNmJhNjRiMTQ3ZGNmYmNmOWEyNmVhZmM1OWNiZi5qcGcmYXhpcz0wLDAmc2l6ZT0wLDAmdHNpemU9MTYwLDEwOA==.jpg" alt="雨中湘湖秋色浓"><span>雨中湘湖秋色浓</span></a></li>\r\n              <li><a href="/photo/view/8266.html" title="湖畔晒满“百家被”" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vLzIwMTUxMS8wMy9lYzU1MDVjYjQxOTY1YzZjZTZjODdiZjM1NDg3Nzg2Zi5qcGcmYXhpcz0wLDAmc2l6ZT0wLDAmdHNpemU9MTYwLDEwOA==.jpg" alt="湖畔晒满“百家被”"><span>湖畔晒满“百家被”</span></a></li>\r\n              <li><a href="/photo/view/8264.html" title="天冷了 盖“被子”" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vLzIwMTUxMS8wMi84MGJmZTI5NTM3NjEyMzQ1NjkxMDNiYWRiYmU2NDc3OC5qcGcmYXhpcz0wLDAmc2l6ZT0wLDAmdHNpemU9MTYwLDEwOA==.jpg" alt="天冷了 盖“被子”"><span>天冷了 盖“被子”</span></a></li>\r\n              <li><a href="/photo/view/8263.html" title="清除干枯水草植物  确保冬季河道清澈" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vLzIwMTUxMS8wMi81MzNiZmEwOTg1MzNmNDEzMGUwNjE4MjNlYTkwNzFkOS5qcGcmYXhpcz0wLDAmc2l6ZT0wLDAmdHNpemU9MTYwLDEwOA==.jpg" alt="清除干枯水草植物  确保冬季河道清澈"><span>清除干枯水草植物  确保冬季河道清澈</span></a></li>\r\n              <li><a href="/photo/view/8261.html" title="清除城市环境，消除安全隐患，全面提升城市景观" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vLzIwMTUxMC8yNy8xZmM3ZTZjNzRjYTdkZjUzNDQyZjRhNDFlZWJmYTNlNC5qcGcmYXhpcz0wLDAmc2l6ZT0wLDAmdHNpemU9MTYwLDEwOA==.jpg" alt="清除城市环境，消除安全隐患，全面提升城市景观"><span>清除城市环境，消除安全隐患，全面提升城市景观</span></a></li>\r\n              <li><a href="/photo/view/8260.html" title="书法家为湘湖题词" target="_blank"><img src="http://image.xianghunet.com/archive/ZmlsZT1odHRwOi8vaW1hZ2UueGlhbmdodW5ldC5jb20vLzIwMTUxMC8yNy80NThjMDZiZmM0MjM4NTAyYjY3MDFhZGZiZGFjYTdlZC5qcGcmYXhpcz0wLDAmc2l6ZT0wLDAmdHNpemU9MTYwLDEwOA==.jpg" alt="书法家为湘湖题词"><span>书法家为湘湖题词</span></a></li>\r\n            </ul>\r\n          </div>\r\n        </div>\r\n      </div>\r\n      <div class="item">\r\n        <div class="pack clearfix">\r\n          <div xmlns="" class="item_list fl">\r\n            <h3><a title="" href="/ent/">萧然文娱</a></h3>\r\n            <div class="box1">\r\n              <div class="pic fl"><a href="/news/view/267368.html" title="专访丹尼尔·克雷格：007只是份工作 没有多喜欢" target="_blank"><img src="http://image.xianghunet.com/201511/19/0d6f0f6ae36ba5145b6cf9db510c8c34.jpg" alt="专访丹尼尔·克雷格：007只是份工作 没有多喜欢"></a></div>\r\n              <dl>\r\n                <dt><a href="/news/view/267368.html" title="专访丹尼尔·克雷格：007只是份工作 没有多喜欢" target="_blank">专访丹尼尔·克雷格：007只是份工作 没有多喜欢</a></dt>\r\n                <dd></dd>\r\n              </dl>\r\n            </div>\r\n            <div class="box2">\r\n              <ul>\r\n                <li><a href="/news/view/267367.html" title="恭喜！五月天怪兽晒戒指证实婚讯" target="_blank">恭喜！五月天怪兽晒戒指证实婚讯</a></li>\r\n                <li><a href="/news/view/267365.html" title="没离婚！杨幂方否认婚变：夫妻关系很好" target="_blank">没离婚！杨幂方否认婚变：夫妻关系很好</a></li>\r\n                <li><a href="/news/view/267364.html" title="躲过死劫后，欧阳震华富贵式续命：每月花费8万进补" target="_blank">躲过死劫后，欧阳震华富贵式续命：每月花费8万进补</a></li>\r\n                <li><a href="/news/view/267361.html" title="港媒曝章子怡结婚证书 疑似今年5月10日领证" target="_blank">港媒曝章子怡结婚证书 疑似今年5月10日领证</a></li>\r\n              </ul>\r\n            </div>\r\n          </div>\r\n          <div xmlns="" class="item_list fl">\r\n            <h3><a title="" href="/health/">萧然健康</a></h3>\r\n            <div class="box1">\r\n              <div class="pic fl"><a href="/news/view/267372.html" title="男女这里痛不检查就迟了" target="_blank"><img src="http://image.xianghunet.com/201511/19/8f008381d944982288a3b20d7059153f.jpg" alt="男女这里痛不检查就迟了"></a></div>\r\n              <dl>\r\n                <dt><a href="/news/view/267372.html" title="男女这里痛不检查就迟了" target="_blank">男女这里痛不检查就迟了</a></dt>\r\n                <dd></dd>\r\n              </dl>\r\n            </div>\r\n            <div class="box2">\r\n              <ul>\r\n                <li><a href="/news/view/267371.html" title="8种食物难吃却很养人" target="_blank">8种食物难吃却很养人</a></li>\r\n                <li><a href="/news/view/267370.html" title="一勺芝麻酱胜吃三块鱼" target="_blank">一勺芝麻酱胜吃三块鱼</a></li>\r\n                <li><a href="/news/view/267369.html" title="哪种食物能清除血液垃圾" target="_blank">哪种食物能清除血液垃圾</a></li>\r\n                <li><a href="/news/view/267192.html" title="紫薯营养价值高但千万别空腹吃" target="_blank">紫薯营养价值高但千万别空腹吃</a></li>\r\n              </ul>\r\n            </div>\r\n          </div>\r\n          <div xmlns="" class="item_list fl" style="margin-bottom:0;">\r\n            <h3><a title="" href="/edu/">网络教育台</a></h3>\r\n            <div class="box1">\r\n              <div class="pic fl"><a href="/news/view/267386.html" title="女生乘电梯遭同学泼冷面 两人斗殴引围观" target="_blank"><img src="http://image.xianghunet.com/201511/19/6d6d5de55bd445991dadaab7cb530e7e.jpg" alt="女生乘电梯遭同学泼冷面 两人斗殴引围观"></a></div>\r\n              <dl>\r\n                <dt><a href="/news/view/267386.html" title="女生乘电梯遭同学泼冷面 两人斗殴引围观" target="_blank">女生乘电梯遭同学泼冷面 两人斗殴引围观</a></dt>\r\n                <dd></dd>\r\n              </dl>\r\n            </div>\r\n            <div class="box2">\r\n              <ul>\r\n                <li><a href="/news/view/267385.html" title="教育部回应教师举报英语教材差错多：将更正" target="_blank">教育部回应教师举报英语教材差错多：将更正</a></li>\r\n                <li><a href="/news/view/267384.html" title="北大杀猪佬邀学弟学妹卖猪肉：比互联网靠谱" target="_blank">北大杀猪佬邀学弟学妹卖猪肉：比互联网靠谱</a></li>\r\n                <li><a href="/news/view/267383.html" title="SAT明年实施新改革 常春藤名校入学要求有变" target="_blank">SAT明年实施新改革 常春藤名校入学要求有变</a></li>\r\n                <li><a href="/news/view/267382.html" title="广东清远5年7名教育局长落马 巧取权钱色交易" target="_blank">广东清远5年7名教育局长落马 巧取权钱色交易</a></li>\r\n              </ul>\r\n            </div>\r\n          </div>\r\n          <div xmlns="" class="item_list fl" style="margin-bottom:0;">\r\n            <h3><a title="" href="/finance/">萧山理财帮</a></h3>\r\n            <div class="box1">\r\n              <div class="pic fl"><a href="/news/view/267391.html" title="2.8万大户回归股市 10月末超500万元账户数回升" target="_blank"><img src="http://image.xianghunet.com/201511/19/df19a18702e42b5d3b6e51e6862916d6.jpg" alt="2.8万大户回归股市 10月末超500万元账户数回升"></a></div>\r\n              <dl>\r\n                <dt><a href="/news/view/267391.html" title="2.8万大户回归股市 10月末超500万元账户数回升" target="_blank">2.8万大户回归股市 10月末超500万元账户数回升</a></dt>\r\n                <dd></dd>\r\n              </dl>\r\n            </div>\r\n            <div class="box2">\r\n              <ul>\r\n                <li><a href="/news/view/267393.html" title="中通快递员偷衣服穿身上当场被抓 公司赔3千元" target="_blank">中通快递员偷衣服穿身上当场被抓 公司赔3千元</a></li>\r\n                <li><a href="/news/view/267390.html" title="高层关注房地产去库存 新一轮救市措施或出台" target="_blank">高层关注房地产去库存 新一轮救市措施或出台</a></li>\r\n                <li><a href="/news/view/267389.html" title="华鑫信托陷兑付危机 1.6亿项目逾期3月未兑付" target="_blank">华鑫信托陷兑付危机 1.6亿项目逾期3月未兑付</a></li>\r\n                <li><a href="/news/view/267204.html" title="上海天喔茶庄蜂蜜柚子茶被检出咖啡因超标" target="_blank">上海天喔茶庄蜂蜜柚子茶被检出咖啡因超标</a></li>\r\n              </ul>\r\n            </div>\r\n          </div>\r\n        </div>\r\n      </div>\r\n    </div>\r\n    <div class="right_part fr">\r\n\r\n{literal}\r\n      <style xmlns="">\r\n  .leaders .pack .box2 span a{ font-size:14px; font-weight:bold;}\r\n  .leaders .pack .box2 { text-align:center}\r\n  </style>\r\n\r\n{/literal}\r\n      <div xmlns="" class="leaders">\r\n        <div class="pack">\r\n          <input type="hidden" value="&lt;em&gt;俞东来&lt;/em&gt; 报道集" name="title1">\r\n          <input type="hidden" value="&lt;em&gt;卢春强&lt;/em&gt; 报道集" name="title2">\r\n          <div class="box1" id="title1" style="text-align:center; font-size:18px"><a href="/leader01" target="_blank"></a></div>\r\n          <div class="box1" id="title2" style="text-align:center; font-size:18px"><a href="http://www.xianghunet.com/leader03" target="_blank"></a></div>\r\n          <div class="box2"><span><a href="http://www.xianghunet.com/news/list/12878.html" title="抗战故事" target="_blank">[抗战故事]</a></span><span><a href="/news/list/12881.html" title="百花齐放" target="_blank">[百花齐放]</a></span></div>\r\n        </div>\r\n      </div>\r\n\r\n{literal}\r\n      <script xmlns="" type="text/javascript">    $(function(){   \r\n    var text = $(''input[name=title1]'').val();     \r\n    \r\ntext=text.replace(/<em/g, "<em style=''font-size:22px''") ;\r\n    var temp = document.createElement("div");       \r\n    temp.innerHTML = text;    \r\n    var output = temp.innerHTML || temp.textContent;\r\n    temp = null;\r\n    $(''#title1 a'').html(output);\r\n    text = $(''input[name=title2]'').val();\r\n    \r\ntext=text.replace(/<em/g, "<em style=''font-size:22px''") ;\r\n    \r\n    temp = document.createElement("div");\r\n    temp.innerHTML = text;\r\n    output = temp.innerHTML || temp.textContent;\r\n    temp = null;\r\n    $(''#title2 a'').html(output);    })    </script>\r\n\r\n{/literal}\r\n      <div xmlns="" class="photo">\r\n        <h3><a href="http://me.xianghunet.com/" title="" target="_blank">热播视频</a></h3>\r\n        <div class="bx_wrap">\r\n          <div class="bx_container">\r\n            <ul id="demo14" class="photo_list">\r\n              <li>\r\n                <div class="box1"></div>\r\n                <div class="box2">\r\n                  <div class="pack"></div>\r\n                </div>\r\n              </li>\r\n              <li>\r\n                <div class="box1"></div>\r\n                <div class="box2">\r\n                  <div class="pack"></div>\r\n                </div>\r\n              </li>\r\n            </ul>\r\n          </div>\r\n        </div>\r\n      </div>\r\n\r\n{literal}\r\n      <script xmlns="" type="text/javascript">\r\n    $.getJSON(''http://me.xianghunet.com/xml/xhjson?221&callback=?'', {}, function(result) {\r\n      var data = result.web;\r\n      var box1html_a,box1html_b,box2html_a="",box2html_b="", temphtml="";\r\n      for(var i=0;i<data.length;i++){\r\n        temphtml =  ''<div class="ss">'';\r\n        temphtml += ''    <a href="''+data[i].href+''" title="''+data[i].title+''" target="_blank">'';\r\n        temphtml += ''        <img src="''+data[i].localpath+''" alt="''+data[i].title+''" />'';\r\n        temphtml += ''        <span>'';\r\n        temphtml += data[i].title;\r\n        temphtml += ''        </span>'';\r\n        temphtml += ''    </a>'';\r\n        temphtml += ''</div>'';\r\n        if(i==0) {\r\n          box1html_a = ''<a href="''+data[i].href+''" title="''+data[i].title+''" target="_blank">'';\r\n          box1html_a += ''       <img src="''+data[i].localpath+''" alt="''+data[i].title+''" />'';\r\n          box1html_a += ''       <span>'';\r\n          box1html_a += data[i].title;\r\n          box1html_a += ''       </span>'';\r\n          box1html_a += ''    </a>'';\r\n        }\r\n        else if(i==5) {\r\n          box1html_b = ''<a href="''+data[i].href+''" title="''+data[i].title+''" target="_blank">'';\r\n          box1html_b += ''       <img src="''+data[i].localpath+''" alt="''+data[i].title+''" />'';\r\n          box1html_b += ''       <span>'';\r\n          box1html_b += data[i].title;\r\n          box1html_b += ''       </span>'';\r\n          box1html_b += ''    </a>'';\r\n        }\r\n        else if(i>0&&i<5) {\r\n          box2html_a += temphtml;\r\n        }\r\n        else if(i>5&&i<10) {\r\n          box2html_b += temphtml;\r\n        }\r\n      }\r\n      $("#demo14 li:eq(0)").find(".box1").html(box1html_a);\r\n      $("#demo14 li:eq(1)").find(".box1").html(box1html_b);\r\n      $("#demo14 li:eq(0)").find(".box2").find(".pack").html(box2html_a);\r\n      $("#demo14 li:eq(1)").find(".box2").find(".pack").html(box2html_b);     \r\n      \r\n      \r\n  $(''#demo14'').bxCarousel({\r\n    display_num:1, \r\n    move:1,\r\n    auto:true, \r\n    margin:15,\r\n    auto_hover: true\r\n  });\r\n      \r\n       });\r\n      \r\n      \r\n        </script>\r\n\r\n{/literal}\r\n      <div xmlns="" class="hot_lm">\r\n        <h3><a href="#" title="">热播栏目</a></h3>\r\n        <ul>\r\n          <li><span class="first"><i><b><a href="javascript:void(0)" title="新闻">新闻</a></b></i></span><span style="width:55px;"><a href="/lanmu/285.html" title="萧山新闻" target="_blank">萧山新闻</a></span><span style="width:55px;"><a href="/lanmu/307.html" title="热线188" target="_blank">热线188</a></span><span style="width:55px;"><a href="/lanmu/281.html" title="社会聚焦" target="_blank">社会聚焦</a></span></li>\r\n          <li><span class="second"><i><b><a href="javascript:void(0)" title="天天看萧山">天天看萧山</a></b></i></span><span style="width:60px;"><a href="/lanmu/303.html" title="金色大地" target="_blank">金色大地</a></span><span style="width:60px;"><a href="/lanmu/306.html" title="萧山烟草" target="_blank">萧山烟草</a></span><span style="width:85px; padding-left:10px;"><a href="/lanmu/305.html" title="交通你我他" target="_blank">交通你我他</a></span><span style="width:60px;"><a href="/lanmu/304.html" title="萧然警界" target="_blank">萧然警界</a></span><span style="width:60px;"><a href="/lanmu/302.html" title="教育天地" target="_blank">教育天地</a></span><span style="width:85px; padding-left:10px;"><a href="/lanmu/360.html" title="人力资源社保" target="_blank">人力资源社保</a></span><span style="width:60px;"><a href="/lanmu/357.html" title="工商时空" target="_blank">工商时空</a></span><span style="width:60px;"><a href="/lanmu/359.html" title="萧山城管" target="_blank">萧山城管</a></span></li>\r\n          <li><span class="second"><i><b><a href="javascript:void(0)" title="专题综艺">专题综艺</a></b></i></span><span style="width:60px;"><a href="/lanmu/289.html" title="萧山法治" target="_blank">萧山法治</a></span><span style="width:70px;"><a href="/lanmu/294.html" title="智多星计划" target="_blank">智多星计划</a></span><span style="width:72px; padding-left:10px;"><a href="/lanmu/288.html" title="萧山党建" target="_blank">萧山党建</a></span><span style="width:60px;"><a href="/lanmu/291.html" title="爱心满天" target="_blank">爱心满天</a></span><span style="width:65px;"><a href="/lanmu/310.html" title="闪亮我舞台" target="_blank">闪亮我舞台</a></span></li>\r\n          <li style="padding-bottom:6px; border-bottom:0; margin-bottom:0;"><span class="second"><i><b><a title="生活服务" href="javascript:void(0)">生活服务</a></b></i></span><span style="width:70px;"><a target="_blank" title="车市报道" href="/lanmu/300.html">车市报道</a></span><span style="width:70px;"><a target="_blank" title="爱尚旅游" href="/lanmu/299.html">爱尚旅游</a></span><span style="width:67px; padding-left:10px;"><a target="_blank" title="每周财经" href="/lanmu/298.html">每周财经</a></span><span style="width:80px;"><a target="_blank" title="房产百事通" href="/lanmu/297.html">房产百事通</a></span><span style="width:70px;"><a target="_blank" title="健康生活" href="/lanmu/295.html">健康生活</a></span><span style="width:80px; padding-left:10px;"><a target="_blank" title="美食娱乐周刊" href="/lanmu/296.html">美食娱乐周刊</a></span></li>\r\n        </ul>\r\n      </div>\r\n      <div class="new_ad_05" style="height:98px; width:240px; margin-bottom:12px;"><script type="text/javascript">BAIDU_CLB_fillSlot("803698");</script></div>\r\n      <div class="new_ad_05" style="height:98px; width:240px; margin-bottom:12px;"><script type="text/javascript">BAIDU_CLB_fillSlot("803704");</script></div>\r\n      <div class="new_ad_05" style="height:98px; width:240px; margin-bottom:12px;"><script type="text/javascript">BAIDU_CLB_fillSlot("803712");</script></div>\r\n      <div class="new_ad_05" style="height:98px; width:240px; margin-bottom:12px;"><script type="text/javascript">BAIDU_CLB_fillSlot("803718");</script></div>\r\n      <div class="new_ad_05" style="height:98px; width:240px; margin-bottom:12px;"><script type="text/javascript">BAIDU_CLB_fillSlot("838111");</script></div>\r\n      <div xmlns="" class="topten">\r\n        <h3><span id="four1" class="on"><a onmouseover="setTab(''four'',1,5)" href="javascript:void(0);" title="">总榜</a></span><span id="four2"><a onmouseover="setTab(''four'',2,5)" href="javascript:void(0);" title="">电影</a></span><span id="four3"><a onmouseover="setTab(''four'',3,5)" href="javascript:void(0);" title="">电视</a></span><span id="four4"><a onmouseover="setTab(''four'',4,5)" href="javascript:void(0);" title="">综艺</a></span><span id="four5"><a onmouseover="setTab(''four'',5,5)" href="javascript:void(0);" title="">动漫</a></span></h3>\r\n        <div id="dsTitle_four_1" class="topten_list">\r\n          <ul>\r\n            <li class="first"><a href="#" title=""><img src="" alt=""></a>\r\n              <div class="title"><span class="count"></span><span class="rn_1">01</span><span class="txt"><a href="#" title=""></a></span></div>\r\n            </li>\r\n            <li><span class="count"></span><span class="rn_1">02</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_1">03</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">04</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">05</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">06</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">07</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">08</span><span class="txt"><a href="#" title=""></a></span></li>\r\n          </ul>\r\n        </div>\r\n        <div id="dsTitle_four_2" class="topten_list dn">\r\n          <ul>\r\n            <li class="first"><a href="#" title=""><img src="" alt=""></a>\r\n              <div class="title"><span class="count"></span><span class="rn_1">01</span><span class="txt"><a href="#" title="">2</a></span></div>\r\n            </li>\r\n            <li><span class="count"></span><span class="rn_1">02</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_1">03</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">04</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">05</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">06</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">07</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">08</span><span class="txt"><a href="#" title=""></a></span></li>\r\n          </ul>\r\n        </div>\r\n        <div id="dsTitle_four_3" class="topten_list dn">\r\n          <ul>\r\n            <li class="first"><a href="#" title=""><img src="" alt=""></a>\r\n              <div class="title"><span class="count"></span><span class="rn_1">01</span><span class="txt"><a href="#" title="">3</a></span></div>\r\n            </li>\r\n            <li><span class="count"></span><span class="rn_1">02</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_1">03</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">04</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">05</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">06</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">07</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">08</span><span class="txt"><a href="#" title=""></a></span></li>\r\n          </ul>\r\n        </div>\r\n        <div id="dsTitle_four_4" class="topten_list dn">\r\n          <ul>\r\n            <li class="first"><a href="#" title=""><img src="" alt=""></a>\r\n              <div class="title"><span class="count"></span><span class="rn_1">01</span><span class="txt"><a href="#" title="">4</a></span></div>\r\n            </li>\r\n            <li><span class="count"></span><span class="rn_1">02</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_1">03</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">04</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">05</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">06</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">07</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">08</span><span class="txt"><a href="#" title=""></a></span></li>\r\n          </ul>\r\n        </div>\r\n        <div id="dsTitle_four_5" class="topten_list dn">\r\n          <ul>\r\n            <li class="first"><a href="#" title=""><img src="" alt=""></a>\r\n              <div class="title"><span class="count"></span><span class="rn_1">01</span><span class="txt"><a href="#" title="">5</a></span></div>\r\n            </li>\r\n            <li><span class="count"></span><span class="rn_1">02</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_1">03</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">04</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">05</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">06</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">07</span><span class="txt"><a href="#" title=""></a></span></li>\r\n            <li><span class="count"></span><span class="rn_2">08</span><span class="txt"><a href="#" title=""></a></span></li>\r\n          </ul>\r\n        </div>\r\n      </div>\r\n\r\n{literal}\r\n      <script xmlns="" type="text/javascript">\r\n  \r\n  $.get("/index.php?module=xianghunet&namespace=default&controller=site&action=videotopten&123456",\r\n        function(result){\r\n        \r\n            var toptypeidstr = "#dsTitle_four_";\r\n            \r\n            i=0;\r\n            for(;i<result.length;i++) {\r\n                toptypeidstr = "#dsTitle_four_"+(i+1);\r\n        \r\n                result2 = result[i];\r\n                \r\n                for(j=0; j<8; j++) {\r\n                if(0==j) {\r\n            \r\n                    tmpstr = ''<a target="_blank" href="''+result2[j].href+''" title=""><img src="''+result2[j].image+''" alt=""></a>''\r\n                                  +''<div class="title"><span class="count">''+result2[j].hits+''</span><span class="rn_1">''+result2[j].index+''</span>''\r\n                                  +''<span class="txt"><a target="_blank" href="''+result2[j].href+''" title="">''+result2[j].name+''</a></span></div>'';\r\n                                    \r\n                              $(toptypeidstr).find("li:eq(0)").html(tmpstr);\r\n            \r\n                }\r\n                else {\r\n                    indexstr = "li:eq("+j+")";\r\n                    tmpstr = ''<span class="count">''+result2[j].hits+''</span>''\r\n                        +''<span class="rn_2">''+result2[j].index+''</span>''\r\n                        +''<span class="txt"><a target="_blank" href="''+result2[j].href+''" title="">''+result2[j].name+''</a></span>'';\r\n                    $(toptypeidstr).find(indexstr).html(tmpstr);\r\n          \r\n                }\r\n                }               \r\n            } \r\n        \r\n        },''json''); \r\n    \r\n  </script>\r\n\r\n{/literal}\r\n      <div class="new_ad_05" style="margin-bottom:12px;height:98px; width:240px;"><script type="text/javascript">BAIDU_CLB_fillSlot("838341");</script></div>\r\n      <div class="new_ad_05" style="height:44px; width:240px; margin-bottom:10px;"><script type="text/javascript">BAIDU_CLB_fillSlot("838327");</script></div>\r\n      <div class="new_ad_05" style="height:60px; width:240px; margin-bottom:12px;"></div>\r\n    </div>\r\n  </div>\r\n  <div xmlns="" class="bm">\r\n    <h3><a href="#" title="">便民服务</a></h3>\r\n    <ul class="clearfix">\r\n      <li>\r\n        <div class="pic"><a href="http://map.baidu.com/?newmap=1&amp;s=s%26wd%3D%E8%90%A7%E5%B1%B1%E5%9C%B0%E5%9B%BE%26c%3D2489&amp;fr=alat0&amp;from=alamap" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_01.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://map.baidu.com/?newmap=1&amp;s=s%26wd%3D%E8%90%A7%E5%B1%B1%E5%9C%B0%E5%9B%BE%26c%3D2489&amp;fr=alat0&amp;from=alamap" title="萧山地图">萧山地图</a></dt>\r\n          <dd>三维 公交 卫星地图</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://www.weather.com.cn/html/weather/101210102.shtml" title="天气预报" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_02.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://www.weather.com.cn/html/weather/101210102.shtml" title="天气预报">天气预报</a></dt>\r\n          <dd>萧山天气信息查询</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://www.xsbus.com/bus.asp" title="公交线路" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_03.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://www.xsbus.com/bus.asp" title="公交线路" target="_blank">公交线路</a></dt>\r\n          <dd>萧山公交线路查询</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://www.xsbus.com/Cycling/" title="公共自行车查询" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_04.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://www.xsbus.com/Cycling" title="公共自行车查询" target="_blank">公共自行车查询</a></dt>\r\n          <dd>公共自行车查询</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://www.12306.cn/mormhweb/" title="火车查询" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_05.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://www.12306.cn/mormhweb/" title="火车查询" target="_blank">火车查询</a></dt>\r\n          <dd>全国列车时刻查询</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://flight.qunar.com/status/alphlet_order.jsp?ex_track=bd_aladding_flightsk_title" title="民航航班" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_06.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://flight.qunar.com/status/alphlet_order.jsp?ex_track=bd_aladding_flightsk_title" title="民航航班" target="_blank">民航航班</a></dt>\r\n          <dd>机票 航班时刻查询</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://www.xsti.gov.cn/bgcar.asp" title="违章查询" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_07.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://www.xsti.gov.cn/bgcar.asp" title="违章查询" target="_blank">违章查询</a></dt>\r\n          <dd>萧山机动车违章查询</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://183.129.195.94:9001/" title="公积金" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_08.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://183.129.195.94:9001/" title="公积金" target="_blank">公积金</a></dt>\r\n          <dd>萧山住房公积金查询</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://www.ip138.com/" title="IP地址查询" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_09.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://www.ip138.com/" title="IP地址查询" target="_blank">IP地址查询</a></dt>\r\n          <dd>查看ip的归属地</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://www.hao123.com/rili" title="万年日历" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_10.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://www.hao123.com/rili" title="万年日历" target="_blank">万年日历</a></dt>\r\n          <dd>万年日历查询</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://guahao.zjol.com.cn/" title="网上挂号" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_11.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://guahao.zjol.com.cn/" title="网上挂号" target="_blank">网上挂号</a></dt>\r\n          <dd>网上挂号</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://www.ldbz.xs.zj.cn/xslss/wsbs/login.jsp" title="社会保险" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_12.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://www.ldbz.xs.zj.cn/xslss/wsbs/login.jsp" title="社会保险" target="_blank">社会保险</a></dt>\r\n          <dd>查询社保医保信息</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://www.mtime.com/" title="影院拍片查询" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_13.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://www.mtime.com/" title="影院拍片查询" target="_blank">影院拍片查询</a></dt>\r\n          <dd>影院拍片查询</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://tool.114la.com/live/phone/" title="常用号码" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_14.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://tool.114la.com/live/phone/" title="常用号码" target="_blank">常用号码</a></dt>\r\n          <dd>常用号码</dd>\r\n        </dl>\r\n      </li>\r\n      <li>\r\n        <div class="pic"><a href="http://www.xswater.com/listnews.asp?id=60" title="停水预告" target="_blank"><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_bm_15.jpg"></a></div>\r\n        <dl class="txt">\r\n          <dt><a href="http://www.xswater.com/listnews.asp?id=60" title="停水预告" target="_blank">停水预告</a></dt>\r\n          <dd>停水预告</dd>\r\n        </dl>\r\n      </li>\r\n    </ul>\r\n  </div>\r\n  <div xmlns="" class="presenter">\r\n    <h3><a href="javascript:;" title="萧山广播电视台主持人">萧山广播电视台主持人</a></h3>\r\n    <div class="bx_wrap">\r\n      <div class="bx_container">\r\n        <ul id="demo5" class="presenter_list">\r\n          <li><a href="/presenter/234919.html" target="_blank" title="周君"><img src="http://image.xianghunet.com/201505/04/c38a94a4917ad9b629d22f3400f73151.jpg" alt="周君"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/234919.html" title="周君">周君</a></span></li>\r\n          <li><a href="/presenter/234897.html" target="_blank" title="刘慧"><img src="http://image.xianghunet.com/201505/04/55b421a38b1967d179b015c003f69e1a.jpg" alt="刘慧"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/234897.html" title="刘慧">刘慧</a></span></li>\r\n          <li><a href="/presenter/234888.html" target="_blank" title="初阳"><img src="http://image.xianghunet.com/201505/04/f5e44419745ad7f5abe1ef2db06ea727.jpg" alt="初阳"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/234888.html" title="初阳">初阳</a></span></li>\r\n          <li><a href="/presenter/215488.html" target="_blank" title="陈洁"><img src="http://image.xianghunet.com/201504/09/e2e7e894841c4bb9abe103f2f6db55ce.jpg" alt="陈洁"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/215488.html" title="陈洁">陈洁</a></span></li>\r\n          <li><a href="/presenter/77883.html" target="_blank" title="沈童"><img src="http://image.xianghunet.com/201311/18/dfae0d31b91185605f88ccdec9a69058.jpg" alt="沈童"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/77883.html" title="沈童">沈童</a></span></li>\r\n          <li><a href="/presenter/43082.html" target="_blank" title="郑健"><img src="http://image.xianghunet.com/201311/09/e948b0e6c09c9dea669cd4afdd28490a.jpg" alt="郑健"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43082.html" title="郑健">郑健</a></span></li>\r\n          <li><a href="/presenter/43080.html" target="_blank" title="袁剑"><img src="http://image.xianghunet.com/201311/09/8d273c1cf6a57a46e257b28a31f9828c.jpg" alt="袁剑"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43080.html" title="袁剑">袁剑</a></span></li>\r\n          <li><a href="/presenter/43079.html" target="_blank" title="依凡"><img src="http://image.xianghunet.com/201311/09/5a453d6ecaaf7a4c32f3e643353b61b9.jpg" alt="依凡"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43079.html" title="依凡">依凡</a></span></li>\r\n          <li><a href="/presenter/43078.html" target="_blank" title="伊鹏"><img src="http://image.xianghunet.com/201311/09/b7fb1ff79044a1ff766f1814c80908f0.jpg" alt="伊鹏"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43078.html" title="伊鹏">伊鹏</a></span></li>\r\n          <li><a href="/presenter/43077.html" target="_blank" title="薛张亮"><img src="http://image.xianghunet.com/201311/09/d10b24b38b20abb25c628811aaa3a383.jpg" alt="薛张亮"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43077.html" title="薛张亮">薛张亮</a></span></li>\r\n          <li><a href="/presenter/43076.html" target="_blank" title="小米"><img src="http://image.xianghunet.com/201311/09/b78e0264d2e2892c9ad14c5e93e12048.jpg" alt="小米"></a><span><em class="sina"><a target="_blank" href="http://weibo.com/u/1768777137" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43076.html" title="小米">小米</a></span></li>\r\n          <li><a href="/presenter/43075.html" target="_blank" title="萧杭"><img src="http://image.xianghunet.com/201505/04/9dc3fe521b6718f4a86529f0f35fde01.jpg" alt="萧杭"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43075.html" title="萧杭">萧杭</a></span></li>\r\n          <li><a href="/presenter/43074.html" target="_blank" title="雯馨"><img src="http://image.xianghunet.com/201311/09/4285d965ac38ea9fcb5cbff12e45b35f.jpg" alt="雯馨"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43074.html" title="雯馨">雯馨</a></span></li>\r\n          <li><a href="/presenter/43072.html" target="_blank" title="闻达"><img src="http://image.xianghunet.com/201311/09/f98cd70d4439566423aecd216ac1eae6.jpg" alt="闻达"></a><span><em class="sina"><a target="_blank" href="http://weibo.com/dagezhang" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43072.html" title="闻达">闻达</a></span></li>\r\n          <li><a href="/presenter/43065.html" target="_blank" title="汪乐"><img src="http://image.xianghunet.com/201311/09/2a35c12d460cd57ab64ff79f91e89d8b.jpg" alt="汪乐"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43065.html" title="汪乐">汪乐</a></span></li>\r\n          <li><a href="/presenter/43061.html" target="_blank" title="乔冲"><img src="http://image.xianghunet.com/201311/09/235f214a30aa4665133b1ef31e949380.jpg" alt="乔冲"></a><span><em class="sina"><a target="_blank" href="http://weibo.com/2097255485/profile?topnav=1&amp;wvr=6" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43061.html" title="乔冲">乔冲</a></span></li>\r\n          <li><a href="/presenter/43060.html" target="_blank" title="倪虹"><img src="http://image.xianghunet.com/201312/09/30643e33d0dfe5de4b2114dac79aab63.jpg" alt="倪虹"></a><span><em class="sina"><a target="_blank" href="http://weibo.com/u/1961965537" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43060.html" title="倪虹">倪虹</a></span></li>\r\n          <li><a href="/presenter/43059.html" target="_blank" title="卢少男"><img src="http://image.xianghunet.com/201311/09/0f808178e38829929979b5af78db3044.jpg" alt="卢少男"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43059.html" title="卢少男">卢少男</a></span></li>\r\n          <li><a href="/presenter/43058.html" target="_blank" title="楼承天"><img src="http://image.xianghunet.com/201311/09/afee285c6fc371e57de7b252a42f989e.jpg" alt="楼承天"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43058.html" title="楼承天">楼承天</a></span></li>\r\n          <li><a href="/presenter/43057.html" target="_blank" title="孔婷"><img src="http://image.xianghunet.com/201311/09/44149779d7eec5a48d169c30d3cd9cb0.jpg" alt="孔婷"></a><span><em class="sina"><a target="_blank" href="http://weibo.com/u/1634476540" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43057.html" title="孔婷">孔婷</a></span></li>\r\n          <li><a href="/presenter/43056.html" target="_blank" title="贾晓婉"><img src="http://image.xianghunet.com/201311/09/b396bac1d6ad4a45836102fa824b9fa7.jpg" alt="贾晓婉"></a><span><em class="sina"><a target="_blank" href="http://weibo.com/xwanwanw" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43056.html" title="贾晓婉">贾晓婉</a></span></li>\r\n          <li><a href="/presenter/43055.html" target="_blank" title="纪晓冬"><img src="http://image.xianghunet.com/201311/09/834a781e778f8fc4d13ff9f8a94fac21.jpg" alt="纪晓冬"></a><span><em class="sina"><a target="_blank" href="http://weibo.com" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43055.html" title="纪晓冬">纪晓冬</a></span></li>\r\n          <li><a href="/presenter/43054.html" target="_blank" title="黄立"><img src="http://image.xianghunet.com/201311/09/582074e6c8bb19802c765aaf0f072606.jpg" alt="黄立"></a><span><em class="sina"><a target="_blank" href="http://weibo.com/u/3748760603" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43054.html" title="黄立">黄立</a></span></li>\r\n          <li><a href="/presenter/43053.html" target="_blank" title="格朗"><img src="http://image.xianghunet.com/201311/09/902ae701e73927daca7555db3c76bdca.jpg" alt="格朗"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43053.html" title="格朗">格朗</a></span></li>\r\n          <li><a href="/presenter/43052.html" target="_blank" title="方乔"><img src="http://image.xianghunet.com/201504/10/1c3043826d0680f3fa0b04dbc2fad333.jpg" alt="方乔"></a><span><em class="sina"><a target="_blank" href="javascript%20void(0)" title=""><img src="http://image.xianghunet.com/templates/imgcssjs/1/31/images/icon_sina.png" alt=""></a></em><a target="_blank" href="/presenter/43052.html" title="方乔">方乔</a></span></li>\r\n        </ul>\r\n      </div>\r\n    </div>\r\n  </div>\r\n  <div xmlns="" class="links">\r\n    <h3><a href="javascript:;" title="友情链接">友情链接</a></h3>\r\n    <ul>\r\n      <li><a href="http://www.hangzhou.gov.cn/" title="中国杭州" target="_blank">中国杭州</a><a href="http://www.xiaoshan.gov.cn/" title="中国萧山" target="_blank">中国萧山</a><a href="http://www.xsdj.gov.cn/" title="萧山党建" target="_blank">萧山党建</a><a href="http://www.lz.xs.zj.cn/" title="萧然清风" target="_blank">萧然清风</a><a href="http://www.xhly.xs.zj.cn/" title="湘湖旅游度假区" target="_blank">湘湖旅游度假区</a><a href="http://www.hb.xs.zj.cn/" title="萧山环保" target="_blank">萧山环保</a><a href="http://www.xsyouth.gov.cn/" title="萧然青年" target="_blank">萧然青年</a><a href="http://www.llw.xs.zj.cn/" title="萧山老龄委" target="_blank">萧山老龄委</a><a href="http://www.women.xs.zj.cn/" title="萧山妇联" target="_blank">萧山妇联</a><a href="http://www.hzxsby.cn/" title="萧山区白蚁防治研究所" target="_blank">萧山区白蚁防治研究所</a><a href="http://www.xsci.gov.cn/" title="萧山文化创意网" target="_blank">萧山文化创意网</a><a href="http://www.jt.xiaoshan.gov.cn/" title="萧山交通信息网" target="_blank">萧山交通信息网</a></li>\r\n      <li><a href="http://www.chinasarft.gov.cn/" title="国家广电总局" target="_blank">国家广电总局</a><a href="http://www.nwc.com.cn/" title="国家广电总局科委" target="_blank">国家广电总局科委</a><a href="http://www.cztv.com" title="新蓝网" target="_blank">新蓝网</a><a href="http://www.hcrt.cn/" title="杭州文广" target="_blank">杭州文广</a><a href="http://www.hugd.com/" title="传媒湖州" target="_blank">传媒湖州</a><a href="http://www.ywcity.cn/" title="义乌城市" target="_blank">义乌城市</a><a href="http://iptvlm.zjol.com.cn/" title="德清广电" target="_blank">德清广电</a><a href="http://www.lxzc.net/" title="兰溪之窗" target="_blank">兰溪之窗</a><a href="http://www.yuhuangd.com/" title="玉环广电" target="_blank">玉环广电</a><a href="http://www.xstv.net/" title="象山广电" target="_blank">象山广电</a><a href="http://www.zhtv.com.cn/" title="镇海广播电视台" target="_blank">镇海广播电视台</a><a href="http://www.yltvb.com/" title="玉林电视网" target="_blank">玉林电视网</a><a href="http://www.36tv.cn/" title="金华广众网" target="_blank">金华广众网</a><a href="http://www.jiandetv.com/" title="建德网" target="_blank">建德网</a></li>\r\n      <li><a href="http://www.hzes.cn/" title="杭州二手网" target="_blank">杭州二手网</a><a href="http://www.25pp.com" title="苹果游戏" target="_blank">苹果游戏</a><a href="http://ningbo.baixing.com/" title="宁波百姓网" target="_blank">宁波百姓网</a><a href="http://xiaoshan.19lou.com/" title="萧山19楼" target="_blank">萧山19楼</a><a href="http://www.zaixs.com/" title="萧内网" target="_blank">萧内网</a><a href="http://www.057191.com/" title="萧山招聘网" target="_blank">萧山招聘网</a><a href="http://newshainan.com/" title="海南网" target="_blank">海南网</a><a href="http://www.zkxww.com/" title="周口新网" target="_blank">周口新网</a><a href="http://www.heze.cn/" title="中国菏泽网" target="_blank">中国菏泽网</a><a href="http://www.wjdaily.com/" title="吴江新闻网" target="_blank">吴江新闻网</a></li>\r\n      <li>\r\n    </ul>\r\n  </div>\r\n</div>\r\n{/block}', '/', 1447728607, 1447728625, 1);

-- --------------------------------------------------------

--
-- 表的结构 `theme`
--

CREATE TABLE IF NOT EXISTS `theme` (
  `id` int(11) unsigned NOT NULL COMMENT '专题ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  `keyword` varchar(255) NOT NULL COMMENT '关键词'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专题表';

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `mobile` varchar(16) NOT NULL COMMENT '手机',
  `name` varchar(20) DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  `salt` varchar(16) NOT NULL COMMENT '密钥',
  `grade` varchar(9) NOT NULL DEFAULT 'normal' COMMENT '会员等级',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年',
  `header_image` varchar(1024) DEFAULT NULL COMMENT '用户头像',
  `nickname` varchar(99) DEFAULT NULL COMMENT '用户昵称',
  `signature` varchar(255) DEFAULT NULL COMMENT '用户签名',
  `status` tinyint(1) DEFAULT '1' COMMENT '用户状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;

-- --------------------------------------------------------

--
-- 表的结构 `users_grade`
--

CREATE TABLE IF NOT EXISTS `users_grade` (
  `id` int(11) unsigned NOT NULL COMMENT '等级ID',
  `code` varchar(9) NOT NULL COMMENT '等级代码',
  `name` varchar(30) NOT NULL COMMENT '等级名',
  `credit` int(11) unsigned NOT NULL COMMENT '所需积分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员等级表';

-- --------------------------------------------------------

--
-- 表的结构 `user_bind`
--

CREATE TABLE IF NOT EXISTS `user_bind` (
  `id` int(11) unsigned NOT NULL,
  `open_id` varchar(40) NOT NULL COMMENT '用户OpenId',
  `user_id` int(11) NOT NULL COMMENT '用户UserId',
  `channel_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `videos`
--

CREATE TABLE IF NOT EXISTS `videos` (
  `id` int(11) unsigned NOT NULL COMMENT '新闻ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `collection_id` int(11) unsigned NOT NULL COMMENT '视频集ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  `thumb` varchar(255) DEFAULT NULL COMMENT '源缩略图',
  `author_id` int(11) unsigned NOT NULL COMMENT '作者ID',
  `author_name` varchar(30) NOT NULL COMMENT '作者名字',
  `supply_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '供应源ID',
  `duration` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '修改时间',
  `no_comment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '禁止评论',
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '分区/年'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='视频文件'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;

--
-- 转存表中的数据 `videos`
--

INSERT INTO `videos` (`id`, `channel_id`, `collection_id`, `title`, `intro`, `thumb`, `author_id`, `author_name`, `supply_id`, `duration`, `created_at`, `updated_at`, `no_comment`, `partition_by`) VALUES
(1, 0, 1, '奔跑吧兄弟 2015 第1集', '奔跑奔跑', 'thumbnails/2015/09/29/963ea7a1ad1994a91aa5361feaedf575.png', 81, '薛炜', 0, 2700, 1447138812, 1447138812, 0, 2015);

-- --------------------------------------------------------

--
-- 表的结构 `video_collections`
--

CREATE TABLE IF NOT EXISTS `video_collections` (
  `id` int(11) unsigned NOT NULL COMMENT '视频集ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `intro` varchar(255) NOT NULL COMMENT '简介',
  `thumb` varchar(255) NOT NULL COMMENT '封面',
  `keywords` varchar(255) DEFAULT NULL COMMENT '关键词',
  `author_id` int(11) unsigned NOT NULL COMMENT '作者ID',
  `author_name` varchar(30) NOT NULL COMMENT '作者姓名',
  `no_comment` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '禁止评论',
  `type` enum('variety','movie','series','cartoon','music') DEFAULT NULL COMMENT '类型',
  `extra` text COMMENT '额外字段',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '修改时间'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='视频集';

--
-- 转存表中的数据 `video_collections`
--

INSERT INTO `video_collections` (`id`, `channel_id`, `title`, `intro`, `thumb`, `keywords`, `author_id`, `author_name`, `no_comment`, `type`, `extra`, `created_at`, `updated_at`) VALUES
(1, 0, '奔跑吧兄弟 2015', '奔跑奔跑', 'thumbnails/2015/09/29/963ea7a1ad1994a91aa5361feaedf575.png', '奔跑吧兄弟 跑男', 81, '薛炜', 0, 'variety', NULL, 1447138812, 1447138812);

-- --------------------------------------------------------

--
-- 表的结构 `video_files`
--

CREATE TABLE IF NOT EXISTS `video_files` (
  `id` int(11) NOT NULL COMMENT '文件ID',
  `video_id` int(11) NOT NULL COMMENT '视频ID',
  `path` varchar(256) NOT NULL DEFAULT '' COMMENT '路径',
  `rate` varchar(50) DEFAULT NULL COMMENT '评分',
  `format` varchar(50) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `width` varchar(50) DEFAULT NULL,
  `partition_by` smallint(4) unsigned NOT NULL COMMENT '年分区'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='视频文件'
/*!50500 PARTITION BY RANGE  COLUMNS(partition_by)
(PARTITION p2014 VALUES LESS THAN (2015) ENGINE = InnoDB,
 PARTITION p2015 VALUES LESS THAN (2016) ENGINE = InnoDB,
 PARTITION p2016 VALUES LESS THAN (2017) ENGINE = InnoDB,
 PARTITION p2017 VALUES LESS THAN (2018) ENGINE = InnoDB,
 PARTITION p2018 VALUES LESS THAN (2019) ENGINE = InnoDB,
 PARTITION p2019 VALUES LESS THAN (2020) ENGINE = InnoDB,
 PARTITION p2020 VALUES LESS THAN (2021) ENGINE = InnoDB,
 PARTITION p2022 VALUES LESS THAN (2023) ENGINE = InnoDB,
 PARTITION p2023 VALUES LESS THAN (2024) ENGINE = InnoDB,
 PARTITION p2024 VALUES LESS THAN (2025) ENGINE = InnoDB,
 PARTITION p2025 VALUES LESS THAN (2026) ENGINE = InnoDB,
 PARTITION pmax VALUES LESS THAN (MAXVALUE) ENGINE = InnoDB) */;

-- --------------------------------------------------------

--
-- 表的结构 `vitae`
--

CREATE TABLE IF NOT EXISTS `vitae` (
  `id` int(11) NOT NULL COMMENT '履历ID',
  `admin_id` int(11) NOT NULL COMMENT '用户ID',
  `experience` text COMMENT '个人经历',
  `skill` text COMMENT '技能情况',
  `contacts` int(11) unsigned DEFAULT NULL COMMENT '联系方式',
  `recruit_time` int(11) unsigned DEFAULT NULL COMMENT '入职时间'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `vitae`
--

INSERT INTO `vitae` (`id`, `admin_id`, `experience`, `skill`, `contacts`, `recruit_time`) VALUES
(1, 87, '[{"start-time":"2013-01-01","end-time":"2014-01-01","location":"\\u676d\\u5dde","description":"\\u6d4b\\u8bd5\\u7528\\u4f8b"},{"start-time":"2014-01-01","end-time":"2015-01-01","location":"\\u676d\\u5dde","description":"\\u6d4b\\u8bd52"}]', '爬树,睡觉', 123456, 1446393600),
(2, 172, '[{"start-time":"2015-11-9","end-time":"","location":"\\u676d\\u5dde","description":"\\u9996\\u6d4b\\u5b8c\\u6210"}]', '正式,ok', NULL, 0),
(3, 85, NULL, NULL, NULL, NULL),
(4, 188, NULL, NULL, NULL, NULL),
(5, 111, '[{"start-time":"2015-10-30","end-time":"2015-10-30","location":"12\\u6492\\u65e6","description":"\\u963f\\u8428\\u5fb7"},{"start-time":"2015-11-06","end-time":"2015-11-06","location":"\\u963f\\u8428\\u5fb7","description":" \\u963f\\u8428\\u5fb7"}]', '撒旦,撒旦呃,娃娃衫的', 111, 1445788800),
(6, 82, NULL, '', NULL, 0),
(7, 81, NULL, NULL, NULL, NULL),
(8, 84, NULL, NULL, NULL, NULL),
(9, 83, NULL, NULL, NULL, NULL),
(10, 90, NULL, NULL, NULL, NULL),
(11, 192, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_signup`
--
ALTER TABLE `activity_signup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `f_activity` (`activity_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `channel_id` (`channel_id`,`mobile`),
  ADD KEY `is_admin` (`is_admin`),
  ADD KEY `remember_token` (`remember_token`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `admin_ext`
--
ALTER TABLE `admin_ext`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `department` (`department`,`duty`,`sort`);

--
-- Indexes for table `admin_relation`
--
ALTER TABLE `admin_relation`
  ADD UNIQUE KEY `admin_id` (`admin_id`,`relation_id`,`type`),
  ADD KEY `relation_id` (`relation_id`),
  ADD KEY `type` (`type`),
  ADD KEY `freq` (`freq`);

--
-- Indexes for table `advert`
--
ALTER TABLE `advert`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spaceid` (`spaceid`,`listorder`);

--
-- Indexes for table `advert_space`
--
ALTER TABLE `advert_space`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disabled` (`channel_id`);

--
-- Indexes for table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `channel_id` (`channel_id`);

--
-- Indexes for table `album_image`
--
ALTER TABLE `album_image`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD KEY `album_id` (`album_id`),
  ADD KEY `sort` (`sort`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `album_tmp`
--
ALTER TABLE `album_tmp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`,`code`);

--
-- Indexes for table `announ`
--
ALTER TABLE `announ`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announ_status`
--
ALTER TABLE `announ_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_doc`
--
ALTER TABLE `api_doc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `app_list`
--
ALTER TABLE `app_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_version`
--
ALTER TABLE `app_version`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asset`
--
ALTER TABLE `asset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachment_common`
--
ALTER TABLE `attachment_common`
  ADD PRIMARY KEY (`id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `ext` (`ext`),
  ADD KEY `type` (`type`),
  ADD KEY `created` (`created`),
  ADD KEY `name` (`name`(255));

--
-- Indexes for table `auth_assign`
--
ALTER TABLE `auth_assign`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `channel_id` (`channel_id`,`user_id`,`element_id`,`type`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `auth_element`
--
ALTER TABLE `auth_element`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `controller` (`controller`,`action`),
  ADD KEY `hide` (`is_hide`),
  ADD KEY `is_system` (`is_system`);

--
-- Indexes for table `auth_module`
--
ALTER TABLE `auth_module`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `sort` (`sort`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `auth_role`
--
ALTER TABLE `auth_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `baoliao`
--
ALTER TABLE `baoliao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `baoliao_reply`
--
ALTER TABLE `baoliao_reply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `board`
--
ALTER TABLE `board`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `board_status`
--
ALTER TABLE `board_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `channel_id` (`channel_id`,`code`),
  ADD KEY `name` (`name`,`channel_id`),
  ADD KEY `father_id` (`father_id`,`channel_id`);

--
-- Indexes for table `category_auth`
--
ALTER TABLE `category_auth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`category_id`);

--
-- Indexes for table `category_data`
--
ALTER TABLE `category_data`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD UNIQUE KEY `data_id` (`data_id`,`category_id`,`partition_by`),
  ADD KEY `sort` (`sort`),
  ADD KEY `weight` (`weight`);

--
-- Indexes for table `channel`
--
ALTER TABLE `channel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tag` (`tag`),
  ADD KEY `name` (`name`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `channel_share`
--
ALTER TABLE `channel_share`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `origin_id` (`origin_id`,`auth_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `data_id` (`data_id`),
  ADD KEY `father_id` (`father_id`),
  ADD KEY `create_at` (`create_at`),
  ADD KEY `status` (`status`),
  ADD KEY `up` (`likes`),
  ADD KEY `down` (`down`),
  ADD KEY `client` (`client`),
  ADD KEY `domain` (`domain`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD UNIQUE KEY `type` (`type`,`source_id`,`partition_by`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `hits` (`hits`),
  ADD KEY `status` (`status`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `province_id` (`province_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `county_id` (`county_id`),
  ADD KEY `village_id` (`village_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `father_id` (`father_id`),
  ADD KEY `name` (`name`),
  ADD KEY `sort` (`sort`);

--
-- Indexes for table `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `channel_id` (`channel_id`);

--
-- Indexes for table `duty`
--
ALTER TABLE `duty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `name` (`name`),
  ADD KEY `sort` (`sort`);

--
-- Indexes for table `exprience`
--
ALTER TABLE `exprience`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotwords`
--
ALTER TABLE `hotwords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lotteries`
--
ALTER TABLE `lotteries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`lottery_channel_id`),
  ADD KEY `open_time` (`open_time`,`close_time`),
  ADD KEY `name` (`name`),
  ADD KEY `times_limit` (`times_limit`);

--
-- Indexes for table `lottery_channels`
--
ALTER TABLE `lottery_channels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `sort` (`sort`);

--
-- Indexes for table `lottery_contacts`
--
ALTER TABLE `lottery_contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `mobile` (`mobile`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `lottery_prizes`
--
ALTER TABLE `lottery_prizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lottery_id` (`lottery_id`);

--
-- Indexes for table `lottery_winnings`
--
ALTER TABLE `lottery_winnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prize_id` (`prize_id`);

--
-- Indexes for table `message_task`
--
ALTER TABLE `message_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `updated_at` (`updated_at`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `author_name` (`author_name`),
  ADD KEY `channel_id` (`channel_id`);

--
-- Indexes for table `news_group`
--
ALTER TABLE `news_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `private_category`
--
ALTER TABLE `private_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `private_category_data`
--
ALTER TABLE `private_category_data`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD UNIQUE KEY `data_id_2` (`data_id`,`partition_by`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `father_id` (`father_id`);

--
-- Indexes for table `salary`
--
ALTER TABLE `salary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`,`channel_id`);

--
-- Indexes for table `site`
--
ALTER TABLE `site`
  ADD PRIMARY KEY (`id`),
  ADD KEY `domain` (`domain`),
  ADD KEY `status` (`status`),
  ADD KEY `app_id` (`app_id`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_system` (`is_system`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `code` (`code`),
  ADD KEY `name` (`name`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `stations_epg`
--
ALTER TABLE `stations_epg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stations_id` (`stations_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `stations_program`
--
ALTER TABLE `stations_program`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD KEY `stations_id` (`stations_id`);

--
-- Indexes for table `supplies`
--
ALTER TABLE `supplies`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD KEY `source_id` (`source_id`,`status`),
  ADD KEY `supply_category_id` (`supply_category_id`);

--
-- Indexes for table `supply_categories`
--
ALTER TABLE `supply_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `source_id` (`source_id`);

--
-- Indexes for table `supply_sources`
--
ALTER TABLE `supply_sources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supply_to_category`
--
ALTER TABLE `supply_to_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supply_category_id` (`supply_category_id`,`category_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `progress` (`progress`),
  ADD KEY `status` (`status`),
  ADD KEY `isolate_code` (`isolate_code`),
  ADD KEY `Lft` (`Lft`),
  ADD KEY `Rgt` (`Rgt`),
  ADD KEY `created` (`created`),
  ADD KEY `priority` (`priority`),
  ADD KEY `is_main` (`is_main`),
  ADD KEY `subs_complete` (`subs_complete`),
  ADD KEY `receiver` (`receiver`),
  ADD KEY `creator` (`creator`),
  ADD KEY `receiver_name` (`receiver_name`),
  ADD KEY `start` (`start`),
  ADD KEY `end` (`end`),
  ADD KEY `actual_end` (`actual_end`),
  ADD KEY `actual_start` (`actual_start`);

--
-- Indexes for table `task_attachs_relation`
--
ALTER TABLE `task_attachs_relation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `attach_id` (`attach_id`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `task_contents`
--
ALTER TABLE `task_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `isolate_code` (`isolate_code`),
  ADD KEY `Lft` (`Lft`),
  ADD KEY `Rgt` (`Rgt`),
  ADD KEY `title` (`title`(255)),
  ADD KEY `signature` (`signature`),
  ADD KEY `encrypt` (`encrypt`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `task_progress`
--
ALTER TABLE `task_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `progress` (`progress`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `updated` (`updated`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domain_id` (`domain_id`,`path`),
  ADD KEY `channel_id` (`channel_id`);

--
-- Indexes for table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `grade` (`grade`),
  ADD KEY `mobile` (`mobile`);

--
-- Indexes for table `users_grade`
--
ALTER TABLE `users_grade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_bind`
--
ALTER TABLE `user_bind`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD KEY `weight` (`created_at`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `updated_at` (`updated_at`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `author_name` (`author_name`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `collection_id` (`collection_id`);

--
-- Indexes for table `video_collections`
--
ALTER TABLE `video_collections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `updated_at` (`updated_at`),
  ADD KEY `author_name` (`author_name`);

--
-- Indexes for table `video_files`
--
ALTER TABLE `video_files`
  ADD PRIMARY KEY (`id`,`partition_by`),
  ADD KEY `video_id` (`video_id`),
  ADD KEY `rate` (`rate`);

--
-- Indexes for table `vitae`
--
ALTER TABLE `vitae`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `activity_signup`
--
ALTER TABLE `activity_signup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',AUTO_INCREMENT=194;
--
-- AUTO_INCREMENT for table `advert`
--
ALTER TABLE `advert`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `advert_space`
--
ALTER TABLE `advert_space`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '相册ID',AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `album_image`
--
ALTER TABLE `album_image`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片ID',AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `album_tmp`
--
ALTER TABLE `album_tmp`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主ID',AUTO_INCREMENT=304;
--
-- AUTO_INCREMENT for table `announ`
--
ALTER TABLE `announ`
  MODIFY `id` int(9) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `announ_status`
--
ALTER TABLE `announ_status`
  MODIFY `id` int(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `api_doc`
--
ALTER TABLE `api_doc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';
--
-- AUTO_INCREMENT for table `app_list`
--
ALTER TABLE `app_list`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `app_version`
--
ALTER TABLE `app_version`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `asset`
--
ALTER TABLE `asset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `attachment_common`
--
ALTER TABLE `attachment_common`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `auth_assign`
--
ALTER TABLE `auth_assign`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '授权ID',AUTO_INCREMENT=1108;
--
-- AUTO_INCREMENT for table `auth_element`
--
ALTER TABLE `auth_element`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '原子ID',AUTO_INCREMENT=154;
--
-- AUTO_INCREMENT for table `auth_module`
--
ALTER TABLE `auth_module`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '模块ID',AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `auth_role`
--
ALTER TABLE `auth_role`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `baoliao`
--
ALTER TABLE `baoliao`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `baoliao_reply`
--
ALTER TABLE `baoliao_reply`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `board`
--
ALTER TABLE `board`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '留言板ID',AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `board_status`
--
ALTER TABLE `board_status`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '状态ID',AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目ID',AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `category_auth`
--
ALTER TABLE `category_auth`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目授权ID';
--
-- AUTO_INCREMENT for table `category_data`
--
ALTER TABLE `category_data`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '关联ID',AUTO_INCREMENT=110;
--
-- AUTO_INCREMENT for table `channel`
--
ALTER TABLE `channel`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `channel_share`
--
ALTER TABLE `channel_share`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '认证ID';
--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '部门ID',AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `domains`
--
ALTER TABLE `domains`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '域名iD',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `duty`
--
ALTER TABLE `duty`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '岗位ID',AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `exprience`
--
ALTER TABLE `exprience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '个人经历';
--
-- AUTO_INCREMENT for table `hotwords`
--
ALTER TABLE `hotwords`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `lotteries`
--
ALTER TABLE `lotteries`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `lottery_channels`
--
ALTER TABLE `lottery_channels`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `lottery_prizes`
--
ALTER TABLE `lottery_prizes`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `lottery_winnings`
--
ALTER TABLE `lottery_winnings`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',AUTO_INCREMENT=152;
--
-- AUTO_INCREMENT for table `message_task`
--
ALTER TABLE `message_task`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '新闻ID',AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `news_group`
--
ALTER TABLE `news_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '新闻集ID';
--
-- AUTO_INCREMENT for table `private_category`
--
ALTER TABLE `private_category`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目ID',AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `private_category_data`
--
ALTER TABLE `private_category_data`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '关联ID',AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '地区ID',AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `salary`
--
ALTER TABLE `salary`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `site`
--
ALTER TABLE `site`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '站点ID',AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `stations`
--
ALTER TABLE `stations`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '电台ID',AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `stations_epg`
--
ALTER TABLE `stations_epg`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '直播流ID',AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `stations_program`
--
ALTER TABLE `stations_program`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '节目ID',AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `supplies`
--
ALTER TABLE `supplies`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID';
--
-- AUTO_INCREMENT for table `supply_categories`
--
ALTER TABLE `supply_categories`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID';
--
-- AUTO_INCREMENT for table `supply_sources`
--
ALTER TABLE `supply_sources`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '供应源ID';
--
-- AUTO_INCREMENT for table `supply_to_category`
--
ALTER TABLE `supply_to_category`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID';
--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=399;
--
-- AUTO_INCREMENT for table `task_attachs_relation`
--
ALTER TABLE `task_attachs_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `task_contents`
--
ALTER TABLE `task_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `task_progress`
--
ALTER TABLE `task_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '模板ID',AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `theme`
--
ALTER TABLE `theme`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '专题ID';
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID';
--
-- AUTO_INCREMENT for table `user_bind`
--
ALTER TABLE `user_bind`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '新闻ID',AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `video_collections`
--
ALTER TABLE `video_collections`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '视频集ID',AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `video_files`
--
ALTER TABLE `video_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文件ID';
--
-- AUTO_INCREMENT for table `vitae`
--
ALTER TABLE `vitae`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '履历ID',AUTO_INCREMENT=12;
--
-- 限制导出的表
--

--
-- 限制表 `activity_signup`
--
ALTER TABLE `activity_signup`
  ADD CONSTRAINT `f_activity` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
