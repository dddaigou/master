<?php 
namespace app\common\model;

use think\Model;

class UserCardSetting extends Model
{
    //插入或更新
    public function setNote($user_id=0,$card_id=0,$note=''){
		$sql = "INSERT INTO __TABLE__ (`user_id`,`card_id`, `denom_id`,`note`) VALUES('".$user_id."','".$card_id."', 0,'".$note."') ON DUPLICATE KEY UPDATE `note`= '".$note."'";
		if($this->execute($sql) === false){
			return false;
		}
        return true;
	}
    
    //获取点卡说明
    public function getNote($user_id,$card_id,$denom_id=0){
        $map                = [];
        $map['user_id']     = $user_id;
        $map['card_id']     = $card_id;
        $map['denom_id']    = $denom_id;
        return $this->where($map)->getField('note');
    }
}