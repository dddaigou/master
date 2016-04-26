<?php
namespace logic\goods;

use app\common\util\Configure;
use org\Validate;
use logic\user\User;
use logic\game\Game;
use logic\game\Server as GameServer;
use logic\goods\Type as GoodsType;

class Publish
{
    protected $data         = [];
    protected $errno        = null;
    protected $error        = null;
    protected $publish_limit= 50;

    static public function instance($goods_type=1, $data=[])
    {
        $class              = __NAMESPACE__.'\\publish\\GoodsType'.$goods_type;
        if (!class_exists($class)) {
            $class          = get_class($this);
        }
        return new $class($data);
    }

    public function __construct($data)
    {
        $this->data         = array_merge([
            'slogan'        => '',
            'status'        => 0,
            'cause'         => 0,
            'renewal_time'  => date('Y-m-d H:i:s'),
            'post_time'     => date('Y-m-d H:i:s'),
            'extend'        => '',
            'images'        => '',
            'detail'        => '',
        ], $data);
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : '';
    }

    public function __set($name, $value)
    {
        $this->data[$name]  = $value;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function check()
    {
        // 是否全部關閉刊登
        if (!$this->_isSystemDenyPublish()) return false;
        // 檢查數據完整
        if (!$this->_checkData()) return false;
        // 單獨類型數據檢測
        if (!$this->_checkExtendData()) return false;
        // 是否會員有權限刊登
        if (!$this->_isSellerDenyPublish()) return false;
        // 是否超出筆數限制
        if (!$this->_isUpdate() && $this->_isOverflowPublishLimit()) return false;
        return true;
    }

    public function save()
    {
        $GoodsModel     = D('Goods');
        $GoodsInfoModel = D('GoodsInfo');
        if ($this->_isUpdate()) {
            if (!$GoodsModel->create($this->data)) {
                $this->errno    = 302;
                $this->error    = '數據有誤';
                return false;
            }
            if (false===$GoodsModel->where(['id'=>$this->id])->save()) {
                $this->errno    = 304;
                $this->error    = '編輯失敗';
                return false;
            }
            if (!$GoodsInfoModel->create($this->data) || false===$GoodsInfoModel->where(['goods_id'=>$this->id])->save()) {
                $this->errno    = 305;
                $this->error    = '編輯失敗';
                return false;
            }
            $goods_id           = $this->id;
        } else {
            if (!$GoodsModel->create($this->data)) {
                $this->errno    = 302;
                $this->error    = '數據有誤';
                return false;
            }
            if (!$goods_id=$GoodsModel->add()) {
                $this->errno    = 304;
                $this->error    = '刊登失敗';
                return false;
            }
            $this->data['goods_id'] = $goods_id;
            if (!$GoodsInfoModel->create($this->data) || !$GoodsInfoModel->add()) {
                $GoodsModel->where(['id'=>$goods_id])->setField(['status'=>2, 'cause'=>5]);
                $this->errno    = 305;
                $this->error    = '刊登失敗';
                return false;
            }
        }
        return $goods_id;
    }

    public function getErrno()
    {
        return $this->errno;
    }

    public function getError()
    {
        return $this->error;
    }

    protected function _isUpdate()
    {
        return isset($this->data['id']) ? true : false;
    }

    protected function _isSystemDenyPublish()
    {
        if (Configure::get('publish.on', 1)==0) {
            $this->errno    = 100;
            $this->error    = '系統禁止刊登';
            return false;
        }
        return true;
    }

    protected function _checkData()
    {
        $rules              = [
            ['type', '商品類型必須', 'require'],
            ['type', '商品類型格式不正確', 'number'],
            ['seller_id', '賣家ID必須', 'require'],
            ['seller_id', '專家ID格式不正確', 'number'],
            ['title', '商品標題必須', 'require'],
            ['title', '商品標題長度超出範圍', 'length', '1,120'],
            ['stock', '庫存必須', 'require'],
            ['stock', '庫存格式不正確', 'number'],
            ['stock', '庫存超出範圍', 'between', '1,1000'],
            ['price', '價格必須', 'require'],
            ['price', '價格格式不正確', 'number'],
            ['price', '價格超出範圍', 'between', '10,200000'],
            ['detail', '商品詳情必須', 'require'],
            ['post_ip', '刊登IP必須', 'require'],
            ['post_ip', '刊登IP格式不正確', 'ipv4'],
        ];
        if (!Validate::valid($this->data, $rules)) {
            $this->errno    = 300;
            $this->error    = Validate::getError();
            return false;
        }
        // 後期數據處理
        $this->data['detail']      = stripcslashes($this->data['detail']);
        // 是否有圖片
        if (!empty($this->data['images'])) {
            $this->data['badges']       .= ',image';
        }
        // 是否折扣
        if (!empty($this->data['cost_price']) && $this->data['cost_price']!=$this->data['price']) {
            $this->data['badges']       .= ',sale';
        } else {
            $this->data['cost_price']   = $this->data['price'];
        }
        $this->data['badges']           = trim($this->data['badges'], ',');
        // 合併ID
        $merge_data                     = [
            $this->type,
            $this->game_id,
            $this->server_id,
            $this->seller_id,
            $this->title,
            $this->price,
        ];
        $this->data['merge_id']         = crc32(join('|', $merge_data));
        // 編輯時幾個字段不能修改
        if ($this->_isUpdate()) {
            if (isset($this->data['type'])) unset($this->data['type']);
            if (isset($this->data['game_id'])) unset($this->data['game_id']);
            if (isset($this->data['server_id'])) unset($this->data['server_id']);
            if (isset($this->data['seller_id'])) unset($this->data['seller_id']);
            if (isset($this->data['status'])) unset($this->data['status']);
        }
        return true;
    }

    protected function _checkExtendData()
    {
        return true;
    }

    protected function _isSellerDenyPublish()
    {
        if (User::isDenyLogin($this->seller_id)) {
            $this->errno    = 110;
            $this->error    = '您已被停權，無法刊登';
            return false;
        }
        if (User::isDenyPublish($this->seller_id)) {
            $this->errno    = 111;
            $this->error    = '您已被禁止刊登';
            return false;
        }
        if ($this->type==1 && !User::isCardSeller()) {
            $this->errno    = 111;
            $this->error    = '您沒有權限刊登點卡';
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
            'seller_id' => $this->seller_id,
        ];
        $publish_limit      = $this->publish_limit;
        $count              = D('Goods')->where($map)->count();
        if ($count>=$publish_limit) { 
            $this->errno    = 140;
            $this->error    = '超出筆數限制';
            return true;
        }
        return false;
    }
}