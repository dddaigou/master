<?php 
namespace app\usercenter\controller;

use think\Input;
use think\Response;
use think\Session;
use org\Page;

class Goods extends Common
{
    //商品管理
    public function index()
    {
        $input                      = Input::get();
        $map                        = [];
        $map['seller_id']           = Session::get('user_id');
        if (!empty($input['game_id'])) {
            $map['game_id']         = $input['game_id'];
        }
        if (!empty($input['server_id'])) {
            $map['server_id']       = $input['server_id'];
        }
        if (!empty($input['item_id'])) {
            $map['item_id']         = $input['item_id'];
        }
        if (!empty($input['stime'])) {
            $map['post_time'][]     = ['egt',$input['stime']];
        }
        if (!empty($input['etime'])) {
            $map['post_time'][]     = ['elt',$input['etime']];
        }
        if (!empty($input['id'])) {
            $map                    = [];
            $map['id']              = $input['id'];
        }
        $p                          = intval($input['p'])>0?intval($input['p']):1;
        $list_rows                  = 25;
        $count                      = D('Goods')->where($map)->count();
        $result                     = D('Goods')->where($map)->page($p,$list_rows)->select();
        $Page                       = new Page($count);
        $this->assign('page',$Page->show());
        $this->assign('list',$result);
        return V();
    }

    // 上架
    public function pullOn()
    {
        $id                   = Input::get('id/d');
        return \api\Goods::pullOn(['id'=>$id]);
    }

    // 下架
    public function pullOff()
    {
        $id                   = Input::get('id/d');
        return \api\Goods::pullOff(['id'=>$id]);
    }

    // 刪除 
    public function delete()
    {
        $id                   = Input::get('id/d');
        return \api\Goods::delete(['id'=>$id]);
    }

    // 更新
    public function renew()
    {
        $id                   = Input::get('id/d');
        return \api\Goods::renew(['id'=>$id]);
    }
}