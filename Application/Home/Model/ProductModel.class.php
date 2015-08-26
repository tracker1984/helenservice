<?php
/*
CREATE TABLE `hs_product` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`cateid` int(11) DEFAULT '0',
	`name` varchar(255) DEFAULT '',
	`serial` varchar(255) DEFAULT '',
	`price` decimal(11,2) DEFAULT '0.0',
	`costprice` decimal(11,2) DEFAULT '0.0',
	`providerprice` decimal(11,2) DEFAULT '0.0',
	`bigimage` varchar(255) DEFAULT '',
	`smallimage` varchar(255) DEFAULT '',
	`remark` text ,
	`isnew` int(5) DEFAULT '0',
	`ishot` int(5) DEFAULT '0',
	`isprice` int(5) DEFAULT '0',
	`isdown` int(5) DEFAULT '0',
	`isrent` int(5) DEFAULT '0',
	`isrenthot` int(5) DEFAULT '0',
	`brandid` int(11) DEFAULT '0',
    `startdate` int(11) DEFAULT '0',
	`count` int(11) DEFAULT '0',
    `status` tinyint(1) DEFAULT '0',    
    PRIMARY KEY (`id`)
    )ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8; 
 */

class ProductModel extends Model {
}
?>