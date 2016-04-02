<?php
namespace app\crontab\controller;

use think\Input;
use logic\goods\Type;

class Game extends Common
{
    //ÓÎÏ·Ê×í“
    public function gameIndex(){
        $type           = Input::get('type/s','pc');
        $map            = [
            'type'              =>$type,
            'is_hidden'         =>0,
            'is_deny_publish'   =>0,
        ];
        $order          ="id DESC";
        $GameModel                          = D('Game');
        $rows    = $GameModel->where($map)->order($order)->select();
        self::log($GameModel->getLastSql());
        foreach ($rows as $k => $game) {
            switch ($type) {
                case 'pc':
                case 'web':
                    $goods_type = strpos($game['goods_type'],'8')!==false?'2,3,4,8':'2,3,4,6';
                    break;
                case 'mobile':
                    $goods_type = '5,4,6,9';
                    break;
            }
            //×é×°Êý¾Ý
            $game_data  = [
                'id'            => $game['id'],
                'name'          => $game['name'],
                'goods_type'    => Type::get($goods_type),
            ];
            if ($game['is_hot']==1) {
                $game_list['hot'][]         = $game_data;
            }
            if ($first_char=self::_getFirstChar($game['name'])) {
                $game_list[$first_char][]    = $game_data;
            }
            if (!self::_hasChinese($game['name']) && !is_numeric($game['name'][0])) {
                $game_list['english'][]     = $game_data;
            }
            if (is_numeric($game['name'][0])) {
                $game_list['figure'][]      = $game_data;
            }
        }
        $status = F("{$type}_game_index", $game_list);
        if (!$status) {
            self::log('faild', 1);
        }
        self::log("success");
    }

    //»ñÈ¡ºº×ÖÆ´ÒôµÄµÚÒ»¸ö×ÖÄ¸
    private static function _getFirstChar($s0){
        if($s0{0} == 1) return "I";
        if(strtoupper($s0{0}) == 'Y') return "I";
        if(strtoupper($s0{0}) == 'W') return "U";
        if(mb_substr($s0,0,1,'utf-8') == '±Š') return "Z";
        if(mb_substr($s0,0,1,'utf-8') == '±I') return "D";
        $firstchar_ord=ord(strtoupper($s0{0}));
        if (($firstchar_ord>=65 and $firstchar_ord<=91)or($firstchar_ord>=48 and $firstchar_ord<=57)) 
            return strtoupper ($s0{0});
        $s=iconv("UTF-8","gbk", $s0);
        $asc=ord($s{0})*256+ord($s{1})-65536;
        if($asc>=-20319 and $asc<=-20284)return "A";
        if($asc>=-20283 and $asc<=-19776)return "B";
        if($asc>=-19775 and $asc<=-19219)return "C";
        if($asc>=-19218 and $asc<=-18711)return "D";
        if($asc>=-18710 and $asc<=-18527)return "E";
        if($asc>=-18526 and $asc<=-18240)return "F";
        if($asc>=-18239 and $asc<=-17923)return "G";
        if($asc>=-17922 and $asc<=-17418)return "H";
        if($asc>=-17417 and $asc<=-16475)return "J";
        if($asc>=-16474 and $asc<=-16213)return "K";
        if($asc>=-16212 and $asc<=-15641)return "L";
        if($asc>=-15640 and $asc<=-15166)return "M";
        if($asc>=-15165 and $asc<=-14923)return "N";
        if($asc>=-14922 and $asc<=-14915)return "O";
        if($asc>=-14914 and $asc<=-14631)return "P";
        if($asc>=-14630 and $asc<=-14150)return "Q";
        if($asc>=-14149 and $asc<=-14091)return "R";
        if($asc>=-14090 and $asc<=-13319)return "S";
        if($asc>=-13318 and $asc<=-12839)return "T";
        if($asc>=-12838 and $asc<=-12557)return "U";
        if($asc>=-12556 and $asc<=-11848)return "X";
        if($asc>=-11847 and $asc<=-11056)return "I";
        if($asc>=-11055 and $asc<=-10247)return "Z";
        return null;
    } 

    //ÅÐ¶ÏÊÇ·ñÎªÖÐÎÄ
    private static function _hasChinese($str){
        $hasChinese = false;
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $ar);
        $aC = count($ar[0]);
        foreach ($ar[0] as $value){
            if (ord($value)>127) {
                $hasChinese = true;
            }
        }
        return $hasChinese;
    }
}