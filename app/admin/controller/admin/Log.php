<?php 
namespace app\admin\controller\admin;

use think\Input;
use think\Response;
use org\Validate;
use app\common\util\Crypt;
use org\Page;
use app\admin\controller\Common;
use logic\admin\Group as UserGroup;
use logic\admin\LoginLog;

class Log extends Common
{
    public function login()
    {
        // 查询条件
        $map    = [];
        if (false!==strpos(Input::get('keyword'), '.')) {
            $map['login_ip']        = Input::get('keyword');
        } elseif (is_numeric(Input::get('keyword'))) {
            $map['admin_user_id']   = Input::get('keyword');
        } elseif (preg_match('/^[a-z0-9]{15,}$/', Input::get('keyword'))) {
            $map['session_id']      = Input::get('keyword');
        }
        if (Input::get('cause')=='success') {
            $map['login_result']    = 1;
        } elseif (''<>Input::get('cause')) {
            $map['fail_cause']      = Input::get('cause');
        } else {
            //
        }

        $model  = D('AdminUserLoginLog');
        // 统计总数
        $total  = $model->where($map)->count();
        // 分页
        $Page   = new Page($total);
        // 查询所有管理员
        $rows   = $model->where($map)->order('login_time DESC')->limit($Page->firstRow, $Page->listRows)->select();
        $causes = (array) LoginLog::cause();
        $causes['success']  = '登入成功';
        return V('', ['rows'=>$rows, 'total'=>$total, 'pager'=>$Page->show(), 'keyword'=>Input::get('keyword'), 'causes'=>$causes]);
    }
}