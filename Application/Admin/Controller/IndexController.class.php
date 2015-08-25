<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller 
{
	public function __construct()
	{
		// 必须先调用父类的构造函数
		parent::__construct();
		if(!session('id'))
			$this->error('必须先登录！', U('Admin/Login/login'));
		// 欢迎页面可以访问
		if(MODULE_NAME == 'Admin' && CONTROLLER_NAME == 'Index')
			return TRUE;
		// 验证权限
		$privilege = session('privilege');
		if($privilege != '*' && !in_array(MODULE_NAME .'/'. CONTROLLER_NAME .'/'. ACTION_NAME, $privilege))
			$this->error('无权访问！');
	}
	public function index()
	{
		$this->display();
	}
	public function top()
	{
		$this->display();
	}
	public function left()
	{
		$this->display();
	}
	public function main()
	{
		$this->display();
	}
}