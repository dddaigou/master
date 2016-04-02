<?php
namespace app\index\controller;
use think\Config;

class Index extends Common
{
    public function index()
    {
        $configs            = [
            'index_online_user_count',  // 在线人数
            'index_new_users',          // 新注册会员
            'index_new_pc_game_trade',  // 端游交易
            'index_new_mb_game_trade',  // 手游交易
            'index_hot_pc_game',        // 热门端游
            'index_hot_mb_game',        // 热门手游
            'index_hot_card'            // 热门点卡
        ];
        foreach ($configs as $key) {
            $this->assign($key, F($key));
        }
        return V();
    }
}
