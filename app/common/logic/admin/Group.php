<?php
namespace logic\admin;

class Group
{
    static public function get($group_id=null)
    {
        static $groups  = null;

        if (is_null($groups)) {
            $groups[0]  = '停权';
            $rows       = D('AdminGroup')->order('id ASC')->select();
            foreach ((array) $rows as $row) {
                $groups[$row['id']] = $row['name'];
            }
        }

        if (is_null($group_id)) return $groups;

        return isset($groups[$group_id]) ? $groups[$group_id] : '';
    }
}