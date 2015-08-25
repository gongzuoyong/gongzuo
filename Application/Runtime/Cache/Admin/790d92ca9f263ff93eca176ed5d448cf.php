<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: brand_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 修改 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/zj/Public/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/zj/Public/Styles/main.css" rel="stylesheet" type="text/css" />
<script src="/zj/Public/Js/jquery-1.4.2.min.js"></script>
<style>
ul li{list-style-type:none;margin:5px;}
</style>
</head>
<body>
<h1>
    <span class="action-span"><a href="/zj/index.php/Admin/Role/lst">列表</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 修改 </span>
    <div style="clear:both"></div>
</h1>
<div class="main-div">
<!-- /zj/index.php/Admin/Role/save/id/4:当前方法 -->
    <form method="POST" action="/zj/index.php/Admin/Role/save/id/4">
        <table cellspacing="1" cellpadding="3" width="100%">
        	<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />            <tr>
                <td class="label">角色名称：</td>
                <td>
                	<input type="text" name="role_name" maxlength="60" value="<?php echo $data['role_name']; ?>" />
                                                            <span class="require-field">*</span>
                                    </td>
            </tr>
                         <tr>
                <td class="label">权限列表：</td>
                <td>
                	<ul>
                	<?php foreach ($priData as $k => $v): if(strpos(','.$data['pri_id_list'].',', ','.$v['id'].',') !== FALSE) $check = 'checked="checked"'; else $check = ''; ?>
                		<li level="<?php echo $v['level']; ?>"><?php echo str_repeat('-', 8*$v['level']); ?><input <?php echo $check; ?> name="pri_id_list[]" type="checkbox" value="<?php echo $v['id']; ?>" /><?php echo $v['pri_name']; ?></li>
                	<?php endforeach; ?>
                	</ul>
                </td>
            </tr>
                        <tr>
                <td colspan="2" align="center"><br />
                    <input type="submit" class="button" value=" 确定 " />
                    <input type="reset" class="button" value=" 重置 " />
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="footer">
共执行 1 个查询，用时 0.018952 秒，Gzip 已禁用，内存占用 2.197 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>
<script>
// 为所有checkbox绑定点击事件
$(":checkbox").click(function(){
	// $(this):点击的那个checkbox
	// 获取这个checkbox所在的li
	var cur_li = $(this).parent();
	// 当前是否是选 中
	var checked = $(this).attr("checked");
	// 获取当前li上的level属性
	var cur_level = cur_li.attr("level");
	// 选择所有前面的li,并且循环每一个li
	cur_li.prevAll("li").each(function(){
		// $(this):代表循环的每一个li
		// 如果是上级并且当前是选中的
		if($(this).attr("level") < cur_level && checked)
		{
			// 让li中的checkbox选中状态
			$(this).find(":checkbox").attr("checked","checked");
			if($(this).attr("level") == '0')
				return false;
		}
	});
	// 非选中状态，那么就设置所有子权限也取消
	if(!checked)
	{
		// 选 中所有后面的li
		cur_li.nextAll("li").each(function(){
			// 如果是子权限就取消选中状态，否则退出循环
			if($(this).attr("level") > cur_level)
				$(this).find(":checkbox").removeAttr("checked");
			else
				return false;
		});
	}
});
</script>