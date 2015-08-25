<?php
namespace Admin\Controller;
use Admin\Controller\IndexController;
class RoleController extends IndexController 
{
	public function add()
	{
		if(IS_POST)
		{
			$model = D('Role');
			if($model->create())
			{
				if($model->add())
				{
					$this->success('添加成功！', U('lst'));
					exit;
				}
				else 
					$this->error('添加失败，请重试！');
			}
			else 
				$this->error($model->getError());
		}
		// 先取出所有的权限
		$priModel = D('Privilege');
		$priData = $priModel->priTree();
		$this->assign('priData', $priData);
		$this->display();
	}
	public function save($id)
	{
		$model = D('Role');
		if(IS_POST)
		{
			if($model->create())
			{
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
		// 先取出所有的权限
		$priModel = D('Privilege');
		$priData = $priModel->priTree();
		$this->assign('priData', $priData);
		$data = $model->find($id);
		$this->assign('data', $data);
		$this->display();
	}
	public function lst()
	{
		$model = D('Role');
		$data = $model->search();
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page'],
		));
		$this->display();
	}
	public function del($id)
	{
		if($id > 1)
		{
			$model = D('Role');
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
				$model = M('Role');
				$model->delete($delid);
			}
		}
		$this->success('操作成功！');
	}
}