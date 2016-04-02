<?php
namespace app\admin\controller\config;

use think\Session;
use think\Response;
use think\Input;
use org\Page;
use app\admin\controller\Common;

class Rank extends Common
{
    #首頁熱門，排行榜等
    public function index()
    {
        $type               = Input::get('type/d', 1);
        $game_id            = Input::get('game_id/d', 0);
        $types              = [1=>'熱賣點卡',2=>'熱門線上遊戲',3=>'熱門手機遊戲'];
        $tags               = ['不顯示',"<span style='color:green;'>顯示","<span style='color:red;'>置頂</span>"];
        $map                = [];
        $map['type']        = $type;
        if ($game_id>0) {
            $map['game_id'] = $game_id; 
        }
        $list               = D('IndexRank')->limit($Page->firstRow, $Page->listRows)->where($map)->order('tag DESC,rank ASC,num DESC')->select();
        $total_num          = D('IndexRank')->where($map)->count();
        $page               = new Page($total_num);
        $this->assign('page',$page->show());
        // echo $list;exit;
        $this->assign('rank_list',$list);
        $this->assign('types',$types);
        $this->assign('type',$type);
        $this->assign('tags',$tags);
        $this->assign('title',$this->getTitle($type));
        return $this->fetch();
    }

    #表格標題
    private function getTitle($type=1)
    {
        $title              = [];
        $title['num']       = '數量';
        $title['rank']      = '排名';
        $title['game_id']   = '遊戲id';
        $types              = [1=>'熱賣點卡',2=>'熱門線上遊戲',3=>'熱門手機遊戲'];
        $title['type']      = $types[$type];
        $title['link']      = '图片链接';
        switch ($type) {
            case '2':
            case '3':
                $title['name']          = '遊戲名稱';
                $title['other']         = '展示商品';
                break;
            default:
                $title['name']          = '點卡名稱';
                $title['num']           = '折扣价';
                $title['other']         = '图片链接2';
                $title['relative_id']   = '原价';
                $title['game_id']       = '点卡id';
                # code...
                break;
        }
        return $title;
    }

    public function edit()
    {
        $id                 = Input::get('id/d', 0);
        $type               = Input::get('type/d', 1);
        if ($id>0) {
            $edit_data      = D('IndexRank')->find($id);
            $this->assign('edit_data',$edit_data);
        }
        $this->assign('type',$type);
        $this->assign('title',$this->getTitle($type));
        return $this->fetch();
    }

    public function save()
    {
        $data               = Input::post();
        if (empty($data['name'])) {
            return Response::error('名稱不能為空');
        }
        $fun                = $data['id']>0?'save':'add';
        $db_res             = D('IndexRank')->$fun($data);
        if ($db_res!==false) {
            return Response::success('保存成功');
        }else{
            return Response::error('保存失敗');
        }
    }

    public function saveToFile()
    {
        for ($type=1; $type<=3; $type++) { 
            $status         = \logic\index\Rank::writeFile($type);
            if (!$status) {
                return Response::error('更新失敗');
            }
        }
        return Response::success('更新成功');
    }

    public function delete()
    {
        $id                 = Input::get('id',0,'int');
        if ($id>0) {
            $status         = D('IndexRank')->delete($id);
        }
        return $status!==false?1:0;
    }
}
