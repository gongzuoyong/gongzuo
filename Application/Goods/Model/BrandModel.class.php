<?php
namespace Goods\Model;
use Think\Model;
class BrandModel extends Model 
{
	protected $_validate = array(
				array('brand_name','require','品牌名称不能为空！',1),
				array('brand_url','require','官方网站不能为空！',1),
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
	
	protected function _before_insert(&$data, $option)
	{
		// 判断有没有上传图片
		if(isset($_FILES['brand_logo']) && $_FILES['brand_logo']['tmp_name'])
		{
			// 如果有原图就删除
			$oldlogo = I('post.oldlogo');
			if($oldlogo)
				@unlink('./Uploads/'.$oldlogo);
			$upload = new \Think\Upload();
		    $upload->maxSize   =     1048576 ;// 1M
		    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
		    $upload->savePath  =     'Brand/';    // 设置附件上传（子）目录
		    $info   =   $upload->upload();
		    $logo = $info['brand_logo']['savepath'] . $info['brand_logo']['savename'];
		    // 生成缩略图
		    $image = new \Think\Image(); 
		    $image->open('./Uploads/'.$logo);
		    // 生成缩略图并覆盖原图
		    $image->thumb(C('BRAND_IMG_WIDTH'), C('BRAND_IMG_HEIGHT'))->save('./Uploads/'.$logo);
		    // 把上传之后的图片的地址存到数据库中
		    $data['brand_logo'] = $logo;
		}
	}
	protected function _before_update(&$data, $option)
	{
		$this->_before_insert($data, $option);
	}
	protected function _before_delete($options)
	{
		if(is_array($options['where']['id']))
		{
			// 先取出所有要删除的品牌的图片
			$data = $this->field('brand_logo')->where("id IN({$options['where']['id'][1]}) AND brand_logo != ''")->select();
			// 循环删除每一张图片
			foreach ($data as $k => $v)
			{
				if(file_exists('./Uploads/'.$v['brand_logo']))
					@unlink('./Uploads/'.$v['brand_logo']);
			}
		}
		else 
		{
			// 先取出要删除的品牌的图片
			$this->field('brand_logo')->find($options['where']['id']);
			if($this->brand_logo && file_exists('./Uploads/'.$this->brand_logo))
					@unlink('./Uploads/'.$this->brand_logo);
		}
	}
}