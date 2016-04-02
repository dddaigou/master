<?php
namespace app\index\controller;

use org\Verify as VerifyCode;

class Verify
{
    public function index()
    {
        $verify = new VerifyCode();
        $verify->entry();
        exit;
    }
}