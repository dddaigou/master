<?php 
namespace logic\goods;

class Transfer
{
    static protected $times     = [
        '1' => [],
        '2' => [15,30,120,300],
        '3' => [15,30,120,300],
        '4' => [],
        '5' => [60,120,300],
        '6' => [],
        '7' => [],
        '8' => [60,120,300],
        '9' => [15,30,120,300],
        '10'=> [],
    ];

    static protected $labels    = [
        '15'    => '15分鐘', 
        '30'    => '30分鐘', 
        '60'    => '1小時', 
        '120'   => '2小時', 
        '300'   => '5小時', 
        '1440'  => '24小時',
        '2880'  => '48小時',
    ];

    static public function get($goods_type=0)
    {
        if (!isset(self::$times[$goods_type])) return [];

        $times  = [];

        foreach (self::$times[$goods_type] as $time) {
            $times[$time]   = self::label($time);
        }

        return $times;
    }

    static public function label($time)
    {
        return isset(self::$labels[$time]) ? self::$labels[$time] : '';
    }
}