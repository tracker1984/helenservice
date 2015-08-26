<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Db;

/**
 * 数据库备份还原控制器
 */
class DatabaseController extends CommonController{

    /**
     * 数据库备份列表
     */
    public function exportlist(){
    	if(IS_POST){
            $Db    = Db::getInstance();
            $list  = $Db->query('SHOW TABLE STATUS');
            $list  = array_map('array_change_key_case', $list);
			foreach($list as $key=>$val){
				$list[$key]['data_length'] = format_bytes($list[$key]['data_length']);
				$list[$key]['tables[]']    = $list[$key]['name'];
				$list[$key]['progress']    = 0;
			}
			$this->ajaxReturn($list);
		}else{
	        $currentpos = D('Menu')->currentPos(I('get.menuid'));  //栏目位置
			$treegrid = array(
				'options'       => array(
					'title'        => $currentpos,
					'url'          => U('Database/exportlist', array('grid'=>'treegrid')),
					'idField'      => 'name',
					'treefield'    => 'name',
					'toolbar'      => 'databaseExprotModule.toolbar',
					'checkOnSelect'=> false,
					'selectOnCheck'=> false
				),
				'fields' => array(
					'表单名'			=> array('field'=>'tables[]','checkbox'=>true),
					'表名'			=> array('field'=>'name','width'=>150),
					'表引擎'			=> array('field'=>'engine','width'=>50),
					'数据量'			=> array('field'=>'rows','width'=>50,'formatter'=>'function(rows){return "<font color=blue>"+rows+"</font>";}'),
					'数据大小'		=> array('field'=>'data_length','width'=>50,'formatter'=>'function(data_length){return "<font color=green>"+data_length+"</font>";}'),
					'创建时间'		=> array('field'=>'create_time','width'=>80,'formatter'=>'function(create_time){return "<font color=red>"+create_time+"</font>";}'),
					'备份状态'		=> array('field'=>'progress','width'=>100,'formatter'=>'databaseExprotModule.progressFormatter'),
					'管理操作'		=> array('field'=>'edit','width'=>80,'formatter'=>'databaseExprotModule.operate'),
				)
			);
			$this->assign('treegrid', $treegrid);
			$this->display("export");
    	}
    }

	/**
     * 数据库还原列表
     */
    public function importlist(){
    	if(IS_POST){
			$path = C('DATA_BACKUP_PATH');
            if(!is_dir($path)){
                mkdir($path, 0755, true);
            }
            $path = realpath($path);
            $flag = \FilesystemIterator::KEY_AS_FILENAME;
            $glob = new \FilesystemIterator($path,  $flag);

            $list = array();
            foreach ($glob as $name => $file) {
                if(preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)){
                    $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                    $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                    $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                    $part = $name[6];

                    if(isset($list["{$date} {$time}"])){
                        $info = $list["{$date} {$time}"];
                        $info['part'] = max($info['part'], $part);
                        $info['size'] = $info['size'] + $file->getSize();
                    } else {
                        $info['part'] = $part;
                        $info['size'] = $file->getSize();
                    }
                    $extension        = strtoupper(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                    $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                    $info['time']     = strtotime("{$date} {$time}");

                    $list["{$date} {$time}"] = $info;
                }
            }
			$temp = array();
			foreach ($list as $key => $value) {
				$list[$key]['name'] 	= date('Ymd-His',strtotime($key));
				$list[$key]['datetime'] = date('Y-m-d H:i:s',strtotime($key));
				$list[$key]['size'] 	= format_bytes($list[$key]['size']);
				$temp[] = $list[$key];
			}
			$list = $temp;
			$this->ajaxReturn($list);
		}else{
	        $currentpos = D('Menu')->currentPos(I('get.menuid'));  //栏目位置
			$treegrid = array(
				'options'       => array(
					'title'        => $currentpos,
					'url'          => U('Database/importlist', array('grid'=>'treegrid')),
					'idField'      => 'time',
					'treefield'    => 'time',
				),
				'fields' => array(
					'备份名称'		=> array('field'=>'name','width'=>80),
					'卷数'			=> array('field'=>'part','width'=>50),
					'压缩'			=> array('field'=>'compress','width'=>50),
					'数据大小'		=> array('field'=>'size','width'=>50,'formatter'=>'function(size){return "<font color=green>"+size+"</font>";}'),
					'备份时间'		=> array('field'=>'datetime','width'=>80,'formatter'=>'function(datetime){return "<font color=red>"+datetime+"</font>";}'),
					'管理操作'		=> array('field'=>'time','width'=>80,'formatter'=>'databaseImportModule.operate')
				)
			);
			$this->assign('treegrid', $treegrid);
			$this->display("import");
    	}
    }

    /**
     * 优化表
     * @param  String $tables 表名
     */
    public function optimize($tables = null){
        if($tables) {
            $Db   = Db::getInstance();
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");

                if($list){
                    $this->success("数据表优化完成！");
                } else {
                    $this->error("数据表优化出错请重试！");
                }
            } else {
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");
                if($list){
                    $this->success("数据表'{$tables}'优化完成！");
                } else {
                    $this->error("数据表'{$tables}'优化出错请重试！");
                }
            }
        } else {
            $this->error("请指定要优化的表！");
        }
    }

    /**
     * 修复表
     * @param  String $tables 表名
     */
    public function repair($tables = null){
        if($tables) {
            $Db   = Db::getInstance();
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->query("REPAIR TABLE `{$tables}`");

                if($list){
                    $this->success("数据表修复完成！");
                } else {
                    $this->error("数据表修复出错请重试！");
                }
            } else {
                $list = $Db->query("REPAIR TABLE `{$tables}`");
                if($list){
                    $this->success("数据表'{$tables}'修复完成！");
                } else {
                    $this->error("数据表'{$tables}'修复出错请重试！");
                }
            }
        } else {
            $this->error("请指定要修复的表！");
        }
    }

    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     */
    public function del($time = 0){
        if($time){
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(C('DATA_BACKUP_PATH')). DIRECTORY_SEPARATOR . $name;
            array_map("unlink", glob($path));
            if(count(glob($path))){
                $this->success('备份文件删除失败，请检查权限！');
            } else {
                $this->success('备份文件删除成功！');
            }
        } else {
            $this->error('参数错误！');
        }
    }

    /**
     * 备份数据库
     * @param  String  $tables 表名
     * @param  Integer $id     表ID
     * @param  Integer $start  起始行数
     */
    public function export($tables = null, $id = null, $start = null){
        if(IS_POST && !empty($tables) && is_array($tables)){ //初始化
            $path = C('DATA_BACKUP_PATH');
            if(!is_dir($path)){
                mkdir($path, 0755, true);
            }
            //读取备份配置
            $config = array(
                'path'     => realpath($path) . DIRECTORY_SEPARATOR,
                'part'     => C('DATA_BACKUP_PART_SIZE'),
                'compress' => C('DATA_BACKUP_COMPRESS'),
                'level'    => C('DATA_BACKUP_COMPRESS_LEVEL'),
            );

			//检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock)){
                $this->error('检测到有一个备份任务正在执行，请稍后再试！');
            } else {
                //创建锁文件
				file_put_contents($lock, NOW_TIME);
            }
            //检查备份目录是否可写 创建备份目录
            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');
            session('backup_config', $config);

            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', NOW_TIME),
                'part' => 1,
            );
            session('backup_file', $file);

            //缓存要备份的表
            session('backup_tables', $tables);

            //创建备份文件
            $Database = new \Admin\Plugin\DatabasePlugin($file, $config);
            if(false !== $Database->create()){
                $tab = array('id' => 0, 'start' => 0);
                $this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
            } else {
                $this->error('初始化失败，备份文件创建失败！');
            }
        } elseif (IS_GET && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = session('backup_tables');
            //备份指定表
            $Database = new \Admin\Plugin\DatabasePlugin(session('backup_file'), session('backup_config'));
            $start  = $Database->backup($tables[$id], $start);
            if(false === $start){ //出错
                $this->error('备份出错！');
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $tab = array('id' => $id, 'start' => 0);
					$this->ajaxReturn(array('info'=>'100 备份完成','status'=>1,'tab' => $tab),"json");
                } else { //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_file', null);
                    session('backup_config', null);
					$this->ajaxReturn(array('info'=>'100 备份完成','status'=>1),"json");
                }
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
				$this->ajaxReturn(array('info'=>$rate.' 正在备份...','status'=>1,'tab' => $tab),"json");
            }
				
        } else { //出错
            $this->error("请指定要备份的表！");
        }
    }

    /**
     * 还原数据库
     */
    public function import($time = 0, $part = null, $start = null){
        if(is_numeric($time) && is_null($part) && is_null($start)){ //初始化
            //获取备份文件信息
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR . $name;
            $files = glob($path);
            $list  = array();
            foreach($files as $name){
                $basename = basename($name);
                $match    = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz       = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);

            //检测文件正确性
            $last = end($list);
            if(count($list) === $last[0]){
                session('backup_list', $list); //缓存备份列表
				$this->ajaxReturn(array('info'=>'初始化完成！','status'=>1,'part'=>1,'start' => 0),"json");
            } else {
				$this->ajaxReturn(array('info'=>'备份文件可能已经损坏，请检查！','status'=>0),"json");
            }
        } elseif(is_numeric($part) && is_numeric($start)) {
            $list  = session('backup_list');
            $db = new \Admin\Plugin\DatabasePlugin($list[$part], array(
                'path'     => realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR,
                'compress' => $list[$part][2]));

            $start = $db->import($start);

            if(false === $start){
				$this->ajaxReturn(array('info'=>'还原数据出错！','status'=>0),"json");
            } elseif(0 === $start) { //下一卷
                if(isset($list[++$part])){
					$this->ajaxReturn(array('info'=>'正在还原...分卷:'.$part,'rate'=>0,'status'=>1,'part' => $part,'start'=>0),"json");
                } else {
                    session('backup_list', null);
					$this->ajaxReturn(array('info'=>'还原完成','status'=>1),"json");
                }
            } else {
                $data = array('part' => $part, 'start' => $start[0]);
                if($start[1]){
                    $rate = floor(100 * ($start[0] / $start[1]));
					$this->ajaxReturn(array('info'=>'正在还原分卷:<font color="red">'.$part.'</font>---->进度:<font color="green">'.$rate.'%</font>','rate'=>$rate,'status'=>1,'part' => $part,'start'=>$start[0]),"json");
                } else {
                    $data['gz'] = 1;
					$this->ajaxReturn(array('info'=>'正在还原分卷:<font color="red">'.$part.'</font>---->进度:<font color="green">'.$start[0].'</font>','status'=>1,'gz'=>1,'part' => $part,'start'=>$start[0]),"json");
                }
            }

        } else {
            $this->error("请指定要还原的备份文件！");
        }
    }

}
