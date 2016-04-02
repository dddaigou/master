<?php
namespace logic\goods;

class Type
{
    static public function getNameById($type_id=0)
    {
        static $names           = [];

        if (!isset($names[$type_id])) {
            $names[$type_id]    = D('GoodsType')->getFieldById($type_id, 'type_name');
        }

        return $names[$type_id];
    }

    static public function get($type_id='')
    {
        // 通过静态变量减少查询
        static $goods_types = null;

        if (is_null($goods_types)) {
            $rows           = D('GoodsType')->select();
            foreach ((array) $rows as $row) {
                $goods_types[$row['id']]    = $row['type_name'];
            }
        }

        // 返回全部类型
        if (!$type_id) return $goods_types;

        // 返回多个
        if (false!==strpos($type_id, ',')) {
            $type_id       = explode(',', $type_id);
        }
        if (is_array($type_id)) {
            $types          = [];
            foreach ($type_id as $id) {
                $types[$id] = $goods_types[$id];
            }
            return $types;
        }

        // 返回单个类型
        return isset($goods_types[$type_id]) ? $goods_types[$type_id] : '';
    }

    static public function isHidden($type_id=0)
    {
        return D('GoodsType')->getFieldById($type_id, 'is_hidden') ? true : false;
    }

    static public function isDenyPublish($type_id=0)
    {
        return D('GoodsType')->getFieldById($type_id, 'is_deny_publish') ? true : false;
    }
}