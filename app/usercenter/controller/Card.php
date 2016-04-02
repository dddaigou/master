<?php 
namespace app\usercenter\controller;

use think\Input;
use think\Response;
use think\Session;
use org\Page;
use org\Validate;
use logic\goods\Type;
use logic\game\Game;
use api\Goods as GoodsApi;
use api\Card as CardApi;

class Card extends Common
{
    // 卡类管理
    public function index(){
        $map        = [];
        $CardModel  = D('Card');
        // 统计总数
        $total      = $CardModel->where($map)->count();
        // 分页
        $Page       = new Page($total);
        // 获取列表
        $rows       = $CardModel->where($map)->limit($Page->firstRow, $Page->listRows)->order("id DESC")->select();
        // 显示模板
        $data           = [];
        $data['rows']   = $rows;
        $data['page']   = $Page->show();
        return V('', $data);
    }
    
    // 卡类关联
    public function relation()
    {
        $CardModel              = D('Card');
        $UserCardSettingModel   = D('UserCardSetting');
        if (IS_POST) {
            $rules  = [
                ['id', '缺少參數', 'require'],
                ['id', '參數錯誤', 'number'],
            ];
            // 校驗參數
            if (!Validate::valid(Input::post(), $rules)) {
                return Response::error(Validate::getError());
            }
            // 处理 game_id
            if (!Input::post('relation_game_id') && is_array(Input::post('relation_game_id'))) {
                $_POST['relation_game_id'] = array_filter(Input::post('relation_game_id'));
                $_POST['relation_game_id'] = join(',', Input::post('relation_game_id'));
            }
            // 保存數據
            if (!$CardModel->create(Input::post()) || !$UserCardSettingModel->create(Input::post())) {
                return Response::error('數據有誤');
            }
            $map                    = ['id'=>Input::post('id')];
            if (false === $CardModel->where($map)->save() || false === $UserCardSettingModel->setNote(Session::get('user_id'),Input::post('id'),Input::post('note'))) {
                return Response::error('編輯失敗');
            }
            
            // 編輯成功
            return Response::success('編輯成功', '', U('cardsList'));
        } else {
            $row            = $CardModel->getbyId(Input::get('id'));
            $row['note']    = $UserCardSettingModel->getNote(Session::get('user_id'),$row['id']);
            if (empty($row)) {
                return Response::error('參數錯誤或已被刪除');
            }
            return V('', ['row'=>$row, 'games'=>\logic\game\Config::getAllGame(false)]);
        }
    }
    
    //销售情况
    public function performance(){
        $map                = [];
        $map['seller_id']   = Session::get('user_id');
        if(Input::get('card_id')){
            $map['card_id']        = Input::get('card_id');
        }
        if(Input::get('denom_id')){
            $map['denom_id']       = Input::get('denom_id');
        }
        if(Input::get('start_time')){
            $map['start_time']     = Input::get('start_time');
        }
        if(Input::get('end_time')){
            $map['end_time']       = Input::get('end_time');
        }
        if(Input::get('time_type')){
            $map['time_type']      = Input::get('time_type');
        }
        $rows           = CardApi::performance($map, 1);
        $data           = [];
        $data['rows']   = $rows;
        return V('',$data);
    }
    
    // 导入库存
    public function import(){
        if(IS_POST){
            if(!empty($_FILES['card_list']['tmp_name'])){
                $card_list = file_get_contents($_FILES['card_list']['tmp_name']);
            }
            $data   = [
                'seller_id'     => Session::get('user_id'),
                'card_id'       => Input::post('card_id/d'),
                'denom_id'      => Input::post('denom_id/d'),
                'card_list'     => $card_list,
            ];
            $res    = CardApi::import($data, 1);
            if($res['code'] == 200){
                return  Response::success('导入成功');
            }else{
                return  Response::error($res['msg']);
            }
        }else{
            return V();
        }
    }

    //库存查询
    public function query(){
        $data   = [
            'seller_id' => Session::get('user_id'),
            'card_id'   => Input::get('card_id/d'),
            'denom_id'  => Input::get('denom_id/d'),
        ];
        $res    = CardApi::query($data, 1);
        if($res['code'] == 200){
            return  V('',['rows'=>$res['rows']]);
        }
        
    }

    //单笔转售
    public function resale(){
        $data   = [
            'seller_id' => Session::get('user_id'),
            'id'        => Input::get('id/d'),
        ];
        $res    = CardApi::resale($data, 1);
        if($res['code'] == 200){
            return  Response::success($res['msg']);
        }else{
            return  Response::error($res['msg']);
        }
    }
    
}