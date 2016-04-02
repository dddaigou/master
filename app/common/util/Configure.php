<?php
namespace app\common\util;

use app\common\model\Config as ConfigModel;

class Configure
{
    static private $configs     = null;

    static public function init()
    {
        if (is_null(self::$configs)) {
            if (false === self::$configs = F('config')) {
                self::$configs  = self::load();
            }
        }
    }

    static public function load()
    {
        $rows                   = D('Config')->select();
        if(empty($rows)) return [];
        foreach ($rows as $row) {
            $type               = strtolower($row['type']);
            $name               = strtolower($row['name']);
            $value              = $row['value'];
            if (!isset($configs[$type])) $configs[$type] = [];
            $configs[$type][$name]  = $value;
        }
        return $configs;
    }

    static public function get($name='', $default=null)
    {
        // 初始化
        self::init();
        // 返回所有配置
        if (''==$name) return self::$configs;
        // 转小写
        $name                   = strtolower($name);
        // 检测配置类型
        if (false!==strpos($name, '.')) {
            list($type, $name)  = explode('.', $name, 2);
        } else {
            $type               = 'system';
        }
        // 返回当前类型所有配置
        if (''==$name) return isset(self::$configs[$type]) ? self::$configs[$type] : null;
        // 返回配置
        return isset(self::$configs[$type][$name]) ? self::$configs[$type][$name] : $default;
    }

    static public function cache()
    {
        return F('config', self::load()) ? true : false;
    }
}