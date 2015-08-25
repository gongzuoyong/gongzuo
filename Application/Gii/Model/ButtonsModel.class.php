<?php
namespace Gii\Model;
use Think\Model;
class ButtonsModel extends Model 
{
	protected $_validate = array(
				array('btn_name','require','按钮名称不能为空！',1),
				array('btn_url','require','按钮地址不能为空！',1),
				array('open_new','require','是否打开新窗口不能为空！',1),
				array('btn_pos','require','按钮的位置不能为空！',1),
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