<?php
namespace app\common\behavior;

use think\Session;
use think\Log;
use app\common\util\Configure;
use org\Ip;
use logic\user\Online;
use logic\admin\User as AdminUser;

class RefreshOnline
{
    private $checkModules   = ['index', 'mobile', 'usercenter'];

    public function run(& $params)
    {
        // 检测module
        if (!in_array(MODULE_NAME, $this->checkModules)) return;
        // 登入後臺則不記錄
        if (AdminUser::isLogin()) return;
        // 查詢系統定義在線時間
        $interval       = Configure::get('system.online_interval', 120);
        // 檢測上次寫入時間
        $last_time      = Session::get('online_last_update', 0);
        // 距離上次未超過間隔
        if (time() < ($last_time+$interval)) return;
        // 更新Online
        $ip             = Ip::getIp();
        $ip_info        = Ip::find($ip);
        $country_code   = isset($ip_info[0]) ? $ip_info[0] : 'N/A';
        Online::add(session_id(), Session::get('user_id'), $ip, $country_code);
        // 更新時間
        Session::set('online_last_update', time());
    }
}