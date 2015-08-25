<?php
namespace Goods\Model;
use Think\Model;
class GoodsModel extends Model 
{
	protected $_validate = array(
				array('goods_name','require','商品名称不能为空！',1),
				array('cat_id','/^[1-9]\d*$/','商品分类不能为空！',1),
				array('market_price','require','市场价不能为空！',1),
				array('market_price','currency','市场价格式不正确！',1),
				array('shop_price','require','本店价不能为空！',1),
				array('shop_price','currency','本店价格式不正确！',1),
				array('is_on_sale','require','是否上架不能为空！',1),
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
		/**
		 * SELECT a.*,SUM(b.goods_number) gn FROM sh_goods a LEFT JOIN sh
_product b ON a.id=b.goods_id WHERE $where GROUP BY a.id LIMIT $page->firstRow, $page->listRows;
		 */
		return array(
			'data' => $this->alias('a')->field('a.*,SUM(b.goods_number) gn')->join('LEFT JOIN sh_product b ON a.id=b.goods_id')->where($where)->group('a.id')->limit($page->firstRow, $page->listRows)->select(),
			'page' => $page->show(), // 翻页的字符串
		);
	}
	protected function _before_insert(&$data, $option)
	{
		// 判断有没有上传图片
		if(isset($_FILES['logo']) && $_FILES['logo']['tmp_name'])
		{
			$upload = new \Think\Upload();
		    $upload->maxSize   =     2097152 ;// 2M
		    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
		    $upload->savePath  =     'Goods/';    // 设置附件上传（子）目录
		    $info   =   $upload->upload(array('logo' => $_FILES['logo'])); // 指定上传logo图片
		    $logo = $info['logo']['savepath'] . $info['logo']['savename'];
		    $smlogo = $info['logo']['savepath'] .'sm_'. $info['logo']['savename'];
		    $midlogo = $info['logo']['savepath'] .'mid_'. $info['logo']['savename'];
		    $biglogo = $info['logo']['savepath'] .'big_'. $info['logo']['savename'];
		    // 生成缩略图
		    $image = new \Think\Image(); 
		    $image->open('./Uploads/'.$logo);
		    // 注意：如果要生成多张缩略图，必须从大到小的生成  大 --->   小
		    // 缩略图的起名原则，在原图的基础上加前缀，如： 123456.jpg ,  sm_123456.jpg
		    $image->thumb(C('GOODS_IMG_BIG_WIDTH'), C('GOODS_IMG_BIG_HEIGHT'))->save('./Uploads/'.$biglogo);
		    $image->thumb(C('GOODS_IMG_MID_WIDTH'), C('GOODS_IMG_MID_HEIGHT'))->save('./Uploads/'.$midlogo);
		    $image->thumb(C('GOODS_IMG_SM_WIDTH'), C('GOODS_IMG_SM_HEIGHT'))->save('./Uploads/'.$smlogo);
		    // 把上传之后的图片的地址存到数据库中
		    $data['logo'] = $logo;
		    $data['sm_logo'] = $smlogo;
		    $data['mid_logo'] = $midlogo;
		    $data['big_logo'] = $biglogo;
		}
		// 为商品生成编号
		$data['sn'] = time() . rand(111111, 999999);
	}
	// 在商品的基本信息插入到商品表中之后
	protected function _after_insert($data, $option)
	{
		/********************** 处理会员价格的 ****************************************/
		$mp = I('post.mp');
		if($mp)
		{
			$mpModel = M('MemberPrice');
			foreach ($mp as $k => $v)
			{
				// 如果价格为空就跳过
				if(trim($v) == '')
					continue ;
				// 插入数据库
				$mpModel->add(array(
					'price' => $v,
					'level_id' => $k,
					'goods_id' => $data['id'],
				));
			}
		}
		/******************* 处理商品图片 ************************************************/
		if($this->_hasImage($_FILES['goods_pics']['tmp_name']))
		{
			$upload = new \Think\Upload();
		    $upload->maxSize   =     2097152 ;// 2M
		    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
		    $upload->savePath  =     'Goods/';    // 设置附件上传（子）目录
		    $info   =   $upload->upload(array('goods_pics' => $_FILES['goods_pics'])); // 指定上传logo图片
		    // 循环每个图片生成缩略图并插入并到数据库中
		    $gpModel = M('GoodsPic');
		    $gibw = C('GOODS_IMG_BIG_WIDTH');
		    $gibh = C('GOODS_IMG_BIG_HEIGHT');
		    $gisw = C('GOODS_IMG_SM_WIDTH');
		    $gish = C('GOODS_IMG_SM_HEIGHT');
		    // 生成缩略图
		    $image = new \Think\Image(); 
		    foreach ($info as $k => $v)
		    {
		    	// 获取上传之后原图的名称
		    	$logo = $v['savepath'] . $v['savename'];
		    	// 打开原图
		    	$image->open('./Uploads/'.$logo);
		    	// 构造缩略图名称
			    $smlogo = $v['savepath'] .'sm_'. $v['savename'];
			    $biglogo = $v['savepath'] .'big_'. $v['savename'];
			    // 生成缩略图图
			    $image->thumb($gibw, $gibh)->save('./Uploads/'.$biglogo);
			    $image->thumb($gisw, $gish)->save('./Uploads/'.$smlogo);
			    // 插入到数据库
			    $gpModel->add(array(
			    	'goods_id' => $data['id'],
			    	'logo' => $logo,
			    	'sm_logo' => $smlogo,
			    	'big_logo' => $biglogo,
			    ));
		    }
		}
		/************************ 处理商品属性 ****************************/
		/**
		 * 提交的数组结构：
		 *   'goods_attr' => 
			    array
			      8 => string 'i5' (length=2)
			      9 => 
			        array
			          0 => string '钃濊壊' (length=6)
			          1 => string '绾㈣壊' (length=6)
			          2 => string '榛戣壊' (length=6)
			      10 => string '20133131' (length=8)
			  'attr_price' => 
			    array
			      0 => string '0' (length=1)
			      1 => string '0' (length=1)
			      2 => string '0' (length=1)
			      3 => string '0' (length=1)
			      4 => string '0' (length=1)
		 */
		$goods_attr = I('post.goods_attr');
		$attr_price = I('post.attr_price');
		if($goods_attr)
		{
			$gaModel = M('GoodsAttr');
			$i = 0;
			foreach ($goods_attr as $k => $v)
			{
				// 判断如果一个属性有多个值，那么循环每一个值
				if(is_array($v))
				{
					foreach ($v as $k1 => $v1)
					{
						$gaModel->add(array(
							'goods_id' => $data['id'],
							'attr_id' => $k,
							'attr_value' => $v1,
							'attr_price' => $attr_price[$i],
						));
						$i++;
					}
				}
				else 
				{
					$gaModel->add(array(
						'goods_id' => $data['id'],
						'attr_id' => $k,
						'attr_value' => $v,
						'attr_price' => $attr_price[$i],
					));
					$i++;
				}
			}
		}
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
	private function _hasImage($files)
	{
		foreach ($files as $k => $v)
		{
			if($v)
				return TRUE;
		}
		return FALSE;
	}
	protected function _before_delete($options)
	{
		if(is_array($options['where']['id']))
		{
			/************ 删除商品的logo图片 *********************/
			// $options['where']['id'][1] 是所有要删除的商品的ID：如：1,2,3,4,5,6
			$data = $this->field('logo,sm_logo,mid_logo,big_logo')->where("id IN ({$options['where']['id'][1]})")->select();
			foreach ($data as $k => $v)
			{
				@unlink('./Uploads/'.$v['logo']);
				@unlink('./Uploads/'.$v['sm_logo']);
				@unlink('./Uploads/'.$v['mid_logo']);
				@unlink('./Uploads/'.$v['big_logo']);
			}
			/*********** 删除会员价格的数据 *************************/
			$mpModel = M('MemberPrice');
			// 相当于：DELETE FROM sh_member_price WHERE goods_id IN({$options['where']['id'][1]})
			$mpModel->where("goods_id IN({$options['where']['id'][1]})")->delete();
			/*********** 删除商品的属性数据 ***************************/
			$gaModel = M('GoodsAttr');
			$gaModel->where("goods_id IN({$options['where']['id'][1]})")->delete();
			/************** 删除商品所有的图片 ***************************/
			$gpModel = M('GoodsPic');
			$data = $gpModel->where("goods_id IN({$options['where']['id'][1]})")->select();
			foreach ($data as $k => $v)
			{
				@unlink('./Uploads/'.$v['logo']);	
				@unlink('./Uploads/'.$v['sm_logo']);	
				@unlink('./Uploads/'.$v['big_logo']);	
			}
			$gpModel->where("goods_id IN({$options['where']['id'][1]})")->delete();
		}
		else 
		{
			/************ 删除商品的logo图片 *********************/
			// 说明： $options['where']['id']是要删除的商品的ID
			// 先找出这件商品的图片
			$this->field('logo,sm_logo,mid_logo,big_logo')->find($options['where']['id']);
			@unlink('./Uploads/'.$this->logo);
			@unlink('./Uploads/'.$this->sm_logo);
			@unlink('./Uploads/'.$this->mid_logo);
			@unlink('./Uploads/'.$this->big_logo);
			/*********** 删除会员价格的数据 *************************/
			$mpModel = M('MemberPrice');
			$mpModel->where('goods_id='.$options['where']['id'])->delete();
			/*********** 删除商品的属性数据 ***************************/
			$gaModel = M('GoodsAttr');
			$gaModel->where('goods_id='.$options['where']['id'])->delete();
			/************** 删除商品所有的图片 ***************************/
			$gpModel = M('GoodsPic');
			$data = $gpModel->where('goods_id='.$options['where']['id'])->select();
			foreach ($data as $k => $v)
			{
				@unlink('./Uploads/'.$v['logo']);	
				@unlink('./Uploads/'.$v['sm_logo']);	
				@unlink('./Uploads/'.$v['big_logo']);	
			}
			$gpModel->where('goods_id='.$options['where']['id'])->delete();
		}
	}
	// 获取一件商品所有单选的属性
	public function getRadioAttr($goods_id)
	{
		$sql = "SELECT a.attr_id,b.attr_name,a.attr_value,a.id
				 FROM sh_goods_attr a LEFT JOIN sh_attribute b ON a.attr_id=b.id
				  WHERE a.goods_id=$goods_id AND b.attr_type='单选'";
		return $this->query($sql);
	}
	protected function _before_update(&$data, $option)
	{
		// 判断有没有上传图片
		if(isset($_FILES['logo']) && $_FILES['logo']['tmp_name'])
		{
			// 删除原图
			@unlink('./Uploads/'.I('post.ologo'));
			@unlink('./Uploads/'.I('post.osm_logo'));
			@unlink('./Uploads/'.I('post.omid_logo'));
			@unlink('./Uploads/'.I('post.obig_logo'));
			$upload = new \Think\Upload();
		    $upload->maxSize   =     2097152 ;// 2M
		    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
		    $upload->savePath  =     'Goods/';    // 设置附件上传（子）目录
		    $info   =   $upload->upload(array('logo' => $_FILES['logo'])); // 指定上传logo图片
		    $logo = $info['logo']['savepath'] . $info['logo']['savename'];
		    $smlogo = $info['logo']['savepath'] .'sm_'. $info['logo']['savename'];
		    $midlogo = $info['logo']['savepath'] .'mid_'. $info['logo']['savename'];
		    $biglogo = $info['logo']['savepath'] .'big_'. $info['logo']['savename'];
		    // 生成缩略图
		    $image = new \Think\Image(); 
		    $image->open('./Uploads/'.$logo);
		    // 注意：如果要生成多张缩略图，必须从大到小的生成  大 --->   小
		    // 缩略图的起名原则，在原图的基础上加前缀，如： 123456.jpg ,  sm_123456.jpg
		    $image->thumb(C('GOODS_IMG_BIG_WIDTH'), C('GOODS_IMG_BIG_HEIGHT'))->save('./Uploads/'.$biglogo);
		    $image->thumb(C('GOODS_IMG_MID_WIDTH'), C('GOODS_IMG_MID_HEIGHT'))->save('./Uploads/'.$midlogo);
		    $image->thumb(C('GOODS_IMG_SM_WIDTH'), C('GOODS_IMG_SM_HEIGHT'))->save('./Uploads/'.$smlogo);
		    // 把上传之后的图片的地址存到数据库中
		    $data['logo'] = $logo;
		    $data['sm_logo'] = $smlogo;
		    $data['mid_logo'] = $midlogo;
		    $data['big_logo'] = $biglogo;
		}
		/********************** 处理会员价格的 ****************************************/
		// 先删除原数据
		$mpModel = M('MemberPrice');
		$mpModel->where('goods_id='.$option['where']['id'])->delete();
		$mp = I('post.mp');
		if($mp)
		{
			foreach ($mp as $k => $v)
			{
				// 如果价格为空就跳过
				if(trim($v) == '')
					continue ;
				// 插入数据库
				$mpModel->add(array(
					'price' => $v,
					'level_id' => $k,
					'goods_id' => $option['where']['id'],
				));
			}
		}
		/******************* 处理商品图片 ************************************************/
		if($this->_hasImage($_FILES['goods_pics']['tmp_name']))
		{
			$upload = new \Think\Upload();
		    $upload->maxSize   =     2097152 ;// 2M
		    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
		    $upload->savePath  =     'Goods/';    // 设置附件上传（子）目录
		    $info   =   $upload->upload(array('goods_pics' => $_FILES['goods_pics'])); // 指定上传logo图片
		    // 循环每个图片生成缩略图并插入并到数据库中
		    $gpModel = M('GoodsPic');
		    $gibw = C('GOODS_IMG_BIG_WIDTH');
		    $gibh = C('GOODS_IMG_BIG_HEIGHT');
		    $gisw = C('GOODS_IMG_SM_WIDTH');
		    $gish = C('GOODS_IMG_SM_HEIGHT');
		    // 生成缩略图
		    $image = new \Think\Image(); 
		    foreach ($info as $k => $v)
		    {
		    	// 获取上传之后原图的名称
		    	$logo = $v['savepath'] . $v['savename'];
		    	// 打开原图
		    	$image->open('./Uploads/'.$logo);
		    	// 构造缩略图名称
			    $smlogo = $v['savepath'] .'sm_'. $v['savename'];
			    $biglogo = $v['savepath'] .'big_'. $v['savename'];
			    // 生成缩略图图
			    $image->thumb($gibw, $gibh)->save('./Uploads/'.$biglogo);
			    $image->thumb($gisw, $gish)->save('./Uploads/'.$smlogo);
			    // 插入到数据库
			    $gpModel->add(array(
			    	'goods_id' => $option['where']['id'],
			    	'logo' => $logo,
			    	'sm_logo' => $smlogo,
			    	'big_logo' => $biglogo,
			    ));
		    }
		}
		/************************ 处理商品属性 ****************************/
		/**
		 * 提交的数组结构：
		 *   'goods_attr' => 
			    array
			      8 => string 'i5' (length=2)
			      9 => 
			        array
			          0 => string '钃濊壊' (length=6)
			          1 => string '绾㈣壊' (length=6)
			          2 => string '榛戣壊' (length=6)
			      10 => string '20133131' (length=8)
			  'attr_price' => 
			    array
			      0 => string '0' (length=1)
			      1 => string '0' (length=1)
			      2 => string '0' (length=1)
			      3 => string '0' (length=1)
			      4 => string '0' (length=1)
		 */
		/**************** 处理新添加的属性 **********************/
		$goods_attr = I('post.goods_attr');
		$attr_price = I('post.attr_price');
		if($goods_attr)
		{
			$gaModel = M('GoodsAttr');
			$i = 0;
			foreach ($goods_attr as $k => $v)
			{
				// 判断如果一个属性有多个值，那么循环每一个值
				if(is_array($v))
				{
					foreach ($v as $k1 => $v1)
					{
						$gaModel->add(array(
							'goods_id' => $option['where']['id'],
							'attr_id' => $k,
							'attr_value' => $v1,
							'attr_price' => $attr_price[$i],
						));
						$i++;
					}
				}
				else 
				{
					$gaModel->add(array(
						'goods_id' => $option['where']['id'],
						'attr_id' => $k,
						'attr_value' => $v,
						'attr_price' => $attr_price[$i],
					));
					$i++;
				}
			}
		}
		/************************* 处理修改的属性 ********************************/
		$goods_attr = I('post.old_goods_attr');
		$attr_price = I('post.old_attr_price');
		if($goods_attr)
		{
			$ids = array_keys($attr_price);
			$prices = array_values($attr_price);
			$gaModel = M('GoodsAttr');
			$i = 0;
			foreach ($goods_attr as $k => $v)
			{
				// 判断如果一个属性有多个值，那么循环每一个值
				if(is_array($v))
				{
					foreach ($v as $k1 => $v1)
					{
						$gaModel->where('id='.$ids[$i])->save(array(
							'attr_value' => $v1,
							'attr_price' => $prices[$i],
						));
						$i++;
					}
				}
				else 
				{
					$gaModel->where('id='.$ids[$i])->save(array(
						'attr_value' => $v,
						'attr_price' => $prices[$i],
					));
					$i++;
				}
			}
		}
		/****************** 处理推荐的代码 *************************/
		// 先删除原来的推荐的数据
		$riModel = M('RecommendItem');
		$riModel->where('value_id='.$option['where']['id'].' AND rec_id IN(SELECT id FROM sh_recommend WHERE rec_type="商品")')->delete();
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