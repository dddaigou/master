<?php
namespace app\admin\controller;

use think\Session;
use think\Response;
use think\Input;
use app\common\util\Crypt;
use org\Page;
use org\Ip;
use logic\admin\User;
use logic\admin\LoginLog;

class Index extends Common
{
    public function index()
    {
    	echo 1;
        $navigation     = is_file(MODULE_PATH.'navigation.php') ? include MODULE_PATH.'navigation.php' : [];
        return V('', ['navigation'=>$navigation]);
        return ;
    }

    public function welcome()
    {
        return V();
    }

    public function login()
    {
        if (IS_POST) {
            $id             = Input::post('account/s', '');
            $login_pwd      = Input::post('password/s', '');
            $is_md5         = Input::post('is_md5/d', 0);
            $session_id     = session_id();
            $ip             = Ip::getIp();
            // 加载模型
            $AdminUserModel = D('AdminUser');
            // 检测用户是否存在
            $user_info  = $AdminUserModel->where(['id'=>$id])->find();
            if (empty($user_info)) {
                LoginLog::add($session_id, $ip, 0, 0, 0, 'invaild_id');
                return Response::error('帐号不存在');
            }
            // 检测密码是否正确
            if ($user_info['login_pwd']!=Crypt::encryptAdminUserLoginPwd($user_info['id'], $login_pwd)) {
                LoginLog::add($session_id, $ip, $user_info['id'], $user_info['group_id'], 0, 'invaild_pwd');
                return Response::error('帐号与密码不相符');
            }
            // 是否被停权
            if (0==$user_info['group_id']) {
                LoginLog::add($session_id, $ip, $user_info['id'], $user_info['group_id'], 0, 'deny_login');
                return Response::error('帐号已被停权');
            }
            // 更新最后登入成功时间
            $AdminUserModel->where(['id'=>$user_info['id']])->setField('last_login_time', date('Y-m-d H:i:s'));
            // 登入成功
            User::setLogin($user_info['id'], $user_info['group_id'], $user_info['realname']);
            LoginLog::add($session_id, $ip, $user_info['id'], $user_info['group_id'], 1, '');
            return Response::success('登入成功', '', '/');
        } else {
            return V();
        }
    }

    public function logout()
    {
        User::setLogout();
        return Response::error('登出成功', '', '/');
    }
}
