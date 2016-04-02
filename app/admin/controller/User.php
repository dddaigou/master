<?php
namespace app\admin\controller;

use think\Session;
use think\Response;
use think\Input;
use org\Page;

class User extends Common
{
    public function index()
    {
        $map            = [];
        if (Input::get('id')) {
            $map['id']        = Input::get('id');
        }
        if (Input::get('account')) {
            $map['account']     = Input::get('account');
        }
        if (Input::get('mobile')) {
            $map['mobile']   = Input::get('mobile');
        }
        if (Input::get('realname')) {
            $map['realname']   = Input::get('realname');
        }
        if (Input::get('reg_ip')) {
            $map['reg_ip']   = Input::get('reg_ip');
        }
        if (Input::get('start_time')) {
            $map['reg_time'][] = ['egt', Input::get('start_time')];
        }
        if (Input::get('end_time')) {
            $map['reg_time'][] = ['elt', Input::get('end_time')];
        }

        $UserModel = D('User');
        $UserLoginLog = D('UserLoginLog');
        // 統計總數
        $total      = $UserModel->where($map)->count();
        // 分頁
        $Page       = new Page($total);
        // 獲取列表
        $rows       = $UserModel->where($map)->limit($Page->firstRow, $Page->listRows)->select();
        foreach($rows as $k=>$v){
            $login_log = $UserLoginLog->where(['user_id'=>$v['id'],'login_result'=>1])->field('login_ip,login_time')->find();
            $rows[$k]['login_time'] = $login_log['login_time'];
            $rows[$k]['login_ip']   = $login_log['login_ip'];
            $rows[$k]['login_status']   = in_array('login', explode(',', $v['deny_access']))?'停權':'正常';
            
            
        }
        $data = [];
        $data['rows'] = $rows;
        $data['page'] = $Page->show();
        return V('',$data);
    }
}