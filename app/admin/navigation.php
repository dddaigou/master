<?php 
return [
    // 首页
    [
        'id'    => '1',
        'text'  => '控制面板',
        'icon'  => 'nav-home',
        'homePage'=> '100',
        'menu'  => [
            [
                'text'  => '欢迎登入',
                'items' => [
                    ['id'=>'100', 'text'=>'后台首页', 'href'=>U('/index/welcome')],
                ]
            ],
            [
                'text'  => '个人中心',
                'items' => [
                    ['id'=>"111", 'text'=>'资料修改', 'href'=>U('/profile/index')],
                    ['id'=>"112", 'text'=>'密码修改', 'href'=>U('/profile/password')],
                ]
            ],
        ],
    ],

    // 会员管理
    [
        'id'    => '2',
        'text'  => '会员管理',
        'icon'  => 'nav-user',
        'homePage' => '200',
        'menu'  => [
            [
                'text'  => '会员管理',
                'items' => [
                    ['id'=>'200', 'text'=>'会员列表', 'href'=>U('/user/index')],
                    ['id'=>'201', 'text'=>'关注会员', 'href'=>U('/user.care/index')],
                    ['id'=>'202', 'text'=>'在线会员', 'href'=>U('/user.online/index')],
                ]
            ],
            [
                'text'  => '会员日志',
                'items' => [
                    ['id'=>'210', 'text'=>'登入日志', 'href'=>U('/user.log/login')],
                ]
            ],
            [
                'text'  => '会员反馈',
                'items' => [
                    ['id'=>'220', 'text'=>'反馈列表', 'href'=>U('/feedback/index')],
                ]
            ],
        ],
    ],

    // 交易管理
    [
        'id'    => '3',
        'text'  => '交易管理',
        'icon'  => 'nav-order',
        'homePage' => '310',
        'menu'  => [
            [
                'text'  => '订单管理',
                'items' => [
                    ['id'=>'310', 'text'=>'订单列表', 'href'=>U('/order/index')],
                    ['id'=>'311', 'text'=>'关注订单', 'href'=>U('/order/care')],
                ]
            ],
            [
                'text'  => '商品管理',
                'items' => [
                    ['id'=>'320', 'text'=>'类型管理', 'href'=>U('/goods.type/index')],
                    ['id'=>'321', 'text'=>'点卡管理', 'href'=>U('/card/index')],
                    ['id'=>'322', 'text'=>'游戏管理', 'href'=>U('/game/index')],
                    ['id'=>'323', 'text'=>'商品管理', 'href'=>U('/goods/index')],
                ]
            ],
        ],
    ],

    // 系统管理
    [
        'id'    => '4',
        'text'  => '系统管理',
        'icon'  => 'nav-order',
        'homePage' => '400',
        'menu'  => [
            [
                'text'  => '网站配置',
                'items' => [
                    ['id'=>'400', 'text'=>'全局配置', 'href'=>U('/config/index')],
                    ['id'=>'430', 'text'=>'首頁配置', 'href'=>U('/config.rank/index')],
                ]
            ],
            [
                'text'  => 'IP管理',
                'items' => [
                    ['id'=>'410', 'text'=>'禁止IP', 'href'=>U('/ip.deny/index')],
                    ['id'=>'410', 'text'=>'安全锁', 'href'=>U('/ip.validate/index')],
                ]
            ],
            [
                'text'  => '管理员管理',
                'items' => [
                    ['id'=>'420', 'text'=>'权限组管理', 'href'=>U('/admin.group/index')],
                    ['id'=>'421', 'text'=>'管理员列表', 'href'=>U('/admin.user/index')],
                    ['id'=>'422', 'text'=>'登入日志', 'href'=>U('/admin.log/login')],
                ]
            ],
            [
                'text'  => '短信功能',
                'items' => [
                    ['id'=>'430', 'text'=>'验证码查询', 'href'=>U('/sms.otp/query')],
                ]
            ],
        ],
    ],
];