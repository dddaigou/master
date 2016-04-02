<?php
namespace app\common\behavior;

use think\Config;
use think\Response;
use app\common\util\Configure;
use org\Ip;

class CheckMaintenance
{
    private $checkModules   = ['index', 'mobile', 'usercenter'];
    private $skipAction     = [
        'index/maintain/index', 
        'mobile/maintain/index',
        'index/maintain/deny', 
        'mobile/maintain/deny',
    ];

    public function run(& $params)
    {
        // 检测module
        if (!in_array(MODULE_NAME, $this->checkModules)) return;
        if (in_array(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME, $this->skipAction)) return;
        // 如果是白名单则允许访问
        $ip_white_list  = Configure::get('maintain.allow_ip');
        $ip_white_list  = explode(',', $ip_white_list);
        if (in_array(Ip::getIp(), $ip_white_list)) return;
        // 检测维护
        if (!Configure::get('maintain.on')) return;
        // 跳转至维护页面
        $message        = Configure::get('maintain.message', '網站維護中...');
        // Response::redirect('/maintain');
        Response::send(Response::error($message, '', '/maintain', 1), Response::type(), Config::get('response_return'));
        exit;
    }
}