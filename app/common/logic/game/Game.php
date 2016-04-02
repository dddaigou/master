<?php
namespace logic\game;

class Game
{
    // 根据ID获取单个游戏名称
    static public function getNameById($game_id=0)
    {
        static $names           = [];

        if (!isset($names[$game_id])) {
            $names[$game_id]    = D('Game')->getFieldById($game_id, 'name');
        }

        return $names[$game_id];
    }

    // 根据ID获取多个游戏名称
    static public function getNamesByIds($game_id='', $glue=',') {
        if (false!==strpos($game_id, ',')) {
            $game_id            = explode(',', $game_id);
        } elseif (is_scalar($game_id)) {
            $game_id            = [$game_id];
        }

        $names                  = [];
        foreach ($game_id as $id) {
            $names[]            = self::getNameById($id);
        }

        return join($glue, $names);
    }

    // 获取所有游戏
    static public function getAll($game_id=0, $exclude_hidden=0)
    {
        $map                    = ['game_id'=>$game_id];
        if ($exclude_hidden) {
            $map['is_hidden']   = 0;
        }
        return D('Game')->where($map)->select();
    }

    // 是否隐藏
    static public function isHidden($game_id=0)
    {
        $result = D('Game')->getFieldById($type_id, 'is_hidden') ? true : false;
        return $result;
    }

    // 是否禁止刊登
    static public function isDenyPublish($game_id=0)
    {
        $result = D('Game')->getFieldById($type_id, 'is_deny_publish') ? true : false;
        return $result;
    }
}