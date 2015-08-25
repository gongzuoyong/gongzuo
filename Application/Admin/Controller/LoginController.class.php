<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller 
{
	public function login()
	{
		if(IS_POST)
		{
			$model = D('Admin');
			// 第二个参数：4：代表登录
			if($model->create($_POST, 4))
			{
				$ret = $model->login();
				if($ret === TRUE)
				{
					$this->success('登录成功！', U('Index/index'));
					exit;
				}
				else 
				{
					$ret == 1 ? $this->error('用户名不存在！') : $this->error('密码不正确！');
				}
			}
			else 
				$this->error($model->getError());
		}
		$this->display();
	}
	public function codeImg()
	{   
		$config = array(
			'useImgBg'  =>  false,           // 使用背景图片 
			'fontSize'  =>  25,              // 验证码字体大小(px)
			'useCurve'  =>  false,            // 是否画混淆曲线
			'useNoise'  =>  true,            // 是否添加杂点	
			'imageH'    =>  0,               // 验证码图片高度
			'imageW'    =>  0,               // 验证码图片宽度
			'length'    =>  5,               // 验证码位数
			'fontttf'   =>  '',              // 验证码字体，不设置随机获取
			'bg'        =>  array(243, 251, 254),  // 背景颜色
		);
		$Verify = new \Think\Verify($config);
		$Verify->entry();
	}
	public function logout()
	{
		$model = D('Admin');
		$model->logout();
		$this->success('操作成功！', U('Admin/Login/login'));
	}
}