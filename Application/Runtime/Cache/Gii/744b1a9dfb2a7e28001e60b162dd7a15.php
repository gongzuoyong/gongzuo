<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: brand_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 修改 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="/index.php/Gii/ShopConfig/lst">列表</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 修改 </span>
    <div style="clear:both"></div>
</h1>
<div class="main-div">
<!-- /index.php/Gii/ShopConfig/save/id/8:当前方法 -->
    <form method="POST" action="/index.php/Gii/ShopConfig/save/id/8">
        <table cellspacing="1" cellpadding="3" width="100%">
        	<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />            <tr>
                <td class="label">参数名称：</td>
                <td>
                	<input type="text" name="config_name" maxlength="60" value="<?php echo $data['config_name']; ?>" />
                                                            <span class="require-field">*</span>
                                    </td>
            </tr>
                        <tr>
                <td class="label">参数类型：</td>
                <td>
                	                		<input <?php if($data['config_type'] == "单行文本") echo 'checked="checked"'; ?> type="radio" name="config_type" maxlength="60" value="单行文本" />单行文本                	                		<input <?php if($data['config_type'] == "单选") echo 'checked="checked"'; ?> type="radio" name="config_type" maxlength="60" value="单选" />单选                	                		<input <?php if($data['config_type'] == "多行文本") echo 'checked="checked"'; ?> type="radio" name="config_type" maxlength="60" value="多行文本" />多行文本                	                                        <span class="require-field">*</span>
                                    </td>
            </tr>
                        <tr>
                <td class="label">参数的可选值：</td>
                <td>
                	<textarea name="config_values" rows="5" cols="60"><?php echo $data['config_values']; ?></textarea>
                		多个可选值用,
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