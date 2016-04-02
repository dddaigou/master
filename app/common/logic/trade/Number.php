<?php 
namespace logic\trade;

class Number
{
    // 校验码生成 key
    const KEY       = '87654321987654321987';

    /**
     * 根据ID生成14位的交易编号
     * @param  integer $id 交易ID
     * @return string      交易编号
     */
    static public function create($id=0)
    {
        $number     = date('ymdH').str_pad(substr($id, -6), 6, '0', STR_PAD_LEFT);
        $number     .= self::_getCheckCode($number);
        return $number;
    }

    /**
     * 生成校验码
     * @param  string $number 首次生成的12位编号
     * @return [type]         2位校验码
     */
    static private function _getCheckCode($number='')
    {
        $code       = 0;
        $key        = self::KEY;
        for ($i=0; $i<strlen($number); $i++) {
            $code   += $number[$i] * $key[$i];
        }
        return str_pad(substr($code, -2), 2, '0', STR_PAD_LEFT);
    }
}