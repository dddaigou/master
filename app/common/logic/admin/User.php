<?php
namespace logic\admin;

use think\Session;

class User
{
    static public function getNameById($id=0)
    {
        static $names   = null;

        if (is_null($names)) {
            $names[0]   = '停权';
            $rows       = D('AdminUser')->order('id ASC')->select();
            foreach ((array) $rows as $row) {
                $names[$row['id']] = $row['realname'];
            }
        }

        if (is_null($id)) return $names;

        return isset($names[$id]) ? $names[$id] : '';
    }

    static public function setLogin($admin_user_id=0, $admin_group_id=0, $admin_realname='')
    {
        if (!$admin_user_id || !$admin_group_id) return;
        Session::set('admin_user_id', $admin_user_id);
        Session::set('admin_group_id', $admin_group_id);
        Session::set('admin_realname', $admin_realname);
        return true;
    }

    static public function setLogout()
    {
        Session::delete('admin_user_id');
        Session::delete('admin_group_id');
        return true;
    }

    static public function isLogin()
    {
        return Session::has('admin_user_id') ? true : false;
    }

    static public function getLoginUserId()
    {
        return Session::get('admin_user_id');
    }

    static public function getLoginGroupId()
    {
        return Session::get('admin_group_id');
    }

    static public function getLoginName()
    {
        return Session::get('admin_realname');
    }
}