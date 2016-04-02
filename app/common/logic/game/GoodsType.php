<?php 
namespace logic\game;

class GoodsType
{
    static public function format($types='')
    {
        return strtr($types, self::load());
    }

    static public function load()
    {
        return D('GoodsType')->getField('id,type_name');
    }
}