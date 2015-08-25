<?php
namespace Goods\Controller;
use Admin\Controller\IndexController;
class GoodsController extends IndexController 
{
	public function add()
	{
		if(IS_POST)
		{
			// 设置脚本一直执行到结束，否则一般30秒就断开了
			set_time_limit(0);
			$model = D('Goods');
			if($model->create())
			{
				if($model->add())
				{
					$this->success('添加成功！', U('lst'));
					exit;
				}
				else 
					$this->error('添加失败，请重试！');
			}
			else 
				$this->error($model->getError());
		}
		$catModel = D('Category');
		$catData = $catModel->catTree();
		$brandModel = M('Brand');
		$brandData = $brandModel->select();
		// 取出所有的会员级别
		$mlModel = M('MemberLevel');
		$mlData = $mlModel->select();
		// 取出所有的类型
		$typeModel = M('Type');
		$typeData = $typeModel->select();
		// 取出所有商品的推荐位
		$recModel = M('Recommend');
		$recData = $recModel->where("rec_type='商品'")->select();
		
		$this->assign(array(
			'catData' => $catData,
			'brandData' => $brandData,
			'mlData' => $mlData,
			'typeData' => $typeData,
			'recData' => $recData,
		));
		$this->display();
	}
	public function save($id)
	{
		$model = D('Goods');
		if(IS_POST)
		{
			if($model->create())
			{
				if($model->save() !== FALSE)
				{
					$this->success('修改成功！', U('lst'));
					exit;
				}
				else 
					$this->error('修改失败，请重试！');
			}
			else 
				$this->error($model->getError());
		}
		$data = $model->find($id);
		$this->assign('data', $data);
		$catModel = D('Category');
		$catData = $catModel->catTree();
		$brandModel = M('Brand');
		$brandData = $brandModel->select();
		// 取出所有的会员级别
		$mlModel = M('MemberLevel');
		$mlData = $mlModel->select();
		// 取出所有的类型
		$typeModel = M('Type');
		$typeData = $typeModel->select();
		// 取出当前商品的会员价格
		$mpModel = M('MemberPrice');
		$mpData = $mpModel->where('goods_id='.$id)->select();
		// 取出当前商品所有的图片
		$gpModel = M('GoodsPic');
		$gpData = $gpModel->where('goods_id='.$id)->select();
		// 先取出当前商品所有的属性
		$gaModel = M('GoodsAttr');
		$_gaData = $gaModel->where('goods_id='.$id)->select();
		$gaData = array();
		foreach ($_gaData as $k => $v)
		{
			$gaData[$v['attr_id']][] = $v;
		}
		// 如果商品有类型，那么直接取出这个类型下所有的属性
		if($data['type_id'] != 0)
		{ 
			$attrModel = M('Attribute');
			$attrData = $attrModel->where('type_id='.$data['type_id'])->select();
			$this->assign('attrData', $attrData);
		}
		// 取出所有商品的推荐位
		$recModel = M('Recommend');
		$recData = $recModel->where("rec_type='商品'")->select();
		// 取出当前这件商品所在的推荐位id
		$riModel = M('RecommendItem');
		$recId = $riModel->alias('a')->field('GROUP_CONCAT(a.rec_id) rec_id')->join('LEFT JOIN sh_recommend b ON a.rec_id=b.id')->where('a.value_id='.$id.' AND b.rec_type="商品"')->select();
		
		$this->assign(array(
			'catData' => $catData,
			'brandData' => $brandData,
			'mlData' => $mlData,
			'typeData' => $typeData,
			'mpData' => $mpData,
			'gpData' => $gpData,
			'gaData' => $gaData,
			'recData' => $recData,
			'recId' => $recId[0]['rec_id'],
		));
		$this->display();
	}
	public function lst()
	{
		$model = D('Goods');
		$data = $model->search();
		$this->assign(array(
			'data' => $data['data'],
			'page' => $data['page'],
		));
		$this->display();
	}
	public function del($id)
	{
		$model = D('Goods');
		$model->delete($id);
		$this->success('操作成功！');
	}
	public function bdel()
	{
		$delid = I('post.delid');
		if($delid)
		{
			$delid = implode(',', $delid); // 2,3,4
			$model = D('Goods');
			$model->delete($delid);
		}
		$this->success('操作成功！');
	}
	public function ajaxGetAttr($type_id)
	{
		// 取出这个类型的属性
		$attrModel = M('Attribute');
		$data = $attrModel->where('type_id='.$type_id)->select();
		echo json_encode($data);
	}
	// 库存
	public function product($goods_id)
	{
		$proModel = M('Product');
		if(IS_POST)
		{
			// 先把之前的数据都删除掉
			$proModel->where('goods_id='.$goods_id)->delete();
			$goods_number = I('post.goods_number');
			$goods_attr = I('post.goods_attr');
			foreach ($goods_number as $k => $v)
			{
				// 循环每一个属性，从每一个属性中拿出第$k个属性的ID
				$_attr = array();
				foreach ($goods_attr as $k1 => $v1)
				{
					// 如果没有ID不是数字就跳过这条记录
					if((int)$v1[$k] <= 0)	
						continue 2;
					$_attr[] = $v1[$k];
				}
				// 最后把这条记录所有属性的ID拼成一个字符串
				sort($_attr);
				$_attr = implode('|', $_attr);
				$proModel->add(array(
					'goods_id' => $goods_id,
					'goods_number' => (int)$v,
					'goods_attr' => $_attr,
				));
			}
			$this->success('操作成功');
			exit;
		}
		// 取出这件商品当前的货品信息
		$proData = $proModel->where('goods_id='.$goods_id)->select();
		// 取出这件商品所有单选的属性
		$goodsModel = D('Goods');
		$_attr = $goodsModel->getRadioAttr($goods_id);
		$attr = array();
		foreach ($_attr as $k => $v)
		{
			$attr[$v['attr_id']][] = $v;
		}
		
		$this->assign(array(
			'attr' => $attr,
			'proData' => $proData,
		));
		$this->display();
	}
	public function ajaxDelPic($pid)
	{
		// 先取出图片
		$gpModel = M('GoodsPic');
		$gpModel->find($pid);
		// 从硬盘上删除图片
		@unlink('./Uploads/'.$gpModel->logo);
		@unlink('./Uploads/'.$gpModel->sm_logo);
		@unlink('./Uploads/'.$gpModel->big_logo);
		// 从数据库中删除记录
		$gpModel->delete($pid);
	}
	public function ajaxDelGoodsAttr($gaid)
	{
		// 先取出图片
		$gaModel = M('GoodsAttr');
		// 从数据库中删除记录
		$gaModel->delete($gaid);
	}
}