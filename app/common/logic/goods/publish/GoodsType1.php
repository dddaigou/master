<?php 
namespace logic\goods\publish;

use org\Validate;
use logic\goods\Publish;
use logic\card\Card;
use logic\card\Denom as CardDenom;

// 點卡
class GoodsType1 extends Publish
{
    protected $publish_limit    = 1;

    // 檢查卡是否被禁止刊登
    protected function _isGameDenyPublish()
    {
        if (Card::isDenyPublish($this->game_id)) {
            $this->errno    = 120;
            $this->error    = '當前卡類被禁止刊登';
            return false;
        }
        return true;
    }

    // 檢查面額是否被禁止刊登
    protected function _isGameServerDenyPublish()
    {
        if (CardDenom::isDenyPublish($this->server_id)) {
            $this->errno    = 120;
            $this->error    = '當前卡類面額被禁止刊登';
            return false;
        }
        return true;
    }

    protected function _isOverflowPublishLimit()
    {
        // 不限制筆數
        if ($this->publish_limit==0) return false;
        // 統計當前筆數
        $map                = [
            'type'      => $this->type,
            'game_id'   => $this->game_id,
            'server_id' => $this->server_id,
            'seller_id' => $this->seller_id,
        ];
        $count              = D('Goods')->where($map)->count();
        if ($count>=$this->publish_limit) { 
            $this->errno    = 140;
            $this->error    = "每個面額只能刊登{$this->publish_limit}筆";
            return true;
        }
        return false;
    }
}