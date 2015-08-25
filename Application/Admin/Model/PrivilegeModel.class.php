<?php
namespace Admin\Model;
use Think\Model;
class PrivilegeModel extends Model 
{
	protected $_validate = array(
				array('pri_name','require','权限名称不能为空！',1),
				array('module_name','require','模块名称不能为空！',1),
				array('controller_name','require','控制器名称不能为空！',1),
				array('action_name','require','方法名称不能为空！',1),
			);
	
	public function getChilrenId($catId)
	{
		$data = $this->select();
		return $this->_getChilrenId($data, $catId, TRUE);
	}
	private function _getChilrenId($data, $parent_id, $isClear = FALSE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$ret[] = $v['id'];
				// 再找这个分类的子分类
				$this->_getChilrenId($data, $v['id']);
			}
		}
		return $ret;
	}
	public function priTree()
	{
		$data = $this->select();
		return $this->_reSort($data);
	}
	// 递归对有的分类进行重新排序
	private function _reSort($data, $parent_id=0, $level=0)
	{
		static $ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				// 把level值放到这个分类里，这样就可以知道这个分类是第几级的
				$v['level'] = $level;
				$ret[] = $v;
				// 再找这个分类的子分类
				$this->_reSort($data, $v['id'], $level+1);
			}
		}
		return $ret;
	}
	protected function _before_delete($options)
	{
		if(is_array($options['where']['id']))
		{
			$_arr = explode(',', $options['where']['id'][1]);
			$children = array();
			foreach ($_arr as $k => $v)
			{
				$_children = $this->getChilrenId($v);
				$children = array_merge($children, $_children);
			}
			$children = array_unique($children);
		}
		else 
			$children = $this->getChilrenId($options['where']['id']);
		if($children)
		{
			$children = implode(',', $children);
			$this->execute("DELETE FROM sh_privilege WHERE id IN($children)");
		}
	}
}