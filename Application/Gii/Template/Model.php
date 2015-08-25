namespace <?php echo $moduleName; ?>\Model;
use Think\Model;
class <?php echo $mvcName; ?>Model extends Model 
{
	protected $_validate = array(
		<?php 
		/** 循环生成不能为空的验证规则 **/
		foreach ($fields as $k => $v):
			if($v['Key'] == 'PRI')
				continue ;
			if($v['Null'] == 'NO' && $v['Default'] == NULL):
		?>
		array('<?php echo $v['Field']; ?>','require','<?php echo $v['Comment']; ?>不能为空！',1),
		<?php endif;;endforeach;
		/** 循环生成唯一的验证规则 **/
		foreach ($fields as $k => $v):
		if($v['Key'] == 'UNI'): ?>
		array('<?php echo $v['Field']; ?>','','<?php echo $v['Comment']; ?>已经存在！',1,'unique'),
		<?php endif;endforeach; ?>
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