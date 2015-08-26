CREATE DATABASE `app` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `app`;

DROP TABLE IF EXISTS `app_admin`;
CREATE TABLE `app_admin` (
  `userid` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `roleid` smallint(5) DEFAULT '0',
  `lastloginip` varchar(15) DEFAULT NULL,
  `lastlogintime` int(10) unsigned DEFAULT '0',
  `email` varchar(40) DEFAULT NULL,
  `realname` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`userid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_admin_role`;
CREATE TABLE `app_admin_role` (
  `roleid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `rolename` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`roleid`),
  KEY `listorder` (`listorder`),
  KEY `disabled` (`disabled`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_admin_role_priv`;
CREATE TABLE `app_admin_role_priv` (
  `roleid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `c` varchar(20) NOT NULL,
  `a` varchar(20) NOT NULL,
  KEY `roleid` (`roleid`,`c`,`a`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_admin_log`;
CREATE TABLE `app_admin_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL,
  `httpuseragent` text NOT NULL,
  `sessionid` varchar(30) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(30) NOT NULL,
  PRIMARY KEY (`logid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_category`;
CREATE TABLE `app_category` (
  `catid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `catname` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `model` varchar(50) NOT NULL DEFAULT 'article' comment '模型',
  `setting` text default NULL,
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0' comment '是否禁用',
  `ismenu` tinyint(1) NOT NULL DEFAULT '1' comment '前台显示',
  PRIMARY KEY (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_category_priv`;
CREATE TABLE `app_category_priv` (
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `roleid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `action` varchar(30) NOT NULL,
  KEY `catid` (`catid`,`roleid`,`is_admin`,`action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_log`;
CREATE TABLE `app_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `controller` varchar(15) NOT NULL,
  `action` varchar(20) NOT NULL,
  `querystring` mediumtext NOT NULL,
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`logid`),
  KEY `module` (`controller`,`action`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_menu`;
CREATE TABLE `app_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `parentid` smallint(6) NOT NULL DEFAULT '0',
  `c` varchar(20) NOT NULL DEFAULT '',
  `a` varchar(20) NOT NULL DEFAULT '',
  `data` varchar(255) NOT NULL DEFAULT '',
  `listorder` smallint(6) unsigned NOT NULL DEFAULT '0',
  `display` enum('1','0') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `listorder` (`listorder`),
  KEY `parentid` (`parentid`),
  KEY `module` (`c`,`a`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_article`;
CREATE TABLE `app_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uuid` varchar(40) NOT NULL,
  `title` varchar(80) NOT NULL DEFAULT '',
  `keywords` varchar(40) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `islink` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `istop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `author` varchar(20) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`,`status`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_page`;
CREATE TABLE `app_page` (
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uuid` varchar(40) NOT NULL,
  `title` varchar(160) NOT NULL,
  `keywords` varchar(40) NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_times`;
CREATE TABLE `app_times` (
  `username` char(40) NOT NULL,
  `ip` char(15) NOT NULL,
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `isadmin` tinyint(1) NOT NULL DEFAULT '0',
  `times` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`,`isadmin`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_setting`;
CREATE TABLE `app_setting` (
  `key` varchar(50) NOT NULL,
  `value` varchar(5000) DEFAULT '',
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_member`;
CREATE TABLE `app_member` (
  `memberid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL COMMENT '帐号',  
  `password` varchar(32) NOT NULL,
  `email`  varchar(40) DEFAULT NULL comment '邮箱',
  `lastloginip` varchar(15) DEFAULT NULL,
  `lastlogintime` int(10) DEFAULT '0',
  `regip` varchar(15) NOT NULL,
  `regtime` int(10) NOT NULL DEFAULT '0' COMMENT '注册时间',
  PRIMARY KEY (`memberid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_journeyman`;
CREATE TABLE `app_journeyman` (
    `journeymanid` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`memberid` int(11) unsigned NOT NULL DEFAULT '0',    
    `realname` varchar(50) NOT NULL comment '真实姓名',	
	`idcard` varchar(20) NOT NULL comment '身份证号',      
	`tel` varchar(50) NOT NULL ,
	`gender` tinyint(1) DEFAULT '0' COMMENT '0:保密,1:男,2:女',	
	`serviceprovince` varchar(255) NOT NULL comment '服务省份',
	`servicecity` varchar(255) NOT NULL comment '服务城市',
	`servicedistrict` varchar(255) NOT NULL comment '服务地区',    
    `createdate` int(10) DEFAULT '0',
	`craftsmanship` int(11) NOT NULL DEFAULT '0',	
    PRIMARY KEY (`journeymanid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
DROP TABLE IF EXISTS `app_order`;
CREATE TABLE `app_order` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   
	`memberid` int(11) DEFAULT '0' comment '会员标识',	
	`orderid` int(11) DEFAULT '0' comment '订单号',
    `servicename` varchar(64) DEFAULT '' comment '服务名称',
	`expecteddate` varchar(64) DEFAULT '' comment '期望服务时间',
	`price` decimal(14,2) DEFAULT '0.0' comment '服务价格',
	`remark` text comment '备注',	
    `createdate` int(10) DEFAULT '0',
	`ipaddress` varchar(15) DEFAULT NULL,
	`status` tinyint(5) DEFAULT '1' comment '1:订单生成2:师傅已接单3:订单完成',
	`lastmodified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`finishdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',	
    PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
DROP TABLE IF EXISTS `app_order_address`;
CREATE TABLE `app_order_address` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,   
	`memberid` int(11) DEFAULT '0' comment '会员标识',	
	`orderid` int(11) DEFAULT '0' comment '订单号',    
	`clientname` varchar(255) DEFAULT '' comment '客户姓名',		
	`tel` varchar(32) DEFAULT '',   	
	`province` varchar(255) DEFAULT '',
	`city` varchar(255) DEFAULT '',
	`district` varchar(255) DEFAULT '',
    `housingestate` varchar(255) DEFAULT '',          
    PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
DROP TABLE IF EXISTS `app_service_address`;
CREATE TABLE `app_service_address` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `memberid` int(11) unsigned NOT NULL DEFAULT '0',   	
	`tel` varchar(50) DEFAULT '',   	
	`province` varchar(255) DEFAULT '',
	`city` varchar(255) DEFAULT '',
	`district` varchar(255) DEFAULT '',
    `housingestate` varchar(255) DEFAULT '',          
    PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
DROP TABLE IF EXISTS `app_service`;
CREATE TABLE `app_service` (	
	`serviceid` int(11) NOT NULL,
	`servicename` varchar(50) DEFAULT '',
	`price` int(11) DEFAULT '0',
	PRIMARY KEY (`serviceid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `app_craftsmanship`;
CREATE TABLE `app_craftsmanship` (	
	`craftsmanshipid` int(11) NOT NULL,
	`name` varchar(50) DEFAULT '',	
	PRIMARY KEY (`craftsmanshipid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `app_service`(`serviceid`, `servicename`, `price`) VALUES 
(1,"家庭清洁", 100), 
(2,"油烟机清洗", 50), 
(3,"马桶修理", 30),
(4,"水电维修", 150),
(5,"墙面打孔", 20),
(6,"管道疏通", 80),
(7,"冰箱除菌", 35),
(8,"开换锁", 40),
(9,"安装门窗", 75),
(10,"纱窗换新", 7),
(11,"清洁玻璃", 1),
(12,"移空调", 300);

INSERT INTO `app_craftsmanship`(`craftsmanshipid`, `name`) VALUES
(1,"清洁工"),
(2,"油烟机清洗工"),
(3,"马桶维修工"),
(4,"水电工"),
(5,"木工"),
(6,"泥瓦工"),
(7,"管道工"),
(8,"门窗修理工"),
(9,"锁匠"),
(10,"空调修理工");

INSERT INTO `app_admin` (`userid`, `username`, `password`, `roleid`, `email`) VALUES (1, 'happiness', 'cc95ff8bc079810fea32527aaee5b9cc', 1, 'dxpan2002@163.com');
INSERT INTO `app_admin_role` VALUES (1,'超级管理员','超级管理员',99,0),(2,'普通用户','普通用户',0,0);
INSERT INTO `app_member_type` VALUES ('1','普通用户','本地用户', '0', '0');
INSERT INTO `app_menu` (`id`, `name`, `parentid`, `c`, `a`, `data`, `listorder`, `display`) VALUES
(1, '我的面板', 0, 'Admin', 'top', '', 1, '1'),
(2, '系统管理', 0, 'System', 'top', '', 2, '1'),
(3, '前台管理', 0, 'Content', 'top', '', 3, '1'),
-- (5, '扩展中心', 0, 'Extend', 'top', '', 5, '1'),

(6, '安全记录', 1, 'Admin', 'userLeft', '', 0, '1'),
(7, '登录日志', 6, 'Admin', 'loginLog', '', 1, '1'),
(8, '删除登录日志', 7, 'Admin', 'loginLogDelete', '', 1, '1'),
(9, '系统设置', 2, 'System', 'settingLeft', '', 1, '1'),
(10, '系统设置', 9, 'System', 'setting', '', 1, '1'),

(11, '菜单设置', 9, 'System', 'menuList', '', 2, '1'),
(12, '查看列表', 11, 'System', 'menuViewList', '', 0, '1'),
(13, '添加菜单', 11, 'System', 'menuAdd', '', 0, '1'),
(14, '修改菜单', 11, 'System', 'menuEdit', '', 0, '1'),
(15, '删除菜单', 11, 'System', 'menuDelete', '', 0, '1'),

(16, '菜单排序', 11, 'System', 'menuOrder', '', 0, '1'),
(17, '菜单导出', 11, 'System', 'menuExport', '', 0, '1'),
(18, '菜单导入', 11, 'System', 'menuImport', '', 0, '1'),
(19, '用户设置', 2, 'Admin', 'left', '', 2, '1'),
(20, '用户管理', 19, 'Admin', 'memberList', '', 1, '1'),

(21, '查看列表', 20, 'Admin', 'memberViewList', '', 0, '1'),
(22, '添加用户', 20, 'Admin', 'memberAdd', '', 0, '1'),
(23, '编辑用户', 20, 'Admin', 'memberEdit', '', 0, '1'),
(24, '删除用户', 20, 'Admin', 'memberDelete', '', 0, '1'),
(25, '角色管理', 19, 'Admin', 'roleList', '', 2, '1'),


(26, '查看列表', 25, 'Admin', 'roleViewList', '', 0, '1'),
(27, '添加角色', 25, 'Admin', 'roleAdd', '', 0, '1'),
(28, '编辑角色', 25, 'Admin', 'roleEdit', '', 0, '1'),
(29, '删除角色', 25, 'Admin', 'roleDelete', '', 0, '1'),
(30, '角色排序', 25, 'Admin', 'roleOrder', '', 0, '1'),

(31, '权限设置', 25, 'Admin', 'rolePermission', '', 0, '1'),
(32, '栏目权限', 25, 'Admin', 'roleCategory', '', 0, '1'),
(33, '系统记录', 2, 'System', 'recordLeft', '', 3, '1'),
(34, '日志管理', 33, 'System', 'logList', '', 3, '1'),
(35, '查看列表', 34, 'System', 'logViewList', '', 0, '1'),

(36, '删除日志', 34, 'System', 'logDelete', '', 0, '1'),
(37, '缓存管理', 33, 'System', 'fileList', '', 1, '1'),
(38, '发布管理', 3, 'Content', 'left', '', 2, '1'),
(39, '内容管理', 38, 'Content', 'index', '', 0, '1'),
(40, '栏目管理', 38, 'Category', 'categoryList', '', 0, '1'),

(41, '查看列表', 40, 'Category', 'categoryViewList', '', 0, '1'),
(42, '添加栏目', 40, 'Category', 'categoryAdd', '', 0, '1'),
(43, '编辑栏目', 40, 'Category', 'categoryEdit', '', 0, '1'),
(44, '删除栏目', 40, 'Category', 'categoryDelete', '', 0, '1'),
(45, '栏目排序', 40, 'Category', 'categoryOrder', '', 0, '1'),

(46, '栏目导出', 40, 'Category', 'categoryExport', '', 0, '1'),
(47, '栏目导入', 40, 'Category', 'categoryImport', '', 0, '1'),
(48, '会员中心', 3, 'Member', 'left', '', 1, '1'),
(49, '会员列表', 48, 'Member', 'memberList', '', 0, '1'),
(50, '会员分类', 48, 'Member', 'typeList', '', 0, '1'),

(51, '查看列表', 49, 'Member', 'memberViewList', '', 0, '1'),
(52, '添加会员', 49, 'Member', 'memberAdd', '', 0, '1'),
(53, '编辑用户', 49, 'Member', 'memberEdit', '', 0, '1'),
(54, '删除用户', 49, 'Member', 'memberDelete', '', 0, '1'),
(55, '用户详情', 49, 'Member', 'memberView', '', 0, '1'),

(56, '添加分类', 50, 'Member', 'typeAdd', '', 0, '1'),
(57, '编辑分类', 50, 'Member', 'typeEdit', '', 0, '1'),
(58, '删除分类', 50, 'Member', 'typeDelete', '', 0, '1'),
(59, '分类排序', 50, 'Member', 'typeOrder', '', 0, '1'),
(60, '查看列表', 50, 'Member', 'typeViewList', '', 0, '1'),

(61, '重置密码', 20, 'Admin', 'memberResetPassword', '', 0, '1'),
(62, '重置密码', 49, 'Member', 'memberResetPassword', '', 0, '1'),
(63, '邮件模版', 9, 'System', 'email', '', 3, '1'),
(64, '模版添加', 63, 'System', 'emailAdd', '', 0, '1'),
(65, '模版编辑', 63, 'System', 'emailEdit', '', 0, '1'),

(66, '模版删除', 63, 'System', 'emailDelete', '', 0, '1'),
(67, '模版列表', 63, 'System', 'emailList', '', 0, '1'),
(68, '上传管理', 38, 'Storage', 'index', '', 0, '1'),

(69, '数据管理',  2, 'Database', 'exportlist', '', 0, '1'),
(70, '备份数据库', 69, 'Database', 'exportlist', '', 0, '1'),
(71, '还原数据库', 69, 'Database', 'importlist', '', 0, '1');

(72, '订单管理', 3, 'Order', 'left', '', 0, '1');
(73, '订单列表', 72, 'Order', 'orderList', '', 0, '1');

(74, '工匠管理', 3, 'Craftsman', 'left', '', 0, '1');
(75, '工匠列表', 74, 'Craftsman', 'craftsmanList', '', 0, '1');

-- (63, '扩展管理', 5, 'Extend', 'left', '', 1, '1'),
-- (64, '扩展下载', 63, 'Extend', 'download', '', 0, '1'),
-- (65, '本地扩展', 63, 'Extend', 'local', '', 0, '1'),
-- (66, '已安装扩展', 5, 'Extend', 'installed', '', 2, '1');

