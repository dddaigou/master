<?php
namespace logic\game;

class Config
{
    /*static public function export($phonetic=false)
    {
        if (!$phonetic)
            return self::getAllGame(true);
        else
            return self::getAllGameByPhonetic();
    }*/

    static public function getAllGame($full=true)
    {
        $rows   = D('Game')->field("*,IF(taxis=0,'99999999',taxis) AS taxis")->where(['is_hidden'=>0])->order('taxis ASC')->select();
        $data   = [];
        foreach((array) $rows as $row) {
            $key            = "_{$row['id']}";
            $data[$key]     = [
                'id'        => $row['id'],
                'type'      => $row['type'],
                'name'      => $row['name'],
                'color'     => $row['color'],
                'is_new'    => $row['is_new'],
                'is_hot'    => $row['is_hot'],
            ];
            if (!$full) continue;
            $data[$key]['types']    = \logic\goods\Type::get($row['goods_type']);
            $data[$key]['servers']  = self::getServersByGameId($row['id']);
            $data[$key]['items']    = self::getItemsByGameId($row['id']);
        }
        return $data;
    }

    static public function getAllGameByPhonetic()
    {
        $config = [
            '_hot_' => [
                'label' => '熱門',
                'games' => self::getHotGames(),
            ],
            'b'     => [
                'label' => 'ㄅb',
                'games' => self::getGamesByPhonetic('b'),
            ],
            'p'     => [
                'label' => 'ㄆp',
                'games' => self::getGamesByPhonetic('p'),
            ],
            'm'     => [
                'label' => 'ㄇm',
                'games' => self::getGamesByPhonetic('m'),
            ],
            'f'     => [
                'label' => 'ㄈf',
                'games' => self::getGamesByPhonetic('f'),
            ],
            'd'     => [
                'label' => 'ㄉd',
                'games' => self::getGamesByPhonetic('d'),
            ],
            't'     => [
                'label' => 'ㄊt',
                'games' => self::getGamesByPhonetic('t'),
            ],
            'n'     => [
                'label' => 'ㄋn',
                'games' => self::getGamesByPhonetic('n'),
            ],
            'l'     => [
                'label' => 'ㄌl',
                'games' => self::getGamesByPhonetic('l'),
            ],
            'g'     => [
                'label' => 'ㄍg',
                'games' => self::getGamesByPhonetic('g'),
            ],
            'k'     => [
                'label' => 'ㄎk',
                'games' => self::getGamesByPhonetic('k'),
            ],
            'h'     => [
                'label' => 'ㄏh',
                'games' => self::getGamesByPhonetic('h'),
            ],
            'j'     => [
                'label' => 'ㄐj',
                'games' => self::getGamesByPhonetic('j'),
            ],
            'q'     => [
                'label' => 'ㄑq',
                'games' => self::getGamesByPhonetic('q'),
            ],
            'x'     => [
                'label' => 'ㄒx',
                'games' => self::getGamesByPhonetic('x'),
            ],
            'r'     => [
                'label' => 'ㄖr',
                'games' => self::getGamesByPhonetic('r'),
            ],
            'z'     => [
                'label' => 'ㄓㄗz',
                'games' => self::getGamesByPhonetic('z'),
            ],
            'c'     => [
                'label' => 'ㄔㄘc',
                'games' => self::getGamesByPhonetic('c'),
            ],
            's'     => [
                'label' => 'ㄕㄙs',
                'games' => self::getGamesByPhonetic('s'),
            ],
            'a'     => [
                'label' => 'ㄚㄞㄠㄢa',
                'games' => self::getGamesByPhonetic('a'),
            ],
            'o'     => [
                'label' => 'ㄛo',
                'games' => self::getGamesByPhonetic('o'),
            ],
            'e'     => [
                'label' => 'ㄜe',
                'games' => self::getGamesByPhonetic('e'),
            ],
            'i'     => [
                'label' => 'ㄧi',
                'games' => self::getGamesByPhonetic('i'),
            ],
            'u'     => [
                'label' => 'ㄨu',
                'games' => self::getGamesByPhonetic('u'),
            ],
            'v'     => [
                'label' => 'ㄩv',
                'games' => self::getGamesByPhonetic('v'),
            ],
            '_en_'  => [
                'label' => '數字/英文',
                'games' => self::getGamesByPhonetic('', 0),
            ],
        ];
        return $config;
    }

    static public function getHotGames()
    {
        $rows   = D('Game')->field("*,IF(taxis=0,'99999999',taxis) AS taxis")->where(['is_hot'=>1, 'is_hidden'=>0])->order('taxis ASC')->select();
        $data   = [];
        foreach((array) $rows as $row) {
            $key            = "_{$row['id']}";
            $data[$key]     = [
                'id'        => $row['id'],
                'type'      => $row['type'],
                'name'      => $row['name'],
                'color'     => $row['color'],
                'is_new'    => $row['is_new'],
                'is_hot'    => $row['is_hot'],
            ];
            $data[$key]['types']    = \logic\goods\Type::get($row['goods_type']);
            $data[$key]['servers']  = self::getServersByGameId($row['id']);
            $data[$key]['items']    = self::getItemsByGameId($row['id']);
        }
        return $data;
    }

    static public function getGamesByPhonetic($phonetic='', $is_chinese=null)
    {
        $map    = ['is_hidden'=>0];
        if (!empty($phonetic)) {
            $map['phonetic']= $phonetic;
        }
        if (!is_null($is_chinese)) {
            $map['is_chinese'] = (int) $is_chinese;
        }
        $rows   = D('Game')->field("*,IF(taxis=0,'99999999',taxis) AS taxis")->where($map)->order('taxis ASC')->select();
        $data   = [];
        foreach((array) $rows as $row) {
            $key            = "_{$row['id']}";
            $data[$key]     = [
                'id'        => $row['id'],
                'type'      => $row['type'],
                'name'      => $row['name'],
                'color'     => $row['color'],
                'is_new'    => $row['is_new'],
                'is_hot'    => $row['is_hot'],
            ];
            $data[$key]['types']    = \logic\goods\Type::get($row['goods_type']);
            $data[$key]['servers']  = self::getServersByGameId($row['id']);
            $data[$key]['items']    = self::getItemsByGameId($row['id']);
        }
        return $data;
    }

    static public function getServersByGameId($game_id=0)
    {
        $rows   = D('GameServer')->field("*,IF(taxis=0,'99999999',taxis) AS taxis")->where(['game_id'=>$game_id, 'is_hidden'=>0])->order('taxis ASC')->select();
        $data   = [];
        foreach((array) $rows as $row) {
            $key            = "_{$row['id']}";
            $data[$key]     = [
                'id'        => $row['id'],
                'name'      => $row['name'],
            ];
        }
        return $data;
    }

    static public function getItemsByGameId()
    {
        $rows   = D('GameItem')->field("*,IF(taxis=0,'99999999',taxis) AS taxis")->where(['game_id'=>$game_id])->order('taxis ASC')->select();
        $data   = [];
        foreach((array) $rows as $row) {
            $key            = "_{$row['id']}";
            $data[$key]     = [
                'id'        => $row['id'],
                'type'      => $row['type'],
                'name'      => $row['name'],
            ];
        }
        return $data;
    }
}