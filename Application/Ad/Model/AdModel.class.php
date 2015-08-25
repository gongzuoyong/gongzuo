<?php
namespace Ad\Model;
use Think\Model;
class AdModel extends Model 
{
	protected $_validate = array(
				array('ad_name','require','广告名称不能为空！',1),
				array('pos_id','require','广告位的id不能为空！',1),
				array('is_on','require','是否启用不能为空！',1),
				array('ad_type','require','广告类型不能为空！',1),
			);
	public function search()
	{
		$where = 1;
		// 每页的条数
		$perpage = 15;
		// 获取总的记录数
		$totalRecord = $this->where($where)->count();
		// 生成翻页的对象
		$page = new \Think\Page($totalRecord, $perpage);
		return array(
			'data' => $this->where($where)->limit($page->firstRow, $page->listRows)->select(),
			'page' => $page->show(), // 翻页的字符串
		);
	}
	// 处理图片广告
	protected function _before_insert(&$data, $option)
	{
		if($data['ad_type'] == '图片')
		{
			// 判断有没有上传图片
			if(isset($_FILES['img_url']) && $_FILES['img_url']['tmp_name'])
			{
				$upload = new \Think\Upload();
			    $upload->maxSize   =     2197152 ;// 2M
			    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
			    $upload->savePath  =     'Ad/';    // 设置附件上传（子）目录
			    $info   =   $upload->upload(array('img_url' => $_FILES['img_url']));
			    $logo = $info['img_url']['savepath'] . $info['img_url']['savename'];
			    // 把上传之后的图片的地址存到数据库中
			    $data['img_url'] = $logo;
			}
		}
		// 在添加之前，先把所有其他的广告设置为否
		if($data['is_on'] == '是')
			$this->where('pos_id='.$data['pos_id'])->setField('is_on', '否');
	}
	// 在广告的基本信息插入到广告表之后，有了广告的ID之后再处理动画的图片
	protected function _after_insert($data, $option)
	{
		if($data['ad_type'] == '动画')
		{
			if($this->_hasImage($_FILES['cartoon_img']['tmp_name']))
			{
				$upload = new \Think\Upload();
			    $upload->maxSize   =     2197152 ;// 2M
			    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
			    $upload->savePath  =     'Ad/';    // 设置附件上传（子）目录
			    $info   =   $upload->upload(array('cartoon_img' => $_FILES['cartoon_img']));
			    // 循环上传之后的每张图片，存到表中
			    $aiModel = M('AdInfo');
			    $links = I('post.cartoon_link');
			    foreach ($info as $k => $v)
			    {
			    	$aiModel->add(array(
			    		'ad_id' => $data['id'],
			    		'img_url' => $v['savepath'].$v['savename'],
			    		'link' => $links[$k],
			    	));
			    }
			}
		}
	}
	private function _hasImage($files)
	{
		foreach ($files as $k => $v)
		{
			if($v)
				return TRUE;
		}
		return FALSE;
	}
	protected function _before_update(&$data, $option)
	{
		if($data['ad_type'] == '图片')
		{
			// 判断有没有上传图片
			if(isset($_FILES['img_url']) && $_FILES['img_url']['tmp_name'])
			{
				// 删除原图
				$oldImg = I('post.logo_img');
				@unlink('./Uploads/'.$oldImg);
				$upload = new \Think\Upload();
			    $upload->maxSize   =     2197152 ;// 2M
			    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
			    $upload->savePath  =     'Ad/';    // 设置附件上传（子）目录
			    $info   =   $upload->upload(array('img_url' => $_FILES['img_url']));
			    $logo = $info['img_url']['savepath'] . $info['img_url']['savename'];
			    // 把上传之后的图片的地址存到数据库中
			    $data['img_url'] = $logo;
			}
		}
		// 如果修改为启用，那么这个位置上其他的广告就应该变成未启用
		if($data['is_on'] == '是')
			$this->where('pos_id='.$data['pos_id'].' AND id <> '.$option['where']['id'])->setField('is_on', '否');
	}

	protected function _after_update($data, $option)
	{
		if($data['ad_type'] == '动画')
		{
			/***************** 处理新添加的图片 *****************/
			if($this->_hasImage($_FILES['cartoon_img']['tmp_name']))
			{
				$upload = new \Think\Upload();
			    $upload->maxSize   =     2197152 ;// 2M
			    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
			    $upload->savePath  =     'Ad/';    // 设置附件上传（子）目录
			    $info   =   $upload->upload(array('cartoon_img' => $_FILES['cartoon_img']));
			    // 循环上传之后的每张图片，存到表中
			    $aiModel = M('AdInfo');
			    $links = I('post.cartoon_link');
			    // 循环上传的图片，找到对应的链接地址插入到数据库中
			    foreach ($info as $k => $v)
			    {
			    	$aiModel->add(array(
			    		'ad_id' => $data['id'],
			    		'img_url' => $v['savepath'].$v['savename'],
			    		'link' => $links[$k],
			    	));
			    }
			}
			/***************** 处理修改的图片 *******************/
			if($this->_hasImage($_FILES['old_cartoon_img']['tmp_name']))
			{
				$upload = new \Think\Upload();
			    $upload->maxSize   =     2197152 ;// 2M
			    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
			    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
			    $upload->savePath  =     'Ad/';    // 设置附件上传（子）目录
			    $info   =   $upload->upload(array('old_cartoon_img' => $_FILES['old_cartoon_img']));
			}
			 // 循环上传之后的每张图片，存到表中
			$aiModel = M('AdInfo');
			$links = I('post.old_cartoon_link');
			// 图片数组和链接地址两个数组的下标不同，因为链接地址的下标是记录的ID不是从0开始的，所以需要定义一个$_i变量用来把两个数组的下标对应上
			$_i = 0;
			// 循环每一个链接地址修改 数据库中的记录
			foreach ($links as $k => $v)
			{
				// 从图片中找有没有对应的图片，如果有就一起修改
				if(isset($info[$_i]))
				{
					// 先取出原图并删除掉
					$aiModel->find($k);
					@unlink('./Uploads/'.$aiModel->img_url);
					// 修改为新的图片
					$aiModel->where('id='.$k)->save(array(
				    	'link' => $links[$k],
				    	'img_url' => $info[$_i]['savepath'] . $info[$_i]['savename'],
				    ));
				}
				else 
				{
					// 如果没有修改图片就只改链接地址
				    $aiModel->where('id='.$k)->save(array(
				    	'link' => $links[$k],
				    ));
				}
				$_i++;
			}
		}
	}
	protected function _before_delete($options)
	{
		$adInfoModel = M('AdInfo');
		if(is_array($options['where']['id']))
		{
			$data = $this->field('id,ad_type,img_url')->where("id IN({$options['where']['id'][1]})")->select();
			// 循环每一个广告删除图片
			foreach ($data as $k => $v)
			{
				if($v['ad_type'] == '图片')
					@unlink('./Uploads/'.$v['img_url']);
				else 
				{
					// 如果是动画广告，那么先从动画表中取出所有的图片	
					$info = $adInfoModel->where('ad_id='.$v['id'])->select();
					foreach ($info as $k1 => $v1)
						@unlink('./Uploads/'.$v1['img_url']);
					// 把数据库中图片的信息也删除掉
					$adInfoModel->where('ad_id='.$v['id'])->delete();
				}
			}
		}
		else 
		{
			$data = $this->field('id,ad_type,img_url')->where("id IN({$options['where']['id'][1]})")->select();
			// 循环每一个广告删除图片
			foreach ($data as $k => $v)
			{
				if($v['ad_type'] == '图片')
					@unlink('./Uploads/'.$v['img_url']);
				else 
				{
					// 如果是动画广告，那么有多个图片，要先从动画表中取出所有的图片	
					$info = $adInfoModel->where('ad_id='.$v['id'])->select();
					// 从硬盘上循环删除这个广告对应的多个图片
					foreach ($info as $k1 => $v1)
						@unlink('./Uploads/'.$v1['img_url']);
					// 再从数据库中把图片的信息也删除掉
					$adInfoModel->where('ad_id='.$v['id'])->delete();
				}
			}
		}
	}
}