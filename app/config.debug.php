<?php
return [
    'url_domain_root'   => '.debug.8591.com.hk',    
    // DATABASE
    'database'      => [
        'type'      => 'mysql',         // 数据库类型
        'hostname'  => '192.168.1.15',  // 服务器地址
        'database'  => 'hk8591',        // 数据库名
        'username'  => 'test',          // 数据库用户名
        'password'  => 'aaaaaa', // 数据库密码
        'hostport'  => '3306',          // 数据库连接端口
        'charset'   => 'utf8',          // 数据库编码默认采用utf8
        'prefix'    => 't8_',           // 数据库表前缀
        'debug'     => true,            // 调试开关
    ],
    // SESSION
    'session'       => [
        'auto_start'=> true,
        'domain'    => '.debug.8591.com.hk',
        'type'      => 'redis',
        'host'      => '192.168.1.15',
        'port'      => 6379,
    ],
    // CACHE
    'cache'         => [
        'type'      => 'redis',
        'host'      => '192.168.1.15',
        'port'      => 6379,
        'expire'    => 60,
    ],
    // api
    'api'           => [
        'url'       => 'http://127.0.0.1/',
        'host'      => 'api.debug.8591.com.hk'
    ],
];
