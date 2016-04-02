<?php
namespace app\crontab\controller;

use think\Input;

class User extends Common
{
    // 清理 online
    public function clearUserOnline()
    {
        $map            = [
            'online_time'   =>['lt', date('Y-m-d H:i:s', strtotime('-130 second'))],
        ];
        $UserOnlineModel= D('UserOnline');
        $affected_rows  = $UserOnlineModel->where($map)->delete();
        Input::get('debug') && self::log($UserOnlineModel->getLastSql());
        if (false === $affected_rows) {
            self::log('clear faild', 1);
        }
        self::log("clear success, affected_rows:{$affected_rows}");
    }

    // 缓存在线人数
    public function updateOnlineUserStatis()
    {
        $count          = D('UserOnline')->comment('crontab.user.update_online_user_statis')->count();
        if (false === F('index_online_user_count', $count)) {
            self::log('update index_online_user_count config faild', 1);
        }
        self::log('update index_online_user_count config success');
    }
}