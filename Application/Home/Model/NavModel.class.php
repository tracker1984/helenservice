<?php
/*
 * fields
 * id int(8)
 * ctype varchar(10)
 * cid smallint(5)
 * status int(1)
 * sort int(1)
 * name varchar(255)
 * url varchar(255)
 * */
class NavModel extends Model {
	protected $_validate=array(
		array('name','require','名称必须填写!'),
		array('name','','名称已经存在!',0,'unique',1),
	);
}
?>
