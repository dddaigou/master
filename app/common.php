<?php
// 增加函數
if(!function_exists('F')) {
    /**
     * 快速文件数据读取和保存 针对简单类型数据 字符串、数组
     * @param string $name 缓存名称
     * @param mixed $value 缓存值
     * @param string $path 缓存路径
     * @return mixed
     */
    function F($name, $value='', $path=DATA_PATH) {
        static $_cache  = array();
        $filename       = $path . $name . '.php';
        if ('' !== $value) {
            if (is_null($value)) {
                // 删除缓存
                return false !== strpos($name,'*')?array_map("unlink", glob($filename)):unlink($filename);
            } else {
                // 缓存数据
                $dir            =   dirname($filename);
                // 目录不存在则创建
                if (!is_dir($dir))
                    mkdir($dir,0755,true);
                $_cache[$name]  =   $value;
                return file_put_contents($filename, "<?php return " . var_export($value, true) . ";");
            }
        }
        if (isset($_cache[$name]))
            return $_cache[$name];
        // 获取缓存数据
        if (is_file($filename)) {
            $value          = include $filename;
            $_cache[$name]  = $value;
        } else {
            $value          = false;
        }
        return $value;
    }
}

if (!function_exists('get_before_time')) {
    function get_before_time($time) {
        $t  = time()-$time;
        if ($t<60) {
            return '剛剛';
        }
        $f  = [
            '31536000'  => '年',
            '2592000'   => '個月',
            '604800'    => '星期',
            '86400'     => '天',
            '3600'      => '小時',
            '60'        => '分鐘'
        ];
        foreach ($f as $k=>$v)    {
            $c  = floor($t/intval($k));
            if ($c>0) {
                return $c . $v . '前';
            }
        }
    }
}

if (!function_exists('getIp')) {
    function getIp()
    {
        if (@$_SERVER["HTTP_X_FORWARDED_FOR"])  
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];  
        else if (@$_SERVER["HTTP_CLIENT_IP"])  
        $ip = $_SERVER["HTTP_CLIENT_IP"];  
        else if (@$_SERVER["REMOTE_ADDR"])  
        $ip = $_SERVER["REMOTE_ADDR"];  
        else if (@getenv("HTTP_X_FORWARDED_FOR"))  
        $ip = getenv("HTTP_X_FORWARDED_FOR");  
        else if (@getenv("HTTP_CLIENT_IP"))  
        $ip = getenv("HTTP_CLIENT_IP");  
        else if (@getenv("REMOTE_ADDR"))  
        $ip = getenv("REMOTE_ADDR");  
        else  
        $ip = "Unknown";  
        return $ip;
    }
}

function isDev(){
    if(getIp()=='127.0.0.1')
        return true;
    else
        return false;
}