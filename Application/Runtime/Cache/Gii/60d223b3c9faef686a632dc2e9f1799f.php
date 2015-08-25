<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: brand_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 店铺参数设置 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/zj/Public/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/zj/Public/Styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 店铺参数设置 </span>
    <div style="clear:both"></div>
</h1>
<div class="main-div">
<!-- /zj/index.php/Gii/ShopConfig/config.html:当前方法 -->
    <form method="POST" action="/zj/index.php/Gii/ShopConfig/config.html">
        <table cellspacing="1" cellpadding="3" width="100%">
        <?php foreach ($data as $k => $v): ?>
        	            <tr>
                <td class="label"><?php echo $v['config_name']; ?>：</td>
                <td>
                	<?php  if($v['config_type'] == '单行文本') : ?>
                	                   		<input type="text" name="config[<?php echo $v['id']; ?>]" maxlength="60" value="<?php echo $v['config_value']; ?>" />
                	<?php elseif($v['config_type'] == '多行文本') : ?>
                                     <textarea rows="5" cols="60" name="config[<?php echo $v['id']; ?>]"><?php echo $v['config_value']; ?></textarea>
                    <?php elseif($v['config_type'] == '单选') : $_attr = explode(',', $v['config_values']); foreach ($_attr as $k1 => $v1): ?>    
                    	<input <?php if($v1 == $v['config_value']) echo 'checked="checked"'; ?> name="config[<?php echo $v['id']; ?>]" type="radio" value="<?php echo $v1; ?>" /><?php echo $v1; ?>
                                     <?php endforeach;endif; ?>
                                    </td>
            </tr>
                      <?php endforeach; ?>
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