<?php
namespace logic\user;

use app\common\util\Configure;
use org\Validate;

class IpLock
{
    static private $error   = null;

    static public function check($user_id=0, $ip='')
    {
        // 數據校驗
        $data               = ['user_id'=>$user_id, 'ip'=>$ip];
        $rules              = [
            ['user_id', '會員ID不正確', 'gt', '0'],
            ['ip', 'IP不正確', 'ipv4']
        ];
        if (!Validate::valid($data, $rules)) {
            self::$error    = Validate::getError();
            return false;
        }
        // iplock.on 開關
        if (Configure::get('iplock.on', 1)==0) {
            return true;
        }
        // iplock.level 級別，默認4段全檢測
        $iplock_level   = Configure::get('iplock.level', 4);
        $ips            = explode('.', $client_info['login_ip']);
        switch ($iplock_level) {
            case 3:
                array_pop($ips);
                break;
            case 2:
                array_pop($ips);
                array_pop($ips);
                break;
            case 1:
                array_pop($ips);
                array_pop($ips);
                array_pop($ips);
                break;
            case 0:
            case 4:
            default:
                break;
        }
        $ip_search          = join('.', $ips);
        // 查詢IP
        $map                = [
            'user_id'       => $user_id,
            'ip'            => ['like', $ip_search.'%'],
            'is_del'        => 0,
            'expire_time'   => ['gt', date('Y-m-d H:i:s')],
        ];
        $count              = D('UserValidateIp')->where($map)->count();
        return $count>0 ? true : false;
    }

    static public function add($user_id=0, $ip='', $type='sms', $status=1, $expire=0)
    {
        $data               = [
            'user_id'       => $user_id,
            'ip'            => $ip,
            'type'          => $type,
            'create_time'   => date('Y-m-d H:i:s'),
            'expire_time'   => $expire>0 ? date('Y-m-d H:i:s', time()+$expire) : date('Y-m-d H:i:s', strtotime('+3 month')),
        ];
        // 數據校驗
        $rules              = [
            ['user_id', '會員ID不正確', 'gt', '0'],
            ['ip', 'IP不正確', 'ipv4']
        ];
        if (!Validate::valid($data, $rules)) {
            self::$error    = Validate::getError();
            return false;
        }
        $Model              = D('UserValidateIp');
        // 存在則更新有效期
        $map                = [
            'user_id'   => $user_id,
            'ip'        => $ip,
        ];
        $ip_info        = $Model->where($map)->find();
        if (!empty($ip_info['id'])) {
            if ($Model->where(['id'=>$ip_info['id']])->setField($data)) {
                return $ip_info['id'];
            }
            self::$error    = '保存數據失敗';
            return false;
        } else {
            // 保存新記錄
            if (!$Model->create($data)) {
                self::$error    = '數據有誤';
                return false;
            }
            if (!$id=$Model->add()) {
                self::$error    = '保存數據失敗';
                return false;
            }
            return $id;
        }
    }

    static public function del($id)
    {
        $rules      = [
            ['id', 'ID不正确', 'number'],
        ];
        if (!Validate::valid(['id'=>$id], $rules)) {
            self::$error    = Validate::getError();
            return false;
        }
        $map        = ['id' => $id];
        $result     = D('UserValidateIp')->where($map)->setField('is_del', 1);
        if (false===$result) {
            self::$error    = '更新數據失敗';
            return false;
        }
        return true;
    }

    static public function renew($id='', $expire=0)
    {
        $rules      = [
            ['id', 'ID不正确', 'number'],
        ];
        if (!Validate::valid(['id'=>$id], $rules)) {
            self::$error    = Validate::getError();
            return false;
        }
        $map            = ['id'=>$id];
        $data           = [
            'is_del'        => 0,
            'expire_time'   => $expire>0 ? date('Y-m-d H:i:s', time()+$expire) : date('Y-m-d H:i:s', strtotime('+3 month')),
        ];
        $result         = D('UserValidateIp')->where($map)->setField($data);
        if (false===$result) {
            self::$error    = '更新數據失敗';
            return false;
        }
        return true;
    }

    static public function remark($id=0, $remark='')
    {
        $rules      = [
            ['id', 'ID不正确', 'number'],
        ];
        if (!Validate::valid(['id'=>$id], $rules)) {
            self::$error    = Validate::getError();
            return false;
        }
        $map            = ['id'=>$id];
        $result         = D('UserValidateIp')->where($map)->setField('remark', $remark);
        if (false===$result) {
            self::$error    = '备注數據失敗';
            return false;
        }
        return true;
    }

    static public function getError()
    {
        return self::$error;
    }
}