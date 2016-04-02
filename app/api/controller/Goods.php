<?php
namespace app\api\controller;

use logic\goods\Publish;
use org\Validate;

class Goods
{
    /**
     * 刊登
     * @param  array  $data 商品信息
     * @return [type]             结果 200=成功
     */
    public function publish($data=[])
    {
        $Publish        = Publish::instance($data['type'], $data);
        if (!$Publish->check()) {
            return ['code'=>$Publish->getErrno(), 'msg'=>$Publish->getError()];
        }
        if (!$goods_id=$Publish->save()) {
            return ['code'=>$Publish->getErrno(), 'msg'=>$Publish->getError()];
        }
        return ['code'=>200, 'msg'=>'刊登成功', 'goods_id'=>$goods_id];
    }

    /**
     * 编辑
     * @param  integer $id         商品ID
     * @return [type]              结果 200=成功
     */
    public function edit($data=[])
    {
        $Publish        = Publish::instance($data['type'], $data);
        if (!$Publish->check()) {
            return ['code'=>$Publish->getErrno(), 'msg'=>$Publish->getError()];
        }
        if (!$goods_id=$Publish->save()) {
            return ['code'=>$Publish->getErrno(), 'msg'=>$Publish->getError()];
        }
        return ['code'=>200, 'msg'=>'編輯成功', 'goods_id'=>$goods_id];
    }

    /**
     * 删除
     * @param  integer $id      商品ID
     * @param  integer $cause   下架或删除原因 0=会员 1=系统 2=客服 3=过期 4=无库存 5=其他
     * @return [type]           结果 200=成功
     */
    public function delete($id=0, $cause=0)
    {
        if (!Validate::valid(
            ['id'=>$id, 'stock'=>$cause],
            [
                ['id', '商品ID不正確', 'number'],
                ['cause', '原因不正確', 'number'],
                ['cause', '原因超出範圍', 'between', '0,5']
            ]
        )) {
            return ['code'=>401, 'msg'=>Validate::getError()];
        }
        if (false===D('Goods')->where(['id'=>$id])->setField(['status'=>2, 'cause'=>$cause])) {
            return ['code'=>400, 'msg'=>'刪除失敗'];
        }
        return ['code'=>200, '刪除成功'];
    }

    /**
     * 上架
     * @param  integer $id 商品ID
     * @param  integer $cause   下架或删除原因 0=会员 1=系统 2=客服 3=过期 4=无库存 5=其他
     * @return [type]      结果 200=成功
     */
    public function pullOn($id=0, $cause=0)
    {
        if (!Validate::valid(
            ['id'=>$id, 'cause'=>$cause],
            [
                ['id', '商品ID不正確', 'number'],
                ['cause', '原因不正確', 'number'],
                ['cause', '原因超出範圍', 'between', '0,5']
            ]
        )) {
            return ['code'=>401, 'msg'=>Validate::getError()];
        }
        if (false===D('Goods')->where(['id'=>$id])->setField(['status'=>0, 'cause'=>$cause])) {
            return ['code'=>400, 'msg'=>'上架失敗'];
        }
        return ['code'=>200, '上架成功'];
    }

    /**
     * 下架
     * @param  integer $id 商品ID
     * @param  integer $cause   下架或删除原因 0=会员 1=系统 2=客服 3=过期 4=无库存 5=其他
     * @return [type]      结果 200=成功
     */
    public function pullOff($id=0, $cause=0)
    {
        if (!Validate::valid(
            ['id'=>$id, 'stock'=>$cause],
            [
                ['id', '商品ID不正確', 'number'],
                ['cause', '原因不正確', 'number'],
                ['cause', '原因超出範圍', 'between', '0,5']
            ]
        )) {
            return ['code'=>401, 'msg'=>Validate::getError()];
        }
        if (false===D('Goods')->where(['id'=>$id])->setField(['status'=>1, 'cause'=>$cause])) {
            return ['code'=>400, 'msg'=>'下架失敗'];
        }
        return ['code'=>200, '下架成功'];
    }

    /**
     * 刷新
     * @param  integer $id 商品ID
     * @return [type]      结果 200=成功
     */
    public function renew($id=0)
    {
        if (!Validate::valid(
            ['id'=>$id],
            [
                ['id', '商品ID不正確', 'number'],
            ]
        )) {
            return ['code'=>401, 'msg'=>Validate::getError()];
        }
        if (false===D('Goods')->where(['id'=>$id])->setField(['renewal_time'=>date('Y-m-d H:i:s')])) {
            return ['code'=>400, 'msg'=>'刷新失敗'];
        }
        return ['code'=>200, '刷新成功'];
    }

    /**
     * 设置库存
     * @param  integer $id 商品ID
     * @return [type]      结果 200=成功
     */
    public function setStock($id=0, $stock=0)
    {
        if (!Validate::valid(
            ['id'=>$id, 'stock'=>$stock],
            [
                ['id', '商品ID不正確', 'number'],
                ['stock', '商品庫存不正確', 'number'],
                ['stock', '庫存超出範圍', 'between', '0,1000']
            ]
        )) {
            return ['code'=>401, 'msg'=>Validate::getError()];
        }
        if (false===D('Goods')->where(['id'=>$id])->setField(['stock'=>$stock])) {
            return ['code'=>400, 'msg'=>'設置庫存失敗'];
        }
        return ['code'=>200, '設置庫存成功'];
    }
}