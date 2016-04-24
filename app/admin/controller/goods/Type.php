<?php
namespace app\admin\controller\goods;

use think\Response;
use think\Input;
use org\Validate;
use app\admin\controller\Common;
use app\common\util\Tree;
use logic\goods\Type as GoodsType;

class Type extends Common
{
    public function index()
    {
        $GoodsTypeModel     = D('GoodsType');
        $rows   = $GoodsTypeModel->where('parent_id=0')->select();
        // dump($res_tree);exit;
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
    //获取子类
    public function getSubType()
    {
        $parent_id = Input::get('id/d',0);
        $all = Input::get('all/d',0);
        $res    = GoodsType::getstype($parent_id,$all);
        $res    = json_encode($res);
    }
    public function add()
    {
        if (IS_POST) {
            $rules  = [
                ['name', '名稱必須', 'require'],
                ['name', '名稱長度超出範圍', 'length', '6,30'],
                ['level', '层级有误', '>=', '0'],
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            if (!isset($_POST['status'])) $_POST['status'] = 0;
            $_POST['level']     += 1;
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
            $parent_id      = Input::get('id');
            if ($parent_id) {
                $parent_data    = D('GoodsType')->find($parent_id);
            }else{
                $parent_data = ['id'=>0,'name'=>'顶级','level'=>0];
            }
            return V('',['data'=>$parent_data]);
        }
    }

    public function edit()
    {
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
            if ($id=Input::get('id')) {
                $row    = M()->table('dd_goods_type as a')
                ->join('left join dd_goods_type as b on a.parent_id=b.id')
                ->field('a.*,b.name as parent_name')
                ->where('a.id='.Input::get('id'))
                ->find();
            }
            if (empty($row)) {
                return Response::error('參數錯誤或已被刪除');
            }
            if ($row['parent_id']==0) {
                $row['parent_name'] = '顶级';
            }
            return V('add', ['row'=>$row]);
        }
    }
    public function saveToConfig()
    {
        $res    = GoodsType::getstype(0,1);
        $config_file    = dirname($_SERVER['SCRIPT_FILENAME']).'/static/js/config/goodsType.js';
        if (false===file_put_contents($config_file, "define(function (require, exports, module){\nreturn ".json_encode($res, JSON_UNESCAPED_UNICODE).";\n});")) {
            return Response::error('更新配置失敗', '', U('index'));
        }else{
            F('goodsType.inc', $res);
            return Response::success('更新配置成功', '', U('index'));
        }
    }
}