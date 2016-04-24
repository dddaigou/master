<?php 
namespace app\usercenter\controller\goods;

use think\Input;
use think\Response;
use think\Session;
use logic\user\User;
use logic\goods\Transfer;
use app\usercenter\controller\Common;
use logic\goods\Type as GoodsType;

class Publish extends Common
{
    // 選擇遊戲伺服商品類型
    public function select()
    {
        $typeList = GoodsType::getstype(0,1);
        if (I('test')) {
            echo json_encode($typeList);exit;
        }
        $this->assign('typeList',$typeList);
        return V();
    }

    // 輸入商品信息
    public function write()
    {
        //輸出刊登頁
        $user_id                    = Session::get('user_id');
        $game_id                = Input::get('game_id/d',0);
        $server_id              = Input::get('server_id/d',0);
        $type                   = Input::get('type/d',0);
        $types                  = F('goodsType.inc');
        $this->assign('type', $type);
        $this->assign('type_name',  $types[$game_id]['name'].'>'.$types[$game_id]['subType'][$server_id]['name'].'>'.$types[$game_id]['subType'][$server_id]['subType'][$type]['name']);
        return V();
    }

    // 刊登
    public function publish()
    {
        if (!IS_POST) {
            Response::redirect(U('select'));
            exit;
        }
        $data                   = Input::post();
        $data['post_ip']        = \org\Ip::getIp();
        $data['post_client']    = 'web';
        $data['seller_id']      = Session::get('user_id');
        $result                 = \api\Goods::publish(['data'=>$data],1);
        if ($result['code']==200) {
            return Response::success('提交成功');
        }else{
            return Response::error($result['msg']);
        }
    }

    // 完成
    public function complete()
    {
        if (!Input::get('id/d')) {
            Response::redirect(U('select'));
            exit;
        }
        return V();
    }
}