<?php
namespace Goods\Model;
use Think\Model;
class CategoryModel extends Model 
{
	protected $_validate = array(
				array('cat_name','require','分类名称不能为空！',1),
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
	public function catTree()
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
	// 获取一个分类所有子分类的id
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
	
	
	
	protected function _before_delete($options)
	{
		/**
		 * 批量删除时
		 * array
  'where' => 
    array
      'id' => 
        array
          0 => string 'IN' (length=2)
          1 => string '13,12' (length=5)
  'table' => string 'sh_category' (length=11)
  'model' => string 'Category' (length=8)
  
  删除单个时
  array
  'where' => 
    array
      'id' => int 13
  'table' => string 'sh_category' (length=11)
  'model' => string 'Category' (length=8)
		 */
		if(is_array($options['where']['id']))
		{
			// 循环每一个要删除的分类，找出每个分类的子分类
			$_arr = explode(',', $options['where']['id'][1]);
			$children = array();
			foreach ($_arr as $k => $v)
			{
				$_children = $this->getChilrenId($v);
				$children = array_merge($children, $_children);
			}
			// 去重
			$children = array_unique($children);
		}
		else 
			// 取出这个分类所有子分类的id
			$children = $this->getChilrenId($options['where']['id']);
		// 如果有子分类就删除
		if($children)
		{
			$children = implode(',', $children);
			// 删除所有的子分类
			// 注意：这里不能调用delete方法，因为会再调用_before_delete钩子函数这样就死循环了
			$this->execute("DELETE FROM sh_category WHERE id IN($children)");
		}
	}
	protected function _after_insert($data, $option)
	{
		/****************** 处理推荐的代码 *************************/
		$rec = I('post.rec');
		if($rec)
		{
			$riModel = M('RecommendItem');
			foreach ($rec as $v)
			{
				$riModel->add(array(
					'rec_id' => $v,
					'value_id' => $data['id'],
				));
			}
		}
	}
	protected function _before_update(&$data, $option)
	{
		/****************** 处理推荐的代码 *************************/
		// 先删除原来的推荐的数据
		$riModel = M('RecommendItem');
		$riModel->where('value_id='.$option['where']['id'].' AND rec_id IN(SELECT id FROM sh_recommend WHERE rec_type="分类")')->delete();
		$rec = I('post.rec');
		if($rec)
		{
			foreach ($rec as $v)
			{
				$riModel->add(array(
					'rec_id' => $v,
					'value_id' => $option['where']['id'],
				));
			}
		}
	}
}