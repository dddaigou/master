<?php
namespace app\api\controller;

use org\Validate;
use app\common\util\Crypt;
use logic\user\LoginLog;
use logic\user\IpLock;

class User
{
    /**
     * 登入检测
     * @param  string $login_type  登入类型 account/mobile/email/facebook
     * @param  string $account     登入帐号
     * @param  string $login_pwd   登入密码
     * @param  string $is_md5      是否已经md5加密
     * @param  string $session_id  会员ID
     * @param  array  $client_info 客户端信息
     * @return array               返回结果
     */
    public function login($login_type='account', $account='', $login_pwd='', $is_md5='', $session_id='', $client_info=[])
    {
        // 參數檢測
        $data           = [
            'login_type'    => $login_type,
            'account'       => $account,
            'login_pwd'     => $login_pwd,
            'session_id'    => $session_id,
            'login_ip'      => $client_info['login_ip'],
        ];
        $rules          = [
            ['login_type', '登入類型必須', 'require'],
            ['login_type', '登入類型不正確', 'in', 'account,mobile,email,facebook'],
            ['account', '帳號必須', 'require'],
            ['account', '手機格式不正確', 'hkmoblie'],
            ['login_pwd', '登入密碼必須', 'require'],
            ['session_id', '會話ID必須', 'require'],
            ['login_ip', '登入IP必須', 'require'],
        ];
        $check          = Validate::valid($data, $rules);
        if (!$check) {
            return ['code'=>400, 'msg'=>Validate::getError(), 'fail_field'=>Validate::getFailFeild()];
        }
        // 查询会员信息
        $map            = [];
        switch ($login_type) {
            case 'email':
                $map['email']   = $account;
                break;
            case 'mobile':
                $map['mobile']  = $account;
                break;
            case 'account':
            default:
                $map['account'] = $account;
                break;
        }
        $user_info  = D('User')->where($map)->find();
        // 找不到会员资料
        if (empty($user_info)) {
            return ['code'=>404, 'msg'=>'帳號與密碼不符'];
        }
        if (!empty($user_info['is_show_realname'])) {
            $user_name  = $user_info['realname'];
        } else {
            $user_name  = !empty($user_info['nickname']) ? $user_info['nickname'] : '8591會員';
        }
        // 登入日誌
        $LoginLog   = new LoginLog($login_type, $user_info['id'], $session_id, $client_info);
        // 禁止登入
        if (in_array('login', explode(',', $user_info['deny_access']))) {
            $LoginLog->addFailLog('deny_login');
            return ['code'=>402, 'msg'=>'帳號已被停權'];
        }
        // 檢測密碼
        if ($user_info['login_pwd']!=Crypt::encryptUserLoginPwd($user_info['id'], $login_pwd, $is_md5)) {
            $LoginLog->addFailLog('invalid_pwd');
            return ['code'=>403, 'msg'=>'帳號與密碼不符'];
        }
        // 檢測安全鎖
        if (!IpLock::check($user_info['id'], $client_info['login_ip'])) {
            $LoginLog->addFailLog('invalid_ip');
            return ['code'=>500, 'msg'=>'請先進行安全鎖解鎖','user_id'=>$user_info['id'], 'user_name'=>$user_name];
        }
        // 返回登入成功
        $LoginLog->addSuccessLog();
        // 更改在级表记录
        D('UserOnline')->where(['session_id'=>$session_id])->setField('user_id', $user_info['id']);
        // 返回成功
        return ['code'=>200, 'msg'=>'登入成功', 'user_id'=>$user_info['id'], 'user_name'=>$user_name];
    }

    public function register($data=[])
    {
        // 检测数据
        $rules                  = [
            ['account', '登入帳號必須', 'require'],
            ['account', '帳號格式不正確', 'regex', '/[a-zA-Z0-9\_]{6,12}/'],
            ['login_pwd', '登入密碼必須', 'require'],
            ['login_pwd', '登入密碼格式不正確', 'regex', '/[a-zA-Z0-9\_]{6,20}/'],
            ['login_pwd', '登入密碼不能包含3位或以上的連續數字', 'function', function($login_pwd=''){
                return preg_match('/(012|123|234|345|456|567|678|789)/', $login_pwd) ? false : true;
            }],
            ['login_pwd_confirm', '確認密碼必須', 'require'],
            ['login_pwd_confirm', '兩次密碼不一致', 'confirm', 'login_pwd'],
            ['mobile', '行動電話必須', 'require'],
            //['mobile', '行動電話不正確', 'hkmobile'],
            ['realname', '真實姓名必須', 'require'],
            ['realname', '長度必須為1-30', 'length', '1,30'],
            ['realname', '真實姓名格式不正確', 'regex', '/^[\sa-zA-Z\x{4e00}-\x{9fa5}]{1,30}$/u'],
            // ['is_show_realname', '請選擇顯示暱稱或真實姓名', 'require'],
            ['reg_ip', '註冊IP必須', 'require'],
            ['reg_client', '註冊客戶端必須', 'require'],
            ['country_code', '國家編碼必須', 'require'],
        ];
        // 暱稱或顯示真實姓名
        /*if ($data['is_show_realname']==0) {
            $rules[]            = ['nickname', '暱稱必須', 'require'];
            $rules[]            = ['nickname', '暱稱必須為1-30', 'length', '1,30'];
            $rules[]            = ['realname', '暱稱格式不正確', 'regex', '/^[\sa-zA-Z\x{4e00}-\x{9fa5}]{1,30}$/u'];
        }*/
        // 驗證數據
        if (!Validate::valid($data, $rules)) {
            return ['code'=>201, 'msg'=>Validate::getError(), 'fail_field'=>Validate::getFailFeild()];
        }
        // 用戶模型
        $UserModel              = D('User');
        // 檢測帳號或行動電話是否已經存在
        $is_account_exists      = $UserModel->where(['account'=>$data['account']])->count();
        if ($is_account_exists) {
            return ['code'=>202, 'msg'=>'帳號已經被註冊'];
        }
        $is_mobile_exists       = $UserModel->where(['mobile'=>$data['mobile']])->count();
        if ($is_mobile_exists) {
            return ['code'=>203, 'msg'=>'行動電話已經被註冊'];
        }
        // 臨時保存密碼
        $login_pwd              = $data['login_pwd'];
        if (isset($data['login_pwd'])) unset($data['login_pwd']);
        if (isset($data['payment_pwd'])) unset($data['payment_pwd']);
        // 默認數據
        $data['authenticate']   = 'mobile';
        $data['reg_time']       = date('Y-m-d H:i:s');
        if (!$UserModel->create($data)) {
            return ['code'=>300, 'msg'=>'數據有誤'];
        }
        if (!$user_id=$UserModel->add()) {
            return ['code'=>301, 'msg'=>'保存數據失敗'];
        }
        // 更新密碼與支付密碼
        $data                   = [
            'payment_pwd'   => Crypt::encryptUserPaymentPwd($user_id, $login_pwd),
            'login_pwd'     => Crypt::encryptUserLoginPwd($user_id, $login_pwd),
        ];
        $UserModel->where(['id'=>$user_id])->save($data);
        // 返回注册成功
        return ['code'=>200, 'msg'=>'註冊成功', 'user_id'=>$user_id];
    }
}