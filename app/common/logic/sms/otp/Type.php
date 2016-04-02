<?php 
namespace logic\sms\otp;

class Type
{
    static protected $types = [
        'reg'               => '注册', 
        'login'             => '登入', 
        'payment'           => '支付', 
        'changepwd'         => '修改密码', 
        'changepaymentpwd'  => '修改支付密码', 
        'changemobile'      => '修改手机', 
        'unlock'            => '解锁', 
        'findpwd'           => '找回密码', 
        'findpaymentpwd'    => '找回支付密码',
    ];

    static public function get($key=null)
    {
        if (is_null($key)) {
            return self::$types;
        }
        return isset(self::$types[$key]) ? self::$types[$key] : '未知';
    }
}