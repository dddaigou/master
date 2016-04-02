<?php 
namespace logic\user;

use think\Log;

class Online
{
    static public function count()
    {
        $count  = D('UserOnline')->count();
        return (int) $count;
    }

    static public function add($session_id='', $user_id=0, $ip='', $ip_country_code='N/A')
    {
        // 更新
        $data           = [
            'session_id'    => $session_id,
            'user_id'       => (int) $user_id,
            'client_ip'     => $ip,
            'country_code'  => $ip_country_code,
            'online_time'   => date('Y-m-d H:i:s'),
        ];
        $UserOnlineModel= D('UserOnline');
        // 检测是否已经存在
        $map            = ['session_id'=>$session_id];
        $count          = $UserOnlineModel->where($map)->count();
        if (!$UserOnlineModel->create($data)) {
            Log::record('[online] date error');
            return;
        }
        if (!$count) {
            if (false!==$UserOnlineModel->add()) {
                Log::record('[online] add fail');
                return;
            }
        } else {
            if (false===$UserOnlineModel->where($map)->save()) {
                Log::record('[online] update fail');
                return;
            }
        }
        return;
    }
}