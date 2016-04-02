<?php
namespace logic\goods;

class Status
{
    static $status      = [
        '0' => '上架',
        '1' => '下架',
        '2' => '刪除',
    ];

    static public function format($status=0)
    {
        return self::$status[$status];
    }

    static public function load()
    {
        return self::$status;
    }
} 