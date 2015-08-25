<?php
namespace Admin\Controller;
use Admin\Controller\IndexController;
class PrivilegeController extends IndexController 
{
	public function add()
	{
		if(IS_POST)
		{
			$model = D('Privilege');
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
		$priModel = D('Privilege');
		$priData = $priModel->priTree();
		$this->assign('priData', $priData);
		$this->display();
	}
	public function save($id)
	{
		$model = D('Privilege');
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
		$priModel = D('Privilege');
		$priData = $priModel->priTree();
		$this->assign('priData', $priData);
		$data = $model->find($id);
		$this->assign('data', $data);
		$this->display();
	}
	public function lst()
	{
		$priModel = D('Privilege');
		$priData = $priModel->priTree();
		$this->assign('data', $priData);
		$this->display();
	}
	public function del($id)
	{
		$model = D('Privilege');
		$model->delete($id);
		$this->success('操作成功！');
	}
	public function bdel()
	{
		$delid = I('post.delid');
		if($delid)
		{
			$delid = implode(',', $delid); // 2,3,4
			$model = D('Privilege');
			$model->delete($delid);
		}
		$this->success('操作成功！');
	}
}