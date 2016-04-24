<?php
namespace app\admin\controller\goods;

use think\Response;
use think\Input;
use org\Validate;
use app\admin\controller\Common;

class Brand extends Common
{
    public function index()
    {
        $GoodsBrandModel     = D('GoodsBrand');
        $rows   = $GoodsBrandModel->select();
        // dump($res_tree);exit;
        return V('', ['rows'=>$rows]);
    }

    // ALTER TABLE t8_goods_type AUTO_INCREMENT = 10;
    public function del()
    {
        $map    = [
            'id'    => ['in', Input::request('id')]
        ];
        if (false=== D('GoodsBrand')->where($map)->delete()) {
            return Response::error('刪除失敗');
        }
        return Response::success('刪除成功', '', U('index'));
    }
    public function add()
    {
        if (IS_POST) {
            $GoodsBrandModel     = D('GoodsBrand');
            $rules  = [
                ['name', '名稱必須', 'require'],
                ['name', '名稱長度超出範圍', 'length', '6,30'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            if (!isset($_POST['status'])) $_POST['status'] = 0;
            $_POST['level']     += 1;
            // 編輯數據
            if (!$GoodsBrandModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            if (!$GoodsBrandModel->add()) {
                return Response::error('新增失敗');
            }
            // 編輯成功
            return Response::success('新增成功', '', U('index'));
        } else {
            $parent_id      = Input::get('id');
            if ($parent_id) {
                $parent_data    = $GoodsBrandModel->find($parent_id);
            }else{
                $parent_data = ['id'=>0,'name'=>'顶级','level'=>0];
            }
            return V('',['data'=>$parent_data]);
        }
    }

    public function edit()
    {
        $GoodsBrandModel     = D('GoodsBrand');
        if (IS_POST) {
            $rules  = [
                ['id', '缺少參數ID', 'require'],
                ['id', '參數ID錯誤', 'number'],
                ['name', '名稱必須', 'require'],
                ['name', '名稱長度超出範圍', 'length', '6,30'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            if (!isset($_POST['status'])) $_POST['status'] = 0;
            // 編輯數據
            if (!$GoodsBrandModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            $map                = ['id'=>$_POST['id']];
            if (false===$GoodsBrandModel->where($map)->save()) {
                return Response::error('編輯失敗');
            }
            // 編輯成功
            return Response::success('編輯成功', '', U('index'));
        } else {
            if ($id=Input::get('id')) {
                $row    = $GoodsBrandModel->find($id);
            }
            if (empty($row)) {
                return Response::error('參數錯誤或已被刪除');
            }
            return V('add', ['row'=>$row]);
        }
    }
    // public function saveToConfig()
    // {
    //     $res    = GoodsType::getstype(0,1);
    //     $config_file    = dirname($_SERVER['SCRIPT_FILENAME']).'/static/js/config/goodsType.js';
    //     if (false===file_put_contents($config_file, "define(function (require, exports, module){\nreturn ".json_encode($res, JSON_UNESCAPED_UNICODE).";\n});")) {
    //         return Response::error('更新配置失敗', '', U('index'));
    //     }else{
    //         F('goodsType.inc', $res);
    //         return Response::success('更新配置成功', '', U('index'));
    //     }
    // }
}