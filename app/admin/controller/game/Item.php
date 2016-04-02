<?php 
namespace app\admin\controller\game;

use think\Input;
use think\Response;
use org\Page;
use org\Validate;
use logic\game\Option as GameOptionLogic;
use app\admin\controller\Common;

class Item extends Common
{
    public function index()
    {
        // 接收参数
        $map                = [];
        if (!Input::get('game_id/d')) {
            return Response::error('參數錯誤', '', U('game/index'));
        }
        $map['game_id']     = Input::get('game_id/d');
        $GameItemModel      = D('GameItem');
        // 数据
        $rows               = $GameItemModel->where($map)->order("id ASC")->select();
        // 显示模板
        return V('', ['rows'=>$rows]);
    }

    public function del()
    {
        // 同時刪除伺服器
        D('GameItem')->where(['id'=>['in', Input::request('id')]])->delete();
        return Response::success('刪除成功', '', U('index', ['game_id'=>Input::get('game_id')]));
    }

    public function add()
    {
        if (IS_POST) {
            $rules  = [
                ['game_id', '遊戲ID必須', 'require'],
                ['game_id', '遊戲ID不正確', 'number'],
                ['name', '品項名稱必須', 'require'],
                ['name', '品項名稱長度超出範圍', 'length', '6,120'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 加載模型
            $GameItemModel          = D('GameItem');
            // 檢測是否已存在相同名字的遊戲
            $map                    = ['game_id'=>$_POST['game_id'], 'name'=>$_POST['name']];
            $count                  = $GameItemModel->where($map)->count();
            if ($count) {
                return Response::error('已經存在同名品项');
            }
            // 保存數據
            if (!$GameItemModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            if (!$GameItemModel->add()) {
                return Response::error('新增失敗');
            }
            // 編輯成功
            return Response::success('新增成功', '', U('index', ['game_id'=>$_POST['game_id']]));
        } else {
            return V('add');
        }
    }

    public function edit()
    {
        $GameItemModel              = D('GameItem');
        if (IS_POST) {
            $rules                  = [
                ['id', '缺少參數', 'require'],
                ['id', '參數錯誤', 'number'],
                ['game_id', '遊戲ID必須', 'require'],
                ['game_id', '遊戲ID不正確', 'number'],
                ['name', '品項名稱必須', 'require'],
                ['name', '品項名稱長度超出範圍', 'length', '6,120'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 處理options
            if (empty($_POST['options'])) {
                $_POST['options']   = '';
            } elseif (!empty($_POST['options']) && is_array($_POST['options'])) {
                $_POST['options']   = join(',', $_POST['options']);
            }
            // 檢測是否已存在相同名字的遊戲
            $map                    = ['id'=>['neq', $_POST['id']], 'game_id'=>$_POST['game_id'], 'name'=>$_POST['name']];
            $count                  = $GameItemModel->where($map)->count();
            if ($count) {
                return Response::error('已經存在同名伺服器');
            }
            // 保存數據
            if (!$GameItemModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            $map                    = ['id'=>$_POST['id']];
            if (false === $GameItemModel->where($map)->save()) {
                return Response::error('編輯失敗');
            }
            // 編輯成功
            return Response::success('編輯成功', '', U('index', ['game_id'=>$_POST['game_id']]));
        } else {
            $row                    = $GameItemModel->getbyId(Input::get('id'));
            if (empty($row)) {
                return Response::error('參數錯誤或已被刪除');
            }
            return V('add', ['row'=>$row]);
        }
    }
}