<?php
namespace Gii\Model;
use Think\Model;
class ShopConfigModel extends Model 
{
	protected $_validate = array(
				array('config_name','require','参数名称不能为空！',1),
				array('config_type','require','参数类型不能为空！',1),
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
		$data['config_values'] = str_replace('，', ',', $data['config_values']);
	}
	protected function _before_update(&$data, $option)
	{
		$data['config_values'] = str_replace('，', ',', $data['config_values']);
	}
}