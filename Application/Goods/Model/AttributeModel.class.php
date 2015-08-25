<?php
namespace Goods\Model;
use Think\Model;
class AttributeModel extends Model 
{
	protected $_validate = array(
				array('attr_name','require','属性名称不能为空！',1),
				array('attr_type','require','属性类型不能为空！',1),
				array('type_id','require','所属类型不能为空！',1),
			);
	public function search()
	{
		$where = 'type_id='.I('get.type_id');
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
		$data['attr_values'] = str_replace('，',',', $data['attr_values']);
	}
	protected function _before_update(&$data, $option)
	{
		$data['attr_values'] = str_replace('，',',', $data['attr_values']);
	}
}