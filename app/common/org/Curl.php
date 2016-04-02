<?php
namespace org;
/**
 * Curl 
 * 简易CURL操作类
 * @package 
 * @version $id$
 * @copyright 2005-2011 SUCOP.COM
 * @author Dijia Huang <huangdijia@gmail.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 *
 * 例子：
 * $res = Curl::get('http://localhost/test.php', "a=1&b=2");
 * echo Curl::getLastUrl();
 * echo "\n";
 * if(!$res) echo Curl::getError();
 * else echo $res;
 * 
 * $res = Curl::post('http://localhost1/test.php', "a=1&b=2");
 * echo Curl::getLastUrl();
 * echo "\n";
 * if(!$res) echo Curl::getError();
 * else echo $res;
 *
 */
class Curl
{
    private static $url;
    private static $errno;
    private static $error;
    
    static public function __callStatic($name, $args){
        // 检测是否支持curl
        if (!extension_loaded('curl')){
            self::$errno    = -1;
            self::$error    = 'class Curl is not exists';
            return false;
        }
        
        // 获取参数
        $name               = strtolower($name);
        $url                = isset($args[0]) ? $args[0] : '';
        $data               = isset($args[1]) ? $args[1] : '';
        $options            = isset($args[2]) ? $args[2] : array();
        $assoc              = null;

        // 第三个参数为true/number时定义为json解析开关
        if(is_bool($options)){
            $value                  = $options;
            $options                = array();
            $options['parse_json']  = $value;
        }

        // 是否解析json
        if(isset($options['parse_json']) && $options['parse_json']){
            $assoc          = $options['parse_json'] ? true : false;
            unset($options['parse_json']);
        }

        // 检测url
        if(empty($url)){
            self::$errno    = -2;
            self::$error    = 'url is empty';
            return false;
        }
        
        // 初始化curl
        $ch                 = curl_init();
        
        // 检测调用方法
        switch($name){
            case 'options':
                $url        = self::__buildUrl($url, $data);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
                break;
            case 'head':
                $url        = self::__buildUrl($url, $data);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
                curl_setopt($ch, CURLOPT_NOBODY, true);
                break;
            case 'delete':
                $url        = self::__buildUrl($url, $data);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'patch':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'put':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'post':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case 'get':
                $url        = self::__buildUrl($url, $data);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            default:
                self::$errno= -3;
                self::$error= "Curl::{$name}() is not exists";
                return false;
                break;
        }
        
        // 设置URL
        self::$url          = $url;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        // 设置其他参数
        foreach((array) $options as $key=>$value){
            switch(strtoupper($key)){
                case 'CURLOPT_USERAGENT':
                    curl_setopt($ch, CURLOPT_USERAGENT, $value);
                    break;
                case 'CURLOPT_REFERER':
                    curl_setopt($ch, CURLOPT_REFERER, $value);
                    break;
                case 'CURLOPT_COOKIE':
                    curl_setopt($ch, CURLOPT_COOKIE, $value);
                    break;
                case 'CURLOPT_COOKIEFILE':
                    curl_setopt($ch, CURLOPT_COOKIEFILE, $value);
                    break;
                case 'CURLOPT_COOKIEJAR':
                    curl_setopt($ch, CURLOPT_COOKIEJAR, $value);
                    break;
                case 'CURLOPT_VERBOSE':
                    curl_setopt($ch, CURLOPT_VERBOSE, $value);
                    break;
				case 'CURLOPT_TIMEOUT':
					curl_setopt($ch, CURLOPT_TIMEOUT, $value);
					break;
                default:
                    curl_setopt($ch, $key, $value);
                    break;
            }
        }
        
        // 执行curl
        $response           = curl_exec($ch);
        $errno              = curl_errno($ch);
        self::$errno        = '';
        self::$error        = '';

        // 执行失败
        if($errno){
            self::$errno    = $errno;
            self::$error    = curl_error($ch);
            curl_close($ch);
            return false;
        }

        // 关闭curl
        curl_close($ch);

        // 解析json
        if(!is_null($assoc)){
            $response       = json_decode($response, $assoc);
        }

        // 返回结果
        return $response;
    }
    
    // 组装URL
    private static function __buildUrl($url, $data=array()){
        if(empty($data)) return $url;
        
        if(is_object($data)) $data = (array) $data;
        $query              = is_array($data) ? http_build_query($data) : $data;
        $url                .= (false!==strpos($url, '?') ? '&' : '?') . $query;

        return $url;
    }
    
    // 获取错误
    public static function getError(){
        return self::$error;
    }
    
    // 获取错误编号
    public static function getErrno(){
        return self::$errno;
    }
    
    // 获取最后执行URL
    public static function getLastUrl(){
        return self::$url;
    }
}

