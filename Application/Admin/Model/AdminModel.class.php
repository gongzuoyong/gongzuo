<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model 
{
	protected $tableName = 'Admin';
	// 表单验证的规则
	protected $_validate = array(
		array('username', 'require', '用户名不能为空！', 1),
		// 只有添加时生效
		array('password', 'require', '密码不能为空！', 1, 'regex', 1),
		array('password', 'require', '密码不能为空！', 1, 'regex', 4),
		// 用户名在数据库中必须是唯一的: 在添加和修改时执行这个验证
		array('username', '', '用户名已经存在！', 1, 'unique', 1),
		array('username', '', '用户名已经存在！', 1, 'unique', 2),
		// 这个只有登录时验证
		array('chk_code', '_chkCode', '验证码不正确！', 1, 'callback', 4),
	);
	
	protected function _chkCode($code)
	{
		$verify = new \Think\Verify();
    	return $verify->check($code, '');
	}
	
	public function logout()
	{
		session(null);
	}
	
	// 取出一个管理员所有的权限并放到session中
	private function _putPriToSession($role_id)
	{
		// 根据角色ID取出这个角色的权限id
		$roleModel = M('Role');
		$roleModel->field('pri_id_list')->find($role_id);
		$priModel = M('Privilege');
		if($roleModel->pri_id_list == '*')
		{
			session('privilege', '*');
			/****************** 取出所有的前两级的权限 ***********************************/
			// 取出所有顶级的权限
			$menu = $priModel->where('parent_id=0')->select();
			// 循环每一个顶级权限再取出二级权限
			foreach ($menu as $k => $v)
			{
				$menu[$k]['sub'] = $priModel->where('parent_id='.$v['id'])->select();
			}
			session('menu', $menu);
		}
		else 
		{
			// 根据权限的ID取出这些权限对应的方法名称
			$_priData = $priModel->field('id,parent_id,pri_name,module_name,controller_name,action_name,CONCAT(module_name,"/",controller_name,"/",action_name) url')->where("id IN({$roleModel->pri_id_list})")->select();
			$menu = array();
			// 把这个数组处理成一个一维数组
			$priData = array();
			foreach ($_priData as $k => $v)
			{
				// 挑出顶级权限
				if($v['parent_id'] == 0)
					$menu[] = $v;
				$priData[] = $v['url'];
			}
			// $menu中保存的是从$_priData这个数组中挑出来的所有的顶级权限
			session('privilege', $priData);
			// 循环每一个顶级的权限取出二级权限
			foreach ($menu as $k => $v)
			{
				// 再从$_priData里挑出每个顶级分类的子分类
				foreach ($_priData as $k1 => $v1)
				{
					if($v1['parent_id'] == $v['id'])
						$menu[$k]['sub'][] = $v1;
				}
			}
			session('menu', $menu);
		}
	}
	
	public function login()
	{
		// 当前调用find方法之后tp会把数据库的记录赋给这个模型，所以需要在调用find之前先从模型中取出表单中的密码
		$password = $this->password;
		// 根据用户名查询数据库看有没有这个账号
		// 相当于：SELECT * FROM sh_member WHERE username='xxx' LIMIT 1
		// find:返回一维数组
		$info = $this->where("username='$this->username'")->find();
		if($info)
		{
			// 再比较密码是否正确
			if($info['password'] == md5($password))
			{
				// 登录成功把ID和用户名存到SESSION中
				session('id', $info['id']);
				session('username', $info['username']);
				// 取出这个管理员的权限并放到session中
				$this->_putPriToSession($info['role_id']);
				return TRUE;
			}
			else 
				return 2;
		}
		else 
			return 1;
	}
	
	// 钩子函数：在添加之前自动调用这个函数
	protected function _before_insert(&$data, $option)
	{
		$data['password'] = md5($data['password']);
	}
	// 钩子函数：在修改之前自动调用这个函数
	protected function _before_update(&$data, $option)
	{
		// 如果有密码就加密，否则就不修改这个字段
		if($data['password'])
			$data['password'] = md5($data['password']);
		else 
			unset($data['password']);
	}
	public function search()
	{
		$where = 1;
		if($un = I('get.un'))
		{
			$where .= ' AND username LIKE "%'.$un.'%"';
		}
		if($id = I('get.id'))
		{
			$where .= ' AND id='.$id;
		}
		// 每页的条数
		$perpage = 15;
		// 获取总的记录数
		$totalRecord = $this->where($where)->count();
		// 生成翻页的对象
		$page = new \Think\Page($totalRecord, $perpage);
		return array(
			'data' => $this->field('a.*,b.role_name')->alias('a')->join('LEFT JOIN sh_role b ON a.role_id=b.id')->where($where)->limit($page->firstRow, $page->listRows)->order('a.id ASC')->select(),
			'page' => $page->show(), // 翻页的字符串
		);
	}
}