<?php
namespace app\index\controller;

class Test extends Common
{
	public function index()
	{
		$list  	= [1,2,3,4,5];
		$this->assign('list',$list);
		return V();
	}
}