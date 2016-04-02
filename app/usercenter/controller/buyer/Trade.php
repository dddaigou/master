<?php 
namespace app\usercenter\controller\buyer;

use app\usercenter\controller\Common;
use think\Input;
use think\Session;

class Trade extends Common
{
    public function index()
    {
        //
    }
    //買家點擊購買商品    
    public function establish()
    {
        $id                     = Input::get('id/d');
        $trade_num              = Input::get('num/d');
        $seller_id             = D('Goods')->getFieldById($id,'seller_id');
        if (empty($seller_id)||$seller_id<0) {
            return json_encode(['code'=>401,'msg'=>'商品異常或不存在']);
        }
        $data                   = [];
        $data['goods_id']       = $id;
        $data['buyer_id']       = intval(Session::get('user_id'));
        $data['trade_num']      = $trade_num?$trade_num:1;
        $data['seller_id']      = $seller_id;
        // dump($data);exit;
        $data                   = [$data];
        $result                 = \api\Trade::establish($data);
        return $result;
    }
}