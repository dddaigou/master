<?php 
namespace app\usercenter\controller\card;

use think\Input;
use think\Response;
use think\Session;
use org\Page;
use org\Validate;
use logic\goods\Type;
use logic\game\Game;
use api\Goods as GoodsApi;
use app\usercenter\controller\Common;

class Goods extends Common
{
    // 卡类待出售列表
    public function index()
    {
        $map            = [];
        $map['type']    = 1;
        $map['status']  = 0;
        
        $GoodsModel     = D('Goods');
        // 统计总数
        $total          = $GoodsModel->where($map)->count();
        // 分页
        $Page           = new Page($total);
        // 获取列表
        $rows           = $GoodsModel->where($map)->limit($Page->firstRow,$Page->listRows)->select();
        foreach ($rows as $k => $row){
            $rows[$k]['type_name']  = Type::get($row['type']);
            $rows[$k]['game_name']  = Game::getNameById($row['id']);
        }
        // 模板变量
        $data           = [];
        $data['rows']   = $rows;
        $data['page']   = $Page->show();
        return V('', $data);
    }
    
    // 点卡商品刊登
    public function publish(){
        if(IS_POST){
            $data               = Input::post();
            $data['type']       = 1;
            $data['seller_id']  = Session::get('user_id');
            $res                = GoodsApi::publish(['data'=>$data],1);
            if($res['code']==200){
                return Response::success('刊登成功');
            }else{
                return Response::error($res['msg']);
            }
        }else{
            return V();
        }
    }
    
    // 点卡商品编辑
    public function edit(){
        if(IS_POST){
            $res    = GoodsApi::edit(['data'=>Input::post()],1);
            if($res['code']==200){
                return Response::success('编辑成功');
            }else{
                return Response::error($res['msg']);
            }
        }else{
            return V();
        }
    }
    
    // 修改库存
    public function setStock()
    {
        $res    = GoodsApi::setStock(Input::post());
        return $res;
    }
    
    // 更新
    public function renew()
    {
        $res    = GoodsApi::renew(Input::post(),1);
        if($res['code']==200){
            return Response::success('更新成功');
        }else{
            return Response::error($res['msg']);
        }
    }
    
    // 上架
    public function pullOn()
    {
        $res    = GoodsApi::pullOn(Input::post(),1);
        if($res['code']==200){
            return Response::success('上架成功');
        }else{
            return Response::error($res['msg']);
        }
    }
    
    // 下架
    public function pullOff()
    {
        $res    = GoodsApi::pullOff(Input::post(),1);
        if($res['code']==200){
            return Response::success('下架成功');
        }else{
            return Response::error($res['msg']);
        }
    }
    
    // 删除
    public function delete()
    {
        $res    = GoodsApi::delete(Input::post(),1);
        if($res['code']==200){
            return Response::success('删除成功');
        }else{
            return Response::error($res['msg']);
        }
    }
}