<?php
namespace app\admin\controller\goods;

use think\Response;
use think\Input;
use org\Validate;
use app\admin\controller\Common;

class Type extends Common
{
    public function index()
    {
        $rows   = D('GoodsType')->select();
        return V('', ['rows'=>$rows]);
    }

    // ALTER TABLE t8_goods_type AUTO_INCREMENT = 10;
    public function del()
    {
        $map    = [
            'id'    => ['in', Input::request('id')]
        ];
        if (false===D('GoodsType')->where($map)->delete()) {
            return Response::error('刪除失敗');
        }
        return Response::success('刪除成功', '', U('index'));
    }

    public function add()
    {
        if (IS_POST) {
            $rules  = [
                ['type_name', '名稱必須', 'require'],
                ['type_name', '名稱長度超出範圍', 'length', '6,30'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 處理 is_hidden
            if (!isset($_POST['is_hidden'])) $_POST['is_hidden'] = 0;
            if (!isset($_POST['is_deny_publish'])) $_POST['is_deny_publish'] = 0;
            // 編輯數據
            $GoodsTypeModel     = D('GoodsType');
            if (!$GoodsTypeModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            if (!$GoodsTypeModel->add()) {
                return Response::error('新增失敗');
            }
            // 編輯成功
            return Response::success('新增成功', '', U('index'));
        } else {
            return V('');
        }
    }

    public function edit()
    {
        if (IS_POST) {
            $rules  = [
                ['id', '缺少參數ID', 'require'],
                ['id', '參數ID錯誤', 'number'],
                ['type_name', '名稱必須', 'require'],
                ['type_name', '名稱長度超出範圍', 'length', '6,30'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 處理 is_hidden
            if (!isset($_POST['is_hidden'])) $_POST['is_hidden'] = 0;
            if (!isset($_POST['is_deny_publish'])) $_POST['is_deny_publish'] = 0;
            // 編輯數據
            $GoodsTypeModel     = D('GoodsType');
            if (!$GoodsTypeModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            $map                = ['id'=>$_POST['id']];
            if (false===$GoodsTypeModel->where($map)->save()) {
                return Response::error('編輯失敗');
            }
            // 編輯成功
            return Response::success('編輯成功', '', U('index'));
        } else {
            $row    = D('GoodsType')->getbyId(Input::get('id'));
            if (empty($row)) {
                return Response::error('參數錯誤或已被刪除');
            }
            return V('add', ['row'=>$row]);
        }
    }
}