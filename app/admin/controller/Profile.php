<?php 
namespace app\admin\controller;

use think\Response;
use org\Validate;
use app\common\util\Crypt;
use logic\admin\User as AdminUserLogic;

class Profile extends Common
{
    public function index()
    {
        if (IS_POST) {
            $rules  = [
                ['realname', '姓名必須', 'require'],
                ['nickname', '暱稱必須', 'require']
            ];
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            $data   = [
                'realname'  => $_POST['realname'],
                'nickname'  => $_POST['nickname'],
            ];
            $AdminUserModel = D('AdminUser');
            if (!$AdminUserModel->create($data)) {
                return Response::error('數據有誤');
            }
            if (false === $AdminUserModel->where(['id'=>AdminUserLogic::getLoginUserId()])->save()) {
                return Response::error('保存失敗');
            }
            return Response::success('保存成功');
        } else {
            $row    = D('AdminUser')->where(['id'=>AdminUserLogic::getLoginUserId()])->find();
            return V('', ['row'=>$row]);
        }
    }

    public function password()
    {
        if (IS_POST) {
            $rules  = [
                ['current_password', '當前密碼必須', 'require'],
                ['password', '新密码必须', 'require'],
                ['password', '新密码长度不正确', 'length', '6,20'],
                ['password', '新密碼與當前密碼相同', 'function', function($password=''){
                    return $_POST['current_password']==$password ? false : true;
                }],
                ['password_confirm', '两次密码不相符', 'confirm', 'password'],
            ];
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 校驗舊密碼
            $user_id            = AdminUserLogic::getLoginUserId();
            $map                = ['id'=>$user_id];
            $AdminUserModel     = D('AdminUser');
            $row                = $AdminUserModel->where($map)->find();
            $current_password   = Crypt::encryptAdminUserLoginPwd($user_id, $_POST['current_password']);
            if ($current_password!=$row['login_pwd']) {
                return Response::error('當前密碼不正確');
            }
            // 修改密碼
            $login_pwd          = Crypt::encryptAdminUserLoginPwd($user_id, $_POST['password']);
            if (false === $AdminUserModel->where($map)->setField('login_pwd', $login_pwd)) {
                return Response::error('修改失敗');
            }
            return Response::success('修改成功');
        } else {
            return V();
        }
    }
}