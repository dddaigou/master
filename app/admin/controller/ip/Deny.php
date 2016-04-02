<?php 
namespace app\admin\controller\ip;

use think\Input;
use think\Response;
use org\Page;
use org\Validate;
use logic\admin\User as AdminUserLogic;
use app\admin\controller\Common;

class Deny extends Common
{
    public function index()
    {
        // 接收参数
        $map            = [];
        if (Input::get('keyword')) {
            $map['ip']  = ['like', '%'.Input::get('keyword').'%'];
        }
        // 加载模型
        $DenyIpModel    = D('DenyIp');
        // 统计总数
        $total          = $DenyIpModel->where($map)->count();
        // 分页
        $Page           = new Page($total);
        // 数据
        $rows           = $DenyIpModel->where($map)->order("id DESC")->limit($Page->firstRow, $Page->listRows)->select();
        // 显示模板
        return V('', ['rows'=>$rows, 'pager'=>$Page->show()]);
    }

    public function del()
    {
        // 刪除卡信息
        if (false===D('DenyIp')->where(['id'=>['in', Input::request('id')]])->delete()) {
            return Response::error('刪除失敗');
        }
        return Response::success('刪除成功', '', U('index'));
    }

    public function add()
    {
        if (IS_POST) {
            $rules  = [
                ['ip', 'IP必須', 'require'],
                ['ip', 'IP格式不正确', 'ipv4']
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 默认封禁1个月
            if (!empty($_POST['expire_time'])) {
                $_POST['expire_time'] = date('Y-m-d H:i:s', strtotime('+1 month'));
            }
            $_POST['action_user_id']= AdminUserLogic::getLoginUserId();
            // 加載模型
            $DenyIpModel            = D('DenyIp');
            // 檢測是否已存在相同名字的遊戲
            $map                    = ['ip'=>$_POST['ip']];
            $count                  = $DenyIpModel->where($map)->count();
            if ($count) {
                return Response::error('已經存在相同IP');
            }
            // 保存數據
            if (!$DenyIpModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            if (!$DenyIpModel->add()) {
                return Response::error('新增失敗');
            }
            // 編輯成功
            return Response::success('新增成功', '', U('index'));
        } else {
            return V('add');
        }
    }

    public function edit()
    {
        $DenyIpModel                = D('DenyIp');
        if (IS_POST) {
            $rules                  = [
                ['id', '缺少參數', 'require'],
                ['id', '參數錯誤', 'number'],
                ['ip', 'IP必須', 'require'],
                ['ip', 'IP格式不正确', 'ipv4']
            ];
            // 校驗參數
            if (!Validate::valid($_POST, $rules)) {
                return Response::error(Validate::getError());
            }
            // 默认封禁1个月
            if (!empty($_POST['expire_time'])) {
                $_POST['expire_time'] = date('Y-m-d H:i:s', strtotime('+1 month'));
            }
            // 檢測是否已存在相同名字的遊戲
            $map                    = ['id'=>['neq', $_POST['id']], 'ip'=>$_POST['ip']];
            $count                  = $DenyIpModel->where($map)->count();
            if ($count) {
                return Response::error('已經存在相同IP');
            }
            // 保存數據
            if (!$DenyIpModel->create($_POST)) {
                return Response::error('數據有誤');
            }
            $map                    = ['id'=>$_POST['id']];
            if (false === $DenyIpModel->where($map)->save()) {
                return Response::error('編輯失敗');
            }
            // 編輯成功
            return Response::success('編輯成功', '', U('index'));
        } else {
            $row    = $DenyIpModel->getbyId(Input::get('id'));
            if (empty($row)) {
                return Response::error('參數錯誤或已被刪除');
            }
            return V('add', ['row'=>$row]);
        }
    }

    public function toConfig()
    {
        // 獲取遊戲列表
        $configs        = [];
        $rows           = D('DenyIp')->field('ip')->where(['expire_time'=>['gt', date('Y-m-d H:i:s')]])->select();
        $config_name    = 'deny_ip';
        foreach ($rows as $row) {
            $configs[]  = $row['ip'];
        }
        if (false===F($config_name, $configs)) {
            return Response::error('更新配置失敗', '', U('index'));
        }
        return Response::success('更新配置成功', '', U('index'));
    }
}