<?php
namespace app\admin\controller;

use think\Input;
use think\Response;
use org\Validate;

class Config extends Common
{
    public function index()
    {
        $ConfigModel    = D('Config');
        // 获取所有配置组
        $types          = $ConfigModel->field('DISTINCT type')->select();
        // 获取当前type
        if (Input::get('type')) {
            $type       = Input::get('type');
        } else {
            $type       = isset($types[0]['type']) ? $types[0]['type'] : '';
        }
        // 获取配置列表
        $map            = ['type'=>$type];
        $rows           = $ConfigModel->where($map)->select();
        return V('', ['types'=>$types, 'type'=>$type, 'rows'=>$rows]);
    }

    public function del()
    {
        $map    = [
            'id'    => ['in', Input::request('id')]
        ];
        if (false===D('Config')->where($map)->delete()) {
            return Response::error('刪除失敗');
        }
        return Response::success('刪除成功', '', '/config/index');
    }

    public function add()
    {
        $ConfigModel        = D('Config');
        if (IS_POST) {
            if (empty($_POST['type']) && !empty($_POST['new_type'])) {
                $_POST['type']  = $_POST['new_type'];
            }
            // 数据校验
            $rules          = [
                ['type', '配置分组必须', 'require'],
                ['type', '配置分组格式不对[0-9a-z\_]', 'regex', '/[0-9a-z\_]{3,20}/'],
                ['name', '配置名必须', 'require'],
                ['name', '配置名格式不对[0-9a-z\_]', 'regex', '/[0-9a-z\_]{1,100}/'],
                ['value', '配置项必须', 'require'],
                ['value', '配置不能为空', 'length', '1,10000000'],
            ];
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 检测是否存在相同的配置
            $map            = [
                'type'  => Input::post('type'),
                'name'  => Input::post('name'),
            ];
            $count          = $ConfigModel->where($map)->count();
            if ($count) {
                return Response::error('已经存在相同配置项');
            }
            if (!$ConfigModel->create($_POST)) {
                return Response::error('数据有误');
            }
            if (!$ConfigModel->add()) {
                return Response::error('保存失败');
            }
            return Response::success('保存成功', '', '/config/index');
        } else {
            // 获取所有配置组
            $types          = $ConfigModel->field('DISTINCT type')->select();
            return V('add', ['types'=>$types]);
        }
    }

    public function edit()
    {
        $ConfigModel        = D('Config');
        if (IS_POST) {
            // 数据校验
            $rules          = [
                ['id', '缺少参数', 'require'],
                ['id', '参数错误', 'number'],
                ['type', '配置分组必须', 'require'],
                ['type', '配置分组格式不对[0-9a-z\_]', 'regex', '/[0-9a-z\_]{3,20}/'],
                ['name', '配置名必须', 'require'],
                ['name', '配置名格式不对[0-9a-z\_]', 'regex', '/[0-9a-z\_]{1,100}/'],
                ['value', '配置项必须', 'require'],
                ['value', '配置不能为空', 'length', '1,10000000'],
            ];
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 检测是否存在相同的配置
            $map            = [
                'id'    => ['neq', Input::post('id')],
                'type'  => Input::post('type'),
                'name'  => Input::post('name'),
            ];
            $count          = $ConfigModel->where($map)->count();
            if ($count) {
                return Response::error('已经存在相同配置项');
            }
            $map            = ['id'=>Input::post('id')];
            if (!$ConfigModel->create($_POST)) {
                return Response::error('数据有误');
            }
            if (false===$ConfigModel->where($map)->save()) {
                return Response::error('保存失败');
            }
            return Response::success('保存成功', '', '/config/index');
        } else {
            // 获取数据
            $row            = $ConfigModel->getbyId(Input::get('id'));
            if (empty($row)) {
                return Response::error('参数错误或资料已被删除', '', '/config/index');
            }
            // 获取所有配置组
            $types          = $ConfigModel->field('DISTINCT type')->select();
            return V('add', ['types'=>$types, 'row'=>$row]);
        }
    }

    public function write()
    {
        if (false === \app\common\util\Configure::cache()) {
            return Response::error('更新配置失敗', '', U('index'));
        }
        return Response::success('更新配置成功', '', U('index'));
    }
}
