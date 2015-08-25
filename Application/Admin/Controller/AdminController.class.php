<?php
namespace Admin\Controller;
use Admin\Controller\IndexController;
class AdminController extends IndexController 
{
	// 添加管理员
	public function add()
	{
		// 处理表单
		if(IS_POST)
		{
			// 生成Admin模型
			// tp中可以使用M和D生成模型
			// M : 用TP自带的方法时用M
			// D : 如果要使用自己创建的模型时需要用D
			// 因为要用到我们自己在模型中写的验证规则，所以要使用D生成自己的模型
			$model = D('Admin');
			// 接收表单并且根据模型中定义的规则进行验证
			if($model->create())
			{
				// 插入数据库中
				if($model->add())
				{
					// 提示成功，并跳到当前控制器的lst方法中
					$this->success('添加成功！', U('lst'));
					// 为什么要加exit?
					exit;
				}
				else 
					$this->error('添加失败，请重试！');
			}
			else 
				// 打印$model->getError(): 从模型中获取验证失败的原因
				$this->error($model->getError());
		}
		// 取出所有的角色
		$roleModel = M('Role');
		$roleData = $roleModel->select();
		$this->assign('roleData', $roleData);
		// 显示表单
		$this->display();
	}
	// 修改管理员
	public function save($id)
	{
		$model = D('Admin');
		if(IS_POST)
		{
			if($model->create())
			{
				// save: 返回值：如果失败返回 FALSE  如果成功返回 mysql_affected_rows();
				if($model->save() !== FALSE)
				{
					$this->success('修改成功！', U('lst'));
					exit;
				}
				else 
					$this->error('修改失败，请重试！');
			}
			else 
				$this->error($model->getError());
		}
		// 取出所有的角色
		$roleModel = M('Role');
		$roleData = $roleModel->select();
		$this->assign('roleData', $roleData);
		// 取出要修改的记录
		$data = $model->find($id);
		$this->assign('data', $data);
		// 显示表单
		$this->display();
	}
	// 管理员列表
	public function lst()
	{
		$model = D('Admin');
		// 获取带翻页的数据
		$data = $model->search();
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page'],
		));
		$this->display();
	}
	public function del($id)
	{
		// 超级管理员不能删除
		if($id > 1)
		{
			$model = M('Admin');
			$model->delete($id);
		}
		$this->success('操作成功！');
	}
	public function bdel()
	{
		$delid = I('post.delid');
		if($delid)
		{
			// 判断数组中有没有1如果有1就删除掉
			$key = array_search(1, $delid);
			if($key !== FALSE)
				unset($delid[$key]);
			// 再根据数组中的ID删除数据库中的记录
			if($delid)
			{
				// delete方法要求必须是一个字符串，所以要先转化成一个字符串
				$delid = implode(',', $delid); // 2,3,4
				// 生成模型从数据库中删除掉
				$model = M('Admin');
				$model->delete($delid);
			}
		}
		$this->success('操作成功！');
	}
}