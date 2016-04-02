<?php 
namespace app\admin\controller\admin;

use think\Input;
use think\Response;
use org\Validate;
use app\admin\controller\Common;

class Group extends Common
{
    public function index()
    {
        // 查询所有管理员
        $rows   = D('AdminGroup')->select();
        return V('', ['rows'=>$rows, 'keyword'=>Input::get('keyword')]);
    }

    public function del()
    {
        $map    = [
            'id'    => ['in', Input::request('id')]
        ];
        if (false===D('AdminGroup')->where($map)->delete()) {
            return Response::error('刪除失敗');
        }
        return Response::success('刪除成功', '', U('index'));
    }

    public function add()
    {
        // 处理新增逻辑
        if (IS_POST) {
            $rules  = [
                ['name', '分组名必须', 'require'],
                ['name', '分组名长度过长', 'length', '1,30'],
            ];
            // 检测参数
            if (!Validate::valid(Input::post(), $rules)) {
                return Response::error(Validate::getError(), ['fail_field'=>Validate::getFailFeild()]);
            }
            // 处理 purviews
            if (isset($_POST['purviews']) && is_array($_POST['purviews'])) {
                $_POST['purviews']  = join(';', $_POST['purviews']);
            } else {
                $_POST['purviews']  = '';
            }
            // 保存数据
            $AdminGroupModel= D('AdminGroup');
            // 检测是否存在相同工号的用户
            $map            = ['name'=>Input::post('name')];
            $count          = $AdminGroupModel->where($map)->count();
            if ($count) {
                return Response::error("已经存在相同分组");
            }
            // 新增用户
            if (!$AdminGroupModel->create($_POST)) {
                return Response::error('数据有误');
            }
            if (!$AdminGroupModel->add()) {
                return Response::error('保存失败');
            }
            return Response::success('新增成功', '', U('index'));
        } else {
            $purviews       = is_file(MODULE_PATH.'purviews.php') ? include MODULE_PATH.'purviews.php' : [];
            return V('', ['purviews'=>$purviews]);
        }
    }

    public function edit()
    {
        // 处理提交编辑逻辑
        if (IS_POST) {
            $rules  = [
                ['id', 'ID必须', 'require'],
                ['id', 'ID不正确', 'number'],
                ['name', '分组名必须', 'require'],
                ['name', '分组名长度过长', 'length', '1,30'],
            ];
            // 检测参数
            if (!Validate::valid(Input::post(), $rules)) {
                return Response::error(Validate::getError(), ['fail_field'=>Validate::getFailFeild()]);
            }
            // 处理 purviews
            if (isset($_POST['purviews']) && is_array($_POST['purviews'])) {
                $_POST['purviews']  = join(';', $_POST['purviews']);
            } else {
                $_POST['purviews']  = '';
            }
            // 保存数据
            $AdminGroupModel= D('AdminGroup');
            $map            = ['id'=>['neq', Input::post('id')], 'name'=>Input::post('name')];
            if (!$AdminGroupModel->create($_POST)) {
                return Response::error('数据有误');
            }
            $map            = ['id'=>Input::post('id')];
            if (false === $AdminGroupModel->where($map)->save()) {
                return Response::error('保存失败');
            }
            return Response::success('编辑成功', '', U('index'));
        } else {
            // 接收参数
            $id                 = (int) Input::get('id');
            if ($id<=0) {
                return Response::error('参数错误');
            }
            // 查询资料
            $row                = D('AdminGroup')->getbyId($id);
            $row['purviews']    = explode(';', $row['purviews']);
            // 权限列表
            $purviews           = is_file(MODULE_PATH.'purviews.php') ? include MODULE_PATH.'purviews.php' : [];
            return V('add', ['row'=>$row, 'purviews'=>$purviews]);
        }
    }

    public function cache()
    {
        $rows   = D('AdminGroup')->select();
        foreach((array) $rows as $row) {
            $purviews   = !empty($row['purviews']) ? preg_split('/,|;/', $row['purviews']) : [];
            if (false === F("purview_{$row['id']}", $purviews)) {
                return Response::error('更新配置失败', '', U('index'));
            }
        }
        return Response::success('更新配置成功', '', U('index'));
    }
}