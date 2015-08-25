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
    <span class="action-span"><a href="/zj/index.php/Member/Member/add">添加</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 列表 </span>
    <div style="clear:both"></div>
</h1>
<form method="post" action="/zj/index.php/Member/Member/bdel" name="listForm">
    <div class="list-div" id="listDiv">
        <table cellpadding="3" cellspacing="1">
            <tr>
                <th width="40"><input id="selall" type="checkbox" /></th>
                                <th>id</th>
                                <th>用户名</th>
                                <th>Email</th>
                                <th>密码</th>
                                <th>注册时间</th>
                                <th>性别</th>
                                <th>操作</th>
            </tr>
            <?php foreach ($data as $k => $v): ?>            <tr>
                <td align="center">
               		<input name="delid[]" class="selall" type="checkbox" value="<?php echo $v['id']; ?>" />
                </td>
                                <td align="center"><?php echo $v['id']; ?></td>
                                <td align="center"><?php echo $v['username']; ?></td>
                                <td align="center"><?php echo $v['email']; ?></td>
                                <td align="center"><?php echo $v['password']; ?></td>
                                <td align="center"><?php echo $v['addtime']; ?></td>
                                <td align="center"><?php echo $v['gender']; ?></td>
                                <td align="center">
                <a href="/zj/index.php/Member/Member/save/id/<?php echo $v['id']; ?>" title="编辑">编辑</a>
                <a onclick="return confirm('确定要删除吗？');" href="/zj/index.php/Member/Member/del/id/<?php echo $v['id']; ?>" title="编辑">移除</a> 
                </td>
            </tr>
           	<?php endforeach; ?>            <tr>
            	<td><input type="submit" value="删除所选" /></td>
                <td align="right" nowrap="true" colspan="7">
                    <div id="turn-page">
                        <?php echo $page; ?>                    </div>
                </td>
            </tr>
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
</script>