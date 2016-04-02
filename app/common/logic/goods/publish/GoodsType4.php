<?php 
namespace logic\goods\publish;

use org\Validate;
use logic\goods\Publish;

// 帳號
// 端遊 or 頁遊
// 遊戲帳號                     account
// 遊戲角色名                   role_name
// 職業                         job
// 等級                         level
// 性別                         sex 男/女
// 註冊資料是否真實             is_reg_data_real 是/否
// 官網密碼是否可更改           is_pwd_can_change 是/否
// 是否綁定保鏢卡/安全卡/防盜卡 is_bind_safe_card 是/否
// 是否提供帳號切結書           is_support_account_cert 是/否
// 是否一手帳號                 is_primary 是/否
// 是否進階認證                 is_adv_verify 是/否
// 
// 手遊
// 遊戲帳號                     account
// 遊戲角色名                   role_name
// 等級                         level
// 性別                         sex 男/女
// 是否提供帳號切結書           is_support_account_cert 是/否
// 是否ID不雅                   is_indecent 是/否
// 是否一手帳號                 is_primary 是/否
class GoodsType4 extends Publish
{
    protected function _checkExtendData()
    {
        // 獲取遊戲類型
        $game_type  = D('Game')->getFieldById($this->game_id, 'type');
       
        switch ($game_type) {
            case 'pc':
            case 'web':
                $rules      = [
                    ['account', '遊戲帳號必須', 'require'],
                    ['role_name', '遊戲角色名必須', 'require'],
                    ['level', '等級必須', 'require'],
                    ['level', '等級格式不正確', 'number'],
                    ['sex', '性別必須', 'require'],
                    ['sex', '性別格式不正確', 'in', '0,1'],
                    ['is_reg_data_real', '註冊資料是否真實選項必須', 'require'],
                    ['is_reg_data_real', '註冊資料是否真實選項不正確', 'in', '0,1'],
                    ['is_pwd_can_change', '官網密碼是否可更改選項必須', 'require'],
                    ['is_pwd_can_change', '官網密碼是否可更改選項不正確', 'in', '0,1'],
                    ['is_bind_safe_card', '是否綁定保鏢卡/安全卡/防盜卡選項必須', 'require'],
                    ['is_bind_safe_card', '是否綁定保鏢卡/安全卡/防盜卡選項不正確', 'in', '0,1'],
                    ['is_support_account_cert', '是否提供帳號切結書選項必須', 'require'],
                    ['is_support_account_cert', '是否提供帳號切結書選項不正確', 'in', '0,1'],
                    ['is_primary', '是否一手帳號選項必須', 'require'],
                    ['is_primary', '是否一手帳號選項不正確', 'in', '0,1'],
                    ['is_adv_verify', '是否進階認證選項必須', 'require'],
                    ['is_adv_verify', '是否進階認證選項不正確', 'in', '0,1'],
                ];
                break;
            
            case 'mobile':
                $rules      = [
                    ['account', '遊戲帳號必須', 'require'],
                    ['role_name', '遊戲角色名必須', 'require'],
                    ['level', '等級必須', 'require'],
                    ['level', '等級格式不正確', 'number'],
                    ['sex', '性別必須', 'require'],
                    ['sex', '性別格式不正確', 'in', '0,1'],
                    ['is_support_account_cert', '是否提供帳號切結書選項必須', 'require'],
                    ['is_support_account_cert', '是否提供帳號切結書選項不正確', 'in', '0,1'],
                    ['is_indecent', '是否ID不雅選項必須', 'require'],
                    ['is_indecent', '是否ID不雅選項不正確', 'in', '0,1'],
                    ['is_primary', '是否一手帳號選項必須', 'require'],
                    ['is_primary', '是否一手帳號選項不正確', 'in', '0,1'],
                ];
                break;

            default:
                $rules      = [];
        }
        if (!Validate::valid($this->data['extend'], $rules)) {
            $this->errno    = 301;
            $this->error    = Validate::getError();
            return false;
        }
        // 封裝extend
        if (is_array($this->data['extend'])) {
            $this->data['extend']   = json_encode($this->data['extend']);
        }
        return true;
    }
}