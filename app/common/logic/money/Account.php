<?php 
namespace logic\money;

class Account
{
    static protected $accounts  = [
        '859101000001'  => '手续费',
        '859101000002'  => '推荐广告',
        // '859102000000'  => '折价券',
        '859102000001'  => '银行储值',
        '859102000002'  => '7-11储值',
        '859102000003'  => '八达通储值',
        '859102000004'  => '支付灵储值',
        '859102000005'  => '支付宝储值',
    ];

    static public function get($account=null)
    {
        if (is_null($account)) {
            return self::$accounts;
        }
        return isset(self::$accounts[$account]) ? self::$accounts[$account] : '未知';
    }
}