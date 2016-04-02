<?php
namespace logic\game;

class Server
{
    static public function getNameById($server_id=0)
    {
        static $names           = [];

        if (!isset($names[$server_id])) {
            $names[$server_id]  = D('GameServer')->getFieldById($server_id, 'name');
        }

        return $names[$server_id];
    }

    static public function getAllByGameId($game_id=0, $exclude_hidden=0)
    {
        $map                    = ['game_id'=>$game_id];
        if ($exclude_hidden) {
            $map['is_hidden']   = 0;
        }
        return D('GameServer')->where($map)->select();
    }

    static public function isHidden($server_id=0)
    {
        $result = D('GameServer')->getFieldById($server_id, 'is_hidden') ? true : false;
        return $result;
    }

    static public function isDenyPublish($server_id=0)
    {
        $result = D('GameServer')->getFieldById($server_id, 'is_deny_publish') ? true : false;
        return $result;
    }
}