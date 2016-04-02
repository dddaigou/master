<?php
namespace app\common\util;

use think\Log;

/**
 * Sms 
 * 簡訊類
 * @package 
 * @version $id$
 * @copyright 2005-2013 addcn.com
 * @author Dijia Huang <hdj@addcn.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class Sms
{
    protected $config   = array();
    protected $type     = null;
    protected $error    = null;
    protected $errno    = null;
    /**
     * factory 
     * 工廠類
     * @param string $type 
     * @access public
     * @return void
     */
    static public function instance($type='hk'){
        $type           = ucfirst(strtolower($type));
        $class          = '\\app\\common\\util\\sms\\'.ucfirst(strtolower($type));
        if(!class_exists($class)){
            $errorStr   = "ERROR:找不到{$class}簡訊驅動";
            Log::write($errorStr, Log::ERROR);
            exit($errorStr);
        }
        $sms            = new $class();
        $sms->setConfig($type);
        return $sms;
    }
    /**
     * setConfig 
     * 設置配置參數
     * @param array $config 
     * @access public
     * @return void
     */
    public function setConfig($config=''){
        $this->type         = preg_replace('/sms$/i', '', __CLASS__);
        if(''==$config){
            $sms_config     = C('sms');
            $config         = isset($sms_config['hk']) ? $sms_config['hk'] : null;
            $this->config   = $config;
        }elseif(is_array($config) && !empty($config)){
            $this->config   = $config;
        }elseif(is_string($config)){
            $sms_config     = C('sms');
            $config         = isset($sms_config[$config]) ? $sms_config[$config] : null;
            $this->config   = $config;
        }
    }
    /**
     * getConfig 
     * 獲取配置參數
     * @param string $key 
     * @param mixed $default 
     * @access public
     * @return void
     */
    public function getConfig($key='', $default=null){
        if(''==$key)
            return $this->config;
        else
            return isset($this->config[$key]) ? $this->config[$key] : $default;
    }
    /**
     * checkMobile 
     * 檢測行動電話
     * @param string $mobile 
     * @access protected
     * @return void
     */
    protected function checkMobile($mobile=''){
        if(''==$mobile){
            $this->error    = '行動電話不能為空';
            return false;
        }
        // 按不同地區檢測號碼
        switch(strtoupper($this->type)){
            case 'HK':
                break;
            case 'CN':
                break;
            case 'TW':
            default:
                break;
        }
        return true;
    }
    /**
     * checkMessage 
     * 檢測簡訊
     * @param string $message 
     * @access protected
     * @return void
     */
    protected function checkMessage($message=''){
        if(''==$message){
            $this->error    = '簡訊內容不能為空';
            return false;
        }
        return true;
    }
    /**
     * getError 
     * 獲取錯誤
     * @access public
     * @return void
     */
    public function getError(){
        return $this->error;
    }
    /**
     * getErrno 
     * 获取错误编码
     * @access public
     * @return void
     */
    public function getErrno(){
        return $this->errno;
    }
    /**
     * curlPost 
     * 遠程提交
     * @param mixed $url 
     * @param mixed $data 
     * @access public
     * @return void
     */
    public function curlRequest($method='POST', $url='', $data=null){
        if(!function_exists('curl_init')){
            $this->error    = '系統不支持curl擴展';
            return false;
        }
        $ch                 = curl_init();
        curl_setopt($ch, CURLOPT_URL,               $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,    1);
    	curl_setopt($ch, CURLOPT_HEADER,            0);
    	curl_setopt($ch, CURLOPT_POST,              $method=='POST'?1:0);
    	curl_setopt($ch, CURLOPT_VERBOSE,           0);
        if(!is_null($data)){
            curl_setopt($ch, CURLOPT_POSTFIELDS,    $data);
        }
        $res                = curl_exec($ch);
        $errno              = curl_errno($ch);
        $error              = curl_error($ch);
        if($errno){
            $this->error    = $error;
            return false;
        }
        return $res;
    }
    /**
     * curlPost 
     * POST请求
     * @param string $url 
     * @param mixed $data 
     * @access public
     * @return void
     */
    public function curlPost($url='', $data=null){
        return $this->curlRequest('POST', $url, $data);
    }
    /**
     * curlGet 
     * GET请求
     * @param string $url 
     * @param mixed $data 
     * @access public
     * @return void
     */
    public function curlGet($url='', $data=null){
        $query              = '';
        if(is_array($data) && !empty($data)){
            $query          = http_build_query($data);
        }elseif(is_string($data)){
            $query          = $data;
        }
        $url                .= $query ? ((0<strpos($url, '?')?'':'?').$query) : '';
        return $this->curlRequest('GET', $url);
    }
}
