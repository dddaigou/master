<?php
namespace logic\game;

class Type
{
    static $types   = [
        'pc'    => '端遊',
        'mobile'=> '手遊',
        'web'   => '頁遊',
    ];

    static public function get($en=null)
    {
        if (is_null($en)) {
            return self::$types;
        }

        return isset(self::$types[$en]) ? self::$types[$en] : 'N/A';
    }
}