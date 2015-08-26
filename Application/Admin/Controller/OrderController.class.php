<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 订单相关模块
 * @author Pan Dongxia
 */
class OrderController extends CommonController {
	/**
	 * 订单管理
	 */
	 
	public function orderList($page = 1, $rows = 10, $search = array(), $sort = 'memberid', $order = 'asc'){
		if(IS_POST){
			$order_db      = M('order');
			
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
						$where[] = "`createdate` >= '{$v}'";
						break;
					case 'end':
						if(!preg_match("/^\d{4}(-\d{2}){2}$/", $v)){
							unset($search[$k]);
							continue;
						}
						if($search['begin'] && $search['begin'] > $v) $v = $search['begin'];
						$v = strtotime($v);
						$where[] = "`createdate` <= '{$v}'";
						break;
				}
			}
			$where = implode(' and ', $where);

			$total = $order_db->where($where)->count();
			$order = $sort.' '.$order;
			$limit = ($page - 1) * $rows . "," . $rows;
			$list = $total ? $order_db->field('memberid,orderid,servicename,price,remark,createdate,status')->where($where)->order($order)->limit($limit)->select() : array();
			foreach($list as &$info){				
				$info['createdate'] = $info['createdate'] ? date('Y-m-d H:i:s', $info['lastlogintime']) : '-';				
			}
			$data = array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$menu_db = D('Menu');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$datagrid = array(
				'options'     => array(
					'title'   => $currentpos,
					'url'     => U('Order/orderList', array('grid'=>'datagrid')),
					'toolbar' => '#order-order-datagrid-toolbar',
				),
				'fields' => array(
					'服务名称'      => array('field'=>'servicename','width'=>10,'sortable'=>true),
					'会员编号'      => array('field'=>'memberid','width'=>10),					
					'订单编号'      => array('field'=>'orderid','width'=>10,'sortable'=>true),
					'价格'         => array('field'=>'price','width'=>10),
					'创建时间'     => array('field'=>'createdate','width'=>10,'sortable'=>true,'formatter'=>'orderOrderModule.time'),
					'状态'      => array('field'=>'status','width'=>10,'sortable'=>true),
					'备注'      => array('field'=>'remark','width'=>20),
					'管理操作'      => array('field'=>'memberid','width'=>30,'formatter'=>'orderOrderModule.operate'),
				)
			);
			$dict = dict('', 'Order');
			$this->assign('dict', $dict);
			$this->assign('datagrid', $datagrid);
			$this->display('order_list');
		}
	}
}