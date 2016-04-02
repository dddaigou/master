<?php 
namespace logic\user;

use org\Ip;
use think\Log;

class LoginLog
{
    private $data                   = [];

    public function __construct($login_type='account', $user_id=0, $session_id='', $client_info=[])
    {
        $this->data['user_id']      = $user_id;
        $this->data['login_type']   = $login_type;
        $this->data['session_id']   = $session_id;
        $this->data['user_agent']   = !empty($client_info['user_agent']) ? $client_info['user_agent'] : '';
        $this->data['accept_language']   = !empty($client_info['accept_language']) ? $client_info['accept_language'] : '';
        $this->data['login_client'] = !empty($client_info['login_client']) ? $client_info['login_client'] : 'web';
        $this->data['login_ip']     = !empty($client_info['login_ip']) ? $client_info['login_ip'] : '';
        $this->data['login_time']   = date('Y-m-d H:i:s');
    }

    public function addSuccessLog()
    {
        $ip_info                    = Ip::find($this->data['login_ip']);
        $this->data['login_result'] = 1;
        $this->data['login_country']= $ip_info[0];
        return $this->_saveLog();
    }

    public function addFailLog($faild_cause='invalid_pwd')
    {
        $ip_info                    = Ip::find($this->data['login_ip']);
        $this->data['login_result'] = 0;
        $this->data['faild_cause']  = $faild_cause;
        $this->data['login_country']= $ip_info[0];
        return $this->_saveLog();
    }

    private function _saveLog(){
        $UserLoginLogModel          = D('UserLoginLog');
        if (!$UserLoginLogModel->create($this->data)) {
            Log::record($UserLoginLogModel->getError());
            return false;
        }
        if (!$UserLoginLogModel->add()) {
            Log::record('user_login_log save fail');
            return false;
        }
        return true;
    }
}