<?php 
namespace app\admin\controller;

use think\Input;
use think\Response;
use org\Page;

class Feedback extends Common
{
    protected $fields   = [
        'title'     => '标题',
        'content'   => '内容',
        'user_id'   => '会员编号',
    ];
    public function index()
    {
        // 搜索参数
        $map        = [];
        if (Input::get('keyword/s')) {
            $field      = isset($this->fields[Input::get('field/s')]) ? $this->fields[Input::get('field/s')] : 'title';
            $map[$field]= $field=='user_id' ? Input::get('keyword/d') : ['like', '%'.Input::get('keyword/s').'%'];
        }
        // 加载模型
        $FeedbackModel  = D('Feedback'); 
        // 统计数量
        $total      = $FeedbackModel->where($map)->count();
        // 设置分页
        $Page       = new Page($total);
        // 获取数据
        $rows       = $FeedbackModel->where($map)->limit($Page->firstRow, $Page->listRows)->select();
        // 加载视图
        return V('', ['rows'=>$rows, 'count'=>$count, 'pager'=>$Page->show(), 'fields'=>$this->fields]);
    }
}