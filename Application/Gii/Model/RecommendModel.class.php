<?php
namespace Gii\Model;
use Think\Model;
class RecommendModel extends Model 
{
	protected $_validate = array(
				array('rec_name','require','推荐位名称不能为空！',1),
				array('rec_type','require','推荐位的类型不能为空！',1),
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
}