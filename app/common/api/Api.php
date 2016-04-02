<?php 
namespace api;

use think\Config;
use think\Log;

class Api
{
    public static function __callStatic($method, $params)
    {
        $controller     = str_replace('\\', '/', get_called_class());
        $controller     = basename($controller);
        $controller     = \think\Loader::parseName($controller);
        $url            = rtrim(Config::get('api.url'), '/').'/'.$controller.'/'.$method;
        $args           = !empty($params[0]) ? $params[0] : [];
        $return_type    = isset($params[1]) ? intval($params[1]) : 0;
        $response       = self::_curlRequest('POST', $url, $args);
        switch($return_type){
            case 2:
                return json_decode($response);
            case 1:
                return json_decode($response, true);
            default:
                return $response;
        }
    }

    private static function _curlRequest($method='POST', $url='', $data=null)
    {
        if(!function_exists('curl_init')){
            Log::record('系統不支持curl擴展', Log::ERROR);
            return false;
        }
        $header         = ["Host:".Config::get('api.host')];
        $ch             = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, $method=='POST' ? 1 : 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
        if(!is_null($data)){
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }else{
            curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        }
        $response       = curl_exec($ch);
        if(curl_errno($ch)){
            Log::record(curl_error($ch), Log::ERROR);
            return false;
        }
        return $response;
    }
}