<?php 
namespace app\admin\controller;

use think\Input;
use think\Response;
use org\Page;
use org\Validate;
use logic\game\Type as GameTypeLogic;
use logic\game\GoodsType as GoodsTypeLogic;
use app\common\util\Phonetic;

class Game extends Common
{
    public function index()
    {
        // 接收参数
        $map        = [];
        if (Input::get('type/s')) {
            $map['type']    = Input::get('type/s');
        }
        if (Input::get('keyword/s')) {
            $map['name']    = ['like', '%'.Input::get('keyword/s').'%'];
        }
        $GameModel  = D('Game');
        // 统计数量
        $count      = $GameModel->where($map)->count();
        // 分页
        $Pager      = new Page($count);
        // limit
        $limit      = intval($Pager->firstRow).','.intval($Pager->listRows);
        // 数据
        $rows       = $GameModel->where($map)->order("id DESC")->limit($limit)->select();
        // 显示模板
        return V('', ['count'=>$count, 'pager'=>$Pager->show(), 'rows'=>$rows, 'types'=>GameTypeLogic::get()]);
    }

    public function del()
    {
        // 刪除遊戲
        if (false===D('Game')->where(['id'=>['in', Input::request('id')]])->delete()) {
            return Response::error('刪除失敗');
        }
        // 同時刪除伺服器
        D('GameServer')->where(['game_id'=>['in', Input::request('id')]])->delete();
        return Response::success('刪除成功', '', '/game/index');
    }

    public function add()
    {
        if (IS_POST) {
            $rules  = [
                ['type', '類型必須', 'require'],
                ['type', '類型不正確', 'in', 'pc,mobile,web'],
                ['name', '名稱必須', 'require'],
                ['name', '名稱長度超出範圍', 'length', '6,30'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 處理 is_hidden
            if (!isset($_POST['is_hidden'])) $_POST['is_hidden'] = 0;
            if (!isset($_POST['is_deny_publish'])) $_POST['is_deny_publish'] = 0;
            if (!isset($_POST['is_new'])) $_POST['is_new'] = 0;
            if (!isset($_POST['is_hot'])) $_POST['is_hot'] = 0;
            // 處理 goods_type
            if (empty($_POST['goods_type'])) {
                $_POST['goods_type']   = '';
            } elseif (!empty($_POST['goods_type']) && is_array($_POST['goods_type'])) {
                $_POST['goods_type']   = join(',', $_POST['goods_type']);
            }
            // 处理注音
            $phonetic               = Phonetic::get($_POST['name']);
            $_POST['is_chinese']    = isset($phonetic[0]) ? $phonetic[0] : 0;
            $_POST['phonetic']      = isset($phonetic[1]) ? $phonetic[1] : '';
            // 加載模型
            $GameModel              = D('Game');
            // 檢測是否已存在相同名字的遊戲
            $map                    = ['type'=>$_POST['type'], 'name'=>$_POST['name']];
            $count                  = $GameModel->where($map)->count();
            if ($count) {
                return Response::error('已經存在同名遊戲');
            }
            // 保存數據
            if (!$GameModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            if (!$GameModel->add()) {
                return Response::error('新增失敗');
            }
            // 編輯成功
            return Response::success('新增成功', '', '/game/index');
        } else {
            return V('add', ['types'=>GameTypeLogic::get(), 'goods_types'=>GoodsTypeLogic::load()]);
        }
    }

    public function edit()
    {
        $GameModel  = D('Game');
        if (IS_POST) {
            $rules  = [
                ['id', '缺少參數', 'require'],
                ['id', '參數錯誤', 'number'],
                ['type', '類型必須', 'require'],
                ['type', '類型不正確', 'in', 'pc,mobile,web'],
                ['name', '名稱必須', 'require'],
                ['name', '名稱長度超出範圍', 'length', '6,30'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 處理 is_hidden
            if (!isset($_POST['is_hidden'])) $_POST['is_hidden'] = 0;
            if (!isset($_POST['is_deny_publish'])) $_POST['is_deny_publish'] = 0;
            if (!isset($_POST['is_new'])) $_POST['is_new'] = 0;
            if (!isset($_POST['is_hot'])) $_POST['is_hot'] = 0;
            // 處理goods_type
            if (empty($_POST['goods_type'])) {
                $_POST['goods_type']   = '';
            } elseif (!empty($_POST['goods_type']) && is_array($_POST['goods_type'])) {
                $_POST['goods_type']   = join(',', $_POST['goods_type']);
            }
            // 处理注音
            $phonetic               = Phonetic::get($_POST['name']);
            $_POST['is_chinese']    = isset($phonetic[0]) ? $phonetic[0] : 0;
            $_POST['phonetic']      = isset($phonetic[1]) ? $phonetic[1] : '';
            // 加載模型
            $GameModel              = D('Game');
            // 檢測是否已存在相同名字的遊戲
            $map                    = ['id'=>['neq', $_POST['id']], 'type'=>$_POST['type'], 'name'=>$_POST['name']];
            $count                  = $GameModel->where($map)->count();
            if ($count) {
                return Response::error('已經存在同名遊戲');
            }
            // 保存數據
            if (!$GameModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            $map                    = ['id'=>$_POST['id']];
            if (false === $GameModel->where($map)->save()) {
                return Response::error('編輯失敗');
            }
            // 編輯成功
            return Response::success('編輯成功', '', '/game/index');
        } else {
            $row    = $GameModel->getbyId(Input::get('id'));
            if (empty($row)) {
                return Response::error('參數錯誤或已被刪除');
            }
            return V('add', ['row'=>$row, 'types'=>GameTypeLogic::get(), 'goods_types'=>GoodsTypeLogic::load()]);
        }
    }

    public function toConfig()
    {
        // 獲取遊戲列表
        $configs        = \logic\game\Config::getAllGame(true);
        $config_file    = dirname($_SERVER['SCRIPT_FILENAME']).'/static/js/config/game.js';
        if (false===file_put_contents($config_file, "define(function (require, exports, module){\nreturn ".json_encode($configs, JSON_UNESCAPED_UNICODE).";\n});")) {
            return Response::error('更新配置失敗', '', U('game/index'));
        }
        // 保存到緩存配置
        F('game.inc', $configs);
        // 按注音導出配置
        $configs        = \logic\game\Config::getAllGameByPhonetic();
        F('game_phonetic.inc', $configs);
        return Response::success('更新配置成功', '', U('game/index'));
    }
}