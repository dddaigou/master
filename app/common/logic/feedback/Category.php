<?php 
namespace logic\feedback;

class Category
{
    static protected $categories   = [
        0   => '其他',
        1   => '注册',
        2   => '登入',
        3   => '刊登',
    ];

    static public function get($category_id=null) {
        if (is_null($category_id)) {
            return self::$categories;
        }
        return isset(self::$categories[$category_id]) ? self::$categories[$category_id] : '未分类';
    }
}