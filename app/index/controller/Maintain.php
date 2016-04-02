<?php
namespace app\index\controller;

use app\common\util\Configure;
use think\Response;

class Maintain extends Common
{
    public function index()
    {
        if (!Configure::get('maintain.on')) {
            Response::redirect('/');
            exit;
        }
        return V('', ['message'=>System::get('maintain.message', '關站維護中...')]);
    }

    public function deny()
    {
        return '當前IP被禁止訪問，請與管理員聯絡！';
    }
}