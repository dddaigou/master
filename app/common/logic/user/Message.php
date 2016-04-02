<?php 
namespace logic\user;

use org\Validate;

class Message
{
    static protected $error     = null;
    static protected $errno     = null;
    static protected $failField = null;
    static protected $types     = [
        1   => '系統',
        2   => '交易',
        3   => '發票',
        4   => '活動',
        5   => '違規',
        6   => '其他',
    ];

    static public function type($type=null)
    {
        if (is_null($type)) {
            return $types;
        }

        return isset(self::$types[$type]) ? self::$types[$type] : '未知';
    }

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
    static public function add($user_id=0, $type=0, $tmpl_id=0, $title='', $content='', $data=[], $admin_user_id=0)
    {
        $dataset        = get_defined_vars();
        // 數據校驗
        $rules          = [
            ['user_id', '會員ID必須', 'require'],
            ['user_id', '會員ID不正確', '>', 0],
            ['type', '消息類型必須', 'require'],
            ['type', '消息類型錯誤', 'number'],
        ];
        if ($tmpl_id==0) {
            $rules[]    = ['title', '消息標題必須', 'require'];
            $rules[]    = ['content', '消息內容必須', 'require'];
        }
        if (!empty($dataset['data'])) {
            if (is_string($dataset['data'])) {
                $dataset['data']    = json_decode($dataset['data'], 1);
                if (empty($dataset['data'])) {
                    self::$errno    = 301;
                    self::$error    = '參數data解析錯誤';
                    self::$failField= 'data';
                    return false;
                }
            }
            // 轉json格式
            $dataset['data']       = json_encode($dataset['data'], JSON_UNESCAPED_UNICODE);
        }
        if (!Validate::valid($dataset, $rules)) {
            self::$errno    = 300;
            self::$error    = Validate::getError();
            self::$failField= Validate::getFailFeild();
            return false;
        }
        // 保存 message
        $UserMessageModel   = D('UserMessage');
        if (!$UserMessageModel->create($dataset)) {
            self::$errno    = 500;
            self::$error    = '數據有誤';
            return false;
        }
        if ($message_id=$UserMessageModel->add()) {
            self::$errno    = 501;
            self::$error    = '數據保存失敗';
            return false;
        }
        // 保存消息內容
        if ($tmpl_id==0) {
            $UserMessageContentModel    = D('UserMessageContent');
            $UserMessageContentModel->create($dataset) && $UserMessageContentModel->add();
        }
        return $message_id;
    }

    static public function getError()
    {
        return self::$error;
    }

    static public function getErrno()
    {
        return self::$errno;
    }

    static public function getFailFeild()
    {
        return self::$failField;
    }
}