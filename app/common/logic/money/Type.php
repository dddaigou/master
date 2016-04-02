<?php 
namespace logic\money;

class Type
{
    static protected $types = [
        '1' => '虚宝交易',
        '2' => '推荐广告',
        '3' => '银行储值',
        '4' => '超商储值',
        '5' => '经销商储值',
    ];

    static public function get($id=null)
    {
        if (is_null($id)) {
            return self::$types;
        }
        return isset(self::$types[$id]) ? self::$types[$id] : '未知';
    }
}