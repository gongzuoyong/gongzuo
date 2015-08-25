<?php
namespace Goods\Controller;
use Admin\Controller\IndexController;
class CategoryController extends IndexController 
{
	public function add()
	{
		if(IS_POST)
		{
			$model = D('Category');
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
		// 取出所有的分类
		$catModel = D('Category');
		$catData = $catModel->catTree();
		$this->assign('catData', $catData);
		// 取出所有分类的推荐位
		$recModel = M('Recommend');
		$recData = $recModel->where("rec_type='分类'")->select();
		$this->assign('recData', $recData);
		$this->display();
	}
	public function save($id)
	{
		$model = D('Category');
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
		// 取出所有的分类
		$catModel = D('Category');
		$catData = $catModel->catTree();
		$this->assign('catData', $catData);
		$data = $model->find($id);
		$this->assign('data', $data);
		// 取出所有分类的推荐位
		$recModel = M('Recommend');
		$recData = $recModel->where("rec_type='分类'")->select();
		// 取出当前这件商品所在的推荐位id
		$riModel = M('RecommendItem');
		$recId = $riModel->alias('a')->field('GROUP_CONCAT(a.rec_id) rec_id')->join('LEFT JOIN sh_recommend b ON a.rec_id=b.id')->where('a.value_id='.$id.' AND b.rec_type="分类"')->select();
		$this->assign(array(
			'recData' => $recData,
			'recId' => $recId[0]['rec_id'],
		));
		
		$this->display();
	}
	public function lst()
	{
		$model = D('Category');
		$data = $model->catTree();
		$this->assign('data', $data);
		$this->display();
	}
	public function del($id)
	{
		$model = D('Category');
		$model->delete($id);
		$this->success('操作成功！');
	}
	public function bdel()
	{
		$delid = I('post.delid');
		if($delid)
		{
			$delid = implode(',', $delid); // 2,3,4
			$model = D('Category');
			$model->delete($delid);
		}
		$this->success('操作成功！');
	}
}