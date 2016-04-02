<?php
namespace app\index\controller;

use think\Input;

class Search extends Common
{
    /**
     * 搜索中轉頁，處理一些特殊的關鍵字或邏輯，再跳轉
     * @return [type] [description]
     */
    public function index()
    {
        // 接收参数
        $game_id    = Input::post('game_id/d', 0);
        $server_id  = Input::post('server_id/d', 0);
        $type       = Input::post('type/d', 0);
        // 必须选择游戏
        if ($game_id<=0) {
            return Response::error('請選擇遊戲');
        }
        // 重新整理參數
        $params     = Input::post();
        // 點卡特別處理
        if ($type==1) {
            $url    = U('Mall/card@www', $params);
        } else {
            $url    = U('Mall/index@www', $params);
        }
        // 跳轉
        Response::redirect($url);
    }
}