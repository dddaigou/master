<?php
namespace app\admin\controller\user;

use think\Session;
use think\Response;
use think\Input;
use org\Page;
use app\admin\controller\Common;

class Online extends Common
{
    public function index()
    {
        // 接收参数
        $map                    = [];
        switch (Input::get('type/d')) {
            case '-1':  // 游客
                $map['user_id'] = ['eq', 0];
                break;
            case '1':   // 登入会员
                $map['user_id'] = ['neq', 0];
                break;
            default:    // 全部
                break;
        }
        // 智能识别关键字
        if (Input::get('keyword')) {
            if (is_numeric(Input::get('keyword'))) { // 搜索会员ID
                $map['user_id']         = Input::get('keyword');
            } elseif (false!==strpos(Input::get('keyword'), '.')) { // 搜索IP
                $map['client_ip|forwarded_ip']  = ['like', '%'.Input::get('keyword').'%'];
            } elseif (preg_match('/^[a-z]{2,3}$/i', Input::get('keyword'))) { // 搜索国家编码
                $map['country_code']    = Input::get('keyword');
            } elseif(preg_match('/^[a-z0-9]{15,}$/i', Input::get('keyword'))) { // 搜索session
                $map['session_id']      = Input::get('keyword');
            } else {
                return Response::error('只支持搜索会员ID/IP/国家编码');
            }
        }
        $UserOnlineModel    = D('UserOnline');
        // 统计总数
        $total              = $UserOnlineModel->where($map)->count();
        // 分页
        $Page               = new Page($total);
        // 数据
        $rows               = $UserOnlineModel->where($map)->limit($Page->firstRow, $Page->listRows)->order('online_time DESC')->select();
        // 视图
        return V('', ['rows'=>$rows, 'total'=>$total, 'pager'=>$Page->show()]);
    }

    public function offline()
    {
        // 接收参数
        $session_id = Input::get('session_id/s');
        // 写入缓存
        $key        = 'force_logout_sessions';
        $sessions   = (array) F('force_logout_sessions');
        $sessions   = array_filter($sessions);
        $sessions   = array_unique($sessions);
        // 保证最多只有30个
        while (count($sessions)>=30) {
            array_shift($sessions);
        }
        if (!in_array($session_id, $sessions)) {
            $sessions[] = $session_id;
        }
        // 保存
        if (false === F($key, $sessions)) {
            return Response::error('操作失败', '', U('index'));
        }
        return Response::success('操作成功', '', U('index'));
    }
}