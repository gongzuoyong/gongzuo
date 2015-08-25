<?php
namespace Article\Controller;
use Admin\Controller\IndexController;
class ArticleController extends IndexController 
{
	public function add()
	{
		if(IS_POST)
		{
			$model = D('Article');
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
		$acModel = M('ArticleCat');
		$acData = $acModel->select();
		$this->assign('acData', $acData);
		$this->display();
	}
	public function save($id)
	{
		$model = D('Article');
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
		$acModel = M('ArticleCat');
		$acData = $acModel->select();
		$this->assign('acData', $acData);
		$this->display();
	}
	public function lst()
	{
		$model = D('Article');
		$data = $model->search();
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page'],
		));
		$this->display();
	}
	public function del($id)
	{
		$model = D('Article');
		$model->delete($id);
		$this->success('操作成功！');
	}
	public function bdel()
	{
		$delid = I('post.delid');
		if($delid)
		{
			$delid = implode(',', $delid); // 2,3,4
			$model = D('Article');
			$model->delete($delid);
		}
		$this->success('操作成功！');
	}
}