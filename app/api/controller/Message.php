<?php 
namespace app\api\controller;

use logic\user\Message as UserMessage;

class Message
{
    /**
     * 發送站內消息
     * @param  integer $user_id       會員ID
     * @param  integer $type          消息類型
     * @param  integer $tmpl_id       消息模板
     * @param  string  $title         消息標題
     * @param  string  $content       消息內容
     * @param  array   $data          數據 array/json
     * @param  integer $admin_user_id 客服ID
     * @return [type]                 [description]
     */
    public function send($user_id=0, $type=0, $tmpl_id=0, $title='', $content='', $data=[], $admin_user_id=0)
    {
        $message_id     = UserMessage::send($user_id, $type, $tmpl_id, $title, $content, $data, $admin_user_id);
        if (!$message_id) {
            return ['code'=>UserMessage::getErrno(), 'msg'=>UserMessage::getError(), 'faild_field'=>UserMessage::getFailFeild()];
        }
        return ['code'=>200, 'msg'=>'發送成功', 'message_id'=>$message_id];
    }
}