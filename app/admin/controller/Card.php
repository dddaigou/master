<?php 
namespace app\admin\controller;

use think\Input;
use think\Response;
use org\Validate;

class Card extends Common
{
    public function index()
    {
        // 接收参数
        $CardModel  = D('Card');
        // 数据
        $rows       = $CardModel->where($map)->order("id DESC")->select();
        // 显示模板
        return V('', ['rows'=>$rows]);
    }

    public function del()
    {
        // 刪除卡信息
        if (false===D('Card')->where(['id'=>['in', Input::request('id')]])->delete()) {
            return Response::error('刪除失敗');
        }
        // 删除面额信息
        D('CardDenom')->where(['card_id'=>['in', Input::request('id')]])->delete();
        return Response::success('刪除成功', '', U('index'));
    }

    public function add()
    {
        if (IS_POST) {
            $rules  = [
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
            // 处理 game_id
            if (!empty($_POST['relation_game_id']) && is_array($_POST['relation_game_id'])) {
                $_POST['relation_game_id'] = join(',', $_POST['relation_game_id']);
            }
            // 加載模型
            $CardModel              = D('Card');
            // 檢測是否已存在相同名字的遊戲
            $map                    = ['name'=>$_POST['name']];
            $count                  = $CardModel->where($map)->count();
            if ($count) {
                return Response::error('已經存在同名點卡');
            }
            // 保存數據
            if (!$CardModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            if (!$CardModel->add()) {
                return Response::error('新增失敗');
            }
            // 編輯成功
            return Response::success('新增成功', '', U('index'));
        } else {
            return V('add', ['games'=>\logic\game\Config::getAllGame(false)]);
        }
    }

    public function edit()
    {
        $CardModel              = D('Card');
        if (IS_POST) {
            $rules  = [
                ['id', '缺少參數', 'require'],
                ['id', '參數錯誤', 'number'],
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
            // 处理 game_id
            if (!empty($_POST['relation_game_id']) && is_array($_POST['relation_game_id'])) {
                $_POST['relation_game_id'] = array_filter($_POST['relation_game_id']);
                $_POST['relation_game_id'] = join(',', $_POST['relation_game_id']);
            }
            // 檢測是否已存在相同名字的遊戲
            $map                    = ['id'=>['neq', $_POST['id']], 'name'=>$_POST['name']];
            $count                  = $CardModel->where($map)->count();
            if ($count) {
                return Response::error('已經存在同名遊戲');
            }
            // 保存數據
            if (!$CardModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            $map                    = ['id'=>$_POST['id']];
            if (false === $CardModel->where($map)->save()) {
                return Response::error('編輯失敗');
            }
            // 編輯成功
            return Response::success('編輯成功', '', U('index'));
        } else {
            $row    = $CardModel->getbyId(Input::get('id'));
            if (empty($row)) {
                return Response::error('參數錯誤或已被刪除');
            }
            return V('add', ['row'=>$row, 'games'=>\logic\game\Config::getAllGame(false)]);
        }
    }

    public function toJsConfig()
    {
        // 獲取遊戲列表
        $configs        = \logic\card\Config::export();
        $config_file    = dirname($_SERVER['SCRIPT_FILENAME']).'/static/js/config/card.js';
        if (false===file_put_contents($config_file, "define(function (require, exports, module){\nreturn ".json_encode($configs, JSON_UNESCAPED_UNICODE).";\n});")) {
            return Response::error('更新配置失敗', '', U('card/index'));
        }
        F('card_denom',$configs);
        return Response::success('更新配置成功', '', U('card/index'));
    }
}