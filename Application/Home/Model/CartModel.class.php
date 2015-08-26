<?php
/* functions
 * check_item
 * add_item
 * delete_item
 * modify_quantity
 * get_item_count
 * get_item_totalcount
 * clear_cart
 * cart_total
 * display_contents
 */
class CartModel extends Model {

	public $memberID;
	function _initialize() {
		parent::_initialize();
		$this->memberID=Session::get('memberID')?Session::get(''memberID'):0;
	}

	public function check_item($sessionID, $pid, $model) {
		$list=$this->where("session_id='".$sessionID."' and pid=".$pid." and model='".$model."'")->find();
		if (!$list) {
			return 0;
		} else {
			return count($list);
		}
	}

	public function add_item($sessionID, $pid, $count, $model) {
		$qty = $this->check_item($sessionID, $pid, $model);
		if ($qty) 
		{
			$data['count']=array('exp','count+'.$count);
			$data['dateline']=time();
			$this->where("session_id='".$sessionID."'and pid=".$pid." and model='".$model."'")->save($data);
		}
		else
		{
			$data['pid']=$pid;
			$data['uid']=$this->memberID;
			$data['session_id']=$sessionID;
			$data['count']=$count;
			$data['model']=$model;
			$data['dateline']=time();

			$this->add($data);
		}
	}

	public function delete_item($sessionID, $id) {
		if($id){
			$map['session_id']=$sessionID;
			$map['id']=array('in', $id);
			$this->where($map)->delete();
		}
	}

	public function modify_quantity($sessionID, $id, $count, $model) {
		$data['count']=$count;
		$data['dateline']=time();
		$this->where("session_id='" . $sessionID . "' and id=" . $id )->save($data);
	}

	public function get_item_count($sessionID) {
		return $this->where("session_id='" . $sessionID . "'")->count();
	}

	public function get_item_totalcount($sessionID) {
		return $this->where("session_id='" . $sessionID . "'")->sum('count');
	}

	public function clear_cart($sessionID) {
		$this->where("session_id='" . $sessionID . "'")->delete();
	}

	public function display_contents($sessionID) {
		$list=$this->where("session_id='" . $sessionID."'")->select();
		if (!$list){
			return null;
		}
		
		$dao = D("Products");
		for($row = 0; $row < count($list); $row++) {
			$list[$row]['model']=unserialize($list[$row]['model']);
			$data=$dao->getpriceInfo($list[$row]['pid'],$list[$row]['count'],$list[$row]['model']);
			$list[$row]['price']=$data['price'];
			$list[$row]['serial']=$data['serial'];
			$list[$row]['name']=$data['name'];
			$list[$row]['bigimage']=$data['bigimage'];
			$list[$row]['count']=$data['count'];
			$list[$row]['total']=$data['total'];
			
		}
		
		return $list;
	}
	}

}
?>
