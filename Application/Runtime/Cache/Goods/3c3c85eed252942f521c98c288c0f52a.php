<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 列表 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/zj/Public/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/zj/Public/Styles/main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/zj/Public/Js/jquery-1.4.2.min.js"></script>
</head>
<body>
<h1>
    <span class="action-span"><a href="/zj/index.php/Goods/Goods/lst">商品列表</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 货品列表 </span>
    <div style="clear:both"></div>
</h1>
<form method="post" action="/zj/index.php/Goods/Goods/product/goods_id/6" name="listForm">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
				<?php  $_count = count($attr); foreach ($attr as $k => $v): ?>
					<th><?php echo $v[0]['attr_name']; ?></th>
				<?php endforeach; ?>
                <th width="80">库存量</th>
                <th width="50">操作</th>
            </tr>
            <?php if($proData): foreach ($proData as $k0 => $v0): ?>
            	<tr>
	            	<?php foreach ($attr as $k => $v): ?>
						<td><select name="goods_attr[<?php echo $k; ?>][]">
							<option value="">请选择</option>
							<?php foreach ($v as $k1 => $v1): if(strpos('|'.$v0['goods_attr'].'|', '|'.$v1['id'].'|') !== FALSE) $select = 'selected="selected"'; else $select = ''; ?>
							<option <?php echo $select; ?> value="<?php echo $v1['id']; ?>"><?php echo $v1['attr_value']; ?></option>
							<?php endforeach; ?>
						</select></td>
					<?php endforeach; ?>
	            	<td><input size="10" type="text" name="goods_number[]" value="<?php echo $v0['goods_number']; ?>" /></td>
	            	<td><input onclick="addRow(this);" type="button" value="<?php echo $k0 == 0 ? '+' : '-'; ?>" /></td>
	            </tr>
            <?php endforeach;else: ?>
            <tr>
            	<?php foreach ($attr as $k => $v): ?>
					<td><select name="goods_attr[<?php echo $k; ?>][]">
						<option value="">请选择</option>
						<?php foreach ($v as $k1 => $v1): ?>
						<option value="<?php echo $v1['id']; ?>"><?php echo $v1['attr_value']; ?></option>
						<?php endforeach; ?>
					</select></td>
				<?php endforeach; ?>
            	<td><input size="10" type="text" name="goods_number[]" /></td>
            	<td><input onclick="addRow(this);" type="button" value="+" /></td>
            </tr>
            <?php endif; ?>
            <tr><td align="center" colspan="<?php echo $_count + 2; ?>"><input type="submit" value="提交" /></td></tr>
        </table>
    </div>
</form>

<div id="footer">
共执行 3 个查询，用时 0.021251 秒，Gzip 已禁用，内存占用 2.194 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>
<script>
$("#selall").click(function(){
	if($(this).attr("checked"))
		$(".selall").attr("checked","checked");   // 设置所有的都选中
	else
		$(".selall").removeAttr("checked");       // 设置都不选中
});
function addRow(o)
{
	var tr = $(o).parent().parent();
	if($(o).val() == "+")
	{
		var newtr = tr.clone();
		newtr.find(":button").val("-");
		tr.after(newtr);
	}
	else
		tr.remove();
}
</script>