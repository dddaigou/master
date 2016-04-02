<?php 
namespace logic\sms\otp;

class Status
{
    static protected $status = [
        '-2'    => '驗證超時', 
        '-1'    => '發送失敗', 
        '0'     => '發送中',
        '1'     => '發送成功',
        '2'     => '驗證成功',
    ];

    static public function get($key=null)
    {
        if (is_null($key)) {
            return self::$status;
        }
        return isset(self::$status[$key]) ? self::$status[$key] : '未知';
    }
}