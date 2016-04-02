<?php
namespace app\common\util;

use think\Input;

class MCARAuth
{
    static $format          = '%s/%s/%s:%s';

    static public function check($rules=[], $request='')
    {
        if (''==$request) {
            $request    = strtolower(sprintf(self::$format, MODULE_NAME, CONTROLLER_NAME, ACTION_NAME, Input::server('REQUEST_METHOD')));
        } else {
            $request    = self::parseRuleItem($request);
        }
        $rules          = self::parseRule($rules);
        $check          = in_array($request, $rules);
        return $check;
    }

    static private function parseRule($rules=[])
    {
        if (is_string($rules)) {
            return [self::parseRuleItem($rules)];
        }
        if (is_array($rules) && !empty($rules)) {
            $_rules         = [];
            foreach($rules as $rule){
                $_rules[]   = self::parseRuleItem($rule);
            }
            return $_rules;
        }
        return [];
    }

    static private function parseRuleItem($rule='')
    {
        if (''==$rule) {
            $rule           = '*';
        }
        // 如果沒有 REQUEST_METHOD
        if (false===strpos($rule, ':')) {
            $rule           .= ":*";
        }
        // 解析 REQUEST_METHOD
        list($rule, $request_method)    = explode(':', $rule, 2);
        // 解析 MODULE/CONTROLLER/ACTION:REQUEST_METHOD
        $rules              = explode('/', $rule, 3);
        switch (count($rules)) {
            case 1:
                $module     = '*';
                $controller = '*';
                $action     = $rules[0];
                break;
            case 2:
                $module     = '*';
                $controller = $rules[0];
                $action     = $rules[1];
                break;
            case 3:
            default:
                $module     = $rules[0];
                $controller = $rules[1];
                $action     = $rules[2];
                break;
        }

        $module             = $module=='*' ? MODULE_NAME : $module;
        $controller         = $controller=='*' ? CONTROLLER_NAME : $controller;
        $action             = $action=='*' ? ACTION_NAME : $action;
        $request_method     = $request_method=='*' ? Input::server('REQUEST_METHOD') : $request_method;

        return strtolower(sprintf(self::$format, $module, $controller, $action, $request_method));
    }
}