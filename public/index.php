<?php
//phpinfo();exit;
// 检测环境
if (PHP_SAPI == 'cli') {
    // 定义环境常量
    if(false!==stripos(__DIR__, ':')){
        $app_status     = 'dev';
        $app_debug      = true;
    }elseif(false!==stripos(__DIR__, 'debug')){
        $app_status     = 'debug';
        $app_debug      = true;
    }else{
        $app_status     = '';
        $app_debug      = false;
    }
} else {
    // 定义环境常量
    if (preg_match('/dev\.dddaigou\.net$/i', $_SERVER['HTTP_HOST'])) {
        $app_status = 'dev';
        $app_debug  = true;
    } elseif (preg_match('/debug\.dddaigou\.net$/i', $_SERVER['HTTP_HOST'])) {
        $app_status = 'debug';
        $app_debug  = true;
    } else {
        $app_status = '';
        $app_debug  = false;
    }
}
// 模式配置
define('APP_STATUS', $app_status);

// 开启调试模式
define('APP_DEBUG', true);

// 定义应用目录
define('APP_PATH', realpath(__DIR__.'/../app/').DIRECTORY_SEPARATOR);

// 自动运行
define('APP_AUTO_RUN', true);

// 开启行为
define('APP_HOOK',true);

// 引入ThinkPHP入口文件
require realpath(__DIR__.'/../thinkphp/').DIRECTORY_SEPARATOR.'start.php';
