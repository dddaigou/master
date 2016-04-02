<?php
namespace app\admin\controller;

use think\Session;
use think\Response;
use think\Input;
use org\Page;

class Goods extends Common
{
    public function index()
    {
        $map            = [];
        if (Input::get('type')) {
            $map['type']        = Input::get('type');
        }
        if (Input::get('game_id')) {
            $map['game_id']     = Input::get('game_id');
        }
        if (Input::get('server_id')) {
            $map['server_id']   = Input::get('server_id');
        }
        if (Input::get('seller_id')) {
            $map['seller_id']   = Input::get('seller_id');
        }
        if (Input::get('?status') && ''!==Input::get('status')) {
            $map['status']      = Input::get('status');
        }
        if (Input::get('start_time')) {
            $map['post_time'][] = ['egt', Input::get('start_time').' 00:00:00'];
        }
        if (Input::get('end_time')) {
            $map['post_time'][] = ['elt', Input::get('end_time').' 23:59:59'];
        }

        $GoodsModel = D('Goods');
        // 統計總數
        $total      = $GoodsModel->where($map)->count();
        // 分頁
        $Page       = new Page($total);
        // 獲取列表
        $rows       = $GoodsModel->where($map)->limit($Page->firstRow, $Page->listRows)->select();
        // 獲取所有類型
        $types      = D('GoodsType')->getField('id,type_name');

        return V('', ['types'=>$types, 'rows'=>$rows, 'pager'=>$Page->show(), 'statuses'=>\logic\goods\Status::load()]);
    }
    
    #後台刊登
    public function publish()
    {
        if (IS_POST) {
            $data               = I('post.');
            $fun                = $data['id']>0?'edit':'publish';
            if ($data['type']<=0||$data['type']>=10) {
                return Response::error('請選擇商品類型');
            }
            $data['post_ip']    = \org\Ip::getIp();
            $result             = \api\Goods::$fun(['data'=>$data],1);
            if ($result['code']==200) {
                return Response::success('提交成功');
            }else{
                return Response::error($result['msg']);
            }
        }
        # 编辑商品
        $res_data   = [];
        $goods_id   = Input::get('id');
        $game_id    = Input::get('game_id');
        if ($goods_id) {
            $edit_goods                 = M()->table('t8_goods as A')
            ->join('left join t8_goods_info as B ON A.id=B.goods_id')
            ->where("A.id=$goods_id")
            ->find();
            $res_data['edit_goods']     = $edit_goods;
            $game_id                    = $edit_goods['game_id'];
        }
        // 獲取所有類型
        $games      = D('Game')->getField('id,name');
        $servers    = D('GameServer')->where(['game_id'=>$game_id])->getField('id,name');
        $types      = D('GoodsType')->getField('id,type_name');
        $res_data['types']  = $types;
        $res_data['games']  = $games;
        $res_data['servers']  = $servers;

        return V('', $res_data);
    }
}
