<?php 
namespace app\admin\controller\admin;

use think\Input;
use think\Response;
use org\Validate;
use logic\admin\Group as UserGroup;
use app\common\util\Crypt;
use app\admin\controller\Common;

class User extends Common
{
    public function index()
    {
        // 查询条件
        $map    = [];
        if (''!=Input::get('keyword')) {
            $map['realname|nickname|id'] = Input::get('keyword');
        }
        // 查询所有管理员
        $rows   = D('AdminUser')->where($map)->select();
        return V('', ['rows'=>$rows, 'keyword'=>Input::get('keyword')]);
    }

    public function del()
    {
        $map    = [
            'id'    => ['in', Input::request('id')]
        ];
        if (false===D('AdminUser')->where($map)->delete()) {
            return Response::error('刪除失敗');
        }
        return Response::success('刪除成功', '', U('index'));
    }

    public function add()
    {
        // 处理新增逻辑
        if (IS_POST) {
            $rules  = [
                ['id', '工号必须', 'require'],
                ['id', '工号错误', 'number'],
                ['login_pwd', '密码必须', 'require'],
                ['login_pwd', '密码长度不正确', 'length', '6,20'],
                ['login_pwd_confirm', '两次密码不相符', 'confirm', 'login_pwd'],
                ['realname', '姓名必须', 'require'],
                ['nickname', '昵称必须', 'require'],
                ['group_id', '缺少参数[group_id]', 'require'],
                ['group_id', '参数[group_id]错误', 'number']
            ];
            // 检测参数
            if (!Validate::valid(Input::post(), $rules)) {
                return Response::error(Validate::getError(), ['fail_field'=>Validate::getFailFeild()]);
            }
            // 保存数据
            $AdminUserModel = D('AdminUser');
            // 检测是否存在相同工号的用户
            $map            = ['id'=>Input::post('id')];
            $count          = $AdminUserModel->where($map)->count();
            if ($count) {
                return Response::error("已经存在相同工号的管理员");
            }
            // 处理密码
            $_POST['login_pwd'] = Crypt::encryptAdminUserLoginPwd(Input::post('id'), Input::post('login_pwd'));
            // 新增用户
            if (!$AdminUserModel->create($_POST)) {
                return Response::error('数据有误');
            }
            if (!$AdminUserModel->add()) {
                return Response::error('保存失败');
            }
            return Response::success('新增成功', '', U('index'));
        } else {
            // 分组数据
            $groups = UserGroup::get();
            return V('', ['groups'=>$groups]);
        }
    }

    public function edit()
    {
        // 处理提交编辑逻辑
        if (IS_POST) {
            $rules  = [
                ['id', '缺少参数[id]', 'require'],
                ['id', '参数[id]错误', 'number'],
                ['realname', '姓名必须', 'require'],
                ['nickname', '昵称必须', 'require'],
                ['group_id', '缺少参数[group_id]', 'require'],
                ['group_id', '参数[group_id]错误', 'number']
            ];
            if (Input::post('login_pwd')) {
                $rules[]    = ['login_pwd', '密码长度不正确', 'length', '6,20'];
                $rules[]    = ['login_pwd_confirm', '两次密码不相符', 'confirm', 'login_pwd'];
            } else {
                if (isset($_POST['login_pwd'])) unset($_POST['login_pwd']);
            }
            // 检测参数
            if (!Validate::valid(Input::post(), $rules)) {
                return Response::error(Validate::getError(), ['fail_field'=>Validate::getFailFeild()]);
            }
            // 处理密码
            if (Input::post('login_pwd')) {
                $_POST['login_pwd'] = Crypt::encryptAdminUserLoginPwd(Input::post('id'), Input::post('login_pwd'));
            }
            // 保存数据
            $AdminUserModel = D('AdminUser');
            $map            = ['id'=>Input::post('id')];
            if (!$AdminUserModel->create($_POST)) {
                return Response::error('数据有误');
            }
            if (false === $AdminUserModel->where($map)->save()) {
                return Response::error('保存失败');
            }
            return Response::success('编辑成功', '', U('index'));
        } else {
            // 接收参数
            $id     = (int) Input::get('id');
            if ($id<=0) {
                return Response::error('参数错误');
            }
            // 查询资料
            $row    = D('AdminUser')->getbyId($id);
            // 分组数据
            $groups = UserGroup::get();
            return V('add', ['row'=>$row, 'groups'=>$groups]);
        }
    }
}