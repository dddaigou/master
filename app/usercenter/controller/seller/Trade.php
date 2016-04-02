<?php 
namespace app\usercenter\controller\seller;

use app\usercenter\controller\Common;
use think\Input;
use think\Session;

class Trade extends Common
{
    public function index()
    {
        //
    }
    //生成專屬商品=賣家生成一筆交易
    public function establish()
    {
        $id                     = Input::get('id/d');
        $buyer                  = Input::get('buyer/d');
        $trade_num              = Input::get('num/d');
        $trade_price            = Input::get('new_price/d');
        $price                  = D('Goods')->getFieldById($id,'price');
        if (empty($price)||$price<0) {
            return json_encode(['code'=>401,'msg'=>'商品異常或不存在']);
        }
        $data                   = [];
        $data['goods_id']       = $id;
        $data['buyer_id']       = $buyer;
        $data['trade_num']      = $trade_num?$trade_num:1;
        $data['trade_price']    = $trade_price?$trade_price:$price;
        $data['is_exclusive']   = 1;
        $data['seller_id']      = intval(Session::get('user_id'));
        $data                   = [$data];
        $result                 = \api\Trade::establish($data);
        return $result;
    }
}