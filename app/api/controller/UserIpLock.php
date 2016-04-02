<?php
namespace app\api\controller;

use logic\user\IpLock;

class UserIpLock
{
    public function add($user_id=0, $ip='', $type='', $status=1, $expire=0)
    {
        if (!IpLock::add($user_id, $ip, $type, $status, $expire)) {
            return ['code'=>300, 'msg'=>IpLock::getError()];
        }
        return ['code'=>200, 'msg'=>'增加成功'];
    }

    public function del($id=0)
    {
        if (!IpLock::del($id)) {
            return ['code'=>300, 'msg'=>IpLock::getError()];
        }
        return ['code'=>200, 'msg'=>'刪除成功'];
    }

    public function renew($id=0, $expire=0)
    {
        if (!IpLock::renew($id, $expire)) {
            return ['code'=>300, 'msg'=>IpLock::getError()];
        }
        return ['code'=>200, 'msg'=>'更新成功'];
    }

    public function remark($id=0, $remark='')
    {
        if (!IpLock::remark($id, $remark)) {
            return ['code'=>300, 'msg'=>IpLock::getError()];
        }
        return ['code'=>200, 'msg'=>'备注成功'];
    }
}