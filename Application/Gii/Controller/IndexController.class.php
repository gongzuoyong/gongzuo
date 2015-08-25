<?php
namespace Gii\Controller;
use Think\Controller;
class IndexController extends Controller 
{
	public function index()
	{
		if(IS_POST)
		{
			$tableName = I('post.table_name');
			$moduleName = I('post.module_name');
			if($tableName && $moduleName)
			{
				$moduleName = ucfirst($moduleName);
				/****************************** 生成控制器 *****************************/
				// 先定义将来生成文件所在的几个目录
				$cDir = './Application/'.$moduleName.'/Controller';
				$mDir = './Application/'.$moduleName.'/Model';
				$vDir = './Application/'.$moduleName.'/View';
				// 判断目录是否存在，如果不存在就创建
				// 第二个参数：权限777：可读要写可执行，只有LINUX有效
				// 第三个参数：同时创建多级目录如果设置为FALSE需要一级一级的创建
				if(!is_dir($cDir))
					mkdir($cDir, 0777, TRUE);
				if(!is_dir($mDir))
					mkdir($mDir, 0777, TRUE);
				if(!is_dir($vDir))
					mkdir($vDir, 0777, TRUE);
				$mvcName = $this->_tableNameToMVCName($tableName);
				// 读控制器的模板
				ob_start();
				// 包含PHP文件并解析PHP代码，把解析之后的字符串放到缓冲区中
				include('./Application/Gii/Template/Controller.php');
				// 从缓冲区中取出解析之后的字符串
				$str = ob_get_clean();
				file_put_contents($cDir.'/'.$mvcName.'Controller.class.php', "<?php\r\n".$str);
				/****************************** 生成模型文件 *****************************/
				// 先取出要生成的表的所有字段的信息
				$db = M();
				$fields = $db->query('SHOW FULL FIELDS FROM '.$tableName);
				ob_start();
				include('./Application/Gii/Template/Model.php');
				$str = ob_get_clean();
				file_put_contents($mDir.'/'.$mvcName.'Model.class.php', "<?php\r\n".$str);
				/****************************** 生成三个静态页 *****************************/
				// 先生成静态页所在的控制器的目录
				if(!is_dir($vDir.'/'.$mvcName))
					mkdir($vDir.'/'.$mvcName, 0777, TRUE);
				// 1.生成添加的表单-add.html
				ob_start();
				include('./Application/Gii/Template/add.html');
				$str = ob_get_clean();
				file_put_contents($vDir.'/'.$mvcName.'/add.html', $str);
				// 2. 生成修改的表单-save.html
				ob_start();
				include('./Application/Gii/Template/save.html');
				$str = ob_get_clean();
				file_put_contents($vDir.'/'.$mvcName.'/save.html', $str);
				// 3. 生成列表页-lst.html
				ob_start();
				include('./Application/Gii/Template/lst.html');
				$str = ob_get_clean();
				file_put_contents($vDir.'/'.$mvcName.'/lst.html', $str);
				$this->success('完成！');
				exit;
			}
		}
		// 显示表单
		$this->display();
	}
	private function _tableNameToMVCName($tableName)
	{
		// 计算控制器的名字
		// sh_admin_abcd  -->   AdminAbcd
		// 1. 去掉前缀
		$tableName = str_replace(C('DB_PREFIX'), '', $tableName);
		// 2. 去掉下划线
		$tableName = explode('_', $tableName);
		// 把数组中每个值都使用ucfirst这个函数处理一遍
		$tableName = array_map('ucfirst', $tableName);
		// 把数组中的每个单词连到一起
		return implode('', $tableName);
	}
}