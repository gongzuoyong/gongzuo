<?php
namespace Ad\Model;
use Think\Model;
class AdPosModel extends Model 
{
	protected $_validate = array(
				array('pos_name','require','广告位名称不能为空！',1),
				array('pos_width','require','广告位的宽不能为空！',1),
				array('pos_height','require','广告位的高不能为空！',1),
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
	# 当删除一个广告位时，把这个广告位下的广告也删除掉
	protected function _before_delete($options)
	{
		$adModel = M('Ad');
		$adInfoModel = M('AdInfo');
		if(is_array($options['where']['id']))
		{
			// 先取出这些广告位下的广告
			$data = $adModel->where("pos_id IN({$options['where']['id'][1]})")->select();
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
			// 把广告表中数据也删除掉
			$adModel->where("pos_id IN({$options['where']['id'][1]})")->delete();
		}
		else 
		{
			// 先取出这个广告位下的广告
			$data = $adModel->where('pos_id='.$options['where']['id'])->select();
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
			// 把广告表中数据也删除掉
			$adModel->where('pos_id='.$options['where']['id'])->delete();
		}
	}
}