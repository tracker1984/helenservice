<?php
/*
 * fields
 * id int(10)
 * name varchar(255)
 * imgurl varchar(255)
 * pid int(11)
 * type_id int(11)
 * sort int(11)
 * 
 * */

class CateModel extends Model {
	protected $_validate=array(

	array('name','require','类别名必须填写!'),
	array('name','','类别名已经存在!',0,'unique',1),

	);

	function getChildren($id) {
		static $children = array ();
		$data = $this->where ( array ('pid' => $id ) )->select ();
		if (func_num_args () > 1) {
			$children = array ();
			$children [] = $id;
		}
		foreach ( $data as $k => $v ) {
			$children [] = $v ['id'];
			$this->getChildren ( $v ['id'] );
		}
		return $children;
	}
?>
