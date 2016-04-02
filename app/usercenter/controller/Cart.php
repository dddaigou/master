<?php 
namespace app\usercenter\controller;

use think\Input;
use think\Session;
//購物車
class Cart extends Common
{
    /**
    *我的購物車列表
    */
    public function index()
    {
        $map                    = [];
        $map['A.user_id']       = Session::get('user_id');
        $map['A.status']        = Input::get('status');
        $map['A.status']        = $map['A.status']?$map['A.status']:0;
        $map['B.status']        = 0;
        $list                   = M()->table('t8_cart A')
        ->join('left join t8_goods B ON A.goods_id=B.id')
        ->field('A.user_id,A.num,B.title,B.price,B.game_id,B.server_id,B.type')
        ->where($map)
        ->select();
        $game_inc               = 
        foreach ($list as $key => $value) {
            
        }
        $this->assign('list',$list);
        return V();
    }
}