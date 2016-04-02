<?php
namespace app;

use think\Config;
use think\Route;
use think\Response;
use think\Hook;
use think\Session;
use think\Loader;

// 補充常量
define('DATA_PATH', RUNTIME_PATH . DS . 'data' . DS);

// 增加命名空间
Loader::addNamespace('org', APP_PATH . COMMON_MODULE . DS . 'org' .DS);
Loader::addNamespace('api', APP_PATH . COMMON_MODULE . DS . 'api' .DS);
Loader::addNamespace('logic', APP_PATH . COMMON_MODULE . DS . 'logic' .DS);

// 加载主配置 config.php
is_file(__DIR__.DS.'config'.EXT) && Config::set(include __DIR__.'/config'.EXT);
// 加载不同环境的配置 config.[debug|dev].php
is_file(__DIR__.DS.'config.'.APP_STATUS.EXT) && Config::set(include __DIR__.DS.'config.'.APP_STATUS.EXT);
// 加載 common.php
is_file(__DIR__.DS.'common'.EXT) && include __DIR__.DS.'common'.EXT;
// 根域名获取
$url_domain_root = ltrim(Config::get('url_domain_root'), '.');

// 检测关站
Hook::add('action_begin', 'app\\common\\behavior\\CheckMaintenance');
// 檢測禁止IP
Hook::add('action_begin', 'app\\common\\behavior\\CheckDenyIp');
// 檢測在線
Hook::add('action_begin', 'app\\common\\behavior\\RefreshOnline');

// http协议
Config::set('url_scheme', Route::isSsl() ? 'https:' : 'http:');

// API地址
Config::set('static_url', Config::get('url_scheme').'//static.'.$url_domain_root);
Config::set('js_url', Config::get('url_scheme').'//static.'.$url_domain_root.'/js/');
Config::set('css_url', Config::get('url_scheme').'//static.'.$url_domain_root.'/css/');
Config::set('img_url', Config::get('url_scheme').'//static.'.$url_domain_root.'/images/');
Config::set('upload_url', Config::get('url_scheme').'//upload.'.$url_domain_root.'/');
Config::set('image_url', Config::get('url_scheme').'//upload.'.$url_domain_root.'/');

// 子域名解析
Route::domain('i', 'usercenter');
Route::domain('m', 'mobile');
Route::domain('api', 'api');
// Route::domain('www', 'index');
Route::domain('www', function() {
    Route::any('index/:action', 'user/:action', [], ['action'=>'(login|reg|logout)']);
    Route::any('index/mall/:game_type', 'mall/index', [], ['game_type'=>'(pc|mobile|web|card)']);
    return ['type'=>'module','module'=>'index'];
});
// Route::domain('admin', 'admin');
Route::domain('admin', function() {
    Route::any('admin/:action', 'admin/index/:action', [], ['action'=>'(login|logout)']);
    return ['type'=>'module', 'module'=>'admin'];
});
// SESSION
Session::init(Config::get('session'));