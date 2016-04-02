-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2016 年 03 月 07 日 18:22
-- 服务器版本: 5.5.31-log
-- PHP 版本: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `hk8591`
--

-- --------------------------------------------------------

--
-- 表的结构 `t8_admin_group`
--

CREATE TABLE IF NOT EXISTS `t8_admin_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分組ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '分組名',
  `purviews` text NOT NULL COMMENT '權限列表',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='後臺用戶分組' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `t8_admin_group`
--

INSERT INTO `t8_admin_group` (`id`, `name`, `purviews`) VALUES
(2, '管理员', '*'),
(3, '产品专员', '*');

-- --------------------------------------------------------

--
-- 表的结构 `t8_admin_user`
--

CREATE TABLE IF NOT EXISTS `t8_admin_user` (
  `id` int(6) NOT NULL COMMENT '工號',
  `login_pwd` char(32) NOT NULL DEFAULT '' COMMENT '登入密碼',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '暱稱',
  `group_id` int(3) NOT NULL DEFAULT '0' COMMENT '分組ID 0=停權',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '開通時間',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登入时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理員表';

--
-- 转存表中的数据 `t8_admin_user`
--

INSERT INTO `t8_admin_user` (`id`, `login_pwd`, `realname`, `nickname`, `group_id`, `create_time`, `last_login_time`) VALUES
(10400, 'cb1cda39951a95202a57ae7933a7d90b', '黄帝佳', 'deeka', 2, '2016-02-18 09:50:39', '2016-03-07 15:12:20'),
(10466, '2a325bbb52c63f285085fdcf3bf33c3b', '彭炬浮', 'Jeff', 2, '2016-02-23 09:11:13', '2016-03-04 17:52:31');

-- --------------------------------------------------------

--
-- 表的结构 `t8_admin_user_login_log`
--

CREATE TABLE IF NOT EXISTS `t8_admin_user_login_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `session_id` varchar(32) NOT NULL DEFAULT '' COMMENT '会话ID',
  `login_ip` char(15) NOT NULL DEFAULT '' COMMENT '登入IP',
  `admin_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `admin_group_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理分组',
  `login_result` tinyint(1) NOT NULL DEFAULT '0' COMMENT '登入结果 1=成功 0=失败',
  `fail_cause` enum('','invaild_ip','invaild_id','invaild_pwd','deny_login') NOT NULL DEFAULT '' COMMENT '失败原因',
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登入时间',
  PRIMARY KEY (`id`),
  KEY `admin_user_id` (`admin_user_id`),
  KEY `login_time` (`login_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='后台登入日志' AUTO_INCREMENT=39 ;

--
-- 转存表中的数据 `t8_admin_user_login_log`
--

INSERT INTO `t8_admin_user_login_log` (`id`, `session_id`, `login_ip`, `admin_user_id`, `admin_group_id`, `login_result`, `fail_cause`, `login_time`) VALUES
(1, 'mc6p9qlturh4ncartkfcf6osf6', '', 10400, 2, 1, '', '2016-03-01 08:27:29'),
(2, 'mc6p9qlturh4ncartkfcf6osf6', '', 10400, 2, 0, 'invaild_pwd', '2016-03-01 08:29:21'),
(3, 'mc6p9qlturh4ncartkfcf6osf6', '', 0, 0, 0, 'invaild_id', '2016-03-01 08:29:37'),
(4, 'mc6p9qlturh4ncartkfcf6osf6', '127.0.0.1', 0, 0, 0, 'invaild_id', '2016-03-01 08:34:40'),
(5, 'mc6p9qlturh4ncartkfcf6osf6', '127.0.0.1', 10400, 2, 1, '', '2016-03-01 08:35:00'),
(6, 'mc6p9qlturh4ncartkfcf6osf6', '127.0.0.1', 10400, 2, 1, '', '2016-03-02 00:49:57'),
(7, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-02 01:17:34'),
(8, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-02 01:56:52'),
(9, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-02 05:59:01'),
(10, 'mc6p9qlturh4ncartkfcf6osf6', '127.0.0.1', 10400, 2, 1, '', '2016-03-02 08:42:07'),
(11, '1d59ki51q1pkbiclklustnbug4', '127.0.0.1', 10466, 2, 1, '', '2016-03-02 10:01:46'),
(12, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-02 10:21:46'),
(13, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-03 01:09:11'),
(14, 'mc6p9qlturh4ncartkfcf6osf6', '127.0.0.1', 10400, 2, 1, '', '2016-03-03 01:27:02'),
(15, 'p29bp6n5e3dnlfugb4snjd6o71', '127.0.0.1', 10466, 2, 1, '', '2016-03-03 02:14:22'),
(16, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-03 02:30:59'),
(17, '1d59ki51q1pkbiclklustnbug4', '127.0.0.1', 10466, 2, 1, '', '2016-03-03 03:33:07'),
(18, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-03 06:12:58'),
(19, 'p29bp6n5e3dnlfugb4snjd6o71', '127.0.0.1', 10466, 2, 1, '', '2016-03-03 06:24:11'),
(20, 'mc6p9qlturh4ncartkfcf6osf6', '127.0.0.1', 10400, 2, 1, '', '2016-03-03 06:34:31'),
(21, 'bmim8ojfdhdmbj7a8m32jo7ci2', '127.0.0.1', 10466, 2, 1, '', '2016-03-03 06:35:17'),
(22, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-03 08:38:27'),
(23, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-04 01:11:02'),
(24, 'mc6p9qlturh4ncartkfcf6osf6', '127.0.0.1', 10400, 2, 1, '', '2016-03-04 02:28:56'),
(25, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-04 02:40:24'),
(26, 'mc6p9qlturh4ncartkfcf6osf6', '127.0.0.1', 10400, 2, 1, '', '2016-03-04 05:15:32'),
(27, 'mc6p9qlturh4ncartkfcf6osf6', '127.0.0.1', 10400, 2, 1, '', '2016-03-04 06:28:03'),
(28, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-04 06:34:06'),
(29, 'fnqrk6iqs9hv1ol77bigt13176', '127.0.0.1', 10466, 2, 1, '', '2016-03-04 06:40:36'),
(30, 'dh2bkvtn185blrm6jfjnhfanm7', '127.0.0.1', 10400, 2, 1, '', '2016-03-04 07:05:30'),
(31, 'fnqrk6iqs9hv1ol77bigt13176', '127.0.0.1', 10466, 2, 1, '', '2016-03-04 07:28:12'),
(32, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-04 09:18:38'),
(33, 'bmim8ojfdhdmbj7a8m32jo7ci2', '127.0.0.1', 10466, 2, 1, '', '2016-03-04 09:53:50'),
(34, 'mc6p9qlturh4ncartkfcf6osf6', '127.0.0.1', 10400, 2, 1, '', '2016-03-07 00:56:31'),
(35, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-07 00:57:11'),
(36, 'mc6p9qlturh4ncartkfcf6osf6', '127.0.0.1', 10400, 2, 1, '', '2016-03-07 05:46:41'),
(37, '04g3ptc6m24nfdvtd9h2hf65i4', '127.0.0.1', 10400, 2, 1, '', '2016-03-07 06:11:07'),
(38, 'mc6p9qlturh4ncartkfcf6osf6', '127.0.0.1', 10400, 2, 1, '', '2016-03-07 07:12:18');

-- --------------------------------------------------------

--
-- 表的结构 `t8_card`
--

CREATE TABLE IF NOT EXISTS `t8_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '卡类ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '卡类名称',
  `image` varchar(300) NOT NULL COMMENT '图片',
  `relation_game_id` varchar(100) NOT NULL DEFAULT '' COMMENT '关联游戏',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `is_deny_publish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否禁止刊登',
  `taxis` int(4) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='卡类表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `t8_card`
--

INSERT INTO `t8_card` (`id`, `name`, `image`, `relation_game_id`, `is_hidden`, `is_deny_publish`, `taxis`) VALUES
(1, 'GASH', '', '6,1', 0, 0, 0),
(2, 'MyCard', '', '1,2,3', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `t8_card_denom`
--

CREATE TABLE IF NOT EXISTS `t8_card_denom` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '面额ID',
  `card_id` int(4) NOT NULL COMMENT '卡类ID',
  `denom` varchar(100) NOT NULL COMMENT '面额',
  `price` int(4) NOT NULL COMMENT '价格（快速购卡用)',
  `is_hidden` int(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `is_deny_publish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否禁止刊登',
  `taxis` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `category_id` (`card_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='卡类面额' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `t8_card_denom`
--

INSERT INTO `t8_card_denom` (`id`, `card_id`, `denom`, `price`, `is_hidden`, `is_deny_publish`, `taxis`) VALUES
(1, 1, '100点', 0, 0, 0, 0),
(2, 1, '200點', 0, 0, 0, 0),
(3, 2, '300點', 0, 0, 0, 0),
(4, 2, '400點', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `t8_card_stock`
--

CREATE TABLE IF NOT EXISTS `t8_card_stock` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `seller_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '卡商ID',
  `card_id` int(11) NOT NULL DEFAULT '0' COMMENT '卡类ID',
  `denom_id` int(11) NOT NULL DEFAULT '0' COMMENT '面额ID',
  `card_no` mediumblob NOT NULL COMMENT '卡号',
  `card_pwd` mediumblob NOT NULL COMMENT '卡密',
  `resale_id` int(20) NOT NULL DEFAULT '0' COMMENT '转售ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 -1=暂停发卡 0=等发卡 1=已发出',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '上传时间',
  `buyer_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '买家ID 0=未发卡 -1=优先发卡',
  `buy_time` datetime NOT NULL COMMENT '购买时间',
  `trade_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '交易ID',
  PRIMARY KEY (`id`),
  KEY `sicidi` (`seller_id`,`card_id`,`denom_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='点卡库存表' AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `t8_card_stock`
--

INSERT INTO `t8_card_stock` (`id`, `seller_id`, `card_id`, `denom_id`, `card_no`, `card_pwd`, `resale_id`, `status`, `create_time`, `buyer_id`, `buy_time`, `trade_id`) VALUES
(1, 1001, 1, 1, 0xe70b0425594cec702b0a40d9b3294e95, 0x1d4cfc3f11d124d5c0622d3f93ec7494, 10, 1, '2016-03-04 00:59:48', 0, '0000-00-00 00:00:00', 0),
(2, 1001, 1, 1, 0x320abcd6ebf8d1c7d1025f3b38fbd0ca, 0xc45c8d6bbe4539db98bf3921411b544e, 0, 0, '2016-03-04 00:59:48', 0, '0000-00-00 00:00:00', 0),
(3, 1001, 1, 1, 0xe62a712ad42c262b659b02700618f510, 0x1b73f4a074d44bac653b166a100a8b4e, 0, 0, '2016-03-04 00:59:48', 0, '0000-00-00 00:00:00', 0),
(4, 1001, 1, 1, 0xe70b0425594cec702b0a40d9b3294e95, 0x1d4cfc3f11d124d5c0622d3f93ec7494, 0, 0, '2016-03-04 01:13:48', 0, '0000-00-00 00:00:00', 0),
(5, 1001, 1, 1, 0x320abcd6ebf8d1c7d1025f3b38fbd0ca, 0xc45c8d6bbe4539db98bf3921411b544e, 0, 0, '2016-03-04 01:13:48', 0, '0000-00-00 00:00:00', 0),
(6, 1001, 1, 1, 0xe62a712ad42c262b659b02700618f510, 0x1b73f4a074d44bac653b166a100a8b4e, 0, 0, '2016-03-04 01:13:48', 0, '0000-00-00 00:00:00', 0),
(7, 1001, 1, 1, 0xe70b0425594cec702b0a40d9b3294e95, 0x1d4cfc3f11d124d5c0622d3f93ec7494, 0, 0, '2016-03-04 01:13:57', 0, '0000-00-00 00:00:00', 0),
(8, 1001, 1, 1, 0x320abcd6ebf8d1c7d1025f3b38fbd0ca, 0xc45c8d6bbe4539db98bf3921411b544e, 0, 0, '2016-03-04 01:13:57', 0, '0000-00-00 00:00:00', 0),
(9, 1001, 1, 2, 0xe62a712ad42c262b659b02700618f510, 0x1b73f4a074d44bac653b166a100a8b4e, 0, 0, '2016-03-04 01:13:57', 0, '0000-00-00 00:00:00', 0),
(10, 1001, 1, 1, 0xe70b0425594cec702b0a40d9b3294e95, 0x1d4cfc3f11d124d5c0622d3f93ec7494, 0, 0, '2016-03-04 10:29:16', -1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- 表的结构 `t8_cart`
--

CREATE TABLE IF NOT EXISTS `t8_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '買家id',
  `num` mediumint(9) NOT NULL DEFAULT '1' COMMENT '購買數量',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:有效，1:刪除，2:過期',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='購物車' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `t8_cart`
--

INSERT INTO `t8_cart` (`id`, `goods_id`, `user_id`, `num`, `status`, `create_time`) VALUES
(1, 1, 1001, 2, 0, '2016-03-07 03:52:39'),
(2, 2, 1001, 1, 0, '2016-03-07 03:52:39');

-- --------------------------------------------------------

--
-- 表的结构 `t8_config`
--

CREATE TABLE IF NOT EXISTS `t8_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT 'system' COMMENT '类型',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `value` text NOT NULL COMMENT '值',
  `remark` varchar(300) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='网站配置' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `t8_config`
--

INSERT INTO `t8_config` (`id`, `type`, `name`, `value`, `remark`) VALUES
(1, 'maintain', 'on', '0', '1=维护 0=正常'),
(2, 'iplock', 'on', '1', 'ip锁开关');

-- --------------------------------------------------------

--
-- 表的结构 `t8_deny_ip`
--

CREATE TABLE IF NOT EXISTS `t8_deny_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ip` char(15) NOT NULL DEFAULT '' COMMENT 'IP',
  `expire_time` datetime NOT NULL COMMENT '禁止至日期',
  `deny_cause` varchar(300) NOT NULL DEFAULT '' COMMENT '禁止原因',
  `action_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人ID，0=系统',
  `action_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='禁止IP' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `t8_deny_ip`
--

INSERT INTO `t8_deny_ip` (`id`, `ip`, `expire_time`, `deny_cause`, `action_user_id`, `action_time`) VALUES
(4, '127.0.0.2', '2016-03-25 18:14:51', '', 10400, '2016-02-25 10:00:19'),
(5, '127.0.0.13', '2016-03-26 08:44:37', '', 10400, '2016-02-26 00:44:38');

-- --------------------------------------------------------

--
-- 表的结构 `t8_feedback`
--

CREATE TABLE IF NOT EXISTS `t8_feedback` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `category_id` int(4) NOT NULL DEFAULT '0' COMMENT '类型',
  `titile` varchar(300) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '会员ID 0=游客',
  `client_info` text NOT NULL COMMENT '客户端信息',
  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '提交时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户反馈表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t8_game`
--

CREATE TABLE IF NOT EXISTS `t8_game` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '游戏ID',
  `type` set('pc','mobile','web') NOT NULL DEFAULT 'pc' COMMENT '游戏类型',
  `is_chinese` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否中文',
  `phonetic` char(1) NOT NULL DEFAULT '' COMMENT '注音',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '游戏名称',
  `color` char(7) NOT NULL DEFAULT '' COMMENT '显示颜色',
  `taxis` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_new` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否新游戏',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否热门游戏',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隱藏',
  `is_deny_publish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否禁止刊登',
  `goods_type` varchar(60) NOT NULL DEFAULT '' COMMENT '商品类型配置 空=所有类型',
  `currency_unit` varchar(60) NOT NULL DEFAULT '' COMMENT '游戏币单位，用逗号隔开',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='游戏表' AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `t8_game`
--

INSERT INTO `t8_game` (`id`, `type`, `is_chinese`, `phonetic`, `name`, `color`, `taxis`, `is_new`, `is_hot`, `is_hidden`, `is_deny_publish`, `goods_type`, `currency_unit`) VALUES
(1, 'pc', 1, 'X', '新枫之谷', '#ff00ff', 0, 1, 1, 0, 0, '1,2,3,4,5', '金幣,萬金幣,億金幣'),
(2, 'pc', 0, '', '英雄联盟', '#000000', 0, 0, 0, 0, 0, '2,3,4,6,8', ''),
(3, 'pc', 0, '', '天堂', '', 0, 0, 0, 0, 0, '', ''),
(4, 'pc', 0, '', '新天堂', '', 0, 0, 0, 0, 0, '', ''),
(5, 'mobile', 1, 'N', '你見過我的小熊嗎', '#000000', 0, 0, 0, 0, 0, '', ''),
(6, 'mobile', 0, '', '魔靈召喚', '', 1, 0, 0, 0, 0, '', '萬金幣,億金幣'),
(8, 'mobile', 0, 'P', 'Project白貓', '#000000', 1, 0, 0, 0, 0, '1,3,4,5,6,9', '萬金幣'),
(9, 'web', 1, 'I', '英雄頁游', '#000000', 0, 0, 0, 0, 0, '2,3,4,6,8', '');

-- --------------------------------------------------------

--
-- 表的结构 `t8_game_item`
--

CREATE TABLE IF NOT EXISTS `t8_game_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `game_id` int(11) NOT NULL DEFAULT '0' COMMENT '遊戲ID',
  `name` varchar(300) NOT NULL DEFAULT '' COMMENT '品項名',
  `taxis` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='遊戲品項' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `t8_game_item`
--

INSERT INTO `t8_game_item` (`id`, `game_id`, `name`, `taxis`) VALUES
(3, 1, '100鑽石', 0),
(4, 1, '200鑽石', 0),
(5, 1, '300鑽石', 0);

-- --------------------------------------------------------

--
-- 表的结构 `t8_game_server`
--

CREATE TABLE IF NOT EXISTS `t8_game_server` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '伺服器ID',
  `game_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '伺服器名称',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隱藏',
  `is_deny_publish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否禁止刊登',
  `taxis` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='游戏伺服器' AUTO_INCREMENT=19 ;

--
-- 转存表中的数据 `t8_game_server`
--

INSERT INTO `t8_game_server` (`id`, `game_id`, `name`, `is_hidden`, `is_deny_publish`, `taxis`) VALUES
(1, 1, '雪吉拉', 0, 0, 2),
(2, 1, '菇菇寶貝', 0, 0, 1),
(3, 1, '星光精靈', 0, 0, 4),
(5, 6, '芝士蛋糕', 0, 0, 0),
(6, 2, '台服', 0, 0, 0),
(7, 2, '美服', 0, 0, 0),
(8, 8, '十一區', 0, 0, 0),
(9, 8, '十二區', 0, 0, 0),
(10, 6, '煽風點火', 0, 0, 0),
(11, 6, '對高房價', 0, 0, 0),
(12, 5, '張三的歌', 0, 0, 0),
(13, 5, '看看就好', 0, 0, 0),
(14, 5, '微軟統一', 0, 0, 0),
(15, 3, '啦啦啦啦', 0, 0, 0),
(16, 3, '~\\(≧▽≦)/~', 0, 0, 0),
(17, 1, 'O(∩_∩)O哈哈~', 0, 0, 0),
(18, 1, '牛牪犇逼', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `t8_goods`
--

CREATE TABLE IF NOT EXISTS `t8_goods` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品类型',
  `game_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID',
  `server_id` int(11) NOT NULL DEFAULT '0' COMMENT '伺服器ID',
  `item_id` int(11) NOT NULL DEFAULT '0' COMMENT '品项ID',
  `seller_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '卖家ID',
  `title` varchar(300) NOT NULL DEFAULT '' COMMENT '商品标题',
  `slogan` varchar(150) NOT NULL DEFAULT '' COMMENT '廣告語',
  `badges` set('image','sale') NOT NULL DEFAULT '' COMMENT '商品徽章',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '商品库存',
  `cost_price` int(11) NOT NULL DEFAULT '0' COMMENT '原价 选填',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '售价 有原价时为折扣价',
  `currency_ratio` int(11) NOT NULL DEFAULT '0' COMMENT '遊戲幣比值',
  `currency_unit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '遊戲幣單位ID',
  `transfer_time` enum('15','30','60','120','300','1440') NOT NULL DEFAULT '15' COMMENT '承诺移交时间（分钟）',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '商品状态 0=上架 1=下架 2=删除',
  `cause` tinyint(4) NOT NULL DEFAULT '0' COMMENT '下架或删除原因 0=会员 1=系统 2=客服 3=过期 4=无库存 5=其他',
  `merge_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '合并标识',
  `renewal_time` datetime NOT NULL COMMENT '更新时间',
  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '刊登时间',
  `post_client` set('web','mobile','android','ios') NOT NULL DEFAULT 'web' COMMENT '刊登客戶端',
  `post_ip` char(15) NOT NULL DEFAULT '' COMMENT '刊登ip',
  PRIMARY KEY (`id`),
  KEY `seller_id` (`seller_id`),
  KEY `renewal_time` (`renewal_time`),
  KEY `gigt` (`game_id`,`type`) USING BTREE,
  KEY `game_id` (`game_id`,`merge_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='商品表' AUTO_INCREMENT=42 ;

--
-- 转存表中的数据 `t8_goods`
--

INSERT INTO `t8_goods` (`id`, `type`, `game_id`, `server_id`, `item_id`, `seller_id`, `title`, `slogan`, `badges`, `stock`, `cost_price`, `price`, `currency_ratio`, `currency_unit`, `transfer_time`, `status`, `cause`, `merge_id`, `renewal_time`, `post_time`, `post_client`, `post_ip`) VALUES
(1, 1, 1, 2, 1, 1001, '這是一條測試數據', '', 'image,sale', 100, 1500, 1000, 0, 0, '15', 0, 0, 0, '2016-02-19 16:42:00', '2016-02-19 08:42:00', 'web', ''),
(2, 12, 3, 123, 2, 0, '21345', '', '', 543, 25, 436, 0, 0, '15', 0, 0, 0, '2016-02-19 19:46:43', '2016-02-19 11:46:43', 'web', ''),
(6, 1, 5, 32, 2, 1002, '愛上大三頓飯', '', '', 11, 1000, 900, 0, 0, '15', 1, 0, 0, '2016-02-19 19:48:54', '2016-02-19 11:48:54', 'web', ''),
(7, 1, 3, 123, 3, 1243, 'As大法师打发', '', '', 213, 1231, 436, 0, 0, '15', 0, 0, 0, '2016-02-19 19:55:49', '2016-02-19 11:55:49', 'web', ''),
(10, 0, 8, 860, 4, 1243, '色人生的分工色', '', '', 11, 1000, 909, 0, 0, '15', 0, 0, 23, '2016-02-22 17:36:29', '2016-02-22 09:36:29', 'web', ''),
(11, 3, 3, 863, 5, 1243, 'keywordRecGames', '', '', 11, 1000, 950, 0, 0, '15', 1, 0, 32, '2016-02-22 17:39:06', '2016-02-22 09:39:06', 'web', ''),
(15, 1, 6, 423, 4, 234, 'f5gh45sdfh', '', '', 12, 111, 0, 0, 0, '15', 1, 0, 31, '2016-02-23 15:41:10', '2016-02-23 07:41:10', 'web', ''),
(16, 1, 2, 1, 2, 1001, '測試商品', '', '', 10, 200, 200, 0, 0, '15', 0, 0, 1155607094, '2016-02-24 10:39:10', '2016-02-24 02:39:10', 'web', '127.0.0.1'),
(18, 1, 1, 1, 4, 1001, '測試商品', '', 'sale', 10, 200, 100, 0, 0, '15', 0, 0, 809676114, '2016-02-26 17:34:38', '2016-02-26 09:34:38', 'web', '127.0.0.1'),
(19, 1, 2, 1, 8, 1001, '測試商品', '', '', 10, 200, 200, 0, 0, '15', 0, 0, 1155607094, '2016-02-24 10:43:02', '2016-02-24 02:43:02', 'web', '127.0.0.1'),
(20, 1, 5, 1, 4, 1001, '測試商品', '嘉威最討厭', '', 1000, 200, 200, 0, 0, '15', 0, 0, 1155607094, '2016-02-24 10:53:03', '2016-02-24 02:53:03', 'web', '127.0.0.1'),
(21, 1, 1, 1, 1, 1001, '測試商品', '嘉威最討厭', '', 10, 200, 200, 0, 0, '15', 0, 0, 1155607094, '2016-02-24 10:45:52', '2016-02-24 02:45:52', 'web', '127.0.0.1'),
(22, 4, 3, 860, 2, 1243, 'sdfgsafdgsfdg', '', 'sale', 11, 1000, 900, 0, 0, '15', 0, 0, 1497156038, '2016-02-26 14:32:56', '2016-02-26 06:32:56', 'web', '127.0.0.1'),
(23, 3, 2, 6, 3, 1243, '阿斯蒂芬申達股份2sss', '', 'sale', 11, 1000, 800, 0, 0, '15', 0, 0, -1891938531, '2016-02-26 17:19:27', '2016-02-26 09:19:27', 'web', '127.0.0.1'),
(24, 3, 2, 6, 8, 1243, '阿斯蒂芬申達股份22ad', '', 'sale', 12, 1000, 900, 0, 0, '120', 0, 0, -1093486516, '2016-02-26 17:17:07', '2016-02-26 09:17:07', 'web', '127.0.0.1'),
(25, 3, 2, 6, 7, 1243, '阿斯蒂芬申達股份22ad', '', 'sale', 12, 1000, 900, 0, 0, '15', 0, 0, -1093486516, '2016-02-26 17:17:17', '2016-02-26 09:17:17', 'web', '127.0.0.1'),
(26, 1, 1, 2, 1, 1001, '這是一條測試數據', '', 'image,sale', 100, 1500, 1050, 0, 0, '15', 0, 0, 0, '2016-02-19 16:42:00', '2016-02-19 00:42:00', 'web', ''),
(27, 12, 3, 123, 2, 0, '21345', '', '', 543, 25, 436, 0, 0, '15', 1, 0, 0, '2016-02-19 19:46:43', '2016-02-19 03:46:43', 'web', ''),
(28, 1, 5, 32, 2, 1243, '愛上大三頓飯', '', '', 11, 1000, 900, 0, 0, '15', 1, 0, 0, '2016-02-19 19:48:54', '2016-02-19 03:48:54', 'web', ''),
(29, 1, 3, 123, 3, 1243, 'As大法师打发', '', '', 213, 1231, 436, 0, 0, '15', 0, 0, 0, '2016-02-19 19:55:49', '2016-02-19 03:55:49', 'web', ''),
(30, 0, 8, 860, 4, 1243, '色人生的分工色', '', '', 11, 1000, 909, 0, 0, '15', 0, 0, 23, '2016-02-22 17:36:29', '2016-02-22 01:36:29', 'web', ''),
(31, 3, 3, 863, 5, 1243, 'keywordRecGames', '', '', 11, 1000, 950, 0, 0, '15', 1, 0, 32, '2016-02-22 17:39:06', '2016-02-22 01:39:06', 'web', ''),
(32, 1, 6, 423, 4, 234, 'f5gh45sdfh', '', '', 12, 111, 0, 0, 0, '15', 1, 0, 31, '2016-02-23 15:41:10', '2016-02-22 23:41:10', 'web', ''),
(33, 1, 1, 1, 2, 1001, '測試商品', '', '', 10, 200, 200, 0, 0, '15', 0, 0, 1155607094, '2016-02-24 10:39:10', '2016-02-23 18:39:10', 'web', '127.0.0.1'),
(34, 1, 1, 1, 4, 1001, '測試商品', '', 'sale', 10, 200, 100, 0, 0, '15', 0, 0, 809676114, '2016-02-26 17:34:38', '2016-02-26 01:34:38', 'web', '127.0.0.1'),
(35, 1, 1, 1, 8, 1001, '測試商品', '', '', 10, 200, 200, 0, 0, '15', 0, 0, 1155607094, '2016-02-24 10:43:02', '2016-02-23 18:43:02', 'web', '127.0.0.1'),
(36, 1, 1, 1, 4, 1001, '測試商品', '嘉威最討厭', '', 1000, 200, 200, 0, 0, '15', 0, 0, 1155607094, '2016-02-24 10:53:03', '2016-02-23 18:53:03', 'web', '127.0.0.1'),
(37, 1, 1, 1, 1, 1001, '測試商品', '嘉威最討厭', '', 10, 200, 200, 0, 0, '15', 0, 0, 1155607094, '2016-02-24 10:45:52', '2016-02-23 18:45:52', 'web', '127.0.0.1'),
(38, 4, 3, 860, 2, 1243, 'sdfgsafdgsfdg', '', 'sale', 11, 1000, 900, 0, 0, '15', 0, 0, 1497156038, '2016-02-26 14:32:56', '2016-02-25 22:32:56', 'web', '127.0.0.1'),
(39, 3, 2, 6, 3, 1243, '阿斯蒂芬申達股份2sss', '', 'sale', 11, 1000, 800, 0, 0, '15', 0, 0, -1891938531, '2016-02-26 17:19:27', '2016-02-26 01:19:27', 'web', '127.0.0.1'),
(40, 3, 2, 6, 8, 1243, '阿斯蒂芬申達股份22ad', '', 'sale', 12, 1000, 900, 0, 0, '120', 0, 0, -1093486516, '2016-02-26 17:17:07', '2016-02-26 01:17:07', 'web', '127.0.0.1'),
(41, 3, 2, 6, 7, 1243, '阿斯蒂芬申達股份22ad', '', 'sale', 12, 1000, 900, 0, 0, '15', 2, 1, -1093486516, '2016-02-26 17:17:17', '2016-02-26 01:17:17', 'web', '127.0.0.1');

-- --------------------------------------------------------

--
-- 表的结构 `t8_goods_info`
--

CREATE TABLE IF NOT EXISTS `t8_goods_info` (
  `goods_id` bigint(11) NOT NULL COMMENT '商品ID',
  `extend` text COMMENT '扩展信息，如帐号信息',
  `description` text COMMENT '商品描述',
  `images` text COMMENT '商品图片',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品信息表';

--
-- 转存表中的数据 `t8_goods_info`
--

INSERT INTO `t8_goods_info` (`goods_id`, `extend`, `description`, `images`) VALUES
(18, '', '測試商品', ''),
(19, '', '測試商品', ''),
(20, '', '測試商品', ''),
(21, '', '測試商品', ''),
(22, '', 'awesarwe', ''),
(23, '', '沙發大概是sdf', ''),
(24, '', '沙發大概是梵蒂a', ''),
(25, '', '沙發大概是梵蒂岡', '');

-- --------------------------------------------------------

--
-- 表的结构 `t8_goods_qa`
--

CREATE TABLE IF NOT EXISTS `t8_goods_qa` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `seller_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '卖家ID',
  `ask_user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '提问者ID',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否前台隐藏',
  `admin_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '隐藏操作人 0=卖家 >0为客服',
  `post_client_ip` char(15) NOT NULL DEFAULT '' COMMENT '提交IP',
  `post_client` enum('web','mobile','android','ios') NOT NULL DEFAULT 'web' COMMENT '提交客户端',
  `ask_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '提问时间',
  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否提问已读',
  `read_ask_time` datetime NOT NULL COMMENT '查看提问时间',
  `is_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已回复',
  `reply_time` datetime NOT NULL COMMENT '回复时间',
  `is_reply_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否回复已读',
  `read_reply_time` datetime NOT NULL COMMENT '查看回复时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `siir` (`seller_id`,`is_read`),
  KEY `auiir` (`ask_user_id`,`is_reply`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品问与答' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t8_goods_qa_content`
--

CREATE TABLE IF NOT EXISTS `t8_goods_qa_content` (
  `qa_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '问与答ID',
  `ask_content` text NOT NULL COMMENT '提问',
  `reply_content` text NOT NULL COMMENT '回复',
  PRIMARY KEY (`qa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品问与答内容';

-- --------------------------------------------------------

--
-- 表的结构 `t8_goods_type`
--

CREATE TABLE IF NOT EXISTS `t8_goods_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品类型ID',
  `type_name` varchar(20) NOT NULL DEFAULT '' COMMENT '商品类型名称',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隱藏',
  `is_deny_publish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否禁止刊登',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='商品类型' AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `t8_goods_type`
--

INSERT INTO `t8_goods_type` (`id`, `type_name`, `is_hidden`, `is_deny_publish`) VALUES
(1, '點卡', 0, 0),
(2, '遊戲幣', 0, 0),
(3, '道具', 0, 0),
(4, '帳號', 0, 0),
(5, '代儲', 0, 0),
(6, '代練', 0, 0),
(7, '送禮', 0, 0),
(8, '商城道具', 0, 0),
(9, '禮包', 0, 0),
(10, '其他', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `t8_index_rank`
--

CREATE TABLE IF NOT EXISTS `t8_index_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '名称',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:热卖点卡 2热门端游 3熱門手游',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `game_id` int(11) NOT NULL DEFAULT '0',
  `rank` mediumint(9) NOT NULL DEFAULT '0',
  `relative_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '相关id(game_id,keyWordId,)',
  `tag` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0:不显示 1:显示 2：置顶',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `link` varchar(200) NOT NULL DEFAULT '' COMMENT '链接',
  `other` varchar(200) NOT NULL DEFAULT '' COMMENT '其他内容',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `rank` (`type`,`rank`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='首页排行榜，榜单，热门游戏等' AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `t8_index_rank`
--

INSERT INTO `t8_index_rank` (`id`, `name`, `type`, `num`, `game_id`, `rank`, `relative_id`, `tag`, `create_time`, `link`, `other`) VALUES
(1, '新枫之谷', 2, 101, 859, 1, 0, 1, '2016-02-23 06:18:10', 'http://static.dev.8591.com.hk/images/index/fzg.png', '1,2,6'),
(3, 'Gash 500点', 1, 100, 859, 2, 63131522, 1, '2016-02-23 06:18:10', 'http://static.dev.8591.com.hk/images/index/fzg.png', ''),
(5, '魔靈召喚', 3, 654, 3304, 1, 0, 1, '2016-02-23 06:04:41', 'http://static.dev.8591.com.hk/images/index/bm.png', '11,15,1,2'),
(8, 'CSO', 0, 100, 6544, 2, 0, 2, '2016-02-23 06:27:09', '', '54655,65465654'),
(9, '劍靈', 3, 123, 431, 3, 0, 1, '2016-02-23 08:56:05', 'http://static.dev.8591.com.hk/images/index/bm.png', '16,18'),
(10, '练练手冷死了', 0, 3541, 859, 23, 0, 1, '2016-02-24 03:44:31', '', '654654,6465655'),
(11, '啦啦啦', 2, 555, 859, 4, 0, 1, '2016-02-24 03:47:50', 'http://static.dev.8591.com.hk/images/index/fzg.png', '7,10'),
(12, '阿尔', 2, 23, 22, 21, 0, 2, '2016-02-24 03:50:33', 'http://static.dev.8591.com.hk/images/index/fzg.png', '1,23,24,25'),
(13, 'MyCard 1000点', 1, 1000, 859, 2, 1, 1, '2016-02-25 09:39:20', 'sss', 'http://upload.dev.8591.com.hk/mobilegame1.png'),
(14, '新枫之谷', 2, 213, 859, 12, 0, 1, '2016-02-25 10:05:58', 'http://static.dev.8591.com.hk/images/index/fzg.png', '2'),
(15, '白貓 Project', 3, 0, 8, 3, 0, 2, '2016-02-29 08:25:48', 'http://static.dev.8591.com.hk/images/index/bm.png', '1,23,24,25'),
(16, ' 辣椒卡1000點', 1, 800, 4, 1, 1000, 1, '2016-02-29 09:10:24', 'http://upload.debug.8591.com.hk/lajiao-half.png', ''),
(17, 'GG貝殼幣', 1, 800, 3, 3, 1000, 1, '2016-02-29 09:10:59', 'http://upload.debug.8591.com.hk/GG-half.png', '');

-- --------------------------------------------------------

--
-- 表的结构 `t8_sms_otp`
--

CREATE TABLE IF NOT EXISTS `t8_sms_otp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` enum('reg','login','payment','changepwd','changepaymentpwd','changemobile','unlock','findpwd','findpaymentpwd') NOT NULL DEFAULT 'reg' COMMENT '类型',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '行动电话',
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `error` varchar(64) NOT NULL DEFAULT '' COMMENT '失败原因',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '簡訊狀態 -2=驗證超時 -1=發送失敗 0=發送中 1=發送成功 2=驗證成功',
  `send_client` enum('web','mobile','android','ios') NOT NULL DEFAULT 'web' COMMENT '发送客户端',
  `send_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发送时间',
  `verify_time` datetime DEFAULT NULL COMMENT '验证时间',
  `expire_time` datetime DEFAULT NULL COMMENT '有效期',
  PRIMARY KEY (`id`),
  KEY `tms` (`type`,`mobile`,`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='短信验证码' AUTO_INCREMENT=43 ;

--
-- 转存表中的数据 `t8_sms_otp`
--

INSERT INTO `t8_sms_otp` (`id`, `type`, `mobile`, `user_id`, `code`, `error`, `status`, `send_client`, `send_time`, `verify_time`, `expire_time`) VALUES
(2, 'reg', '13538089871', 0, '6117', '', 2, 'web', '2016-02-26 08:35:07', '2016-02-26 16:36:17', '2016-02-26 16:40:07'),
(3, 'reg', '13538089871', 0, '5145', '', 2, 'web', '2016-02-26 08:37:26', '2016-02-26 16:38:19', '2016-02-26 16:42:26'),
(4, 'reg', '13538089871', 0, '4129', '', 2, 'web', '2016-02-26 08:43:06', '2016-02-26 16:43:44', '2016-02-26 16:48:06'),
(5, 'reg', '13538089871', 0, '8368', '驗證碼已超時', -2, 'web', '2016-02-26 08:50:47', NULL, '2016-02-26 16:55:47'),
(6, 'reg', '13538089871', 0, '8234', '', 2, 'web', '2016-02-26 08:55:59', '2016-02-26 16:56:44', '2016-02-26 17:00:59'),
(7, 'unlock', '56789124', 0, '3813', '', 2, 'web', '2016-02-26 09:01:18', '2016-02-26 17:03:22', '2016-02-26 17:06:18'),
(8, 'unlock', '56789124', 0, '7553', '', 2, 'web', '2016-02-26 09:08:44', '2016-02-26 17:13:19', '2016-02-26 17:13:44'),
(9, 'unlock', '56789125', 0, '5564', '', 1, 'web', '2016-02-26 09:21:18', NULL, '2016-02-26 17:26:18'),
(10, 'reg', '56655443', 0, '8878', '', 1, 'web', '2016-02-29 10:02:56', NULL, '2016-02-29 18:07:56'),
(11, 'reg', '53322113', 0, '5488', '', 1, 'web', '2016-02-29 10:03:53', NULL, '2016-02-29 18:08:53'),
(12, 'reg', '56655443', 0, '3741', '', 1, 'web', '2016-02-29 10:04:48', NULL, '2016-02-29 18:09:48'),
(13, 'reg', '56655443', 0, '9542', '', 1, 'web', '2016-02-29 10:21:50', NULL, '2016-02-29 18:26:50'),
(14, 'reg', '51122334', 0, '8602', '', 1, 'web', '2016-02-29 10:23:02', NULL, '2016-02-29 18:28:02'),
(15, 'reg', '52233445', 0, '6818', '', 1, 'web', '2016-02-29 10:25:05', NULL, '2016-02-29 18:30:05'),
(16, 'reg', '52233441', 0, '8871', '', 1, 'web', '2016-02-29 10:26:33', NULL, '2016-02-29 18:31:33'),
(17, 'reg', '59922331', 0, '9669', '', 1, 'web', '2016-03-01 01:43:01', NULL, '2016-03-01 09:48:01'),
(18, 'reg', '54433663', 0, '6383', '', 1, 'web', '2016-03-01 01:57:07', NULL, '2016-03-01 10:02:07'),
(19, 'reg', '56677443', 0, '1735', '', 1, 'web', '2016-03-01 02:05:15', NULL, '2016-03-01 10:10:15'),
(20, 'reg', '54433221', 0, '6957', '', 2, 'web', '2016-03-01 02:15:05', '2016-03-01 10:15:36', '2016-03-01 10:20:05'),
(21, 'reg', '91122334', 0, '7363', '', 1, 'web', '2016-03-01 03:03:41', NULL, '2016-03-01 11:08:41'),
(22, 'reg', '93322114', 0, '0941', '', 2, 'web', '2016-03-01 03:04:57', '2016-03-01 11:05:37', '2016-03-01 11:09:57'),
(23, 'reg', '54433222', 0, '5846', '', 1, 'web', '2016-03-01 07:15:11', NULL, '2016-03-01 15:20:11'),
(24, 'unlock', '54433221', 0, '6035', '', 2, 'web', '2016-03-01 08:49:27', '2016-03-01 16:50:08', '2016-03-01 16:54:27'),
(25, 'unlock', '93322114', 0, '0804', '', 1, 'web', '2016-03-01 08:53:11', NULL, '2016-03-01 16:58:11'),
(26, 'unlock', '93322114', 0, '2786', '', 2, 'web', '2016-03-01 08:56:04', '2016-03-01 16:56:35', '2016-03-01 17:01:04'),
(27, 'reg', '54433222', 0, '7784', '', 2, 'web', '2016-03-01 09:08:30', '2016-03-01 17:08:55', '2016-03-01 17:13:30'),
(28, 'reg', '54433222', 0, '3980', '', 2, 'web', '2016-03-01 09:09:33', '2016-03-01 17:09:55', '2016-03-01 17:14:33'),
(29, 'unlock', '54433222', 0, '2166', '', 1, 'web', '2016-03-01 09:11:48', NULL, '2016-03-01 17:16:48'),
(30, 'unlock', '54433222', 0, '1712', '', 1, 'web', '2016-03-01 09:12:56', NULL, '2016-03-01 17:17:56'),
(31, 'reg', '55443322', 0, '1964', '', 1, 'web', '2016-03-01 09:23:13', NULL, '2016-03-01 17:28:13'),
(32, 'unlock', '54433221', 0, '3433', '', 2, 'web', '2016-03-02 09:25:37', '2016-03-02 17:25:57', '2016-03-02 17:30:37'),
(33, 'unlock', '54433221', 0, '1220', '', 2, 'web', '2016-03-02 10:02:01', '2016-03-02 18:02:16', '2016-03-02 18:07:01'),
(34, 'unlock', '54433221', 0, '3287', '', 2, 'web', '2016-03-03 01:10:12', '2016-03-03 09:10:43', '2016-03-03 09:15:12'),
(35, 'unlock', '56789123', 0, '6097', '', 2, 'web', '2016-03-03 01:32:09', '2016-03-03 09:32:40', '2016-03-03 09:37:09'),
(36, 'unlock', '54433221', 0, '7214', '', 2, 'web', '2016-03-03 03:57:23', '2016-03-03 11:57:50', '2016-03-03 12:02:23'),
(37, 'unlock', '54433221', 0, '2183', '', 2, 'web', '2016-03-03 05:49:31', '2016-03-03 13:49:48', '2016-03-03 13:54:31'),
(38, 'unlock', '54433221', 0, '5725', '', 2, 'web', '2016-03-04 01:08:36', '2016-03-04 09:08:49', '2016-03-04 09:13:36'),
(39, 'unlock', '54433221', 0, '0889', '', 2, 'web', '2016-03-04 05:29:54', '2016-03-04 13:30:08', '2016-03-04 13:34:54'),
(40, 'unlock', '56789124', 0, '8680', '', 2, 'web', '2016-03-04 06:43:28', '2016-03-04 14:43:45', '2016-03-04 14:48:28'),
(41, 'unlock', '54433221', 0, '9578', '', 2, 'web', '2016-03-07 01:26:26', '2016-03-07 09:26:36', '2016-03-07 09:31:26'),
(42, 'unlock', '54433221', 0, '8884', '', 2, 'web', '2016-03-07 01:28:20', '2016-03-07 09:28:40', '2016-03-07 09:33:20');

-- --------------------------------------------------------

--
-- 表的结构 `t8_trade`
--

CREATE TABLE IF NOT EXISTS `t8_trade` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '交易ID',
  `trade_no` char(20) NOT NULL DEFAULT '' COMMENT '交易编号',
  `seller_id` int(11) NOT NULL DEFAULT '0' COMMENT '卖家ID',
  `buyer_id` int(11) NOT NULL DEFAULT '0' COMMENT '买家ID',
  `goods_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商品编号',
  `goods_price` int(11) NOT NULL DEFAULT '0' COMMENT '商品价格',
  `trade_num` smallint(4) NOT NULL COMMENT '交易笔数',
  `trade_price` int(11) NOT NULL DEFAULT '0' COMMENT '交易价格',
  `trade_fee` int(11) NOT NULL DEFAULT '0' COMMENT '交易手续费',
  `is_exclusive` tinyint(1) NOT NULL DEFAULT '0' COMMENT '卖家发起的专属',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '交易状态 0=等待付款 1=移交 2=领收 3=评价 4=完成 5=买家取消 6=卖家取消 -1=取消',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  PRIMARY KEY (`id`),
  KEY `trade_no` (`trade_no`) USING BTREE,
  KEY `seller_id` (`seller_id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `siie` (`seller_id`,`is_exclusive`),
  KEY `biie` (`buyer_id`,`is_exclusive`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='交易表' AUTO_INCREMENT=25 ;

--
-- 转存表中的数据 `t8_trade`
--

INSERT INTO `t8_trade` (`id`, `trade_no`, `seller_id`, `buyer_id`, `goods_id`, `goods_price`, `trade_num`, `trade_price`, `trade_fee`, `is_exclusive`, `status`, `create_time`) VALUES
(1, 'D354654651', 30668, 12222, 1, 100, 0, 90, 0, 0, 4, '2016-02-24 09:18:50'),
(2, 'D354654651', 30668, 12252, 1, 100, 0, 100, 0, 0, 4, '2016-02-24 09:18:50'),
(3, 'D3546542134', 1001, 1002, 2, 200, 0, 100, 0, 0, 2, '2016-02-24 09:18:50'),
(4, 'D3546542135', 1001, 1002, 2, 200, 0, 100, 0, 0, 4, '2016-02-24 09:18:50'),
(5, 'D3546df51', 30668, 12252, 15, 100, 0, 100, 0, 0, 4, '2016-02-24 09:18:50'),
(6, 'D3546542169', 1001, 1002, 6, 200, 0, 100, 0, 0, 2, '2016-02-24 09:18:50'),
(7, 'D3855ss55', 1001, 1002, 8, 200, 0, 100, 0, 0, 4, '2016-02-24 09:18:50'),
(8, '0', 1001, 1, 1, 0, 1, 100, 6, 1, 0, '2016-03-02 06:45:38'),
(9, '', 1001, 12, 1, 1500, 1, 90, 6, 1, 0, '2016-03-04 09:09:31'),
(10, '', 1001, 12, 1, 1500, 1, 1000, 60, 1, 0, '2016-03-04 09:15:59'),
(11, '1603041700001195', 1001, 12, 1, 1500, 1, 1000, 60, 1, 0, '2016-03-04 09:16:25'),
(12, '1603041700001299', 1001, 12, 1, 1500, 1, 1000, 60, 1, 0, '2016-03-04 09:24:04'),
(13, '1603041700001303', 1002, 1001, 6, 1000, 1, 0, 0, 0, 0, '2016-03-04 09:25:52'),
(14, '1603041700001407', 1002, 1001, 6, 1000, 1, 0, 0, 0, 0, '2016-03-04 09:28:57'),
(15, '1603041700001511', 1002, 1001, 6, 900, 1, 900, 0, 0, 0, '2016-03-04 09:37:48'),
(16, '1603041700001615', 1002, 1001, 6, 900, 2, 900, 0, 0, 0, '2016-03-04 09:40:01'),
(17, '1603041700001719', 1001, 0, 1, 1000, 1, 1000, 60, 1, 0, '2016-03-04 09:41:23'),
(18, '1603041700001823', 1001, 12, 1, 1000, 1, 1000, 60, 1, 0, '2016-03-04 09:43:08'),
(19, '1603041700001927', 1002, 1001, 6, 900, 2, 900, 0, 0, 0, '2016-03-04 09:43:43'),
(20, '1603041700002096', 1002, 1001, 6, 900, 2, 900, 0, 0, 0, '2016-03-04 09:45:26'),
(21, '1603041700002100', 1002, 1001, 6, 900, 2, 900, 108, 0, 0, '2016-03-04 09:48:23'),
(22, '1603041700002204', 1001, 1002, 1, 1000, 1, 1000, 60, 1, 0, '2016-03-04 09:49:22'),
(23, '1603041700002308', 1001, 1002, 1, 1000, 3, 800, 144, 1, 0, '2016-03-04 09:49:50'),
(24, '1603041800002413', 1001, 12, 1, 1000, 1, 1000, 60, 1, 0, '2016-03-04 10:47:20');

-- --------------------------------------------------------

--
-- 表的结构 `t8_trade_schedule`
--

CREATE TABLE IF NOT EXISTS `t8_trade_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trade_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '交易ID',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '当前状态',
  `action_type` enum('system','service','seller','buyer') NOT NULL DEFAULT 'system' COMMENT '操作类型',
  `action_user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '操作人员ID',
  `action_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `trade_id` (`trade_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='交易时间' AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `t8_trade_schedule`
--

INSERT INTO `t8_trade_schedule` (`id`, `trade_id`, `state`, `action_type`, `action_user_id`, `action_time`) VALUES
(1, 8, 0, 'seller', 1001, '2016-03-02 06:45:38'),
(2, 11, 0, 'seller', 1001, '2016-03-04 09:16:25'),
(3, 12, 0, 'seller', 1001, '2016-03-04 09:24:05'),
(4, 13, 0, 'buyer', 1001, '2016-03-04 09:25:52'),
(5, 14, 0, 'buyer', 1001, '2016-03-04 09:28:57'),
(6, 15, 0, 'buyer', 1001, '2016-03-04 09:37:48'),
(7, 16, 0, 'buyer', 1001, '2016-03-04 09:40:01'),
(8, 17, 0, 'seller', 1001, '2016-03-04 09:41:23'),
(9, 18, 0, 'seller', 1001, '2016-03-04 09:43:08'),
(10, 19, 0, 'buyer', 1001, '2016-03-04 09:43:43'),
(11, 20, 0, 'buyer', 1001, '2016-03-04 09:45:26'),
(12, 21, 0, 'buyer', 1001, '2016-03-04 09:48:23'),
(13, 22, 0, 'seller', 1001, '2016-03-04 09:49:22'),
(14, 23, 0, 'seller', 1001, '2016-03-04 09:49:50'),
(15, 24, 0, 'seller', 1001, '2016-03-04 10:47:20');

-- --------------------------------------------------------

--
-- 表的结构 `t8_user`
--

CREATE TABLE IF NOT EXISTS `t8_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '会员ID',
  `account` varchar(20) NOT NULL DEFAULT '' COMMENT '帐号',
  `mobile` char(20) NOT NULL DEFAULT '' COMMENT '移动电话',
  `tel` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `email` varchar(320) NOT NULL DEFAULT '' COMMENT '邮箱',
  `idcard` varchar(20) NOT NULL DEFAULT '' COMMENT '身份证',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `is_show_realname` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否顯示真實姓名',
  `login_pwd` char(32) NOT NULL DEFAULT '' COMMENT '登入密码',
  `payment_pwd` char(32) NOT NULL DEFAULT '' COMMENT '支付密码',
  `deny_access` set('login','publish','buy','drawn') NOT NULL DEFAULT '' COMMENT '禁止操作',
  `authenticate` set('mobile','idcard','passport','tel') NOT NULL DEFAULT '' COMMENT '用户认证',
  `badges` set('card_seller') NOT NULL DEFAULT '' COMMENT '會員徽章',
  `country_code` char(3) NOT NULL DEFAULT '' COMMENT '国家编码',
  `reg_client` enum('web','mobile','andorid','ios') NOT NULL DEFAULT 'web' COMMENT '注册客户端',
  `reg_ip` char(15) NOT NULL DEFAULT '' COMMENT '註冊IP',
  `reg_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '注册时间',
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  KEY `email` (`email`(255)),
  KEY `mobile` (`mobile`),
  KEY `reg_time` (`reg_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='会员表' AUTO_INCREMENT=1007 ;

--
-- 转存表中的数据 `t8_user`
--

INSERT INTO `t8_user` (`id`, `account`, `mobile`, `tel`, `email`, `idcard`, `realname`, `nickname`, `is_show_realname`, `login_pwd`, `payment_pwd`, `deny_access`, `authenticate`, `badges`, `country_code`, `reg_client`, `reg_ip`, `reg_time`) VALUES
(1001, 'modd', '56789123', '', '', '', '賤人威', '賤人威', 0, 'e004864254882b40ff22c026a02b6ed1', 'e004864254882b40ff22c026a02b6ed1', '', '', '', 'HK', 'web', '127.0.0.1', '2016-02-18 07:26:36'),
(1002, 'luis', '56789124', '', '', '', '小俊', '单身汪', 0, '862ba9ebb1445546469bf4f64d12467a', '862ba9ebb1445546469bf4f64d12467a', '', '', '', 'HK', 'web', '', '2016-02-24 09:13:36'),
(1003, '123456', '56789125', '', '', '', 'nba', '', 0, 'f51404c13d192fd05a0dd2899daab1dd', '483111767e4dcc8beca006e0ae7d8bd0', '', 'mobile', '', 'HK', 'web', '112.120.145.138', '2016-02-26 08:56:44'),
(1004, 'qwerty', '54433221', '', '', '', 'qwerty', '', 0, 'ef85cc5899076b9684d534b7ce691e92', '68be4a89d390346885e556913bdc61f0', '', 'mobile', '', 'HK', 'web', '112.120.145.138', '2016-03-01 02:15:37'),
(1005, 'qwerty2', '93322114', '', '', '', 'qwerty', '', 0, '5ba98a5dfbc8109158b1a1088291500f', '850373ff3095cc0a1fe3ebec70b2c552', '', 'mobile', '', 'HK', 'web', '112.120.145.138', '2016-03-01 03:05:38'),
(1006, 'qwerty3', '54433222', '', '', '', 'qwerty', '', 0, 'e82f670d204fc6d28e330be55e3f1fae', '8bc537b85b67144963f0110ec97545aa', '', 'mobile', '', 'HK', 'web', '112.120.145.138', '2016-03-01 09:09:55');

-- --------------------------------------------------------

--
-- 表的结构 `t8_user_card_setting`
--

CREATE TABLE IF NOT EXISTS `t8_user_card_setting` (
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `card_id` int(11) NOT NULL DEFAULT '0' COMMENT '卡类ID',
  `denom_id` int(11) NOT NULL DEFAULT '0' COMMENT '0=卡类设置',
  `stock_alarm` int(11) NOT NULL DEFAULT '0' COMMENT '库存提醒',
  `note` text NOT NULL COMMENT '说明',
  PRIMARY KEY (`user_id`,`card_id`,`denom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='卡商卡类设置';

--
-- 转存表中的数据 `t8_user_card_setting`
--

INSERT INTO `t8_user_card_setting` (`user_id`, `card_id`, `denom_id`, `stock_alarm`, `note`) VALUES
(1001, 1, 0, 0, 'testset撒的发生的'),
(1001, 1, 1, 100, 'jkjk'),
(1001, 2, 0, 0, ''),
(1001, 3, 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `t8_user_certify_apply`
--

CREATE TABLE IF NOT EXISTS `t8_user_certify_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '申请ID',
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `real_name` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `credential_type` enum('idcard','passport','hvps') NOT NULL DEFAULT 'idcard' COMMENT '证件类型',
  `credential_no` varchar(20) NOT NULL DEFAULT '' COMMENT '证件编号',
  `credential_date` date NOT NULL COMMENT '证件日期',
  `credential_address` varchar(300) NOT NULL DEFAULT '' COMMENT '证件地址',
  `apply_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '申请时间',
  `audit_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态/结果 0=等待 1=通过 -1=失败',
  `audit_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '审核人员ID',
  `audit_time` datetime NOT NULL COMMENT '审核时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='实名认证申请' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t8_user_images`
--

CREATE TABLE IF NOT EXISTS `t8_user_images` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` bigint(20) NOT NULL COMMENT '会员ID',
  `save_path` varchar(60) NOT NULL DEFAULT '' COMMENT '图片路径',
  `save_name` varchar(60) NOT NULL COMMENT '图片文件名',
  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '上传时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='会员图片集' AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `t8_user_images`
--

INSERT INTO `t8_user_images` (`id`, `user_id`, `save_path`, `save_name`, `post_time`) VALUES
(2, 1001, '/upload/goods/20160307/', '56dd273d67d93.png', '2016-03-07 07:01:40'),
(3, 1001, '/upload/goods/20160307/', '56dd283c318b7.png', '2016-03-07 07:05:55'),
(4, 1001, '/upload/goods/20160307/', '56dd2986a770e.png', '2016-03-07 07:11:26'),
(5, 1001, '/upload/goods/20160307/', '56dd29c0a784b.png', '2016-03-07 07:12:24'),
(6, 1001, '/upload//aaa/20160307/', '56dd2bc771ba4.png', '2016-03-07 07:21:03'),
(7, 1001, 'aaa/aaa/20160307/56dd2c253e8c0.png', '56dd2c253e8c0.png', '2016-03-07 07:22:36'),
(8, 1001, 'aaa/20160307/56dd2c3ee75ce.png', '56dd2c3ee75ce.png', '2016-03-07 07:23:02'),
(9, 1001, 'aaa/20160307/56dd2ce8400ca.png', '56dd2ce8400ca.png', '2016-03-07 07:25:51'),
(10, 1001, 'aaa/20160307/56dd2cf910ad3.png', '56dd2cf910ad3.png', '2016-03-07 07:26:08'),
(11, 1001, 'aaa/20160307/56dd2d030595d.png', '56dd2d030595d.png', '2016-03-07 07:26:18'),
(12, 1001, 'aaa/20160307/56dd3836a1988.png', '56dd3836a1988.png', '2016-03-07 08:14:06'),
(13, 1004, 'aaa/20160307/56dd392f13482.jpg', '56dd392f13482.jpg', '2016-03-07 08:19:14'),
(14, 1004, 'goods/20160307/56dd3aa1eaa4d.jpg', '56dd3aa1eaa4d.jpg', '2016-03-07 08:25:25');

-- --------------------------------------------------------

--
-- 表的结构 `t8_user_login_log`
--

CREATE TABLE IF NOT EXISTS `t8_user_login_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `login_type` enum('account','mobile','email','facebook') NOT NULL DEFAULT 'account' COMMENT '登入类型',
  `login_result` tinyint(1) NOT NULL DEFAULT '0' COMMENT '登入结果 1=成功 0=失败',
  `faild_cause` enum('invalid_pwd','deny_login','invalid_ip','') NOT NULL DEFAULT '' COMMENT '失败原因',
  `session_id` char(32) NOT NULL DEFAULT '' COMMENT '会员ID',
  `user_agent` varchar(200) NOT NULL DEFAULT '' COMMENT '浏览器信息',
  `accept_language` varchar(50) NOT NULL DEFAULT '' COMMENT '浏览器语言',
  `login_client` enum('web','mobile','andorid','ios') NOT NULL DEFAULT 'web' COMMENT '登入客户端',
  `login_ip` char(15) NOT NULL DEFAULT '' COMMENT '登入IP',
  `login_country` char(3) NOT NULL DEFAULT '' COMMENT '登入地区',
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登入时间',
  PRIMARY KEY (`id`),
  KEY `login_ip` (`login_ip`),
  KEY `login_time` (`login_time`),
  KEY `user_id` (`user_id`),
  KEY `uilr` (`user_id`,`login_result`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=198 ;

--
-- 转存表中的数据 `t8_user_login_log`
--

INSERT INTO `t8_user_login_log` (`id`, `user_id`, `login_type`, `login_result`, `faild_cause`, `session_id`, `user_agent`, `accept_language`, `login_client`, `login_ip`, `login_country`, `login_time`) VALUES
(18, 1001, 'mobile', 0, 'invalid_ip', 'vo9hdabdnects6vtcp04vicgl2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-25 09:05:42'),
(19, 1001, 'mobile', 0, 'invalid_ip', 'vo9hdabdnects6vtcp04vicgl2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-25 09:51:04'),
(20, 1001, 'mobile', 1, '', 'vo9hdabdnects6vtcp04vicgl2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-25 09:52:31'),
(21, 1001, 'mobile', 0, 'deny_login', 'vo9hdabdnects6vtcp04vicgl2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-25 09:54:28'),
(22, 1001, 'mobile', 0, 'deny_login', 'vo9hdabdnects6vtcp04vicgl2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-25 09:54:40'),
(23, 1001, 'mobile', 0, 'deny_login', 'vo9hdabdnects6vtcp04vicgl2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-25 09:55:05'),
(24, 1001, 'mobile', 0, 'deny_login', 'vo9hdabdnects6vtcp04vicgl2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-25 09:55:41'),
(25, 1001, 'mobile', 1, '', 'vo9hdabdnects6vtcp04vicgl2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-25 10:07:17'),
(26, 1003, 'mobile', 0, 'invalid_pwd', 'fvuodf8nnlrr4p94o46drajso7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-26 08:58:56'),
(27, 1003, 'mobile', 0, 'invalid_ip', 'fvuodf8nnlrr4p94o46drajso7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-26 08:59:44'),
(28, 1003, 'mobile', 0, 'invalid_ip', 'fvuodf8nnlrr4p94o46drajso7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-26 09:12:22'),
(29, 1003, 'mobile', 1, '', 'fvuodf8nnlrr4p94o46drajso7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-26 09:13:19'),
(30, 1001, 'account', 1, '', 'bcrr7ivp4pl9p2djss1atn2ja5', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 08:41:31'),
(31, 1001, 'account', 1, '', 'bcrr7ivp4pl9p2djss1atn2ja5', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 08:41:42'),
(32, 1001, 'account', 1, '', 'bcrr7ivp4pl9p2djss1atn2ja5', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 08:48:47'),
(33, 1001, 'account', 1, '', 'bcrr7ivp4pl9p2djss1atn2ja5', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 08:58:58'),
(34, 1001, 'account', 1, '', 'bcrr7ivp4pl9p2djss1atn2ja5', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 08:59:05'),
(35, 1001, 'account', 1, '', 'bcrr7ivp4pl9p2djss1atn2ja5', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 08:59:14'),
(36, 1001, 'account', 1, '', 'bt0shbapa3nm6b8squfksbl287', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:01:17'),
(37, 1001, 'account', 1, '', 'par4k1ce2mkq44b44anre8e7v6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:05:00'),
(38, 1001, 'account', 1, '', 'par4k1ce2mkq44b44anre8e7v6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:06:53'),
(39, 1001, 'account', 1, '', 'par4k1ce2mkq44b44anre8e7v6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:06:55'),
(40, 1001, 'account', 1, '', 'par4k1ce2mkq44b44anre8e7v6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:06:57'),
(41, 1001, 'account', 1, '', 'par4k1ce2mkq44b44anre8e7v6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:06:58'),
(42, 1001, 'account', 1, '', 'par4k1ce2mkq44b44anre8e7v6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:07:00'),
(43, 1001, 'account', 1, '', 'par4k1ce2mkq44b44anre8e7v6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:07:01'),
(44, 1001, 'account', 1, '', 'par4k1ce2mkq44b44anre8e7v6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:07:03'),
(45, 1001, 'account', 1, '', '4u63dh2hegmbejoia333g465u0', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:08:39'),
(46, 1001, 'account', 1, '', '4u63dh2hegmbejoia333g465u0', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:10:14'),
(47, 1001, 'account', 1, '', 'r9953i5b0d1cpqgqneqe91fmd3', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:10:36'),
(48, 1001, 'account', 1, '', 'gaoc0lh1jg1v8acl7i2mmpriq3', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:13:40'),
(49, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:21:06'),
(50, 1001, 'account', 0, 'invalid_ip', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:40:10'),
(51, 1001, 'account', 0, 'invalid_ip', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:41:30'),
(52, 1001, 'account', 0, 'invalid_ip', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:42:35'),
(53, 1001, 'account', 0, 'invalid_ip', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:42:45'),
(54, 1001, 'account', 0, 'invalid_ip', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:44:12'),
(55, 1001, 'account', 0, 'invalid_ip', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:45:26'),
(56, 1001, 'account', 0, 'invalid_ip', 't9ujukb3va1rb5fjcrc8d4sqs1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 09:45:39'),
(57, 1003, 'mobile', 0, 'invalid_ip', 'ke10g7k4r1htqslb155c3os9t3', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-29 09:52:47'),
(58, 1001, 'mobile', 0, 'invalid_ip', 'ke10g7k4r1htqslb155c3os9t3', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-02-29 09:52:58'),
(59, 1001, 'account', 0, 'invalid_ip', 't9ujukb3va1rb5fjcrc8d4sqs1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 10:23:23'),
(60, 1001, 'account', 0, 'invalid_ip', 't9ujukb3va1rb5fjcrc8d4sqs1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 10:24:47'),
(61, 1001, 'account', 0, 'invalid_ip', 't9ujukb3va1rb5fjcrc8d4sqs1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 10:27:41'),
(62, 1001, 'account', 0, 'invalid_ip', 't9ujukb3va1rb5fjcrc8d4sqs1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 10:27:53'),
(63, 1001, 'account', 1, '', 't9ujukb3va1rb5fjcrc8d4sqs1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 10:28:24'),
(64, 1001, 'account', 1, '', 'p29bp6n5e3dnlfugb4snjd6o71', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-02-29 10:47:06'),
(65, 1001, 'account', 1, '', '5r090a112rttr92o9smhm7rog1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-01 01:21:03'),
(66, 1001, 'account', 1, '', 'p29bp6n5e3dnlfugb4snjd6o71', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-01 01:45:22'),
(67, 1001, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 03:10:51'),
(68, 1001, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 03:10:53'),
(69, 1001, 'account', 0, 'invalid_pwd', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 03:50:31'),
(70, 1001, 'account', 0, 'invalid_pwd', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 03:50:34'),
(71, 1001, 'account', 0, 'invalid_pwd', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 03:50:40'),
(72, 1001, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 03:50:54'),
(73, 1001, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 05:45:34'),
(74, 1001, 'account', 1, '', 'p29bp6n5e3dnlfugb4snjd6o71', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-01 06:05:42'),
(75, 1001, 'account', 0, 'invalid_pwd', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 06:14:34'),
(76, 1001, 'account', 1, '', '5r090a112rttr92o9smhm7rog1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-01 06:18:45'),
(77, 1001, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 06:15:07'),
(78, 1001, 'account', 0, 'invalid_pwd', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 06:41:04'),
(79, 1001, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 07:06:37'),
(80, 1001, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 07:07:32'),
(81, 1001, 'account', 1, '', '5r090a112rttr92o9smhm7rog1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-01 08:19:19'),
(82, 1001, 'account', 1, '', '5r090a112rttr92o9smhm7rog1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-01 08:19:21'),
(83, 1004, 'account', 0, 'invalid_ip', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 08:32:19'),
(84, 1004, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 08:50:08'),
(85, 1005, 'account', 0, 'invalid_ip', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 08:53:06'),
(86, 1005, 'account', 0, 'invalid_ip', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 08:55:35'),
(87, 1005, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 08:56:35'),
(88, 1005, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 09:10:40'),
(89, 1006, 'account', 0, 'invalid_ip', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 09:11:10'),
(90, 1001, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 09:21:22'),
(91, 1001, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 09:46:45'),
(92, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-01 09:57:08'),
(93, 1001, 'account', 1, '', '3ai80osbc81iifm0houna4p2s7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-01 10:04:20'),
(94, 1001, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 10:01:44'),
(95, 1001, 'account', 1, '', 'vv6cmm52hqaq0kecbfgpvupgv5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 10:04:08'),
(96, 1001, 'account', 1, '', 'gl08o2lq0li4g79nhpomi1fu74', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-01 10:05:00'),
(97, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-02 00:49:37'),
(98, 1001, 'account', 1, '', 'p29bp6n5e3dnlfugb4snjd6o71', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-02 01:19:46'),
(99, 1001, 'account', 1, '', '3ai80osbc81iifm0houna4p2s7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-02 01:22:46'),
(100, 1004, 'account', 0, 'invalid_pwd', 'gl08o2lq0li4g79nhpomi1fu74', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-02 01:35:39'),
(101, 1004, 'account', 0, 'invalid_pwd', 'gl08o2lq0li4g79nhpomi1fu74', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-02 01:36:14'),
(102, 1004, 'account', 1, '', 'gl08o2lq0li4g79nhpomi1fu74', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-02 01:36:26'),
(103, 1001, 'account', 1, '', 'bunqjohif045s5j1dv5mletc77', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-02 02:14:08'),
(104, 1004, 'account', 1, '', 'gl08o2lq0li4g79nhpomi1fu74', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-02 02:29:30'),
(105, 1003, 'mobile', 0, 'invalid_ip', 'kthbdhrn9gbgurschotftdt6n7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-02 03:50:45'),
(106, 1004, 'account', 1, '', 'gl08o2lq0li4g79nhpomi1fu74', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-02 05:39:43'),
(107, 1001, 'account', 1, '', 'p29bp6n5e3dnlfugb4snjd6o71', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-02 05:43:36'),
(108, 1003, 'mobile', 0, 'invalid_ip', 'kthbdhrn9gbgurschotftdt6n7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-02 05:51:04'),
(109, 1003, 'mobile', 0, 'invalid_ip', 'kthbdhrn9gbgurschotftdt6n7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-02 07:22:16'),
(110, 1001, 'account', 1, '', '3ai80osbc81iifm0houna4p2s7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-02 07:35:10'),
(111, 1001, 'account', 1, '', 'kthbdhrn9gbgurschotftdt6n7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-02 07:46:14'),
(112, 1001, 'account', 1, '', 'kthbdhrn9gbgurschotftdt6n7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-02 08:20:58'),
(113, 1001, 'account', 1, '', 'kthbdhrn9gbgurschotftdt6n7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-02 08:24:23'),
(114, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-02 08:41:43'),
(115, 1004, 'account', 1, '', 'jn8l0qlvodkr10ml4p901b2ra0', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-02 08:46:03'),
(116, 1001, 'account', 1, '', 'kthbdhrn9gbgurschotftdt6n7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-02 08:50:20'),
(117, 1001, 'account', 0, 'invalid_ip', 'kthbdhrn9gbgurschotftdt6n7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-02 09:14:55'),
(118, 1001, 'account', 0, 'invalid_ip', 'kthbdhrn9gbgurschotftdt6n7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-02 09:15:42'),
(119, 1001, 'account', 0, 'invalid_ip', 'kthbdhrn9gbgurschotftdt6n7', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-02 09:16:37'),
(120, 1004, 'account', 0, 'invalid_ip', 'jn8l0qlvodkr10ml4p901b2ra0', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-02 09:24:14'),
(121, 1004, 'account', 1, '', 'jn8l0qlvodkr10ml4p901b2ra0', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-02 09:25:57'),
(122, 1004, 'account', 0, 'invalid_ip', '1d59ki51q1pkbiclklustnbug4', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-02 10:00:05'),
(123, 1004, 'account', 1, '', '1d59ki51q1pkbiclklustnbug4', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-02 10:02:16'),
(124, 1004, 'account', 0, 'invalid_ip', '1d59ki51q1pkbiclklustnbug4', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-03 01:09:57'),
(125, 1004, 'account', 1, '', '1d59ki51q1pkbiclklustnbug4', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-03 01:10:43'),
(126, 1001, 'account', 0, 'invalid_ip', '3ai80osbc81iifm0houna4p2s7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-03 01:31:44'),
(127, 1001, 'account', 1, '', '3ai80osbc81iifm0houna4p2s7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-03 01:32:40'),
(128, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-03 02:19:07'),
(129, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-03 02:21:59'),
(130, 1001, 'account', 1, '', 'p29bp6n5e3dnlfugb4snjd6o71', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-03 02:21:48'),
(131, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-03 02:22:25'),
(132, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-03 02:22:48'),
(133, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-03 02:23:13'),
(134, 1001, 'account', 1, '', '3ai80osbc81iifm0houna4p2s7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-03 03:55:32'),
(135, 1004, 'account', 0, 'invalid_ip', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-03 03:57:18'),
(136, 1004, 'account', 1, '', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-03 03:57:50'),
(137, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-03 05:10:42'),
(138, 1004, 'account', 0, 'invalid_ip', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-03 05:49:21'),
(139, 1004, 'account', 1, '', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-03 05:49:48'),
(140, 1001, 'account', 1, '', 'p29bp6n5e3dnlfugb4snjd6o71', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-03 06:22:38'),
(141, 1001, 'account', 1, '', '5qpkfev1dvav4o2vu5gkq4p441', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-03 07:12:09'),
(142, 1001, 'account', 1, '', '5qpkfev1dvav4o2vu5gkq4p441', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-03 07:12:10'),
(143, 1001, 'account', 1, '', 'p29bp6n5e3dnlfugb4snjd6o71', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-03 09:58:15'),
(144, 1001, 'account', 1, '', '3ai80osbc81iifm0houna4p2s7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-03 11:10:25'),
(145, 1001, 'account', 1, '', 'p29bp6n5e3dnlfugb4snjd6o71', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-03 11:09:40'),
(146, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 00:18:59'),
(147, 1004, 'account', 0, 'invalid_ip', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-04 01:08:24'),
(148, 1004, 'account', 0, 'invalid_ip', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-04 01:08:25'),
(149, 1004, 'account', 1, '', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-04 01:08:49'),
(150, 1001, 'account', 1, '', '3ai80osbc81iifm0houna4p2s7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-04 01:12:48'),
(151, 1001, 'account', 1, '', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 01:18:56'),
(152, 1001, 'account', 1, '', '3ai80osbc81iifm0houna4p2s7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-04 02:42:49'),
(153, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 03:23:49'),
(154, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 03:31:54'),
(155, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 03:32:46'),
(156, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 03:35:23'),
(157, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 03:37:10'),
(158, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 03:38:05'),
(159, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 05:11:52'),
(160, 1004, 'account', 0, 'invalid_ip', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-04 05:29:40'),
(161, 1004, 'account', 1, '', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-04 05:30:08'),
(162, 1001, 'account', 1, '', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:00:23'),
(163, 1001, 'account', 1, '', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:05:26'),
(164, 1001, 'account', 1, '', '3ai80osbc81iifm0houna4p2s7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-04 06:19:49'),
(165, 1002, 'account', 0, 'invalid_pwd', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:21:13'),
(166, 1002, 'account', 0, 'invalid_pwd', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:21:27'),
(167, 1002, 'account', 0, 'invalid_pwd', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:22:01'),
(168, 1002, 'account', 0, 'invalid_pwd', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:22:40'),
(169, 1002, 'account', 0, 'invalid_pwd', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:26:55'),
(170, 1002, 'account', 0, 'invalid_pwd', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:27:47'),
(171, 1002, 'account', 0, 'invalid_pwd', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:35:02'),
(172, 1002, 'account', 0, 'invalid_pwd', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:35:10'),
(173, 1002, 'account', 0, 'invalid_pwd', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:35:29'),
(174, 1002, 'account', 0, 'invalid_pwd', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:37:07'),
(175, 1002, 'account', 1, '', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:38:52'),
(176, 1002, 'account', 0, 'invalid_ip', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:42:49'),
(177, 1002, 'account', 1, '', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:43:45'),
(178, 1002, 'account', 1, '', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:48:36'),
(179, 1002, 'account', 1, '', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:51:50'),
(180, 1002, 'account', 1, '', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:53:29'),
(181, 1002, 'account', 1, '', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:56:05'),
(182, 1002, 'account', 1, '', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:56:20'),
(183, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 06:58:06'),
(184, 1001, 'account', 1, '', 'fnqrk6iqs9hv1ol77bigt13176', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 07:06:53'),
(185, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-04 08:24:30'),
(186, 1001, 'account', 1, '', '3ai80osbc81iifm0houna4p2s7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-04 09:21:42'),
(187, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.103 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-07 00:56:37'),
(188, 1001, 'account', 1, '', '3ai80osbc81iifm0houna4p2s7', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0', 'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3', 'web', '127.0.0.1', '', '2016-03-07 01:01:13'),
(189, 1001, 'account', 1, '', 'crc02jfrd07cc9kqvvre8nhkh5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-07 01:01:16'),
(190, 1004, 'account', 0, 'invalid_ip', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-07 01:26:12'),
(191, 1004, 'account', 1, '', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-07 01:26:36'),
(192, 1004, 'account', 0, 'invalid_ip', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-07 01:28:11'),
(193, 1004, 'account', 1, '', 'bmim8ojfdhdmbj7a8m32jo7ci2', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.97 Safari/537.36', 'zh-CN,zh;q=0.8,zh-TW;q=0.6', 'web', '127.0.0.1', '', '2016-03-07 01:28:40'),
(194, 1001, 'account', 1, '', 'b3tbrfit8btbhmjgbffippfr34', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.130 Safari/537.36', 'zh-CN,zh;q=0.8', 'web', '127.0.0.1', '', '2016-03-07 01:52:03'),
(195, 1001, 'account', 1, '', 'crc02jfrd07cc9kqvvre8nhkh5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-07 02:24:46'),
(196, 1001, 'account', 1, '', 'mc6p9qlturh4ncartkfcf6osf6', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.75 Safari/537.36', 'zh-CN,zh;q=0.8,en;q=0.6,zh-TW;q=0.4', 'web', '127.0.0.1', '', '2016-03-07 05:45:07'),
(197, 1001, 'account', 1, '', 'crc02jfrd07cc9kqvvre8nhkh5', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36', 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4', 'web', '127.0.0.1', '', '2016-03-07 05:51:43');

-- --------------------------------------------------------

--
-- 表的结构 `t8_user_message`
--

CREATE TABLE IF NOT EXISTS `t8_user_message` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '消息ID',
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `type` int(4) NOT NULL DEFAULT '0' COMMENT '消息类型',
  `tmpl_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '内容ID',
  `data` text NOT NULL COMMENT '参数 json格式',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0=未读 1=已读 -1=删除',
  `admin_user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '客服ID 0=系统',
  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `uit` (`user_id`,`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='站内短信' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `t8_user_message`
--

INSERT INTO `t8_user_message` (`id`, `user_id`, `type`, `tmpl_id`, `data`, `status`, `admin_user_id`, `post_time`) VALUES
(1, 1001, 1, 1, '{"user_id":"1002"}', 0, 0, '2016-03-04 09:01:58'),
(2, 1001, 2, 0, '', 0, 0, '2016-03-04 09:01:58');

-- --------------------------------------------------------

--
-- 表的结构 `t8_user_message_content`
--

CREATE TABLE IF NOT EXISTS `t8_user_message_content` (
  `message_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '消息ID 0=模板',
  `title` varchar(300) NOT NULL DEFAULT '' COMMENT '消息标题',
  `content` text NOT NULL COMMENT '消息内容',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息模板表';

--
-- 转存表中的数据 `t8_user_message_content`
--

INSERT INTO `t8_user_message_content` (`message_id`, `title`, `content`) VALUES
(2, 'test', 'haha,my id is {user_id}');

-- --------------------------------------------------------

--
-- 表的结构 `t8_user_message_template`
--

CREATE TABLE IF NOT EXISTS `t8_user_message_template` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '消息ID 0=模板',
  `title` varchar(300) NOT NULL DEFAULT '' COMMENT '消息标题',
  `content` text NOT NULL COMMENT '消息内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='消息模板表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `t8_user_message_template`
--

INSERT INTO `t8_user_message_template` (`id`, `title`, `content`) VALUES
(1, '這是標題', '這是內容{user_id}');

-- --------------------------------------------------------

--
-- 表的结构 `t8_user_money`
--

CREATE TABLE IF NOT EXISTS `t8_user_money` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `out_acc_no` bigint(20) NOT NULL DEFAULT '0' COMMENT '转出会员ID <1000为系统帐号',
  `in_acc_no` bigint(20) NOT NULL DEFAULT '0' COMMENT '输入会员ID',
  `fee_acc_no` bigint(20) NOT NULL DEFAULT '0' COMMENT '手续费帐号',
  `trans_type` int(4) NOT NULL DEFAULT '0' COMMENT '类型 1=交易 2=储值 3=退款',
  `trade_amount` int(11) NOT NULL DEFAULT '0' COMMENT '交易金额',
  `realpay_amount` int(11) NOT NULL DEFAULT '0' COMMENT '实付金额',
  `promo_amount` int(11) NOT NULL DEFAULT '0' COMMENT '优惠金额',
  `fee_amount` int(11) NOT NULL DEFAULT '0' COMMENT '手续费',
  `realrec_amount` int(11) NOT NULL COMMENT '实收金额',
  `promo_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '优惠类型',
  `promo_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '优惠ID',
  `trade_no` varchar(20) NOT NULL DEFAULT '' COMMENT '编号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '-1=取消 0=圈存 1=交易成功',
  `invoice_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发票类型 0=捐赠 1=对中 2=索取 3=电子',
  `invoice_id` bigint(20) NOT NULL COMMENT '发票ID',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `completed_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '完成时间',
  `cancel_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '取消时间',
  PRIMARY KEY (`id`),
  KEY `out` (`out_acc_no`,`status`),
  KEY `in` (`in_acc_no`,`status`),
  KEY `trade_no` (`trade_no`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='会员现金明细' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `t8_user_money`
--

INSERT INTO `t8_user_money` (`id`, `out_acc_no`, `in_acc_no`, `fee_acc_no`, `trans_type`, `trade_amount`, `realpay_amount`, `promo_amount`, `fee_amount`, `realrec_amount`, `promo_type`, `promo_id`, `trade_no`, `status`, `invoice_type`, `invoice_id`, `create_time`, `completed_time`, `cancel_time`) VALUES
(1, 1001, 1002, 1, 1, 100, 0, 0, 6, 94, 0, 0, '201601010001', 1, 0, 0, '2016-02-29 05:22:00', '2016-02-29 13:22:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `t8_user_online`
--

CREATE TABLE IF NOT EXISTS `t8_user_online` (
  `session_id` char(32) NOT NULL COMMENT '会话ID',
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `client_ip` char(15) NOT NULL DEFAULT '' COMMENT '真实IP',
  `forwarded_ip` char(15) NOT NULL DEFAULT '' COMMENT '代理IP',
  `country_code` char(3) NOT NULL DEFAULT '' COMMENT '国家编码',
  `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登入时间',
  `online_time` datetime NOT NULL COMMENT '最后在线时间',
  PRIMARY KEY (`session_id`),
  KEY `user_id` (`user_id`),
  KEY `online_time` (`online_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='在线表';

--
-- 转存表中的数据 `t8_user_online`
--

INSERT INTO `t8_user_online` (`session_id`, `user_id`, `client_ip`, `forwarded_ip`, `country_code`, `login_time`, `online_time`) VALUES
('3ai80osbc81iifm0houna4p2s7', 0, '127.0.0.1', '', '', '2016-03-07 06:10:52', '2016-03-07 14:13:27'),
('bmim8ojfdhdmbj7a8m32jo7ci2', 1004, '127.0.0.1', '', '', '2016-03-07 02:27:40', '2016-03-07 16:32:36'),
('crc02jfrd07cc9kqvvre8nhkh5', 1001, '127.0.0.1', '', '', '2016-03-07 02:25:04', '2016-03-07 16:26:57'),
('mc6p9qlturh4ncartkfcf6osf6', 1001, '127.0.0.1', '', '', '2016-03-07 05:29:42', '2016-03-07 13:45:07');

-- --------------------------------------------------------

--
-- 表的结构 `t8_user_validate_ip`
--

CREATE TABLE IF NOT EXISTS `t8_user_validate_ip` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '會員ID',
  `type` enum('sms','email','reg','service') NOT NULL DEFAULT 'sms' COMMENT '解锁类型',
  `ip` char(15) NOT NULL DEFAULT '' COMMENT 'IP',
  `remark` varchar(300) NOT NULL DEFAULT '' COMMENT '備註說明',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 1=会员删除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '驗證時間',
  `expire_time` datetime DEFAULT NULL COMMENT '過期時間',
  PRIMARY KEY (`id`),
  KEY `uip` (`user_id`,`ip`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='會員驗證IP' AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `t8_user_validate_ip`
--

INSERT INTO `t8_user_validate_ip` (`id`, `user_id`, `type`, `ip`, `remark`, `is_del`, `create_time`, `expire_time`) VALUES
(6, 1004, 'sms', '127.0.0.1', 'test', 1, '2016-03-07 01:28:40', '2016-06-07 09:28:40'),
(7, 1005, 'sms', '127.0.0.1', '', 2, '2016-03-01 08:56:35', '2016-12-01 16:56:35'),
(8, 1001, 'sms', '127.0.0.1', '', 0, '2016-03-03 01:32:40', '2016-06-03 09:32:40'),
(9, 1002, 'sms', '127.0.0.1', '', 0, '2016-03-04 06:43:45', '2016-06-04 14:43:45');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
