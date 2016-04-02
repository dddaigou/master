<?php
namespace app\usercenter\controller;

use think\Controller;

class Common extends Controller
{
    public function __initialize()
    {
        $navigation     = is_file(MODULE_PATH.'navigation.php') ? include MODULE_PATH.'navigation.php' : [];
        $this->assign('navigation',$navigation);
    }
}
