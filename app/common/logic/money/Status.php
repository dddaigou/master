<?php 
namespace logic\money;

class Status
{
    static protected $status = [
        '-1'    => '取消',
        '0'     => '进行中',
        '1'     => '完成'
    ];

    static public function get($status=null)
    {
        if (is_null($status)) {
            return self::$status;
        }
        return isset(self::$status[$status]) ? self::$status[$status] : '未知';
    }
}