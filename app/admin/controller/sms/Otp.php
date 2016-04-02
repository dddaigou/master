<?php 
namespace app\admin\controller\sms;

use think\Input;
use app\admin\controller\Common;
use api\Sms as SmsApi;

class Otp extends Common
{
    public function query()
    {
        // 接收参数
        $msg        = '';
        $rows       = [];
        if (Input::get('mobile')) {
            $data   = [
                'mobile'    => Input::get('mobile', '', 'trim'), 
                'is_user_id'=> Input::get('is_user_id/d', 0)
            ];
            $result = SmsApi::queryOtp($data, 1);
            if ($result['code']==200) {
                $rows   = $result['list'];
            } else {
                $msg    = $result['msg'];
            }
        }
        return V('', ['rows'=>$rows, 'msg'=>$msg]);
    }
}