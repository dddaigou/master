<?php
namespace app\admin\controller\user;

use think\Session;
use think\Response;
use think\Input;
use org\Page;
use app\admin\controller\Common;

class Log extends Common
{   
    //登入日志
    public function login()
    {
        $map                    = [];
        switch (Input::get('search_type')) {
            case 'user_id':
                $map['user_id']         = Input::get('keyword/s');
                break;
            case 'login_ip':
                $map['login_ip']        = Input::get('keyword/s');
                break;
            case 'account':
                $map['account']         = Input::get('keyword/s');
                break;
        }
        switch (Input::get('status/s')){
            case 'invalid_pwd':
            case 'deny_login':
            case 'invalid_ip':
                $map['faild_cause']     = Input::get('status/s');
                break;
            case "1":
                $map['login_result']    = 1;
                break;
        }
        if (Input::get('start_time')) {
            $map['login_time'][]        = ['egt', Input::get('start_time')];
        }
        if (Input::get('end_time')) {
            $map['login_time'][]        = ['elt', Input::get('end_time')];
        }

        $UserLoginLog   = D('UserLoginLog');
        // 統計總數
        $total          = $UserLoginLog->table('t8_user_login_log A')->join('left join `t8_user` B ON A.user_id = B.id')->where($map)->count();
        // 分頁
        $Page           = new Page($total);
        // 獲取列表
        $rows           = $UserLoginLog->table('t8_user_login_log A')->join('left join `t8_user` B ON A.user_id = B.id')->where($map)->limit($Page->firstRow, $Page->listRows)->select();
        $faild_cause =[
            '1'             =>'成功登入',
            'invalid_pwd'   =>'密碼錯誤',
            'deny_login'    =>'停權帳號',
            'invalid_ip'    =>'安全鎖阻擋',
        ];
        $data           = [];
        $data['rows']   = $rows;
        $data['page']   = $Page->show();
        $data['status'] = $faild_cause;
        return V('', $data);
    }
}
