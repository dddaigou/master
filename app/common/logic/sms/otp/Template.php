<?php 
namespace logic\sms\otp;

class Template
{
    static protected $templates = [
        'reg'               => '您的註冊確證碼是 %s，請勿提供給他人！【香港8591寶物交易網】', 
        'login'             => '', 
        'payment'           => '', 
        'changepwd'         => '', 
        'changepaymentpwd'  => '', 
        'changemobile'      => '', 
        'unlock'            => '您的安全鎖確證碼是 %s，請勿提供給他人！【香港8591寶物交易網】', 
        'findpwd'           => '', 
        'findpaymentpwd'    => '',
    ];

    static public function get($key=null)
    {
        if (is_null($key)) {
            return self::$templates;
        }
        return isset(self::$templates[$key]) ? self::$templates[$key] : '';
    }
}