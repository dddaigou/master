<?php 
namespace app\admin\controller\card;

use think\Input;
use think\Response;
use org\Validate;
use app\admin\controller\Common;

class Denom extends Common
{
    public function index()
    {
        // 接收参数
        $map                = [];
        if (!Input::get('card_id/d')) {
            return Response::error('參數錯誤', '', U('card/index'));
        }
        $map['card_id']     = Input::get('card_id/d');
        $CardDenomModel     = D('CardDenom');
        // 数据
        $rows               = $CardDenomModel->where($map)->order("id ASC")->select();
        // 显示模板
        return V('', ['rows'=>$rows]);
    }

    public function del()
    {
        // 同時刪除伺服器
        D('CardDenom')->where(['id'=>['in', Input::request('id')]])->delete();
        return Response::success('刪除成功', '', U('index', ['card_id'=>Input::get('card_id')]));
    }

    public function add()
    {
        if (IS_POST) {
            $rules              = [
                ['card_id', '点卡ID必須', 'require'],
                ['card_id', '点卡ID不正確', 'number'],
                ['denom', '面额必須', 'require'],
                ['denom', '面额長度超出範圍', 'length', '1,30'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 處理 is_hidden
            if (!isset($_POST['is_hidden'])) $_POST['is_hidden'] = 0;
            if (!isset($_POST['is_deny_publish'])) $_POST['is_deny_publish'] = 0;
            // 加載模型
            $CardDenomModel     = D('CardDenom');
            // 檢測是否已存在相同名字的遊戲
            $map                = ['card_id'=>$_POST['card_id'], 'denom'=>$_POST['denom']];
            $count              = $CardDenomModel->where($map)->count();
            if ($count) {
                return Response::error('已經存在同名面额');
            }
            // 保存數據
            if (!$CardDenomModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            if (!$CardDenomModel->add()) {
                return Response::error('新增失敗');
            }
            // 編輯成功
            return Response::success('新增成功', '', U('index', ['card_id'=>$_POST['card_id']]));
        } else {
            return V('add');
        }
    }

    public function edit()
    {
        $CardDenomModel         = D('CardDenom');
        if (IS_POST) {
            $rules              = [
                ['id', '缺少參數', 'require'],
                ['id', '參數錯誤', 'number'],
                ['card_id', '点卡ID必須', 'require'],
                ['card_id', '点卡ID不正確', 'number'],
                ['denom', '面额必須', 'require'],
                ['denom', '面额長度超出範圍', 'length', '1,30'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 處理 is_hidden
            if (!isset($_POST['is_hidden'])) $_POST['is_hidden'] = 0;
            if (!isset($_POST['is_deny_publish'])) $_POST['is_deny_publish'] = 0;
            // 檢測是否已存在相同名字的遊戲
            $map                    = ['id'=>['neq', $_POST['id']], 'card_id'=>$_POST['card_id'], 'denom'=>$_POST['denom']];
            $count                  = $CardDenomModel->where($map)->count();
            if ($count) {
                return Response::error('已經存在同名面额');
            }
            // 保存數據
            if (!$CardDenomModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            $map                    = ['id'=>$_POST['id']];
            if (false === $CardDenomModel->where($map)->save()) {
                return Response::error('編輯失敗');
            }
            // 編輯成功
            return Response::success('編輯成功', '', U('index', ['card_id'=>$_POST['card_id']]));
        } else {
            $row    = $CardDenomModel->getbyId(Input::get('id'));
            if (empty($row)) {
                return Response::error('參數錯誤或已被刪除');
            }
            return V('add', ['row'=>$row]);
        }
    }
}