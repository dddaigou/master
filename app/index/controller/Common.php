<?php
namespace app\index\controller;

use think\Controller;
use think\Session;
use think\Config;

class Common extends Controller 
{
    public function _initialize()
    {
        #頭部
            #會員登入信息,交給了Session
            #在線人數，已成交筆數，出售中筆數
        $counter    = F('t8_counter');
        $this->assign('_count_user_online', $counter['user_online_count']);
        $this->assign('_count_trade_complete', $counter['trade_complete_count']);
        $this->assign('_count_goods_onsell', $counter['goods_onsell_count']);
    }
}