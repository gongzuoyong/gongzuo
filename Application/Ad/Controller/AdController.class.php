<?php
namespace Ad\Controller;
use Admin\Controller\IndexController;
class AdController extends IndexController 
{
	public function add()
	{
		if(IS_POST)
		{
			$model = D('Ad');
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
		// 取出所有的广告位
		$apModel = M('AdPos');
		$apData = $apModel->select();
		$this->assign('apData', $apData);
		$this->display();
	}
	public function save($id)
	{
		$model = D('Ad');
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
		// 取出所有的广告位
		$apModel = M('AdPos');
		$apData = $apModel->select();
		$this->assign('apData', $apData);
		// 如果当前广告是动画的广告，那么就动画表中取出所有的图片
		if($data['ad_type'] == '动画')
		{
			$aiModel = M('AdInfo');
			$aiData = $aiModel->where('ad_id='.$id)->select();
			$this->assign('aiData', $aiData);
		}
		$this->display();
	}
	public function lst()
	{
		$model = D('Ad');
		$data = $model->search();
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page'],
		));
		$this->display();
	}
	public function del($id)
	{
		$model = D('Ad');
		$model->delete($id);
		$this->success('操作成功！');
	}
	public function bdel()
	{
		$delid = I('post.delid');
		if($delid)
		{
			$delid = implode(',', $delid); // 2,3,4
			$model = D('Ad');
			$model->delete($delid);
		}
		$this->success('操作成功！');
	}
	public function test()
	{
		if(IS_POST)
		{
			$upload = new \Think\Upload();
			    $upload->maxSize   =     2197152 ;// 2M
			    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
			    $upload->savePath  =     'Ad/';    // 设置附件上传（子）目录
			    $info   =   $upload->upload();
			    $url = IMG_URL . $info['img']['savepath'] . $info['img']['savename'];
				echo "<script>parent.document.getElementById('pre_img').src='$url';</script>";
			    exit;
		}
		$this->display();
	}
}