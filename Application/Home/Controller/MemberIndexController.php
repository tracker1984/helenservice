<?php
/*
 * functions
 * Logout
 * modify shipping address
 * modify password
 * look up order
 * confirm order
 * */
namespace Home\Controller;
use Think\Controller;
class MemberIndexController extends MemberCommonController {
    public function index(){
	    $this->redirect ( 'MemberIndex/Orders' );
    } 

    public function Logout(){
	    Session::set("memberID", 0);
	    redirect ( 'MemberIndex-index' );
    }

    public function ShippingAddress(){
    }

    public function DoShippingAddress(){

    }

    public function ChangePassword(){
	    $this->display();
    }

    public function DoChangePassword(){

    }

    public function Orders(){

    }

    function ConfirmsOrders(){

    }
}
