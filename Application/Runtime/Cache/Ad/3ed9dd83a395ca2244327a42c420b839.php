<?php if (!defined('THINK_PATH')) exit();?><!-- $Id: brand_info.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 修改 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/zj/Public/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/zj/Public/Styles/main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/zj/Public/Js/jquery-1.4.2.min.js"></script>
</head>
<body>
<h1>
    <span class="action-span"><a href="/zj/index.php/Ad/Ad/lst">列表</a></span>
    <span class="action-span1"><a href="#">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 修改 </span>
    <div style="clear:both"></div>
</h1>
<div class="main-div">
<!-- /zj/index.php/Ad/Ad/save/id/8:当前方法 -->
    <form method="POST" action="/zj/index.php/Ad/Ad/save/id/8" enctype="multipart/form-data">
    	<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
    	<input type="hidden" name="logo_img" value="<?php echo $data['img_url']; ?>" />
    	<input type="hidden" name="ad_type" value="<?php echo $data['ad_type']; ?>" />
        <table cellspacing="1" cellpadding="3" width="100%">
        <tr>
                <td class="label">广告位的id：</td>
                <td>
                	<select name="pos_id">
                		<?php foreach ($apData as $k => $v): if($data['pos_id'] == $v['id']) $select = 'selected="selected"'; else $select = ''; ?>
                			<option <?php echo $select; ?> value="<?php echo $v['id']; ?>"><?php echo $v['pos_name']; ?></option>
                		<?php endforeach; ?>
                	</select>
                                                            <span class="require-field">*</span>
                                    </td>
            </tr>
        	            <tr>
                <td class="label">广告名称：</td>
                <td>
                	                   		<input type="text" name="ad_name" maxlength="60" value="<?php echo $data['ad_name']; ?>" />
                                                            <span class="require-field">*</span>
                                    </td>
            </tr>
                        
                        <tr>
                <td class="label">是否启用：</td>
                <td>
                	<input <?php if($data['is_on'] == '是') echo 'checked="checked"'; ?> type="radio" name="is_on" maxlength="60" value="是" />是
                	<input <?php if($data['is_on'] == '否') echo 'checked="checked"'; ?> type="radio" name="is_on" maxlength="60" value="否" />否                	                                        <span class="require-field">*</span>
                                    </td>
            </tr>
                        <tr>
                <td class="label">广告类型：</td>
                <td><?php echo $data['ad_type']; ?></td>
            </tr>
            	<tr>
            	<td colspan="2"><hr /></td></tr>
                        <tr <?php if($data['ad_type'] != '图片') echo 'style="display:none;"'; ?> class="imgad">
                <td class="label">图片的地址：</td>
                <td>
                	<img src="<?php echo IMG_URL . $data['img_url']; ?>" width="150" /><br />
                	                   		<input type="file" name="img_url" />
                                    </td>
            </tr>
                        <tr <?php if($data['ad_type'] != '图片') echo 'style="display:none;"'; ?> class="imgad">
                <td class="label">链接地址：</td>
                <td>
                	                   		<input size="60" type="text" name="link" maxlength="60" value="<?php echo $data['link']; ?>" />
                                    </td>
            </tr>
            <tr id="cartoon" <?php if($data['ad_type'] != '动画') echo 'style="display:none;"'; ?>>
                <td class="label">动画的图片信息：</td>
                <td>
                	<input id="addimg" type="button" value="再来一个" /><br />
                	<?php foreach ($aiData as $k => $v): ?>
                	图片：<img src="<?php echo IMG_URL . $v['img_url']; ?>" width="150" /><input type="file" name="old_cartoon_img[]" /><br />
               		链接:<input size="60" type="text" name="old_cartoon_link[<?php echo $v['id']; ?>]" value="<?php echo $v['link']; ?>" /><hr />
               		<?php endforeach; ?>
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
$("#addimg").click(function(){
	var td = $(this).parent();
	td.append('图片：<input type="file" name="cartoon_img[]" /><br />链接:<input size="60" type="text" name="cartoon_link[]" /><hr />');
});
</script>