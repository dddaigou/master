<?php 
namespace app\common\util;

class Crypt
{
    static public function encryptUserLoginPwd($user_id=0, $login_pwd='', $is_pwd_md5=false)
    {
        if (!$is_pwd_md5) {
            $login_pwd  = md5($login_pwd);
        }
        return md5($user_id.$login_pwd);
    }

    static public function encryptUserPaymentPwd($user_id=0, $payment_pwd='', $is_pwd_md5=false)
    {
        if (!$is_pwd_md5) {
            $payment_pwd  = md5($payment_pwd);
        }
        return md5($user_id.md5($payment_pwd));
    }

    static public function encryptAdminUserLoginPwd($id=0, $login_pwd='', $is_pwd_md5=false)
    {
        if (!$is_pwd_md5) {
            $login_pwd  = md5($login_pwd);
        }
        return md5($id.md5($login_pwd));
    }
}