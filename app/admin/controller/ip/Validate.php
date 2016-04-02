<?php 
namespace app\admin\controller\ip;

use think\Input;
use think\Response;
use org\Page;
use org\Validate as Validator;
use logic\admin\User as AdminUserLogic;
use app\admin\controller\Common;

class Validate extends Common
{
    public function index()
    {
        // 接收参数
        $map            = [];
        if (Input::get('keyword')) {
            if (strpos(Input::get('keyword'), '.')) {
                $map['ip']      = ['like', '%'.Input::get('keyword').'%'];
            } elseif (is_numeric(Input::get('keyword'))) {
                $map['user_id'] = Input::get('keyword');
            }
        }
        // 加载模型
        $Model          = D('UserValidateIp');
        // 统计总数
        $total          = $Model->where($map)->count();
        // 分页
        $Page           = new Page($total);
        // 数据
        $rows           = $Model->where($map)->order("id DESC")->limit($Page->firstRow, $Page->listRows)->select();
        // 显示模板
        return V('', ['rows'=>$rows, 'pager'=>$Page->show()]);
    }
}