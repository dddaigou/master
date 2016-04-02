<?php 
namespace app\api\behavior;

use org\Ip;
use think\Response;
use think\Config;

class CheckRequest
{
    public function run(& $params)
    {
        $ip         = Ip::getIp();
        $ips        = explode('.', $ip);
        $check_ip   = join('.', [isset($ips[0])?$ips[0]:0, isset($ips[1])?$ips[1]:0]);
        if (in_array($check_ip, ['127.0', '192.168'])) return;
        // 非法请求
        Response::send(Response::error('非法請求'), Response::type(), Config::get('response_return'));
        exit;
    }
}