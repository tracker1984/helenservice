<?php
namespace Home\Controller;
use Think\Controller;
class MemberCommonController extends Controller {
	protected static $Model=null;
	protected $ProductModel=null;
	protected $theme;
	public $sessionID,$memberID,$memberInfo,$memberShippingAddress;

	function _initialize(){

		$this->ProductModel=D('Products');
		$this->sessionID = Session::get ( 'sessionID' );
		if (! $this->sessionID ) {
			$this->sessionID = md5 ( uniqid ( rand () ) );
			Session::set ( 'sessionID', $this->sessionID );
		}

		$this->memberID = Session::get('memberID');
		if (! $this->memberID ) {
			$this->memberID = 0;
		}
		else {
			$this->mid=$this->memberID;
			self::$Model=D("Members");
			$this->memberInfo=self::$Model->where("id=".$this->memberID)->find();
			$this->member_Info=$this->memberInfo;
			self::$Model=D("Shippingaddress");
		}
	}

	private function _Products_Cache(){
		
	}

	protected function _empty(){
		C('TMPL_ACTION_ERROR','Public:404');
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		$this->error('404-Document Not Found');
	}

    
}
