<?php
namespace logic\goods;

class Goods
{
    static public function count($status=1,$column=1)
    {
        // 查status=$status 的商品數量
        $map            = [];
        $map['status']  = $status;
        return D('Goods')->where($map)->count($column);
    }
} 