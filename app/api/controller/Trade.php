<?php
namespace app\api\controller;

use org\Validate;

class Trade
{
    /**
     * 建立交易
     * @param  array      $data  = [      
     *          int      goods_id       商品編號
     *          int      seller_id      賣家id 
     *          int      buyer_id       買家id
     *          int      trade_num      購買數量
     *          [0,1]    is_exclusive   是否專屬
     *          int      trade_price    成交價   
                #        client         客戶端...  
     *       ]
     * @return [type]   结果 200=成功
     */
    public function establish($data=[])
    {
        // return $data;
        $rule           = [
            ['goods_id', '商品ID不正確', 'require'],
            ['goods_id', '商品ID不正確', 'number'],
            ['goods_id', '商品ID不正確', '>','0'],
            ['seller_id', '賣家不正確', 'require'],
            ['seller_id', '賣家不正確', 'number'],
            ['seller_id', '賣家不正確', '>','0'],
            ['buyer_id', '買家不正確', 'require'],
            ['buyer_id', '買家不正確', 'number'],
            ['buyer_id', '買家不正確', '>','0'],
            ['trade_num', '購買數量不正確', 'number'],
            ['trade_num', '購買數量不正確', '>','0'],
            ['trade_price', '交易價格不正確', 'number'],
            ['trade_price', '交易價格不正確', '>','0'],
            ['is_exclusive', '專屬標記不正確', 'between', '0,1']
        ];
        if (!Validate::valid($data,$rule)) {
            return ['code'=>401, 'msg'=>Validate::getError()];
        }
        $goods_info     = D('Goods')->find($data['goods_id']);
        if (empty($goods_info)||$goods_info['status']==2) {
            return ['code'=>401, 'msg'=>'商品不存在或已刪除'];
        }
        if ($goods_info['seller_id']!=$data['seller_id']) {
            return ['code'=>401, 'msg'=>'非法操作，你不是該商品的主人'];
        }
        if ($goods_info['seller_id']==$data['buyer_id']) {
            return ['code'=>401, 'msg'=>'非法操作，你不能出售商品給自己'];
        }
        if ($data['is_exclusive']==0&&$data['trade_price']>0) {
            return ['code'=>401, 'msg'=>'非法操作，你無法修改交易價格'];
        }
        if ($data['trade_num']>$goods_info['stock']) {
            return ['code'=>401, 'msg'=>'庫存不足'];
        }

        $tarde_data                     = $data;
        $tarde_data['goods_price']      = $goods_info['price'];
        //生成專屬時可以修改交易價格
        if ($tarde_data['is_exclusive']) {
            $tarde_data['trade_price']  = $tarde_data['trade_price']?$tarde_data['trade_price']:$tarde_data['goods_price'];
        }else{
            $tarde_data['trade_price']  = $tarde_data['goods_price'];
        }
        $tarde_data['trade_fee']        = \logic\trade\Fee::get($tarde_data['trade_price']*$tarde_data['trade_num']);
        
        if (D('Trade')->create($tarde_data)&&$insert_id=D('Trade')->add()) {
            //创建交易编号
            D('Trade')->where("id=$insert_id")->setField('trade_no',\logic\trade\Number::create($insert_id));
            //創建交易步驟記錄t8_trade_schedule
            $schedule_data['trade_id']  = $insert_id;
            $schedule_data['action_type']  = $data['is_exclusive']>0?'seller':'buyer';
            $schedule_data['action_user_id']  = $data['is_exclusive']>0?$data['seller_id']:$data['buyer_id'];
            if (M('TradeSchedule')->create($schedule_data)&&M('TradeSchedule')->add()) {
                return ['code'=>200, 'msg'=>'操作成功','data'=>['id'=>$insert_id]];
            }else{
                \think\Log::write("普通商品$insert_id插入交易流程t8_trade_schedule失敗\nsql:".M('TradeSchedule')->getLastSql());
                return ['code'=>401, 'msg'=>'操作失敗，請聯繫客服[1]'];
            }
        }
        \think\Log::write('高級商品'.$data['goods_id']."生成普通商品失敗\nsql：".D('Trade')->getLastSql());
        return ['code'=>401, 'msg'=>'操作失敗，請聯繫客服[2]'];
    }

    /**
     * 移交商品
     * @param  x      $x       x
     * @param  x      $x       x
     * @return [type]   结果 200=成功
     */
    public function transfer($data=[]){

    }
    /**
     * 領收商品
     * @param  x      $x       x
     * @param  x      $x       x
     * @return [type]   结果 200=成功
     */
    public function receive($data=[]){
        
    }

    /**
     * 取消交易商品
     * @param  x      $x       x
     * @param  x      $x       x
     * @return [type]   结果 200=成功
     */
    public function cancel($data=[]){
        
    }
}