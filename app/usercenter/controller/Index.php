<?php 
namespace app\usercenter\controller;

use think\Session;
use logic\trade\Trade;

class Index extends Common
{
    //會員中心首頁
    public function index()
    {
        $user_id                                = Session::get('user_id');
        $res_data                               = [];
        //賬戶餘額，信用評價
        $res_data['user_money']                 = 0;
        $res_data['credit']                     = 0;
        //我是買家的消息【購物車、待付款、移交中、領收中】
        $res_data['buyer']['buy_cart']          = 0;
        $res_data['buyer']['unpaid']            = Trade::count(['buyer_id'=>$user_id,'status'=>0]);
        $res_data['buyer']['tran_goods']        = Trade::count(['buyer_id'=>$user_id,'status'=>1]);
        $res_data['buyer']['recieving']         = Trade::count(['buyer_id'=>$user_id,'status'=>2]);
        //我是賣家的消息【移交中、領收中、轉賬中】
        $res_data['seller']['tran_goods']       = Trade::count(['seller_id'=>$user_id,'status'=>1]);
        $res_data['seller']['recieving']        = Trade::count(['seller_id'=>$user_id,'status'=>1]);
        $res_data['seller']['tran_money']       = Trade::count(['seller_id'=>$user_id,'status'=>1]);
        //客服中心消息 【申訴回復、未讀簡訊】
        $res_data['service']['appeal']          = 0;
        $res_data['service']['unread_msg']      = 0;
        //賬號安全資訊【登入密碼、支付密碼、行動電話認證、實名認證】
        $res_data['safety']['login_pwd']        = 0;
        $res_data['safety']['payment_pwd']      = 0;
        $res_data['safety']['mobile']           = 0;
        $res_data['safety']['authenticate']     = 0;
        $this->assign('user_data',$res_data);
        return $this->fetch();
    }
}