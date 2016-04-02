<?php 
namespace app\api\controller;

use org\Validate;
use logic\sms\otp\Type as SmsType;
use logic\sms\otp\Template as SmsTemplate;
use app\common\util\Sms AS SmsService;

class Sms
{
    public function sendOtp($type='reg', $mobile='', $is_user_id=0, $length=4, $interval=60, $expire=300, $send_client='web')
    {
        // 驗證數據
        $data               = [
            'type'          => $type,
            'mobile'        => $mobile,
            'length'        => $length,
            'send_client'   => $send_client,
        ];
        $otp_types          = array_keys(SmsType::get());
        $rules              = [
            ['mobile', '行動電話為空', 'require'],
            ['mobile', '行動電話類型錯誤', 'number'],
            ['mobile', '行動電話錯誤', '>', 0],
            ['type', '類型為空', 'require'],
            ['type', '類型不在範圍內', 'in', $otp_types],
            ['length', '驗證碼長度錯誤', 'between', '4,10'],
            ['send_client', '發送客戶端必須', 'require'],
            ['send_client', '發送客戶端範圍不正確', 'in', 'web,mobile,android,ios']
        ];
        if (!Validate::valid($data, $rules)) {
            return ['code'=>201, 'msg'=>Validate::getError(), 'fail_field'=>Validate::getFailField()];
        }
        // 根據 user_id 獲取 mobile
        if ($is_user_id==1) {
            $user_id        = $mobile;
            $user_info      = D('User')->field('mobile,country_code')->where(['id'=>$user_id])->find();
            if (empty($user_info)) {
                return ['code'=>300, 'msg'=>'獲取會員資料失敗'];
            }
            $mobile         = $user_info['mobile'];
            $country_code   = $user_info['country_code'];
        } else {
            // 識別手機國家編碼
            if (Validate::check($mobile, 'cnmobile')) {
                $country_code   = 'CN';
            } elseif (Validate::check($mobile, 'hkmobile')) {
                $country_code   = 'HK';
            } elseif (Validate::check($mobile, 'twmobile')) {
                $country_code   = 'TW';
            } else {
                return ['code'=>301, 'msg'=>'行動電話不正確'];
            }
            $user_id        = 0;
        }
        // 檢查時間間隔
        if ($interval>0 && self::checkInterval($mobile, $type, $interval)) {
            // 转换为友好时间
            if($interval>=24*60*60){
                $str        = floor($interval/(24*60*60)).'天';
            }elseif($interval>=60*60){
                $str        = floor($interval/(60*60)).'小時';
            }elseif($interval>=60){
                $str        = floor($interval/60).'分鐘';
            }else{
                $str        = $interval.'秒';
            }
            return ['code'=>302, 'msg'=>"{$str}內不能重覆發送"];
        }
        // 生成隨機碼
        $code               = self::getRandCode($length);
        // 格式化內容
        if (!SmsTemplate::get($type)) {
            return ['code'=>303, 'msg'=>'找不到簡訊模板，請與工程師聯繫'];
        }
        // 保存數據庫
        $data               = [
            'type'          => $type,
            'mobile'        => $mobile,
            'user_id'       => $user_id,
            'code'          => $code,
            'send_client'   => $send_client,
            'send_time'     => date('Y-m-d H:i:s'),
            'expire_time'   => date('Y-m-d H:i:s', time()+$expire),
        ];
        $SmsOtpModel        = D('SmsOtp');
        if (!$SmsOtpModel->create($data)) {
            return ['code'=>304, 'msg'=>'數據有誤'];
        }
        if (!$otp_id=$SmsOtpModel->add()) {
            return ['code'=>305, 'msg'=>'數據保存失敗'];
        }
        // 加載短信服務
        $content            = sprintf(SmsTemplate::get($type), $code);
        if (defined('APP_STATUS') && APP_STATUS=='') {
            $Sms            = SmsService::instance($country_code);
            // 發送失敗
            if (false===$Sms->send($mobile, $content)) {
                $error      = $Sms->getError();
                $SmsOtpModel->where(['id'=>$otp_id])->save(['status'=>-1, 'error'=>$error]);
                return ['code'=>306, 'msg'=>$error];
            }
        }
        // 發送成功
        $SmsOtpModel->where(['id'=>$otp_id])->save(['status'=>1]);
        return ['code'=>200, 'msg'=>'發送成功'];
    }

    public function checkOtp($type='reg', $mobile='', $code='', $check_only=0, $is_user_id=0)
    {
        // 檢測參數
        $data           = ['type'=>$type, 'mobile'=>$mobile, 'code'=>$code];
        $otp_types      = array_keys(SmsType::get());
        $rules          = [
            ['mobile', '錯誤：參數為空', 'require'],
            ['mobile', '錯誤：參數類型錯誤', 'number'],
            ['mobile', '錯誤：參數錯誤', '>', 0],
            ['type', '錯誤：類型為空', 'require'],
            ['type', '錯誤：類型不在範圍內', 'in', $otp_types],
            ['code', '錯誤：驗證碼為空', 'require'],
            ['code', '錯誤：驗證碼不正確', 'number'],
        ];
        if (!Validate::valid($data, $rules)) {
            return ['code'=>300, 'msg'=>Validate::getError(), 'fail_field'=>Validate::getFailFeild()];
        }
        // 獲取驗證碼信息
        $SmsOtpModel    = D('SmsOtp');
        $map            = [];
        $map['type']    = $type;
        if ($is_user_id) {
            $map['user_id'] = $mobile;
        } else {
            $map['mobile']  = $mobile;
        }
        $map['code']    = $code;
        $map['status']  = 1; // 已發送成功
        $otp_info       = $SmsOtpModel->where($map)->order('id DESC')->find();
        // 驗證碼錯誤
        if (empty($otp_info)) {
            return ['code'=>404, 'msg'=>'驗證碼錯誤'];
        }
        $otp_id         = $otp_info['id'];
        // 已超時
        if (strtotime($otp_info['expire_time'])<time()) {
            $SmsOtpModel->where(['id'=>$otp_id])->save(['status'=>-2, 'error'=>'驗證碼已超時']);
            return ['code'=>403, 'msg'=>'驗證碼已超時'];
        }
        // 非只讀
        if (!$check_only) {
            $SmsOtpModel->where(['id'=>$otp_id])->save(['status'=>2, 'verify_time'=>date('Y-m-d H:i:s')]);
        }
        // 返回成功
        return ['code'=>200, 'msg'=>'驗證成功'];
    }

    static public function queryOtp($mobile='', $is_user_id=0)
    {
        // 數據檢測
        $data           = ['mobile'=>$mobile];
        $rules          = [
            ['mobile', '錯誤：手機號或會員編號為空', 'require'],
            ['mobile', '錯誤：手機號或會員編號不正確', 'number'],
        ];
        if (!Validate::valid($data, $rules)) {
            return ['code'=>300, 'msg'=>Validate::getError(), 'fail_field'=>Validate::getFailFeild()];
        }
        // mobile 為 user_id
        if ($is_user_id==1) {
            $user_id    = $mobile;
            $user_info  = D('User')->field('mobile,country_code')->where(['id'=>$user_id])->find();
            if (empty($user_info)) {
                return ['code'=>301, 'msg'=>'獲取會員資料失敗'];
            }
            $mobile     = $user_info['mobile'];
        }
        // 查詢所有驗證碼
        $rows           = D('SmsOtp')->where(['mobile'=>$mobile])->select();
        return ['code'=>200, 'msg'=>'查詢成功', 'list'=>$rows];
    }

    static public function checkInterval($mobile='', $type='reg', $interval=60)
    {
        $map    = [
            'mobile'    => $mobile,
            'type'      => $type,
            'status'    => 1, // 發送成功
            'send_time' => ['gt', date('Y-m-d H:i:s', strtotime("-{$interval} seconds"))]
        ];
        $count  = D('SmsOtp')->where($map)->count();
        return $count>0 ? true : false;
    }

    static public function getRandCode($len=6, $seed=0){
        if($seed>0) {
            srand($seed);
        } else {
            srand((double)microtime()*1000000);
        }
        $max    = pow(10, $len)-1;
        $code   = rand(0, $max);
        $code   = str_pad($code, $len, '0', STR_PAD_LEFT);
        return $code;
    }
}