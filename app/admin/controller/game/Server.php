<?php 
namespace app\admin\controller\game;

use think\Input;
use think\Response;
use org\Page;
use org\Validate;
use app\admin\controller\Common;

class Server extends Common
{
    public function index()
    {
        // 接收参数
        $map                = [];
        if (!Input::get('game_id/d')) {
            return Response::error('參數錯誤', '', U('game/index'));
        }
        $map['game_id']     = Input::get('game_id/d');
        $GameServerModel    = D('GameServer');
        // 数据
        $rows               = $GameServerModel->where($map)->order("id ASC")->select();
        // 显示模板
        return V('', ['rows'=>$rows]);
    }

    public function del()
    {
        // 同時刪除伺服器
        D('GameServer')->where(['id'=>['in', Input::request('id')]])->delete();
        return Response::success('刪除成功', '', U('index', ['game_id'=>Input::get('game_id')]));
    }

    public function add()
    {
        if (IS_POST) {
            $rules  = [
                ['game_id', '遊戲ID必須', 'require'],
                ['game_id', '遊戲ID不正確', 'number'],
                ['name', '名稱必須', 'require'],
                ['name', '名稱長度超出範圍', 'length', '1,30'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 處理 is_hidden
            if (!isset($_POST['is_hidden'])) $_POST['is_hidden'] = 0;
            if (!isset($_POST['is_deny_publish'])) $_POST['is_deny_publish'] = 0;
            // 加載模型
            $GameServerModel        = D('GameServer');
            // 檢測是否已存在相同名字的遊戲
            $map                    = ['game_id'=>$_POST['game_id'], 'name'=>$_POST['name']];
            $count                  = $GameServerModel->where($map)->count();
            if ($count) {
                return Response::error('已經存在同名伺服器');
            }
            // 保存數據
            if (!$GameServerModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            if (!$GameServerModel->add()) {
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
        $GameServerModel        = D('GameServer');
        if (IS_POST) {
            $rules                  = [
                ['id', '缺少參數', 'require'],
                ['id', '參數錯誤', 'number'],
                ['game_id', '遊戲ID必須', 'require'],
                ['game_id', '遊戲ID不正確', 'number'],
                ['name', '名稱必須', 'require'],
                ['name', '名稱長度超出範圍', 'length', '1,30'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 處理 is_hidden
            if (!isset($_POST['is_hidden'])) $_POST['is_hidden'] = 0;
            if (!isset($_POST['is_deny_publish'])) $_POST['is_deny_publish'] = 0;
            // 檢測是否已存在相同名字的遊戲
            $map                    = ['id'=>['neq', $_POST['id']], 'game_id'=>$_POST['game_id'], 'name'=>$_POST['name']];
            $count                  = $GameServerModel->where($map)->count();
            if ($count) {
                return Response::error('已經存在同名伺服器');
            }
            // 保存數據
            if (!$GameServerModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            $map                    = ['id'=>$_POST['id']];
            if (false === $GameServerModel->where($map)->save()) {
                return Response::error('編輯失敗');
            }
            // 編輯成功
            return Response::success('編輯成功', '', U('index', ['game_id'=>$_POST['game_id']]));
        } else {
            $row    = $GameServerModel->getbyId(Input::get('id'));
            if (empty($row)) {
                return Response::error('參數錯誤或已被刪除');
            }
            return V('add', ['row'=>$row]);
        }
    }
}