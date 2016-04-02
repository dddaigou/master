<?php 
namespace app\common\util;

class Phonetic
{
    static private $alias   = [
        '1' => 'I',
        'Y' => 'I',
        'W' => 'U',
    ];
    static private $maps    = [
        '眾' => 'Z',
        '盜' => 'D',
        '風' => 'F',
    ];

    /**
     * 获取首字符注音
     * @param  string $string 字符串
     * @return array          [是否中文, 注音]
     */
    static public function get($string='')
    {
        $string         = (string) $string;
        // 空
        if (''==$string) return [0, ''];
        // 注意对照
        $map            = strtoupper($string{0});
        if (isset(self::$alias[$map])) return [0, self::$alias[$map]];
        // 特殊中文
        $sp             = mb_substr($string, 0, 1, 'utf-8');
        if (isset(self::$maps[$sp])) return [1, self::$maps[$sp]];
        // ord
        $ord            = ord(strtoupper($string{0}));
        // 数字或英文
        if (($ord>=65 && $ord<=91) || ($ord>=48 && $ord<=57)) return [0, strtoupper($string{0})];
        // 中文注音
        $gbk            = iconv("UTF-8","gbk", $string);
        $asc            = ord($gbk{0}) * 256 + ord($gbk{1}) - 65536;
        if($asc>=-20319 && $asc<=-20284) return [1, "A"];
        if($asc>=-20283 && $asc<=-19776) return [1, "B"];
        if($asc>=-19775 && $asc<=-19219) return [1, "C"];
        if($asc>=-19218 && $asc<=-18711) return [1, "D"];
        if($asc>=-18710 && $asc<=-18527) return [1, "E"];
        if($asc>=-18526 && $asc<=-18240) return [1, "F"];
        if($asc>=-18239 && $asc<=-17923) return [1, "G"];
        if($asc>=-17922 && $asc<=-17418) return [1, "H"];
        if($asc>=-17417 && $asc<=-16475) return [1, "J"];
        if($asc>=-16474 && $asc<=-16213) return [1, "K"];
        if($asc>=-16212 && $asc<=-15641) return [1, "L"];
        if($asc>=-15640 && $asc<=-15166) return [1, "M"];
        if($asc>=-15165 && $asc<=-14923) return [1, "N"];
        if($asc>=-14922 && $asc<=-14915) return [1, "O"];
        if($asc>=-14914 && $asc<=-14631) return [1, "P"];
        if($asc>=-14630 && $asc<=-14150) return [1, "Q"];
        if($asc>=-14149 && $asc<=-14091) return [1, "R"];
        if($asc>=-14090 && $asc<=-13319) return [1, "S"];
        if($asc>=-13318 && $asc<=-12839) return [1, "T"];
        if($asc>=-12838 && $asc<=-12557) return [1, "U"];
        if($asc>=-12556 && $asc<=-11848) return [1, "X"];
        if($asc>=-11847 && $asc<=-11056) return [1, "I"];
        if($asc>=-11055 && $asc<=-10247) return [1, "Z"];
        return [0, ''];
    }
}