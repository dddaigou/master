<?php
namespace logic\index;

class Rank
{
    static public function writeFile($type=1)
    {
        // 修改首页的配置文件
        $map            = [];
        $map['type']    = $type;
        $map['tag']     = ['gt',0];
        $limit          = $type>1?4:20;
        $list           =  D('IndexRank')->where($map)
        ->order('tag DESC,rank ASC,num DESC')
        ->limit($limit)
        ->select();
        if (empty($list)) {
            return false;
        }
        $data           = [];
        foreach ($list as $key => $value) {
            if (in_array($type,[2,3])) {
                #热门游戏展示商品
                $data[$key]['game_id']      = $value['game_id'];
                $data[$key]['name']         = $value['name'];
                $data[$key]['link']         = $value['link'];
                if (!empty($value['other'])) {
                    $goods_list             = D('Goods')->field('id,item_id as item,title')
                    ->where("id IN ({$value['other']})")
                    ->limit(3)
                    ->select();
                    foreach ($goods_list as $k => $v) {
                        $v['item']          = $v['item']>0?$v['item']:1;
                        $goods_list[$k]['item'] = \logic\goods\Type::get($v['item']);
                    }
                    $data[$key]['goods_list'] = $goods_list;
                }
            }
            if ($type==1) {
                # 热门点卡数据
                $data[$key]['card_name']    = $value['name'];
                $data[$key]['card_id']      = $value['game_id'];
                $data[$key]['old_price']    = $value['relative_id'];
                $data[$key]['price']        = $value['num'];
                $data[$key]['card_img']     = $value['link'];
                $data[$key]['discount_img'] = $value['other'];
            }
        }
        $files          = [1=>'index_hot_card',2=>'index_hot_pc_game',3=>'index_hot_mb_game'];
        $status         = F($files[$type], $data);
        return $status;
    }
} 