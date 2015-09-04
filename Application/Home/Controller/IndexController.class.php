<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	public $sessionID, $memberID, $memberInfo, $service, $craftsmanship;
		
	function _initialize(){
		$this->sessionID = session('sessionID');
		if (! $this->sessionID){
			$this->sessionID = md5(uniqid(rand()));
			session('sessionID', $this->sessionID);
		}
		
		$this->memberID = session('memberID');		
		if (! $this->memberID){
			$this->memberID = 0;
			$this->mid = 0;
			$this->craftsmanshipid = 0;
		}
		else{
			$this->mid = $this->memberID;  //mid for template
			
			$dao = D("member");
			$this->memberInfo = $dao->where("memberid=".$this->memberID)->find();
			$this->username = $this->memberInfo['username']; //mid for template
			
			$this->craftsmanshipid = D("journeyman")->field('journeymanid')->where("memberid=".$this->memberID)->find();
		}
		
		$this->service = D('Service')->select();		
		$this->assign('service', $this->service);		//mid for template		
		$this->craftsmanship = D('Craftsmanship')->select();
		$this->assign('craftsman', $this->craftsmanship);
	}
	
    public function index(){		
        $this->display();
    }
	
	public function Join(){
		$this->display();
	}
	
	public function checkName(){
		$dao = D('Member');
		if (! $dao->create ()) {
			exit ( $dao->getError () );
		} else {
			echo 0;//这是回传给$.post的数据，对应上面的data
		}
	}
	
	public function doJoin(){

	$data['username'] = strip_tags(trim($_POST['username']));
	$data['email'] = strip_tags(trim($_POST['email']));
	$data['password'] = strip_tags($_POST['password']);
	$data['confirm'] = strip_tags($_POST['confirm']);
	if($data['username'] == ''){
		$this->error('用户名不能为空');
	}
	
	if($data['email'] == ''){
		$this->error('邮箱不能为空');
	}
	
	if(!preg_match('/^[1-9a-zA-Z\_]{3,15}\@[1-9a-zA-Z\_]{2,10}\.[1-9a-zA-Z\_\.]{3,15}$/', $data['email'])){
		$this->error('邮箱格式错误');
	}
	
	if($data['password'] == ''){
		$this->error('密码不能为空');
	}
	
	if($data['password'] != $data['confirm']){
		$this->error('两次密码不一致');
	}
	
	$data['password'] = MD5($data['password']);
	$dao = D('Member');
	$id = $dao->add($data);
	if($id){
		session('memberID', $id);
		$this->success('注册成功',U("home/Index/index"));
	}
	else{
		$this->error('注册失败');
	}
		//if ($dao->create()){

			//$this->ajaxReturn(array('status'=>1,'msg'=>"good"));
			//$this->redirect ("Index/index");			
		//}
		//else{
		//	dump ( $dao->getError () );
		//}		
	}
	
	public function Login(){
		$this->display();
	}
	
	public function doLogin(){
		$dao = D("Member");
		$status = 0;
		$msg = "";
		$list=$dao->where("username ='".$_POST['username']."'")->find();
		if (!$list){
			$this->error ( "username error, do not have this account!");
		}
		else{
			if (md5($_POST['password']) != $list['password']){
				$this->error ( "Password error!");
			}
			else{
				session('memberID',$list['memberid']);				
				$data['lastlogintime'] = time();
				$data['lastloginip'] = get_client_ip();
				$dao->where("memberid ='".$list['id']."'")->save($data);
				//$status = 1;				
				//$this->jumpUrl=U('Index/index');
				$this->success("Login Successful!",U("home/index/index"));
				
			}
		}
		
		//$data = array('status'=>$status,'msg'=>$msg);
		//$this->ajaxReturn($data);
	}
	
	public function enterorder(){
		//dump(I('post.checkbox'));
		$arr = array();
		$arr = I('post.checkbox');
		/* 即使不选择，$arr也会有一个string ""存在，所以需要用count(I('post.')) == 1去判断 */		
		if (count(I('post.')) == 1)
		{
			$this->error("请至少选择一项服务,谢谢!");
		}
		
		$AllService = D('Service');
		$service = array();
		foreach ($arr as $key => $value) 
		{ 
			$condition['serviceid'] = $value;
			//dump($AllService->where($condition)->select());
			$service[] = $AllService->where($condition)->select();
		}
		$this->services = $service;
		$this->display('Member:Order');
	}
	
	public function craftsmanregister() {
		$arr = array();
		$arr = I('post.checkbox');
		/* 即使不选择，$arr也会有一个string ""存在，所以需要用count(I('post.')) == 1去判断 */		
		if (count(I('post.')) == 1)
		{
			$this->error("请至少选择一项服务,谢谢!");
		}
		
		$AllService = D('craftsmanship');
		$ship = array();
		foreach ($arr as $key => $value) 
		{ 
			$condition['craftsmanshipid'] = $value;
			//dump($AllService->where($condition)->select());
			$ship[] = $AllService->where($condition)->select();
		}
		$this->craftsmanships = $ship;	
		//dump($this->craftsmanship);
		$this->display('Member:Craftsmanship');
	}
}