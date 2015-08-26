<?php
/*
 CREATE TABLE `hs_user` (
    `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,    
    `acount` varchar(64) DEFAULT '',
    `password` char(32) DEFAULT '',
    `lastlogindate` int(11) DEFAULT '0',
    `logincount` mediumint(8) DEFAULT '0',
    `email` varchar(255) DEFAULT '',
    `remark` varchar(255) DEFAULT '',
    `createdate` int(11) DEFAULT '0',
    `status` tinyint(1) DEFAULT '0',
    `typeid` tinyint(2) DEFAULT '0',
    `mobile` varchar(50) DEFAULT '',
    PRIMARY KEY (`id`)
    )ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
 * */
class UserModel extends Model{
	protected $_auto=array(
		array('password','md5',1,'function'),
	);
}
?>