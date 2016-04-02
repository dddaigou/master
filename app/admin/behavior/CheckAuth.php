<?php
namespace app\admin\behavior;

use think\Session;
use think\Response;
use logic\admin\User;
use app\common\util\MCARAuth as Auth;

class CheckAuth
{
    public function run(&$params){
        // 权限检测
        if (in_array(CONTROLLER_NAME.'/'.ACTION_NAME, ['index/login'])) return;
        // 未登入
        if (!User::isLogin()) {
            Response::redirect('/index/login');
            exit;
        } elseif (!User::getLoginGroupId()) {
            User::setLogout();
            echo Response::error('權限不足', null, '/index/login');
            exit;
        }
        // 检测权限，没有权限则返回上一级
        $key    = 'purview_'.User::getLoginGroupId();
        $rules  = (array) F($key);
        if (!Auth::check($rules)) {
            echo Response::error('權限不足');
            exit;
        }
    }
}