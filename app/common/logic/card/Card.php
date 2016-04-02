<?php
namespace logic\card;

class Card
{
    static public function getNameById($card_id=0)
    {
        static $names           = [];

        if (!isset($names[$card_id])) {
            $names[$card_id]    = D('Card')->getFieldById($card_id, 'name');
        }

        return $names[$card_id];
    }

    static public function getNamesByIds($card_id='', $glue=',') {
        if (false!==strpos($card_id, ',')) {
            $card_id            = explode(',', $card_id);
        } elseif (is_scalar($card_id)) {
            $card_id            = [$card_id];
        }

        $names                  = [];
        foreach ($card_id as $id) {
            $names[]            = self::getNameById($id);
        }

        return join($glue, $names);
    }

    static public function isHidden($card_id=0)
    {
        $result = D('Card')->getFieldById($type_id, 'is_hidden') ? true : false;
        return $result;
    }

    static public function isDenyPublish($card_id=0)
    {
        $result = D('Card')->getFieldById($type_id, 'is_deny_publish') ? true : false;
        return $result;
    }
}