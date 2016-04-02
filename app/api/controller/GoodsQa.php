<?php 
namespace app\api\controller;

use org\Validate;

class GoodsQa
{
    /**
     * 問與答提問
     * @param  integer $goods_id       商品ID
     * @param  integer $ask_user_id    提問人ID
     * @param  string  $ask_content    提問內容
     * @param  string  $post_client    提問客戶端 web/mobile/android/ios
     * @param  string  $post_client_ip 提問IP
     * @return [type]                  [description]
     */
    public function ask($goods_id=0, $ask_user_id=0, $ask_content='', $post_client='web', $post_client_ip='')
    {
        $data   = get_defined_vars();
        // 检查数据
        $rules  = [
            ['goods_id', '商品編號必須', 'require'],
            ['goods_id', '商品編號錯誤或商品已被刪除', 'function', function($goods_id=0){
                return D('Goods')->where(['id'=>$goods_id, 'status'=>['in', [0,1]]])->count() ? true : false;
            }],
            ['ask_user_id', '提問者編號必須', 'require'],
            ['ask_user_id', '提問者不存在或已被停權', 'function', function($user_id=0){
                $user_info = D('User')->getbyId($user_id);
                if (empty($user_info)) return false;
                if (false!==strpos($user_info['deny_access'], 'login')) return false;
                return true;
            }],
            ['ask_content', '提問內容必須', 'require'],
            ['ask_content', '提問內容不能為空', 'length', '1,3000'],
        ];
        if (!Validate::valid($data, $rules)) {
            return ['code'=>300, 'error'=>Validate::getError(), 'fail_field'=>Validate::getFailFeild()];
        }
        // 查詢賣家ID
        $data['seller_id']  = D('Goods')->getFieldById($goods_id, 'seller_id');
        // 保存提問
        $GoodsQaModel       = D('GoodsQa');
        if (!$GoodsQaModel->create($data)) {
            return ['code'=>500, 'msg'=>'數據有誤'];
        }
        if (!$qa_id=$GoodsQaModel->add()) {
            return ['code'=>501, 'msg'=>'提問失敗'];
        }
        // 保存內容
        $data['qa_id']          = $qa_id;
        $GoodsQaContentModel    = D('GoodsQaContent');
        $GoodsQaContentModel->create($data) && $GoodsQaContentModel->add();
        // 返回成功
        return ['code'=>200, 'msg'=>'提問成功', 'qa_id'=>$qa_id];
    }

    /**
     * 設置已讀
     * @param integer $id 問與答ID
     */
    public function setAskRead($id=0)
    {
        // 檢查ID正確性
        $rules  = [
            ['id', '問與答編號錯誤', 'number'],
            ['id', '問與答不存在', 'function', function($id){
                return D('GoodsQa')->where(['id'=>$id])->count() ? true : false;
            }]
        ];
        if (!Validate::valid($data, $rules)) {
            return ['code'=>300, 'error'=>Validate::getError(), 'fail_field'=>Validate::getFailFeild()];
        }
        // 保存數據
        $map    = ['id'=>$id];
        $data   = ['is_read'=>1, 'read_ask_time'=>date('Y-m-d H:i:s')];
        if (false===D('GoodsQa')->where($map)->setField($data)) {
            return ['code'=>500, 'msg'=>'設置已讀失敗'];
        }
        return ['code'=>200, 'msg'=>'設置已讀成功'];
    }

    /**
     * 提問回覆
     * @param  integer $id            提問ID
     * @param  string  $reply_content 回覆內容
     * @return [type]                 [description]
     */
    public function reply($id=0, $reply_content='')
    {
        // 檢查ID正確性
        $rules  = [
            ['id', '問與答編號錯誤', 'number'],
            ['id', '問與答不存在', 'function', function($id){
                return D('GoodsQa')->where(['id'=>$id])->count() ? true : false;
            }]
        ];
        if (!Validate::valid($data, $rules)) {
            return ['code'=>300, 'error'=>Validate::getError(), 'fail_field'=>Validate::getFailFeild()];
        }
        // 保存數據
        $data   = ['is_read'=>1, 'is_reply'=>1, 'reply_time'=>date('Y-m-d H:i:s')];
        if (false===D('GoodsQa')->where($map)->setField($data)) {
            return ['code'=>500, 'msg'=>'保存回覆數據失敗'];
        }
        if (false===D('GoodsQaContent')->where(['qa_id'=>$id])->setField($data)) {
            return ['code'=>501, 'msg'=>'保存回覆內容失敗'];
        }
        return ['code'=>200, 'msg'=>'回覆成功'];
    }

    public function setReplyRead($id=0)
    {
        // 檢查ID正確性
        $rules  = [
            ['id', '問與答編號錯誤', 'number'],
            ['id', '問與答不存在', 'function', function($id){
                return D('GoodsQa')->where(['id'=>$id])->count() ? true : false;
            }]
        ];
        if (!Validate::valid($data, $rules)) {
            return ['code'=>300, 'error'=>Validate::getError(), 'fail_field'=>Validate::getFailFeild()];
        }
        // 保存數據
        $map    = ['id'=>$id];
        $data   = ['is_reply_read'=>1, 'read_reply_time'=>date('Y-m-d H:i:s')];
        if (false===D('GoodsQa')->where($map)->setField($data)) {
            return ['code'=>500, 'msg'=>'設置回覆已讀失敗'];
        }
        return ['code'=>200, 'msg'=>'設置回覆已讀成功'];
    }
}