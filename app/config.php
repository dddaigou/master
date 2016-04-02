<?php
return [
    'url_domain_deploy' => true,
    'url_domain_root'   => '.test.com',
    'url_route_on'      => true, // 路由开关
    'base_url'          => '', // 隐藏index.php
    'url_html_suffix'   => '.html',
    // database
    'db_fields_strict'  => false,    // DATABASE
    'database'      => [
        'type'      => 'mysql',         // 数据库类型
        'hostname'  => '127.0.0.1',  // 服务器地址
        'database'  => 'hk8591',        // 数据库名
        'username'  => 'test',          // 数据库用户名
        'password'  => 'aaaaaa', // 数据库密码
        'hostport'  => '3306',          // 数据库连接端口
        'charset'   => 'utf8',          // 数据库编码默认采用utf8
        'prefix'    => 't8_',           // 数据库表前缀
        'debug'     => true,            // 调试开关
    ],
    // exception
    'exception_ignore_type' => 8,
    // sms
    'sms'               => [
        'hk'    => [
            'api'=>'http://api.accessyou.com/sms/sendsms.php',
            'account'=>'11007283',
            'password'=>'52883235',
        ]
    ],
    // SESSION
    'session'       => [
        'auto_start'=> true,
        'domain'    => '.test.com',
        'type'      => 'redis',
        'host'      => '127.0.0.1',
        'port'      => 6379,
    ],
    // CACHE
    'cache'         => [
        'type'      => 'redis',
        'host'      => '127.0.0.1',
        'port'      => 6379,
        'expire'    => 0,
    ],
    // api
    'api'               => [
        'url'   => 'http://127.0.0.1/',
        'host'  => 'api.test.com'
    ],
];