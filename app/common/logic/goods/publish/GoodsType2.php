<?php 
namespace logic\goods\publish;

use org\Validate;
use logic\goods\Publish;

// 遊戲幣
class GoodsType2 extends Publish
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
}