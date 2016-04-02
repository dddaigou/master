<?php
namespace app\common\util\sms;

use app\common\util\Sms;

/**
 * HkSms 
 * 香港簡訊驅動
 * @uses Sms
 * @package 
 * @version $id$
 * @copyright 2005-2013 addcn.com
 * @author Dijia Huang <hdj@addcn.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class Hk extends Sms
{
    /**
     * send 
     * 發送簡訊
     * @param mixed $mobile 
     * @param string $message 
     * @access public
     * @return void
     */
    public function send($mobile='', $message=''){
        // 默认错误信息
        $this->error            = null;
        $this->errno            = null;
        // 检测数据
        if(!$this->checkMobile($mobile)){
            $this->error        = '手機號碼不正確';
            return false;
        }
        if(!$this->checkMessage($message)){
            $this->error        = '簡訊內容為空';
            return false;
        }
        $message                = self::msgEncode($message);
        $data['msg']            = $message;
        $data['phone']          = '852'.$mobile;
        $data['accountno']      = $this->getConfig('account');
        $data['pwd']            = $this->getConfig('password');
        $data                   = http_build_query($data);
        $url                    = $this->getConfig('api');
        $url                    .= strpos($url, '?') ? $data : "?{$data}";

        $res                    = $this->curlGet($url);
        $res                    = trim($res);

        if(false === $res){
            return false;
        }
        if($res==''){
            $this->error        = '返回結果為空';
            return false;
        }
        if(!is_numeric($res)){
            $this->error        = $res;
            return false;
        }
        if(!$res){
            $this->error        = "發送失敗";
            return false;
        }
        return true;
    }

    protected function checkMobile($mobile=''){
        return preg_match('/^(9|6|5)\d{7}$/', $mobile);
    }

    protected function checkMessage($message=''){
        return !empty($message) ? true : false;
    }

    private static function msgEncode($str=''){
        return self::unicodeGet(self::convert(2, $str));
    }

    private static function unicodeGet($str){
		$str            = preg_replace("/&#/", "%26%23",$str);
		$str            = preg_replace("/;/", "%3B",$str);
		return $str;
    }

    private static function convert($language, $cell){
		$str            = "";
		preg_match_all("/[\x80-\xff]?./",$cell,$ar);
		
        switch ($language){
            case 0://繁体中文
				foreach ($ar[0] as $v)
				$str    .= "&#".self::chineseUnicode(iconv("big5-hkscs","UTF-8",$v)).";";
				return $str;
			break;
            case 1://简体中文
				foreach ($ar[0] as $v)
				$str    .= "&#".self::chineseUnicode(iconv("gb2312","UTF-8",$v)).";";
                return $str;
			break;
			case 2://二进制编码
                $cell   = self::utf8Unicode($cell);
				foreach ($cell as $v)
                    $str .= "&#".$v.";";
				return $str;
			break;
        }
	}

    private static function chineseUnicode($c){
		switch (strlen($c)){
			case 1:
			return ord($c);
			case 2:
				$n      = (ord($c[0]) & 0x3f) << 6;
		    	$n      += ord($c[1]) & 0x3f;
                return $n;
			case 3:
				$n      = (ord($c[0]) & 0x1f) << 12;
				$n      += (ord($c[1]) & 0x3f) << 6;
				$n      += ord($c[2]) & 0x3f;
                return $n;
			case 4:
				$n      = (ord($c[0]) & 0x0f) << 18;
				$n      += (ord($c[1]) & 0x3f) << 12;
				$n      += (ord($c[2]) & 0x3f) << 6;
				$n      += ord($c[3]) & 0x3f;
                return $n;
		}
	}

    private static function utf8Unicode($str){
		$unicode        = array();
		$values         = array();
		$lookingFor     = 1;

		for ($i = 0; $i < strlen($str); $i++){
			$thisValue  = ord($str[$i]);

			if ($thisValue < 128)
				$unicode[] = $thisValue;
			else{
				if (count($values) == 0)
					$lookingFor = ($thisValue < 224) ? 2 : 3;

				$values[] = $thisValue;

				if (count($values) == $lookingFor){
					$number = ( $lookingFor == 3 ) ?  ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ): ( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );

					$unicode[] = $number;
					$values = array();
					$lookingFor = 1;
				}
			}
		}
		return $unicode;
	}
}
