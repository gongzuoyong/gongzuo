<?php
namespace Article\Model;
use Think\Model;
class ArticleModel extends Model 
{
	protected $_validate = array(
				array('title','require','标题不能为空！',1),
				array('content','require','内容不能为空！',1),
				array('cat_id','require','文章分类的id不能为空！',1),
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
		// SELECT a.*,b.cat_name FROM sh_article a LEFT JOIN sh_article_cat b ON a.cat_id=b.id
		return array(
			'data' => $this->field('a.*,b.cat_name')->alias('a')->join('LEFT JOIN sh_article_cat b ON a.cat_id=b.id')->where($where)->limit($page->firstRow, $page->listRows)->select(),
			'page' => $page->show(), // 翻页的字符串
		);
	}
	protected function _before_insert(&$data, $option)
	{
		$data['addtime'] = date('Y-m-d H:i:s');
	}
}