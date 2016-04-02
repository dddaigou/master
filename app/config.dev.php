<?php
return [
    'url_domain_root'   => '.dev.dddaigou.net',
    // DATABASE
    'database'      => [
        'type'      => 'mysql',         // 数据库类型
        'hostname'  => '127.0.0.1',  // 服务器地址
        'database'  => 'dddaigou',        // 数据库名
        'username'  => 'test',          // 数据库用户名
        'password'  => 'aaaaaa', // 数据库密码
        'hostport'  => '3306',          // 数据库连接端口
        'charset'   => 'utf8',          // 数据库编码默认采用utf8
        'prefix'    => 'dd_',           // 数据库表前缀
        'debug'     => true,            // 调试开关
    ],
    // SESSION
    'session'       => [
        'auto_start'=> true,
        'domain'    => '.dev.dddaigou.net',
        'type'      => 'redis',
        'host'      => '127.0.0.1',
        'port'      => 6379,
    ],
    // CACHE
    'cache'         => [
        'type'      => 'redis',
        'host'      => '127.0.0.1',
        'port'      => 6379,
        'expire'    => 60,
    ],
    // api
    'api'           => [
        'url'       => 'http://127.0.0.1/',
        'host'      => 'api.dev.dddaigou.net'
    ],
];