<?php
namespace logic\trade;

class Trade
{
    static public function count($map=[],$column=1)
    {
        return D('Trade')->where($map)->count($column);
    }
} 