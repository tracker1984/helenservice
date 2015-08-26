<?php
namespace Home\Controller;
use Think\Controller;

class MemberController extends Controller {
	var $sessionID, $memberID;
	
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
		}
		else{
			$this->mid = $this->memberID;  //mid for template
			//$dao = D("member");
			//$this->memberInfo = $dao->where("id=".$this->memberID)->find();
			//$this->username = $this->memberInfo['username']; //mid for template
		}
	}
	
	public function index(){
		echo "show my home page here!";
	}   
	
	public function submit_order(){
		//data:{service:arraySrv,name:name,phone:phone,province:province,city:city,area:area,detailarea:detailarea}
		$loop_var = 1;
		$order_id = md5(uniqid(rand()));		
		$data1['memberid'] = $this->memberID;
		$data1['orderid'] = $order_id;
		$data1['clientname'] = I('post.name');
		$data1['tel'] = I('post.phone');
		$data1['province'] = I('post.province');
		$data1['city'] = I('post.city');
		$data1['district'] = I('post.area');
		$data1['housingestate'] = I('post.detailarea');		
		
		$OrderAddress = D("Order_address");
		$OrderAddress->add($data1);
		
		$msg = "订单信息\n";
		
		$service = array();
		$service = I('post.service');
		//dump($service);
		
		$Order = M("Order");
		$createdate = time();
		$ipaddress = get_client_ip();
		
		for ($i= 0; $i < count($service); $i++){
			if ($i % 3 == 0){
				$data['servicename'] = $service[$i];				
			}
			
			if ($i % 3 == 1){
				$data['expecteddate'] = $service[$i];
			}
			
			if ($i % 3 == 2){
				$data['price'] = $service[$i];
			}		
			
			$msg .= $service[$i];
			
			if ($loop_var == 3)	{
				$data['memberid'] = $this->memberID;
				$data['orderid'] = $order_id;
				//$data[''] = date("Y-m-d H:i:s", time());
				$data['status'] = 1;  ///! 1 : 初始状态，无人接单， 2 ： 已接受   3. 订单完成
				$data['createdate'] = $createdate;
				$data['ipaddress'] = $ipaddress;								
				$Order->add($data);
				$loop_var = 1;
				$msg .= "\n";
			} else {			
				$loop_var++;
			}
		}		
		
		//dump($msg);
		$status = 1;
		//$msg = "hello world!";
		
		$data = array('status'=>$status,'msg'=>$msg);
		$this->ajaxReturn($data);
		
		//dump($arr);
	}	
	
	public function craftsmanship(){		
		//$condition['name'] = I('post.skill');
		//$craftsmanshipid = M("Craftsmanship")->field('craftsmanshipid')->where($condition)->select();

		$dao = D("Journeyman");
		$data1['realname'] = I('post.realname');
		$data1['idcard'] = I('post.idcard');
		$data1['tel'] = I('post.tel');
		$data1['gender'] = 1;// I('post.gender');
		$data1['serviceprovince'] = I('post.province');
		$data1['servicecity'] = I('post.city');
		$data1['servicedistrict'] = I('post.area');
		$data1['memberid'] = $this->$memberID;
		$data1['createdate'] = time();
		$data1['craftsmanship'] = (int)I('post.skill');
		$dao->add($data1);
		//dump($data);
		$msg = "gao ding";
		$data = array('status'=>1,'msg'=>$msg);
		$this->ajaxReturn($data);
	}
	
	public function Logout(){
		session("memberID", 0);
		$this->redirect("Index/index");;
	}
}