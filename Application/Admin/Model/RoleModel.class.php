<?php
namespace Admin\Model;
use Think\Model;
class RoleModel extends Model 
{
	protected $_validate = array(
		array('role_name','require','角色名称不能为空！',1),
		array('role_name','','角色名称已经存在！',1, 'unique'),
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
		// SELECT a.*,GROUP_CONCAT(b.pri_name) FROM sh_role a LEFT JOIN sh_privilege b ON FIND_IN_SET(b.id,a.pri_id_list) GROUP BY a.id;
		return array(
			'data' => $this->field('a.*,GROUP_CONCAT(b.pri_name) pri_name')->alias('a')->join('LEFT JOIN sh_privilege b ON FIND_IN_SET(b.id,a.pri_id_list)')->where($where)->group('a.id')->limit($page->firstRow, $page->listRows)->select(),
			'page' => $page->show(), // 翻页的字符串
		);
	}
	protected function _before_insert(&$data, $option)
	{
		$data['pri_id_list'] = implode(',', $data['pri_id_list']);
	}
	
	protected function _before_update(&$data, $option)
	{
		$data['pri_id_list'] = implode(',', $data['pri_id_list']);
	}
}