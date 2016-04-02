<?php 
namespace logic\goods\publish;

use org\Validate;
use logic\goods\Publish;

// 代儲
class GoodsType5 extends Publish
{
    protected $publish_limit    = 5;

    protected function _checkExtendData()
    {
        // 檢測標題
        $rules  = [
            ['slogan', '廣告語必須', 'require'],
            ['slogan', '廣告語長度超出範圍', '1,120'],
        ];
        if (!Validate::valid($this->data, $rules)) {
            $this->errno    = 301;
            $this->error    = Validate::getError();
            return false;
        }
        return true;
    }

    // 有品項則每個品項3筆
    // 沒有品項則5筆
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
        if ($this->item_id>0) {
            $map['item_id'] = $this->item_id;
            $this->publish_limit    = 3;
        }
        $count              = D('Goods')->where($map)->count();
        if ($count>=$this->publish_limit) { 
            $this->errno    = 140;
            $this->error    = '超出筆數限制';
            return true;
        }
        return false;
    }
}