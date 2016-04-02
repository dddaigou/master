<?php
namespace logic\card;

class Config
{
    static public function export()
    {
        return self::getAllCard(true);
    }

    static public function getAllCard($full=true)
    {
        $rows   = D('Card')->field("*,IF(taxis=0,'99999999',taxis) AS taxis")->where(['is_hidden'=>0])->order('taxis ASC')->select();
        $data   = [];
        foreach((array) $rows as $row) {
            $key            = "_{$row['id']}";
            $data[$key]     = [
                'id'        => $row['id'],
                'name'      => $row['name'],
            ];
            if (!$full) continue;
            $data[$key]['denoms']   = self::getDenomsByCardId($row['id']);
        }
        return $data;
    }

    static public function getDenomsByCardId($card_id=0)
    {
        $rows   = D('CardDenom')->field("*,IF(taxis=0,'99999999',taxis) AS taxis")->where(['card_id'=>$card_id, 'is_hidden'=>0])->order('taxis ASC')->select();
        $data   = [];
        foreach((array) $rows as $row) {
            $key            = "_{$row['id']}";
            $data[$key]     = [
                'id'        => $row['id'],
                'denom'     => $row['denom'],
            ];
        }
        return $data;
    }
}