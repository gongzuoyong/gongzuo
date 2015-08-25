<?php
namespace Article\Model;
use Think\Model;
class ArticleCatModel extends Model 
{
	protected $_validate = array(
				array('cat_name','require','分类名称不能为空！',1),
				array('is_help','require','是否是帮助不能为空！',1),
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
		// SELECT a.*,COUNT(b.id) article_count FROM sh_article_cat a LEFT JOIN sh_article b ON a.id=b.cat_id GROUP BY a.id ORDER BY article_count DESC
		return array(
			'data' => $this->field('a.*,COUNT(b.id) article_count')->alias('a')->join('LEFT JOIN sh_article b ON a.id=b.cat_id')->group('a.id')->where($where)->order('article_count DESC')->limit($page->firstRow, $page->listRows)->select(),
			'page' => $page->show(), // 翻页的字符串
		);
	}
	protected function _before_delete($options)
	{
		$artModel = M('Article');
		// 批量删除时 : $options['where']['id'][1] : 1,2,3,4,5,6,7
		if(is_array($options['where']['id']))
			$artModel->where("cat_id IN({$options['where']['id'][1]})")->delete();
		else 
			$artModel->where('cat_id='.$options['where']['id'])->delete();
	}
}