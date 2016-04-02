<?php
namespace app\usercenter\behavior;

use think\Session;
use think\Response;

class CheckAuth
{
    public function run(& $params)
    {
        // 检测是否被踢出
        $sessions   = (array) F('force_logout_sessions');
        if (Session::has('user_id') && in_array(session_id(), $sessions)) {
            session_regenerate_id(true);
            Session::destroy();
            Response::redirect(U('/user/login@www'));
            return;
        }
        // 未登入
        if (!Session::get('user_id')) {
            Response::redirect(U('/user/login@www'));
            exit;
        }
        return;
    }
}