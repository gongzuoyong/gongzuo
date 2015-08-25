<?php
namespace Member\Model;
use Think\Model;
class MemberLevelModel extends Model 
{
	protected $_validate = array(
				array('level_name','require','级别名称不能为空！',1),
				array('num_bottom','require','积分下限不能为空！',1),
				array('num_top','require','积分上限不能为空！',1),
				array('rate','require','折扣率,1-100的数字90:9折不能为空！',1),
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