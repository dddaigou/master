<?php 
namespace app\common\behavior;

use org\Ip;
use think\Response;
use think\Session;

class CheckDenyIp
{
    private $checkModules   = ['index', 'mobile', 'usercenter'];
    private $skipAction     = [
        'index/maintain/index', 
        'mobile/maintain/index',
        'index/maintain/deny', 
        'mobile/maintain/deny',
    ];

    public function run(& $params) {
        // 检测module
        if (!in_array(MODULE_NAME, $this->checkModules)) return;
        if (in_array(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME, $this->skipAction)) return;
        // 当前IP
        $ip         = Ip::getIp();
        $deny_ips   = (array) F('deny_ip');
        if (in_array($ip, $deny_ips)) {
            Response::redirect('/maintain/deny');
            exit;
        }
        return;
    }
}