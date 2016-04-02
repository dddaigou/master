<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

return [
    // 生成运行时目录
    '__dir__'  => ['runtime/cache', 'runtime/log', 'runtime/temp', 'runtime/template'],
    '__file__' => ['common.php', 'init.php', 'config.php', 'config.debug.php', 'config.dev.php'],

    // 定义index模块的自动生成
    'index'    => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view'],
        'controller' => ['Index'],
        'model'      => [],
        'view'       => ['index/index'],
    ],
    // 。。。 其他更多的模块定义
    'common'    => [
        '__dir__'   => ['model', 'logic', 'service', 'util'],
        'model'     => ['Config', 'Game', 'GameServer', 'Goods', 'GoodsDescription', 'GoodsType', 'SmsOtp', 'Trade', 'TradeSchedule', 'User', 'UserCertifyApply', 'UserLoginLog', 'UserOnline', 'UserValidateIp', 'Feedback'],
    ],
    'admin'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view'],
    ],
    'api'       => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view'],
    ],
    'crontab'   => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view'],
    ],
    'mobile'    => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view'],
    ],
];
