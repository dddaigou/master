<?php
namespace logic\user;

class User
{
    static public function getNameById($user_id=0)
    {
        static $names       = [];

        if (!isset($names[$user_id])) {
            $name           = D('User')->getFieldById($user_id, 'realname');
            $names[$user_id]= $name ? $name : 'Unknow';
        }

        return $names[$user_id];
    }

    static public function getAccess($user_id=0)
    {
        static $access          = [];

        if (!isset($access[$user_id])) {
            $deny_access        = (string) D('User')->getFieldById($user_id, 'deny_access');
            $access[$user_id]   = explode(',', $deny_access);
        }

        return $access[$user_id];
    }

    static public function isDenyLogin($user_id=0)
    {
        return in_array('login', self::getAccess($user_id));
    }

    static public function isDenyPublish($user_id=0)
    {
        return in_array('publish', self::getAccess($user_id));
    }

    static public function isDenyDrawn($user_id=0)
    {
        return in_array('drawn', self::getAccess($user_id));
    }

    static public function isDenyBuy($user_id=0)
    {
        return in_array('buy', self::getAccess($user_id));
    }

    static public function isCardSeller($user_id=0)
    {
        $badges = D('User')->getFieldById($user_id, 'badges');
        if (empty($badges)) return 0;
        return in_array('card_seller', explode(',', $badges)) ? 1 : 0;
    }
}