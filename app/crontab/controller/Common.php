<?php
namespace app\crontab\controller;

class Common
{
    static public function log($message='', $exit=false)
    {
        echo '[ '.date('Y-m-d H:i:s').' ] '.$message."\n";
        $exit && exit();
    }
}