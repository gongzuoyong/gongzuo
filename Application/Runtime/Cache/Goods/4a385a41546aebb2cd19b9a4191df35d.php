<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: brand_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 添加 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span"><a href="/index.php/Goods/Attribute/lst/type_id/<?php echo I('get.type_id'); ?>">列表</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 添加 </span>
    <div style="clear:both"></div>
</h1>
<div class="main-div">
<!-- /index.php/Goods/Attribute/add/type_id/7:当前方法 -->
    <form method="POST" action="/index.php/Goods/Attribute/add/type_id/7">
    	<input type="hidden" name="type_id" value="<?php echo I('get.type_id'); ?>" />
        <table cellspacing="1" cellpadding="3" width="100%">
        	            <tr>
                <td class="label">属性名称：</td>
                <td>
                	                   		<input type="text" name="attr_name" maxlength="60" value="" />
                                                            <span class="require-field">*</span>
                                    </td>
            </tr>
                        <tr>
                <td class="label">属性类型：</td>
                <td>
                	                		<input checked="checked" type="radio" name="attr_type" maxlength="60" value="唯一" />唯一                	                		<input  type="radio" name="attr_type" maxlength="60" value="单选" />单选                	                                        <span class="require-field">*</span>
                                    </td>
            </tr>
                        <tr>
                <td class="label">属性的可选值：</td>
                <td>
                	<textarea name="attr_values" rows="5" cols="60"></textarea>
                	<br />如果有多个值用，号隔开
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