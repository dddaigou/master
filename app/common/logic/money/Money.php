<?php 
namespace logic\money;

class Balance
{
    static public function get($user_id=0)
    {
        $UserMoneyDetailModel   = D('UserMoneyDetail');
        // 收入
        $map                = [];
        $map['in_acc_no']   = $user_id;
        $map['status']      = 1;
        $income             = (int) $UserMoneyDetailModel->where($map)->sum('realrec_amount');
        // 支出
        $map                = [];
        $map['out_acc_no']  = $user_id;
        $map['status']      = ['in', [0,1]];
        $outlay             = (int) $UserMoneyDetailModel->where($map)->sum('realpay_amount');
        // 余额
        $balance            = $income - $outlay;
        // 返回
        return $balance >=0 ? $balance : 0;
    }
}