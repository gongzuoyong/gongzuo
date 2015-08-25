<?php
namespace Gii\Controller;
use Admin\Controller\IndexController;
class ShopConfigController extends IndexController 
{
	public function add()
	{
		if(IS_POST)
		{
			$model = D('ShopConfig');
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
		$this->display();
	}
	public function save($id)
	{
		$model = D('ShopConfig');
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
		$data = $model->find($id);
		$this->assign('data', $data);
		$this->display();
	}
	public function lst()
	{
		$model = D('ShopConfig');
		$data = $model->search();
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page'],
		));
		$this->display();
	}
	public function del($id)
	{
		$model = D('ShopConfig');
		$model->delete($id);
		$this->success('操作成功！');
	}
	public function bdel()
	{
		$delid = I('post.delid');
		if($delid)
		{
			$delid = implode(',', $delid); // 2,3,4
			$model = D('ShopConfig');
			$model->delete($delid);
		}
		$this->success('操作成功！');
	}
	public function config()
	{
		$scModel = M('ShopConfig');
		if(IS_POST)
		{
			$config = I('post.config');
			if($config)
			{
				foreach ($config as $k => $v)
				{
					$scModel->where('id='.$k)->setField('config_value', $v);
				}
			}
			$this->success('操作成功！');
			exit;
		}
		$data = $scModel->select();
		$this->assign('data', $data);
		$this->display();
	}
}