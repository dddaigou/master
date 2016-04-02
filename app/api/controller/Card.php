<?php 
namespace app\api\controller;

use org\Validate;

class Card
{
    /**
     * 导入库存
     * @param  integer $seller_id 卖家ID
     * @param  integer $card_id   卡类ID
     * @param  integer $denom_id  面额ID
     * @param  array   $card_list 卡号密码
     * @param  integer $crypt_type加密类型 0=明文 1=ENCODE
     * @return array              导入结果
     */
    public function import($seller_id=0, $card_id=0, $denom_id=0, $card_list=[], $crypt_type=0)
    {
        $data   = get_defined_vars(); // 放在第一行
        $rules  = [
            ['seller_id', '卡商ID必须', 'require'],
            ['seller_id', '卡商ID不正确', 'number'],
            ['seller_id', '传入用户不是卡商', 'function', function($user_id){
                return \logic\user\User::isCardSeller($user_id) ? true : false;
            }],
            ['card_id', '卡类ID必须', 'require'],
            ['card_id', '卡类ID不正确', 'number'],
            ['card_id', '卡类不存在', 'function', function($card_id){
                return \logic\card\Card::getNameById($card_id) ? true : false;
            }],
            ['denom_id', '面额ID必须', 'require'],
            ['denom_id', '面额ID不正确', 'number'],
            ['denom_id', '面额不存在', 'function', function($denom_id){
                return \logic\card\Denom::getNameById($denom_id) ? true : false;
            }],
            ['card_list', '库存必须', 'require'],
            ['card_list', '库存不能为空', 'function', function($card_list){
                return !empty($card_list) ? true : false;
            }],
        ];
        if (!Validate::valid($data, $rules)) {
            return ['code'=>300, 'msg'=>Validate::getError(), 'fail_field'=>Validate::getFailFeild()];
        }
        // 解释card_list
        if (is_string($card_list)) {
            $card_list      = explode("\n", $card_list);
            $card_list      = array_filter($card_list);
        }
        // 拼接SQL
        $values             = [];
        foreach ($card_list as $line) {
            $line           = trim($line);
            if (empty($line)) {
                continue;
            }
            // 字符串转数组
            if (is_string($line)) {
                $line       = explode(',', $line, 2);
            }
            // 只有密码没有卡号
            if (count($line)<=1) {
                continue;
            }
            $card_no        = trim($line[0]);
            $card_pwd       = isset($line[1]) ? trim($line[1]) : '';
            $values[]       = "('{$seller_id}', '{$card_id}', '{$denom_id}', AES_ENCRYPT('{$card_no}', '{$seller_id}'), AES_ENCRYPT('{$card_pwd}', '{$seller_id}'))";
        }
        // 库存整理后为空
        if (empty($values)) {
            return ['code'=>301, 'msg'=>'库存错误'];
        }
        $sql                = "INSERT INTO __TABLE__ (`seller_id`, `card_id`, `denom_id`, `card_no`, `card_pwd`) VALUES " . join(',', $values);
        $CardStockModel     = D('CardStock');
        if (false === $CardStockModel->execute($sql)) {
            return ['code'=>500, 'msg'=>$CardStockModel->getDbError()];
        }
        return ['code'=>200, 'msg'=>'导入库存成功', 'count'=>count($values)];
    }
    
    /**
     * 销售情况
     * @param  integer $seller_id 卖家ID
     * @param  integer $card_id   卡类ID
     * @param  integer $denom_id  面额ID
     * @param  string  $start_time 开始时间
     * @param  string  $end_time   结束时间
     * @param  integer $time_type  时间类型 0=上传时间 1=购买时间
     * @return [type]             统计结果
     */
    public function performance($seller_id=0, $card_id=0, $denom_id=0, $start_time='', $end_time='', $time_type=0)
    {
        $map                = [];
        $map['seller_id']   = $seller_id;
        if ($card_id>0) {
            $map['card_id'] = $card_id;
        }
        if ($denom_id>0) {
            $map['denom_id']= $denom_id;
        }
        $time_field         = $time_type==1 ? 'buy_time' : 'create_time';
        if (!empty($start_time)) {
            $map[$time_field][] = ['egt', $start_time];
        }
        if (!empty($end_time)) {
            $map[$time_field][] = ['egt', $end_time];
        }
        $field              = ["card_id", "denom_id", "COUNT(1) AS total", "SUM(IF(status=1, 1, 0)) AS sold", "SUM(IF(status=0, 1, 0)) AS unsold"];
        $group              = "card_id, denom_id";
        $order              = "card_id ASC, denom_id ASC";
        $CardStockModel     = D('CardStock');
        $rows               = $CardStockModel->field($field)->where($map)->group($group)->order($order)->select();
        if (false===$rows) {
            return ['code'=>500, 'msg'=>'查询失败', 'error'=>$CardStockModel->getDbError()];
        }
        return ['code'=>200, 'msg'=>'查询成功', 'data'=>$rows];
    }

    /**
     * 库存查询
     * @param  integer $seller_id  卖家ID，必须
     * @param  integer $card_id    卡类ID
     * @param  integer $denom_id   面额ID
     * @param  string  $card_no    卡号
     * @param  string  $card_pwd   卡密
     * @param  integer $buyer_id   买家ID
     * @param  integer $status     状态 -1=全部 0=待出售 1=已出售
     * @param  string  $start_time 开始时间
     * @param  string  $end_time   结束时间
     * @param  integer $time_type  时间类型 0=上传时间 1=购买时间
     * @return [type]              返回结果
     */
    public function query($seller_id=0, $card_id=0, $denom_id=0, $card_no='', $card_pwd='', $buyer_id=0, $status=-1, $start_time='', $end_time='', $time_type=0)
    {
        $map                = ['seller_id'=>$seller_id];
        if ($card_id>0) {
            $map['card_id'] = $card_id;
        }
        if ($denom_id>0) {
            $map['denom_id']= $denom_id;
        }
        if (!empty($card_no)) {
            $map['card_no'] = ['exp', "ENCODE('{$card_no}')"];
        }
        if (!empty($card_pwd)) {
            $map['card_pwd']= ['exp', "ENCODE('{$card_pwd}')"];
        }
        if ($buyer_id>0) {
            $map['buyer_id']= $buyer_id;
        }
        if ($status>=0) {
            $map['status']  = $stauts;
        }
        $time_field         = $time_type==1 ? 'buy_time' : 'create_time';
        if (!empty($start_time)) {
            $map[$time_field][] = ['egt', $start_time];
        }
        if (!empty($end_time)) {
            $map[$time_field][] = ['elt', $end_time];
        }
        // 查询字段
        $field  = "id, card_id, denom_id, AES_DECRYPT(card_no, seller_id) AS card_no, '****' AS card_pwd, resale_id, status, create_time, buyer_id, buy_time, trade_id";
        $rows   = D('CardStock')->field($field)->where($map)->select();
        return ['code'=>200, 'rows'=>$rows];
    }

    /**
     * 单笔转售
     * @param  integer $seller_id 卖家ID
     * @param  integer $id        旧库存ID
     * @return [type]             转售结果
     */
    public function resale($seller_id=0, $id=0)
    {
        $CardStockModel = D('CardStock');
        // 原来的记录
        $map            = ['seller_id'=>$seller_id, 'id'=>$id];
        $stock_info     = $CardStockModel->where($map)->find();
        if (empty($stock_info)) {
            return ['code'=>404, 'msg'=>'找不到原记录'];
        }
        if ($stock_info['status']!=1) {
            return ['code'=>403, 'msg'=>'已出售商品才能转售'];
        }
        if (!empty($stock_info['resale_id'])) {
            return ['code'=>402, 'msg'=>'不能重复转售'];
        }
        // 生成一笔新记录
        $data           = [
            'seller_id' => $stock_info['seller_id'],
            'card_id'   => $stock_info['card_id'],
            'denom_id'  => $stock_info['denom_id'],
            'card_no'   => $stock_info['card_no'],
            'card_pwd'  => $stock_info['card_pwd'],
            'buyer_id'  => -1,
        ];
        if (!$CardStockModel->create($data)) {
            return ['code'=>500, 'msg'=>'数据有误'];
        }
        if (!$resale_id=$CardStockModel->add()) {
            return ['code'=>501, 'msg'=>'生成转售记录失败'];
        }
        // 修改原记录 resale_id
        if (false===$CardStockModel->where($map)->setField('resale_id', $resale_id)) {
            return ['code'=>502, 'msg'=>'修改旧记录失败'];
        }
        // 返回成功
        return ['code'=>200, 'msg'=>'转售成功', 'resale_id'=>$resale_id];
    }

    /**
     * 发卡
     * @param  integer $trade_id  交易ID，通过 trade_id 获取 seller_id, card_id, denom_id
     * @param  integer $return    是否返回卡信息
     * @return array              发卡结果
     */
    public function send($trade_id=0, $return=false)
    {
        // 查询交易记录
        $TradeModel     = D('Trade');
        $trade_info     = $TradeModel->getbyId($trade_id);
        if (empty($trade_info)) {
            return ['code'=>404, 'msg'=>'参数错误或交易不存在'];
        }
        // 检测交易状态
        if ($trade_info['status']==0) {
            return ['code'=>403, 'msg'=>'交易未付款'];
        }
        // 购买笔数
        if (empty($trade_info['trade_num'])) {
            return ['code'=>405, 'msg'=>'交易笔数不正确'];
        }
        $number         = (int) $trade_info['trade_num'];
        $CardStockModel = D('CardStock');
        // 发卡
        $map            = [
            'seller_id' => $trade_info['seller_id'],
            'card_id'   => $trade_info['game_id'],
            'denom_id'  => $trade_info['denom_id'],
            'status'    => 0,
        ];
        $data           = [
            'status'    => 1,
            'buyer_id'  => $trade_info['buyer_id'],
            'buy_time'  => date('Y-m-d H:i:s'),
            'trade_id'  => $trade_id,
        ];
        // buyer_id ASC 优先 buyer_id=-1 转售
        $affected_rows  = $CardStockModel->where($map)->order('buyer_id ASC, create_time ASC')->limit($number)->setField($data);
        if (!$affected_rows) {
            return ['code'=>500, 'msg'=>'库存不足，发卡失败'];
        }
        // 库存不足，重置被修改的记录
        if ($affected_rows<>$number) {
            $map            = ['trade_id'=>$trade_id];
            $data           = [
                'status'    => 0,
                'buyer_id'  => 0,
                'buy_time'  => '0000-00-00 00:00:00',
                'trade_id'  => 0,
            ];
            $CardStockModel->where($map)->setField($data);
            return ['code'=>501, 'msg'=>'库存不足，发卡失败'];
        }
        // 不返回卡号与密码
        if (!$return) {
            return ['code'=>200, 'msg'=>'发卡成功'];
        }
        // 查询卡号和密码
        $card_info      = $CardStockModel->field("AES_DECRYPT(card_no, seller_id) AS card_no, AES_DECRYPT(card_pwd, seller_id) AS card_pwd")->where(['trade_id'=>$trade_id, 'status'=>1])->select();
        return ['code'=>200, 'msg'=>'发卡成功', 'card_info'=>$card_info];
    }

    /**
     * 提取卡信息
     * @param  integer $trade_id 交易ID
     * @return [type]            卡信息
     */
    public function extract($trade_id=0)
    {
        // 查询卡号和密码
        $card_info      = D('CardStock')->field("AES_DECRYPT(card_no, seller_id) AS card_no, AES_DECRYPT(card_pwd, seller_id) AS card_pwd")->where(['trade_id'=>$trade_id, 'status'=>1])->select();
        if (empty($card_info)) {
            return ['code'=>500, 'msg'=>'提取失败'];
        } 
        return ['code'=>200, 'msg'=>'提取成功', 'card_info'=>$card_info];
    }
}