<?php
namespace logic\card;

class Denom
{
    static public function getNameById($denom_id=0)
    {
        static $names           = [];

        if (!isset($names[$denom_id])) {
            $names[$denom_id]   = D('CardDenom')->getFieldById($denom_id, 'denom');
        }

        return $names[$denom_id];
    }

    static public function getAllByCardId($card_id=0, $exclude_hidden=0)
    {
        $map                    = ['card_id'=>$card_id];
        if ($exclude_hidden) {
            $map['is_hidden']   = 0;
        }
        return D('CardDenom')->where($map)->select();
    }

    static public function isHidden($denom_id=0)
    {
        $result = D('CardDenom')->getFieldById($denom_id, 'is_hidden') ? true : false;
        return $result;
    }

    static public function isDenyPublish($denom_id=0)
    {
        $result = D('CardDenom')->getFieldById($denom_id, 'is_deny_publish') ? true : false;
        return $result;
    }
}