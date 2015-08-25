<?php
namespace Member\Model;
use Think\Model;
class MemberModel extends Model 
{
	protected $_validate = array(
				array('username','require','用户名不能为空！',1),
				array('email','require','Email不能为空！',1),
				array('password','require','密码不能为空！',1),
				array('addtime','require','注册时间不能为空！',1),
				array('gender','require','性别不能为空！',1),
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