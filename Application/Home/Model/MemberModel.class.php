<?php
namespace Home\Model;
use Think\Model;
class MemberModel extends Model {
	protected $_validate = array(
		array('username','require','用户名不能为空!'),
		array('username','','用户名已经存在',0,'unique',1), 
		array('username','/^[a-zA-Z][a-zA-Z0-9_]{1,19}$/','用户名不合法！'),	
			
		array('email','require','邮箱不能为空!'),
		array('email','email','邮箱格式不正确!'),
		array('email','','该邮箱已经注册过！',0,'unique',1),		
		array('repassword','password','确认密码不正确',0,'confirm'),
	);
	
	protected $_auto = array( 
		array('password', 'md5', 3, 'function'), // 对password字段在新增的时候使md5函数处理
		array('regtime', 'time', 1, 'function'),  //创建时更新
		array('regip', 'get_client_ip', 1, 'function'), 
		array('lastlogintime','time', 3,'function'), //创建、修改时更新
		array('lastloginip','get_client_ip', 3,'function'),
	);
}
?>