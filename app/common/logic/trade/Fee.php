<?php 
namespace logic\trade;

class Fee
{
    const PERCENT   = 0.06;

    static public function get($price=0)
    {
        if ($price<=0) 
            return 0;
        if ($price<=100) 
            return 6;
        else 
            return floor($price * self::PERCENT);
    }
}