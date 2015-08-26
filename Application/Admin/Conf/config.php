<?php
defined('THINK_PATH') or exit();
return array(
	//'配置项'=>'配置值'
	'SYSTEM_NAME'           => '后台管理系统',
	'SYSTEM_VERSION'        => '1.1.0[开发版]',
	
	'TMPL_L_DELIM'          => '<{',         // 模板引擎普通标签开始标记
	'TMPL_R_DELIM'          => '}>',         // 模板引擎普通标签结束标记

	'SHOW_PAGE_TRACE'       => false,
	
	/* 模板引擎设置 */
	'TMPL_ACTION_ERROR'     => MODULE_PATH.'View'.DS.'Common'.DS.'dispatch_jump.html',   // 默认错误跳转对应的模板文件
	'TMPL_ACTION_SUCCESS'   => MODULE_PATH.'View'.DS.'Common'.DS.'dispatch_jump.html',   // 默认成功跳转对应的模板文件
	'TMPL_EXCEPTION_FILE'   => MODULE_PATH.'View'.DS.'Common'.DS.'exception.html',       // 异常页面的模板文件

	/* 后台自定义设置 */
	'SAVE_LOG_OPEN'         => 0,          //开启后台日志记录
	'MAX_LOGIN_TIMES'       => 9,          //最大登录失败次数，防止为0时不能登录，因此不包含第一次登录
	'LOGIN_WAIT_TIME'       => 60,         //登录次数达到后需要等待时间才能再次登录，单位：分钟
	'LOGIN_ONLY_ONE'        => 0,          //开启单点登录
	'DATAGRID_PAGE_SIZE'    => 20,         //列表默认分页数

	
	/* 单独配置，会覆盖全局配置 */
	'FILE_UPLOAD_LINK_CONFIG' => array(
		'exts' => array('zip','rar', 'tar', 'gz', '7z', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx','txt'),
	),
	'FILE_UPLOAD_IMG_CONFIG' => array(
		'exts' => array('jpg','jpeg','gif','png'),
	),
	'FILE_UPLOAD_FLASH_CONFIG' => array(
		'exts' => array('swf'),
	),
	'FILE_UPLOAD_MEDIA_CONFIG' => array(
		'exts' => array('avi'),
	),
	
	/* 数据库备份设置 */
	'DATA_BACKUP_PATH'			=> './Public/Runtime/Data/',    //路径必须以 / 结尾
	'DATA_BACKUP_PART_SIZE'     => '20971520',  				//该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
	'DATA_BACKUP_COMPRESS'     	=> '1',							//压缩备份文件需要PHP环境支持gzopen,gzwrite函数 0:不压缩 1:启用压缩
	'DATA_BACKUP_COMPRESS_LEVEL'=> '9',							//压缩级别   1:普通   4:一般   9:最高
);