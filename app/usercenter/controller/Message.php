<?php 
namespace app\usercenter\controller;

use think\Input;
use think\Response;
use think\Session;

class Message extends Common
{
    /**
     * 站内消息列表
     */
    public function index()
    {
        // ajax加载列表
        if (!IS_AJAX) {
            // 接收参数 0=全部類型
            $map                = [];
            $map['user_id']     = Session::get('user_id');
            if (Input::get('type/d', 0)>0) {
                $map['type']    = Input::get('type/d', 0);
            }
            // 模型
            $field              = ["a.id", "type", "IF( a.tmpl_id, c.title, b.title ) AS title", "post_time"];
            $UserMessageModel   = D('UserMessage');
            $rows               = $UserMessageModel->field($field)
                                    ->alias('a')
                                    ->join('__USER_MESSAGE_CONTENT__ b', 'a.id=b.message_id', 'LEFT')
                                    ->join('__USER_MESSAGE_TEMPLATE__ c', 'a.tmpl_id=c.id', 'LEFT')
                                    ->where($map)
                                    ->select();
            return json_encode($rows, JSON_UNESCAPED_UNICODE);
        } else {
            return V('');
        }
    }

    /**
     * ajax加载站内消息
     */
    public function load()
    {
        // 接收参数
        $id         = Input::get('id/d');
        $user_id    = Session::get('user_id');
        $map        = [
            'a.id'      => $id,
            'a.user_id' => $user_id,
            'a.status'  => ['egt', '0'], // 0=未讀 1=已讀 -1=刪除（不顯示）
        ];
        // 模型
        $UserMessageModel = D('UserMessage');
        // 查询消息
        $field              = ["*", "IF( a.tmpl_id, c.title, b.title ) AS title", "IF( a.tmpl_id, c.content, b.content ) AS content"];
        $UserMessageModel   = D('UserMessage');
        $message            = $UserMessageModel->field($field)
                                ->alias('a')
                                ->join('__USER_MESSAGE_CONTENT__ b', 'a.id=b.message_id', 'LEFT')
                                ->join('__USER_MESSAGE_TEMPLATE__ c', 'a.tmpl_id=c.id', 'LEFT')
                                ->where($map)
                                ->find();
        // 找不到信息
        if (empty($message)) {
            return Response::error('參數錯誤或消息已被刪除');
        }
        if (!empty($message['data']) && $data=json_decode($message['data'])) {
            $replaces       = [];
            foreach ($data as $key => $value) {
                $replaces["{{$key}}"]   = $value;
            }
            $message['title']   = strtr($message['title'], $replaces);
            $message['content'] = strtr($message['content'], $replaces);
        }
        // 返回類型
        $message['type']    = \logic\user\Message::type($message['type']);
        // 返回json
        return json_encode($message, JSON_UNESCAPED_UNICODE);
    }
}