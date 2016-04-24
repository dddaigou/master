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
        $is_card_seller             = User::isCardSeller($user_id);
        $this->assign('is_card_seller',$is_card_seller);
        if (!$is_card_seller) {
            $game_id                = Input::get('game_id/d',0);
            $server_id              = Input::get('server_id/d',0);
            $type                   = Input::get('type/d',0);
            if ($type==2) {//輸出遊戲幣單位
                $currency_unit      = D('Game')->getFieldById($game_id, 'currency_unit');
                $currency_unit      = explode(',',$currency_unit);
                $units              = [];
                #根据中文单位计算数值：百=100、千=1000
                $value_names        = ['十'=>'10','拾'=>'10','百'=>'100','佰'=>'100','千'=>'1000','仟'=>'1000','萬'=>'10000','億'=>'100000000'];
                foreach ($currency_unit as $k => $v) {
                    $units[$k]['name']      = $v;
                    $units[$k]['value']     = 1;
                    foreach ($value_names as $name => $value) {
                        if (mb_strpos($v,$name,0,'utf8')!==false) {
                            $units[$k]['value'] = $value*$units[$k]['value'];
                        }
                    }
                }
                $this->assign('currency_unit',$units);
            }
            if ($type==5) {//輸出本遊戲的代儲品項
                $game_item          = D('GameItem')->where("game_id=$game_id")->select();
                $items              = [];
                foreach ($game_item as $key => $item) {
                    $items[$item['id']]        = $item['name'];
                }
                $this->assign('items',$items);
            }
            $games                  = F('game.inc');
            $this->assign('game_id',    $game_id);
            $this->assign('server_id',  $server_id);
            $this->assign('type',       $type);
            $this->assign('game_name',  $games['_'.$game_id]['name']);
            $this->assign('server_name',$games['_'.$game_id]['servers']['_'.$server_id]['name']);
            $this->assign('type_name',  $games['_'.$game_id]['types'][$type]);
            $this->assign('transfer_time_list',Transfer::get($type));
        }else{
            $card_id                = Input::get('card_id/d',0);
            $denom_id               = Input::get('denom_id/d',0);  
            $card_denom             = F('card_denom');
            $this->assign('card_id',$card_id);
            $this->assign('denom_id',$denom_id);
            $this->assign('card_name',$card_denom['_'.$card_id]['name']);
            $this->assign('denom_name',$card_denom['_'.$card_id]['denoms']['_'.$denom_id]['denom']);
        }

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