<?php 
return [
    [
        'text'  => '我是買家',
        'items' => [
            ['text'=>'我的購物車', 'href'=>'goods_cart/index'],
            ['text'=>'我購買的商品', 'href'=>'trade/list'],
            ['text'=>'賣家的回答', 'href'=>'faq/list'],
        ]
    ],
    [
        'text'  => '我是賣家',
        'items' => [
            ['text'=>'網站首頁', 'href'=>'index/index','domain'=>'www','target'=>'_blank'],
            ['text'=>'交易進行中', 'href'=>'profile/index'],
            ['text'=>'商品待出售', 'href'=>'index/index'],
            ['text'=>'買家的提問', 'href'=>'index/index'],
            ['text'=>'免費刊登', 'href'=>'goods/publish','domain'=>'www'],
        ]
    ],
    [
        'text'  => '帳戶管理',
        'items' => [
            ['text'=>'提款', 'href'=>'profile/index'],
            ['text'=>'帳戶明細', 'href'=>'index/index'],
        ]
    ],
    [
        'text'  => '會員資料',
        'items' => [
            ['text'=>'修改資料', 'href'=>'profile/index'],
            ['text'=>'安全鎖列表', 'href'=>'index/index'],
        ]
    ],
    [
        'text'  => '客服中心',
        'items' => [
            ['text'=>'我要申訴', 'href'=>'profile/index'],
            ['text'=>'客服中心回復', 'href'=>'index/index'],
            ['text'=>'站內簡訊', 'href'=>'index/index'],
        ]
    ],
];