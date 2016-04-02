<?php 
namespace logic\admin;

use think\Config;

class LoginLog
{
    static protected $causes  = [
        'invaild_ip'    => '非法IP',
        'invaild_id'    => '错误帐号',
        'invaild_pwd'   => '错误密码',
        'deny_login'    => '停权',
    ];

    static public function cause($key=null)
    {
        if (is_null($key)) {
            return self::$causes;
        }
        return isset(self::$causes[$key]) ? self::$causes[$key] : '未知';
    }

    static public function add($session_id='', $login_ip='', $admin_user_id=0, $admin_group_id=0, $login_result=0, $fail_cause='')
    {
        $data       = get_defined_vars();
        $model      = D('AdminUserLoginLog');
        if (!$model->create($data)) {
            Log::record('admin user login data error');
            return false;
        }
        if (!$model->add()) {
            Log::record('admin user login data save fail');
            return false;
        }
        return true;
    }
}