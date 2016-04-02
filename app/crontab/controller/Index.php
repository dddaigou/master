<?php
namespace app\crontab\controller;
use think\Input;
class Index
{
    public function index()
    {
        return 'cli';
    }

    #前台首頁：生成最新加入會員列表
    public function newUsers()
    {
        $userModel                          = D('User');
        $user_count                         = $userModel->count();
        $user_list                          = $userModel->limit(20)->order('id DESC')->select();
        foreach ($user_list as $key => $value) {
            #是否全中文
            if (preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$value['realname'])) {
                $new_users[$key]['user']    = mb_substr($value['realname'],0,2,'utf-8').'*';
            }else {
                $new_users[$key]['user']    = substr($value['realname'], 0,2).'***';
            }
            $new_users[$key]['time']        = get_before_time(strtotime($value['reg_time']));
        }
        $data['new_user_list']              = $new_users;
        $data['total_count']                = $user_count;
        $status = F('index_new_users', $data);
        if ($status) {
            return 'success';
        }else{
            return 'error';
        }
    }

    #前台首頁：生成最新(端遊/手游)成交列表
    public function newGameTrade()
    {
        $type                               = Input::get('type');
        $type                               = $type=='mb'?'mb':'pc';
        $game_type                          = $type=='mb'?'mobile':'pc';
        $tradeModel                         = D('Trade');
        $trade_count                        = $tradeModel->table('t8_trade A')
        ->join('left join `t8_goods` B ON A.goods_id=B.id')
        ->join('left join  `t8_game` C ON B.game_id=C.id')
        ->where("A.status=4 AND C.type='{$game_type}'")
        ->count();
        $trade_list                         = $tradeModel->table('t8_trade A')
        ->join('left join `t8_goods` B ON A.goods_id=B.id')
        ->join('left join `t8_game` C ON B.game_id=C.id')
        ->join('left join `t8_trade_schedule` D ON D.trade_id=A.id')
        ->where("A.status=4 AND C.type='{$game_type}'")
        ->field('A.id,C.id as game_id,C.name as game_name,B.title,D.action_time as time,A.goods_id')
        ->limit(15)
        ->order('A.id DESC')
        ->select();
        foreach ($trade_list as $key => $value) {
            $time                           = $trade_list[$key]['time'];
            $time                           = empty($time)?0:strtotime($time);
            $trade_list[$key]['time']       = get_before_time($time);
        }

        $data['new_trade_list']             = $trade_list;
        $data['total_count']                = $trade_count;
        $status = F("index_new_{$type}_game_trade", $data);
        if ($status) {
            return 'success';
        }else{
            return 'error';
        }
    }
    #定时更新在線人數，已成交筆數，出售中筆數
    public function counter()
    {
        $data['user_online_count']          = \logic\user\Online::count();
        $data['trade_complete_count']       = \logic\trade\Trade::count(['status'=>4]);
        $data['goods_onsell_count']         = \logic\goods\Goods::count();
        $status                             =F('t8_counter',$data);
        if ($status) {
            return 'success';
        }else{
            return 'error';
        }
    }
}
