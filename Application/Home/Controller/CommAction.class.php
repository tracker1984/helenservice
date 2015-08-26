<?php

class CommonController extends Controller 
{
	protected static $Model=null;
	protected $ProductModel = null;
	protected $ServiceModel = null;
	protected $BrandModel = null;
	public $sessionID, $memberID, $memberInfo, $shippingAddress;

	function _initialize() 
	{
		header("Content-Type:text/html; charset=utf-8");
		$this->ProductModel = D('Product');
		$this->ServiceModel = D('Service');
		$this->BrandModel = D('Brand');

		$this->sessionID = Session::get('sessionID');
		if (! $this->sessionID)
		{
			$this->sessionID = md5(uniqid(rand()));
			Session::set('sessionID', $this->sessionID);
		}

		$this->memberID = Session::get('memberID');
		if (!$this->memberID)
		{
			$this->memberID = 0;
		}
		else
		{
			$this->mid = $this->memberID;
			self::$Model = D("Member");
			$this->memberInfo = self::$Model->where("id=".$this->memberID)->find();
			$this->member_Info = $this->memberInfo;
		}

		$today = getdate();
		$this->month = $today['month'];

		if (F('Common_Cache') == '')
		{
			F('Common_Cache', $this->_Common_Cache());
		}

		$this->assign(F('Common_Cache'));

		if (F('Products_Cache') == '')
		{
			F('Products_Cache', $this->_Products_Cache());
		}

		$Products_Cache = F('Products_Cache');
		$this->assign($Products_Cache);
	}

	private function _Common_Cache()
	{
		$Common_Cache = array();
		//$Common_Cache['
		return $Common_Cache;
	}

	private function _Products_Cache()
	{
		$show_num = 6;
		$Products_Cache['HotService'] = $this->ServiceModel->where("ishot=1")->order("sort desc, id desc")->limit("0, $show_num")->select();
		$Products_Cache['HotRentGoods'] = $this->ProductModel->where("isrenthot=1")->order("sort desc, id desc")->limit("0, $show_num")->select();
		$Products_Cache['HotGoods'] = $this->ProductModel->where("ishot=1")->order("sort desc, id desc")->limit("0, $show_num")->select();
		$Products_Cache['HotGoodsRemark'] = $this->ProductModel->where("ishotremark=1")->order("sort desc, id desc")->limit("0, $show_num")->select();
		$Products_Cache['HotBrand'] = $this->BrandModel->where("ishot=1")->order("sort desc, id desc")->limit("0, $show_num")->select();
		$Products_Cache['NewProduct'] = $this->ProductModel->where("isnew=1")->order("sort desc, id desc")->limit("0, $show_num")->select();


		return $Products_Cache;
	}

	protected function _empty()
	{
		C('TMPL_ACTION_ERROR', 'Public:404');
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		$this->error('404-Document Not Found');
	}
}

?>
