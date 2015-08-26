<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 前台会员模块
 * @author wangdong
 */
class MemberController extends CommonController {
	/**
	 * 会员管理
	 */
	public function memberList($page = 1, $rows = 10, $search = array(), $sort = 'memberid', $order = 'asc'){
		if(IS_POST){
			$member_db      = M('member');
			
			$where = array();
			foreach ($search as $k=>$v){
				if(!$v) continue;
				switch ($k){
					case 'username':
						$where[] = "`{$k}` like '%{$v}%'";
						break;
					case 'begin':
						if(!preg_match("/^\d{4}(-\d{2}){2}$/", $v)){
							unset($search[$k]);
							continue;
						}
						if($search['end'] && $search['end'] < $v) $v = $search['end'];
						$v = strtotime($v);
						$where[] = "`regtime` >= '{$v}'";
						break;
					case 'end':
						if(!preg_match("/^\d{4}(-\d{2}){2}$/", $v)){
							unset($search[$k]);
							continue;
						}
						if($search['begin'] && $search['begin'] > $v) $v = $search['begin'];
						$v = strtotime($v);
						$where[] = "`regtime` <= '{$v}'";
						break;
				}
			}
			$where = implode(' and ', $where);

			$total = $member_db->where($where)->count();
			$order = $sort.' '.$order;
			$limit = ($page - 1) * $rows . "," . $rows;
			$list = $total ? $member_db->field('memberid,username,lastloginip,lastlogintime,regtime')->where($where)->order($order)->limit($limit)->select() : array();
			foreach($list as &$info){				
				$info['lastlogintime'] = $info['lastlogintime'] ? date('Y-m-d H:i:s', $info['lastlogintime']) : '-';
				$info['regtime']       = $info['regtime'] ? date('Y-m-d H:i:s', $info['regtime']) : '-';
			}
			$data = array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$menu_db = D('Menu');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$datagrid = array(
				'options'     => array(
					'title'   => $currentpos,
					'url'     => U('Member/memberList', array('grid'=>'datagrid')),
					'toolbar' => '#member-member-datagrid-toolbar',
				),
				'fields' => array(
					'会员名'      => array('field'=>'username','width'=>15,'sortable'=>true),
					'最后登录时间' => array('field'=>'lastlogintime','width'=>15,'sortable'=>true,'formatter'=>'memberMemberModule.time'),
					'最后登录IP'   => array('field'=>'lastloginip','width'=>15,'sortable'=>true),
					'注册时间'      => array('field'=>'regtime','width'=>15,'sortable'=>true,'formatter'=>'memberMemberModule.time'),
					'管理操作'      => array('field'=>'memberid','width'=>30,'formatter'=>'memberMemberModule.operate'),
				)
			);
			$dict = dict('', 'Member');
			$this->assign('dict', $dict);
			$this->assign('datagrid', $datagrid);
			$this->display('member_list');
		}
	}
	
	/**
	 * 添加会员
	 */
	public function memberAdd(){
		if(IS_POST){
			$member_db = M('member');
			$data = I('post.info');
			if($member_db->where(array('username'=>$data['username']))->field('username')->find()){
				$this->error('会员名称已经存在');
			}
			$passwordinfo       = password($data['password']);
			$data['password'] = $passwordinfo['password'];
			$data['encrypt']  = $passwordinfo['encrypt'];
			$data['regtime']  = time();
			$data['regip']    = get_client_ip(false, true);

			$id = $member_db->add($data);
			if($id){
				$this->success('添加成功');
			}else {
				$this->error('添加失败');
			}
		}else{
			$dict = dict('', 'Member');
			$this->assign('dict', $dict);
			$this->display('member_add');
		}
	}
		
	/**
	 * 删除会员
	 */
	public function memberDelete($id){
		$member_oauth_db = M('member_oauth');
		$member_oauth_db->where(array('memberid'=>$id))->delete();

		$member_db = M('member');
		$result = $member_db->where(array('memberid'=>$id))->delete();

		if ($result){
			$this->success('删除成功');
		}else {
			$this->error('删除失败');
		}
	}
	
	/**
	 * 重置密码
	 */
	public function memberResetPassword($id){
		$member_db = M('member');
		$password = rand(100000, 999999);
		$info = password($password);
		$data = array(
			'password' => $info['password'],
			'encrypt'  => $info['encrypt']
		);
		$result = $member_db->where(array('memberid'=>$id))->save($data);
		
		if ($result){
			$this->ajaxReturn(array('status'=>1, 'info'=>'重置成功', 'password'=>$password));
		}else {
			$this->error('重置失败');
		}
	}
	
	/**
	 * 查看会员
	 */
	public function memberView($id){
		if(IS_POST){
			$data = array();
			
			//基本信息
			$member_db       = M('member');
			$field = array(
				'username'      => '用户名',
				'regip'          => '注册IP',
				'regtime'       => '注册时间',
				'lastloginip'   => '上次登录IP',
				'lastlogintime' => '上次登录时间',
			);
			$info = $member_db->field('memberid,password', true)->where(array('memberid'=>$id))->find();
			$dict = dict('', 'Member');
			foreach ($info as $key=>$value){

				switch ($key){
					case 'regtime':
					case 'lastlogintime':
						$value = $value ? date('Y-m-d H:i:s', $value) : '-';
						break;

					case 'regip':
					case 'lastloginip':
						$value = $value ? $value : '-';
						break;					
				}
					
				$data[] = array(
					'name'    => $field[$key],
					'group'   => '基本信息',
					'value'   => $value,
				);
			}			
			
			$this->ajaxReturn($data);
		}else {
			$propertygrid = array(
				'options'     => array(
					'url'     => U('Member/memberView', array('id'=>$id, 'grid'=>'propertygrid')),
				)
			);
			$this->assign('propertygrid', $propertygrid);
			$this->display('member_view');
		}
	}
	
	/**
	 * 验证会员名
	 */
	public function public_checkName($name){
		if (I('get.default') == $name) {
			exit('true');
		}
		$member_db = M('member');
		$exists = $member_db->where(array('username'=>$name))->field('username')->find();
		if ($exists) {
			exit('false');
		}else{
			exit('true');
		}
	}	
}