-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 2018-07-05 10:01:28
-- 服务器版本： 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xiche`
--

-- --------------------------------------------------------

--
-- 表的结构 `xc_admin`
--

DROP TABLE IF EXISTS `xc_admin`;
CREATE TABLE IF NOT EXISTS `xc_admin` (
  `id` int(80) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(80) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_admin`
--

INSERT INTO `xc_admin` (`id`, `name`, `password`, `salt`) VALUES
(1, '1', '1', '');

-- --------------------------------------------------------

--
-- 表的结构 `xc_advertising`
--

DROP TABLE IF EXISTS `xc_advertising`;
CREATE TABLE IF NOT EXISTS `xc_advertising` (
  `id` int(80) NOT NULL AUTO_INCREMENT,
  `sid` varchar(80) NOT NULL COMMENT '商家id',
  `title` varchar(80) NOT NULL COMMENT '标题',
  `artitle` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sid` (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='爱心广告';

--
-- 转存表中的数据 `xc_advertising`
--

INSERT INTO `xc_advertising` (`id`, `sid`, `title`, `artitle`) VALUES
(1, '1', '1', '1');

-- --------------------------------------------------------

--
-- 表的结构 `xc_brand`
--

DROP TABLE IF EXISTS `xc_brand`;
CREATE TABLE IF NOT EXISTS `xc_brand` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `brand` varchar(50) NOT NULL,
  `money` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_brand`
--

INSERT INTO `xc_brand` (`id`, `brand`, `money`) VALUES
(1, '轮子1', '100'),
(2, '轮子2', '0'),
(3, '轮子3', '50');

-- --------------------------------------------------------

--
-- 表的结构 `xc_business`
--

DROP TABLE IF EXISTS `xc_business`;
CREATE TABLE IF NOT EXISTS `xc_business` (
  `id` int(88) NOT NULL AUTO_INCREMENT,
  `busid` varchar(88) NOT NULL,
  `password` varchar(88) NOT NULL,
  `salt` varchar(88) NOT NULL,
  `a` int(10) NOT NULL DEFAULT '20' COMMENT '没有会员',
  `b` int(10) NOT NULL DEFAULT '0' COMMENT '白金会员',
  `c` int(10) NOT NULL DEFAULT '0' COMMENT '钻石会员消费时花费的金额',
  PRIMARY KEY (`id`),
  UNIQUE KEY `busid` (`busid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_business`
--

INSERT INTO `xc_business` (`id`, `busid`, `password`, `salt`, `a`, `b`, `c`) VALUES
(1, '1', '1', '1', 20, 10, 10);

-- --------------------------------------------------------

--
-- 表的结构 `xc_connection`
--

DROP TABLE IF EXISTS `xc_connection`;
CREATE TABLE IF NOT EXISTS `xc_connection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `busi` varchar(20) NOT NULL,
  `pathone` varchar(20) NOT NULL,
  `pathtwo` varchar(20) NOT NULL,
  `paththree` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_connection`
--

INSERT INTO `xc_connection` (`id`, `busi`, `pathone`, `pathtwo`, `paththree`) VALUES
(1, '白先生', 'd:\\\\', 'd:\\\\', 'd:\\\\');

-- --------------------------------------------------------

--
-- 表的结构 `xc_fabu`
--

DROP TABLE IF EXISTS `xc_fabu`;
CREATE TABLE IF NOT EXISTS `xc_fabu` (
  `id` int(80) NOT NULL AUTO_INCREMENT,
  `uid` varchar(80) NOT NULL,
  `title` varchar(80) NOT NULL COMMENT '我的发布',
  `artitle` varchar(80) NOT NULL COMMENT '发布内容',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的发布';

-- --------------------------------------------------------

--
-- 表的结构 `xc_fans`
--

DROP TABLE IF EXISTS `xc_fans`;
CREATE TABLE IF NOT EXISTS `xc_fans` (
  `id` int(80) NOT NULL,
  `uid` varchar(80) NOT NULL,
  `sum` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_fans`
--

INSERT INTO `xc_fans` (`id`, `uid`, `sum`) VALUES
(1, '1', '128');

-- --------------------------------------------------------

--
-- 表的结构 `xc_huiyuan`
--

DROP TABLE IF EXISTS `xc_huiyuan`;
CREATE TABLE IF NOT EXISTS `xc_huiyuan` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `jjo` varchar(80) NOT NULL,
  `jjtw` varchar(80) NOT NULL,
  `jjth` varchar(80) NOT NULL,
  `money` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_huiyuan`
--

INSERT INTO `xc_huiyuan` (`id`, `name`, `jjo`, `jjtw`, `jjth`, `money`) VALUES
(1, '自动洗车', '这是一句标题与', '会员白金年卡', '自动洗车服务', 400);

-- --------------------------------------------------------

--
-- 表的结构 `xc_huiyuano`
--

DROP TABLE IF EXISTS `xc_huiyuano`;
CREATE TABLE IF NOT EXISTS `xc_huiyuano` (
  `id` int(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `jjo` varchar(50) NOT NULL,
  `jjtw` varchar(50) NOT NULL,
  `jjth` varchar(50) NOT NULL,
  `jjf` varchar(50) NOT NULL,
  `money` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_huiyuano`
--

INSERT INTO `xc_huiyuano` (`id`, `name`, `jjo`, `jjtw`, `jjth`, `jjf`, `money`) VALUES
(2, '蒸汽洗车', '0', '会员钻石年卡', '蒸汽洗车服务', '这是一句标题', 400);

-- --------------------------------------------------------

--
-- 表的结构 `xc_lingqu`
--

DROP TABLE IF EXISTS `xc_lingqu`;
CREATE TABLE IF NOT EXISTS `xc_lingqu` (
  `id` int(80) NOT NULL AUTO_INCREMENT,
  `uid` varchar(80) NOT NULL,
  `busid` varchar(80) NOT NULL COMMENT '商家id',
  `carstyle` varchar(80) NOT NULL COMMENT '车型',
  `size` varchar(80) NOT NULL COMMENT '轮子尺寸',
  `km` varchar(80) NOT NULL COMMENT '行驶里程',
  `carnum` varchar(80) NOT NULL COMMENT '车牌',
  `brand` varchar(80) NOT NULL DEFAULT '0' COMMENT '轮子品牌',
  `chajia` varchar(50) NOT NULL,
  `shenqing` varchar(80) NOT NULL DEFAULT '0' COMMENT '0申请失败1成功',
  `lingqu` varchar(80) NOT NULL DEFAULT '0' COMMENT '0领取失败1成功',
  `time` date NOT NULL,
  `quanxian` varchar(11) NOT NULL DEFAULT '0' COMMENT '0可以领取1不可以',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_lingqu`
--

INSERT INTO `xc_lingqu` (`id`, `uid`, `busid`, `carstyle`, `size`, `km`, `carnum`, `brand`, `chajia`, `shenqing`, `lingqu`, `time`, `quanxian`) VALUES
(1, '小白', '小白', '车型', '轮胎尺寸', '行驶里程', '车牌号码', '轮胎品牌', '0', '0', '0', '0000-00-00', '0'),
(2, '小红', '', '', '', '', '', '0', '', '0', '0', '0000-00-00', '0'),
(3, '1', '', '', '', '', '', '0', '40', '1', '1', '0000-00-00', '1'),
(4, '1', '', '', '', '', '', '0', '0', '1', '1', '0000-00-00', '0'),
(5, '1', '', '', '', '', '', '0', '30', '0', '0', '0000-00-00', '0'),
(6, '1', '', '', '', '', '', '0', '0', '0', '1', '0000-00-00', '1');

-- --------------------------------------------------------

--
-- 表的结构 `xc_lunbo`
--

DROP TABLE IF EXISTS `xc_lunbo`;
CREATE TABLE IF NOT EXISTS `xc_lunbo` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `busid` varchar(50) NOT NULL,
  `path` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_lunbo`
--

INSERT INTO `xc_lunbo` (`id`, `busid`, `path`) VALUES
(2, '2', 'd:\\\\'),
(1, '1', 'c:\\\\');

-- --------------------------------------------------------

--
-- 表的结构 `xc_member`
--

DROP TABLE IF EXISTS `xc_member`;
CREATE TABLE IF NOT EXISTS `xc_member` (
  `id` int(80) NOT NULL AUTO_INCREMENT,
  `uid` varchar(80) NOT NULL,
  `nicheng` varchar(30) NOT NULL DEFAULT '昵称',
  `sex` varchar(10) NOT NULL,
  `tel` int(20) NOT NULL,
  `birthday` date NOT NULL,
  `carnum` varchar(30) NOT NULL DEFAULT '0',
  `carstyle` varchar(30) NOT NULL DEFAULT '0',
  `weixin` varchar(30) DEFAULT '0',
  `idcard` int(30) NOT NULL DEFAULT '0',
  `city` varchar(20) NOT NULL,
  `address` varchar(80) NOT NULL,
  `time` date NOT NULL,
  `touxiang` varchar(80) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_member`
--

INSERT INTO `xc_member` (`id`, `uid`, `nicheng`, `sex`, `tel`, `birthday`, `carnum`, `carstyle`, `weixin`, `idcard`, `city`, `address`, `time`, `touxiang`) VALUES
(1, '1', 'xiaoba', '男', 110, '0000-00-00', '浙a88888', '思域', '17712345678', 2147483647, '杭州', '杭州西湖雷峰塔', '2018-06-27', '');

-- --------------------------------------------------------

--
-- 表的结构 `xc_money`
--

DROP TABLE IF EXISTS `xc_money`;
CREATE TABLE IF NOT EXISTS `xc_money` (
  `id` int(80) NOT NULL AUTO_INCREMENT,
  `uid` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(80) NOT NULL,
  `money` varchar(80) NOT NULL,
  `vip` varchar(80) NOT NULL DEFAULT '0' COMMENT '白金会员0无1有',
  `vipi` varchar(20) NOT NULL COMMENT '钻石会员0无1有',
  `time` date NOT NULL,
  `busname` varchar(80) NOT NULL,
  `vipid` varchar(50) NOT NULL,
  `vipiid` varchar(50) NOT NULL,
  `suijiid` varchar(80) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_money`
--

INSERT INTO `xc_money` (`id`, `uid`, `password`, `salt`, `money`, `vip`, `vipi`, `time`, `busname`, `vipid`, `vipiid`, `suijiid`) VALUES
(1, '1', '1', '1', '1', '1', '0', '2018-07-06', '车爵仕汉江店会员', 'J123A183EF', '', '123456');

-- --------------------------------------------------------

--
-- 表的结构 `xc_order`
--

DROP TABLE IF EXISTS `xc_order`;
CREATE TABLE IF NOT EXISTS `xc_order` (
  `id` int(80) NOT NULL AUTO_INCREMENT,
  `uid` varchar(70) NOT NULL,
  `busid` varchar(11) NOT NULL,
  `xcstyle` int(5) NOT NULL COMMENT '洗车方式8自动9蒸汽',
  `money` varchar(80) NOT NULL,
  `zhifu` int(20) NOT NULL COMMENT '支付状态0未或失败；1成功，2退款中',
  `liuyan` varchar(80) NOT NULL,
  `ordernum` int(30) NOT NULL COMMENT '订单号',
  `time` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_order`
--

INSERT INTO `xc_order` (`id`, `uid`, `busid`, `xcstyle`, `money`, `zhifu`, `liuyan`, `ordernum`, `time`) VALUES
(1, '1', '小白', 1, '20', 1, '擦促使u', 0, '0000-00-00');

-- --------------------------------------------------------

--
-- 表的结构 `xc_store`
--

DROP TABLE IF EXISTS `xc_store`;
CREATE TABLE IF NOT EXISTS `xc_store` (
  `id` int(80) NOT NULL AUTO_INCREMENT,
  `busid` varchar(80) NOT NULL,
  `busname` varchar(80) NOT NULL,
  `address` varchar(80) NOT NULL COMMENT '地址',
  `produce` varchar(80) NOT NULL COMMENT '简介',
  `path` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `busid` (`busid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_store`
--

INSERT INTO `xc_store` (`id`, `busid`, `busname`, `address`, `produce`, `path`) VALUES
(1, '白先生', '白马湖汽车服务', '白马湖小区33-34号', '白马湖汽车是一家专业的汽车美容服务机构', 'd:\\\\'),
(2, '1', '1', '1', '1', '');

-- --------------------------------------------------------

--
-- 表的结构 `xc_tuijian`
--

DROP TABLE IF EXISTS `xc_tuijian`;
CREATE TABLE IF NOT EXISTS `xc_tuijian` (
  `id` int(80) NOT NULL AUTO_INCREMENT,
  `uid` varchar(80) NOT NULL,
  `tidsum` int(80) NOT NULL,
  `quanxian` int(80) NOT NULL DEFAULT '0' COMMENT '0可以推荐1不可以推荐',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_tuijian`
--

INSERT INTO `xc_tuijian` (`id`, `uid`, `tidsum`, `quanxian`) VALUES
(1, '1', 1, 0),
(2, '小白', 13, 0);

-- --------------------------------------------------------

--
-- 表的结构 `xc_tuijiansum`
--

DROP TABLE IF EXISTS `xc_tuijiansum`;
CREATE TABLE IF NOT EXISTS `xc_tuijiansum` (
  `id` int(80) NOT NULL AUTO_INCREMENT,
  `uid` varchar(20) NOT NULL,
  `tid` varchar(80) NOT NULL,
  `success` int(80) NOT NULL DEFAULT '0' COMMENT '0失败1成功',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xc_tuijiansum`
--

INSERT INTO `xc_tuijiansum` (`id`, `uid`, `tid`, `success`) VALUES
(1, '小白', '小红', 1),
(2, '小白', '小黑', 1),
(3, '小白', '小子', 0),
(4, '1', '1', 0);

-- --------------------------------------------------------

--
-- 表的结构 `xc_user`
--

DROP TABLE IF EXISTS `xc_user`;
CREATE TABLE IF NOT EXISTS `xc_user` (
  `id` int(80) NOT NULL AUTO_INCREMENT,
  `username` varchar(80) NOT NULL,
  `tel` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
