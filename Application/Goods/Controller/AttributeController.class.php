<?php
namespace Goods\Controller;
use Admin\Controller\IndexController;
class AttributeController extends IndexController 
{
	public function add($type_id)
	{
		if(IS_POST)
		{
			$model = D('Attribute');
			if($model->create())
			{
				if($model->add())
				{
					$this->success('添加成功！', U('lst', array('type_id' => $type_id)));
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
	public function save($id, $type_id)
	{
		$model = D('Attribute');
		if(IS_POST)
		{
			if($model->create())
			{
				if($model->save() !== FALSE)
				{
					$this->success('修改成功！', U('lst', array('type_id' => $type_id)));
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
		$model = D('Attribute');
		$data = $model->search();
		// 取出所有的类型
		$typeModel = M('Type');
		$typeData = $typeModel->select();
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page'],
			'typeData' => $typeData,
		));
		$this->display();
	}
	public function del($id)
	{
		$model = D('Attribute');
		$model->delete($id);
		$this->success('操作成功！');
	}
	public function bdel()
	{
		$delid = I('post.delid');
		if($delid)
		{
			$delid = implode(',', $delid); // 2,3,4
			$model = D('Attribute');
			$model->delete($delid);
		}
		$this->success('操作成功！');
	}
}