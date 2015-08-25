<?php
namespace Goods\Model;
use Think\Model;
class TypeModel extends Model 
{
	protected $_validate = array(
				array('type_name','require','类型名称不能为空！',1),
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
	protected function _before_delete($options)
	{
		// 把这个类型下的属性也删除掉
		if(is_array($options['where']['id']))
			$this->execute("DELETE FROM sh_attribute WHERE type_id IN({$options['where']['id'][1]})");
		else 
			$this->execute('DELETE FROM sh_attribute WHERE type_id='.$options['where']['id']);
	}
}