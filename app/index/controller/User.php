<?php
namespace app\index\controller;

use think\Config;
use think\Input;
use think\Session;
use think\Response;
use org\Ip;
use org\Verify;
use logic\user\LoginLog;
use logic\user\IpLock;
use api\User as UserApi;
use api\Sms as SmsApi;

class User extends Common
{
    //注册
    public function reg()
    {
        if(IS_POST){
            // 默认以json返回
            Config::set('default_return_type', 'json');
            //检测验证码
            $res    = $this->checkOtp(1);
            if($res['code'] != 200){
                return $res;
            }
            //注册操作
            $data                   = [];
            $data                   = Input::post();
            $data['reg_ip']         = Ip::getIp();
            $data['reg_client']     = 'web';
            $ip_info                = Ip::find($data['reg_ip']);
            $data['country_code']   = 'CN';
            $res                    = UserApi::register(['data'=>$data],1);
            //注册失败提示错误信息
            if($res['code'] != 200){
                   return $res;
            }
            //注册成功跳转页面
            if($res['code'] == 200){
                $res['url']         = U('user/regsuccess');
                return $res;
            }
        }else{
            return V();
        }
    }
    //注册成功页面
    public function regsuccess()
    {
        return V();
    }

    //登录
    public function login()
    {
        if(IS_POST){
            // 默认以json返回
            Config::set('default_return_type', 'json');
            // 登录账密需要检测验证码
            if(Session::get('is_show_code')){
                $verify = new Verify();
                $code   = Input::post('code/s');
                if(!$verify->check($code)){
                    return ['code'=>201, 'msg'=>'验证码错误'];
                    die();
                }
            }
            // 登录操作
            $data           = [
            'account'       => Input::post('account/s'),
            'login_type'    => self::_getLoginType(Input::post('account/s')),
            'login_pwd'     => Input::post('password/s'),
            'session_id'    => session_id(),
            'client_info'   => [
                    'login_ip'          => Ip::getIp(),
                    'user_agent'        => Input::server('HTTP_USER_AGENT'),
                    'accept_language'   => Input::server('HTTP_ACCEPT_LANGUAGE'),
                    'login_client'      => 'web',
                ]
            ];
            $result         = UserApi::login($data,1);
            // 输错账密，需要验证码
            if($result['code'] == 404 || $result['code'] == 403 ){
                Session::set('is_show_code', 1);
            }
            // 被安全锁阻挡
            if($result['code'] == 500){
                $data       = [
                    'user_id'       => $result['user_id'],
                    'login_type'    => $data['login_type'],
                    'client_info'   => $data['client_info']
                ];
                Session::set('invalid_ip_info', $data);
                return ['code'=>500, 'msg'=>'请先解锁', 'url'=>U('unlock')];
            }
            // 登录失败显示错误信息
            if($result['code'] != 200){
                return $result;
            }
            // 登录成功记录session和跳转
            if($result['code'] == 200){
                Session::set('user_id', $result['user_id']);
                Session::set('user_name', $result['user_name']);
                Session::delete('is_show_code');
                $url    = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : U('/@i');
                return ['code'=>200, 'msg'=>'登入成功', 'url'=>$url];
            }
        }else{
            if(Session::get('user_id')){
               $url     = U('/@i');
               Response::redirect($url);
               exit;
            }
            $data       = [];
            $data['is_show_code'] = Session::get('is_show_code');
            return V('', $data);
        }
    }
    
    //登出
    public function logout()
    {
        // 更新user_online
        D('UserOnline')->where(['session_id'=>session_id()])->setField('user_id', 0);
        // 注销session
        Session::destroy();
        return Response::success('登出成功', '', ('/'));
    }

    //安全锁解锁页面
    public function unlock()
    {
        if(!Session::has('invalid_ip_info')){
            return Response::error('请先登錄', '', U('User/login'));
        }
        $mobile     = D('User')->where(['id'=>Session::get('invalid_ip_info.user_id')])->getField('mobile');
        return V('',['mobile'=>$mobile]);
    }

    //获取登录方式
    static private function _getLoginType($account)
    {
        if(preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $account)){
            return 'email';
        }elseif(preg_match('/^(00){0,1}(852){0,1}0{0,1}[1,5,6,9](?:\d{7}|\d{8}|\d{12})$/', $account)){
            return 'mobile';
        }elseif(preg_match('/[a-zA-Z0-9\_]{6,12}/', $account)){
            return 'account';
        }else{
            return 'account';
        }
    }
    
    //发送验证码
    public function sendOtp()
    {   
        //安全锁验证码 手机必须为本人手机号码
        if(Input::post('type') == 'unlock'){ 
            $UserModel      = D('User');
            $real_mobile    = $UserModel->where(['id'=>Session::get('invalid_ip_info.user_id')])->getField('mobile');
            $mobile         = Input::post('mobile');
            if($mobile != $real_mobile){
                echo json_encode(['code'=>201, 'msg'=>'手機號碼不正確']);die();
            }
        }
        
        //注册验证码，检测手机是否已注册
        if(Input::post('type') == 'reg'){ 
            $res    = $this->validIsRegistered(Input::post('mobile'),'mobile',1);
            if($res['code'] != 200){
                echo json_encode($res);
                die();
            }
        }
        
        $res    = SmsApi::sendOtp(Input::post());
        if (isDev()) {
            $res  = json_encode(['code'=>200,'msg'=>'测试：跳过！']);
        }
        echo $res;
        die();
    }

    //检查验证码
    public function checkOtp($is_return=0)
    {
        $res    = SmsApi::checkOtp(Input::post(),$is_return);
        if (isDev()) {
            if ($is_return) {
                $res  = ['code'=>200,'msg'=>'测试：跳过！'];
            }else{
                $res  = json_encode(['code'=>200,'msg'=>'测试：跳过！']);
            }
        }
        if($is_return){
            return $res;
        }else{
            echo $res;
            die();
        }
    }
    
    //安全锁页面进入8591
    public function checkUnlock()
    {
       //验证验证码
       $res    = $this->checkOtp(1);
       if ($res['code'] != 200 ) {
           echo json_encode($res);
           die();
       }
       if (!session('invalid_ip_info')) {
           echo json_encode(['code'=>201, 'msg'=>'您還未登入']);
           die();
       }
       if ($res['code'] == 200 && Session::get('invalid_ip_info')) {
           // 如果解锁成功，记录登入日誌，session和加入安全锁记录
           if(Session::get('invalid_ip_info')){
               //登入日誌
               $invalid_ip_info    = session('invalid_ip_info');
               $login_type         = $invalid_ip_info['login_type'];
               $user_id            = $invalid_ip_info['user_id'];
               $session_id         = session_id();
               $client_info        = $invalid_ip_info['client_info'];
               $LoginLog           = new LoginLog($login_type,$user_id, $session_id, $client_info);
               $LoginLog->addSuccessLog();
               //安全锁
               IpLock::add($user_id,$client_info['login_ip']);
               //session
               Session::set('user_id', $user_id);
               //销毁安全锁session信息
               Session::delete('invalid_ip_info');
               echo json_encode(['code'=>200,'msg'=>'安全鎖解鎖成功']);
               die();
           }
       }
    }
    
    // 验证手机号、账号，邮箱是否注册过
    public function validIsRegistered($account, $type='account',$is_return=0)
    {
        if (!$account || !$type) {
            return ['code'=>201, 'msg'=>'参数有误'];
        }
        // 用戶模型
        $UserModel  = D('User');
        switch ($type) {
            case 'account':
                $map = ['account'=>$account];
                break;
            case 'mobile':
                $map = ['mobile'=>$account];
                break;
            case 'email':
                $map = ['email'=>$account];
                break;
        }
        if ($UserModel->where($map)->count()) {
            $res    = ['code'=>202, 'msg'=>'帳號已經被註冊'];
            if($is_return)  return $res;
            echo json_encode($res);
            die();
        }
        $res    = ['code'=>200, 'msg'=>'帳號可用'];
        if ($is_return) return $res;
        echo json_encode($res);
        die();
    }
}