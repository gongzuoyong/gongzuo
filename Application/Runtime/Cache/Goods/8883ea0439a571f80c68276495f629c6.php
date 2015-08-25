<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 修改商品 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/zj/Public/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/zj/Public/Styles/main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/zj/Public/Js/jquery-1.4.2.min.js"></script>

<script type="text/javascript" charset="utf-8" src="/zj/Public/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/zj/Public/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/zj/Public/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/zj/Public/ueditor/btn_config.js"></script>


</head>
<body>
<h1>
    <span class="action-span"><a href="/zj/index.php/Goods/Goods/lst">商品列表</a>
    </span>
    <span class="action-span1"><a href="__GROUP__">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 修改商品 </span>
    <div style="clear:both"></div>
</h1>

<div class="tab-div">
    <div id="tabbar-div">
        <p>
            <span class="tab-front">基本信息</span>
            <span class="tab-back">商品描述</span>
            <span class="tab-back">会员价格</span>
            <span class="tab-back">商品属性</span>
            <span class="tab-back">商品图片</span>
        </p>
    </div>
    <div id="tabbody-div">
        <form enctype="multipart/form-data" action="/zj/index.php/Goods/Goods/save/id/10" method="post">
        	<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
        	<input type="hidden" name="ologo" value="<?php echo $data['logo']; ?>" />
        	<input type="hidden" name="osm_logo" value="<?php echo $data['sm_logo']; ?>" />
        	<input type="hidden" name="omid_logo" value="<?php echo $data['mid_logo']; ?>" />
        	<input type="hidden" name="obig_logo" value="<?php echo $data['big_logo']; ?>" />
            <table width="90%" class="goods_table" align="center">
            	<tr>
                    <td class="label">商品logo：</td>
                    <td>
                    	<img src="<?php echo IMG_URL . $data['mid_logo']; ?>" /><br />
                        <input type="file" name="logo" size="35" />
                    </td>
                </tr>
                <tr>
                    <td class="label">商品名称：</td>
                    <td><input type="text" name="goods_name" value="<?php echo $data['goods_name']; ?>"size="60" />
                    <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">商品分类：</td>
                    <td>
                        <select name="cat_id">
                            <option value="0">请选择...</option>
                            <?php foreach ($catData as $k => $v): if($data['cat_id'] == $v['id']) $select = 'selected="selected"'; else $select = ''; ?>
                            <option <?php echo $select; ?> value="<?php echo $v['id']; ?>"><?php echo str_repeat('-', $v['level']*8).$v['cat_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">商品品牌：</td>
                    <td>
                        <select name="brand_id">
                            <option value="0">请选择...</option>
                            <?php foreach ($brandData as $k => $v): if($data['brand_id'] == $v['id']) $select = 'selected="selected"'; else $select = ''; ?>
                            <option <?php echo $select; ?> value="<?php echo $v['id']; ?>"><?php echo $v['brand_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label">市场售价：</td>
                    <td>
                        <input type="text" name="market_price" value="<?php echo $data['market_price']; ?>" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">本店售价：</td>
                    <td>
                        <input type="text" name="shop_price" value="<?php echo $data['shop_price']; ?>" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <input <?php if($data['is_on_sale'] == '是') echo 'checked="checked"'; ?> type="radio" name="is_on_sale" value="是" /> 是
                        <input <?php if($data['is_on_sale'] == '否') echo 'checked="checked"'; ?> type="radio" name="is_on_sale" value="否" /> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">重量：</td>
                    <td>
                        <input type="text" name="goods_weight" value="<?php echo $data['goods_weight']; ?>" />
                        <select name="weight_unit">
                        	<option <?php if($data['weight_unit'] == '克') echo 'selected="selected"'; ?> value="克">克</option>
                        	<option <?php if($data['weight_unit'] == '千克') echo 'selected="selected"'; ?> value="千克">千克</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label">推荐到：</td>
                    <td>
                    	<?php foreach ($recData as $k => $v): if(strpos(','.$recId.',', ','.$v['id'].',') !== FALSE) $check = 'checked="checked"'; else $check = ''; ?>
                        <input <?php echo $check; ?> type="checkbox" name="rec[]" value="<?php echo $v['id']; ?>" /><?php echo $v['rec_name']; ?>
                    	<?php endforeach; ?>
                    </td>
                </tr>
            </table>
            <!-- 商品描述 -->
            <table class="goods_table" width="100%" style="display:none;" align="center">
            	<tr><td>
            	<textarea id="goods_desc" name="goods_desc"><?php echo $data['goods_desc']; ?></textarea>
            	</td></tr>
            </table>
            <!-- 会员价格 -->
            <table class="goods_table" width="90%" style="display:none;" align="center">
            <?php foreach ($mlData as $k => $v): $_price = ''; foreach ($mpData as $k1 => $v1) { if($v['id'] == $v1['level_id']) { $_price = $v1['price']; break ; } } ?>
            	<tr>
            		<td width="80"><?php echo $v['level_name']; ?>：</td>
            		<td align="left">￥<input type="text" value="<?php echo $_price; ?>" name="mp[<?php echo $v['id']; ?>]" />元</td>	
            	</tr>
            <?php endforeach; ?>
            </table>
            <!-- 商品属性 -->
            <table class="goods_table" width="90%" style="display:none;" align="center">
            	<tr><td>
            	<!-- 这里循环输出所有的商品类型制作 一个下拉框，如果之前有商品类型（type_id!=0)那么就设置为不允许修改，如果之前没有类型（type_id=0)才可以修改 -->
            	<select name="type_id" <?php if($data['type_id'] != 0) echo 'disabled="disabled"'; ?>>
            		<option value="">选择商品类型</option>
            	<?php foreach ($typeData as $k => $v): if($data['type_id'] == $v['id']) $select = 'selected="selected"'; else $select = ''; ?>
            		<option <?php echo $select; ?> value="<?php echo $v['id']; ?>"><?php echo $v['type_name']; ?></option>
            	<?php endforeach; ?>
            	</select></td></tr>
            	<!-- 下面输出这个类型下所有的属性 -->
            	<tr><td id="attrTd">
            		<?php  if($attrData): foreach ($attrData as $k => $v): if($v['attr_type'] == '单选'): $_attr = explode(',', $v['attr_values']); foreach ($gaData[$v['id']] as $k1 => $v1): ?>
            				<!-- 把商品属性表的ID放到span -->
            				<span gaid="<?php echo $v1['id']; ?>">
            					<!-- 输出这个属性的名称 -->
            					<?php echo $v['attr_name']; ?>
            					<!-- 输出一个+与并判断，如果是第一个属性就是+号，否则是-号 -->
	            				<a href='#' onclick='addRow(this);'>[<?php if($k1 == 0) echo '+';else echo '-'; ?>]</a>
	            				<!-- 输出这个属性可选值的下拉框 -->
	            				<select name="old_goods_attr[<?php echo $v['id']; ?>][]">
	            					<option>请选择</option>
	            					<?php foreach ($_attr as $k2 => $v2): if($v2 == $v1['attr_value']) $select = 'selected="selected"'; else $select = ''; ?>
	            						<option <?php echo $select; ?> value="<?php echo $v2; ?>"><?php echo $v2; ?>
	            					<?php endforeach; ?>
	            				</select>
	            				<!-- 输出这个属性的属性价格框 -->
	            				￥<input name="old_attr_price[<?php echo $v1['id']; ?>]" type="text" value="<?php echo $v1['attr_price']; ?>" />元
            				<br /></span>
            				<?php endforeach; ?>
            			<?php  else: ?>
            				<!-- 输出属性的名称 -->
            				<?php echo $v['attr_name']; ?>
            				<?php  if(!$v['attr_values']): ?>
            					<input name="old_goods_attr[<?php echo $v['id']; ?>]" type="text" value="<?php echo $gaData[$v['id']][0]['attr_value']; ?>" />
            					￥<input name="old_attr_price[<?php echo $gaData[$v['id']][0]['id']; ?>]" type="text" value="<?php echo $gaData[$v['id']][0]['attr_price']; ?>" />元<br />
            				<?php  else: $_attr = explode(',', $v['attr_values']); ?>
            					<select name="old_goods_attr[<?php echo $v['id']; ?>]">
	            					<option>请选择</option>
	            					<?php foreach ($_attr as $k1 => $v1): if($v1 == $gaData[$v['id']][0]['attr_value']) $select = 'selected="selected"'; else $select = ''; ?>
	            						<option <?php echo $select; ?> value="<?php echo $v1; ?>"><?php echo $v1; ?>
	            					<?php endforeach; ?>
	            				</select>￥<input name="old_attr_price[<?php echo $gaData[$v['id']][0]['id']; ?>]" type="text" value="<?php echo $gaData[$v['id']][0]['attr_price']; ?>" />元<br />
            				<?php endif; ?>
            			<?php endif; ?>
            		<?php endforeach;endif; ?>
            	</td></tr>
            </table>
            <!-- 商品图片 -->
            <table id="table_img" class="goods_table" width="90%" style="display:none;" align="center">
            	<tr><td><input type="button" id="btn_add_img" value="再来一张" /></td></tr>
            	<tr><td>
            	<ul>
            	<?php foreach ($gpData as $k => $v): ?>
            		<li pid="<?php echo $v['id']; ?>" style="float:left;list-style-type:none;margin:5px;">
            		<img width="180" src="<?php echo IMG_URL . $v['big_logo']; ?>" /><br />
            		<a href="#" onclick="delLi(this);">[-]</a>
            		</li>
            	<?php endforeach; ?>
            	</ul>
            	</td></tr>
            	<tr><td><input type="file" name="goods_pics[]" /></td></tr>
            </table>
            <div class="button-div">
                <input type="submit" value=" 确定 " class="button"/>
                <input type="reset" value=" 重置 " class="button" />
            </div>
        </form>
    </div>
</div>

<div id="footer">
共执行 9 个查询，用时 0.025161 秒，Gzip 已禁用，内存占用 3.258 MB<br />
版权所有 &copy; 2005-2012 上海商派网络科技有限公司，并保留所有权利。</div>
</body>
</html>
<script>
$("#tabbar-div span").click(function(){
	// 获取点击的是第几个按钮
	var i = $(this).index();
	$(".goods_table").hide();
	// 显示第i个table
	$(".goods_table").eq(i).show();
	// 先把当前选中的按钮变为未选中
	$("#tabbar-div span.tab-front").removeClass("tab-front").addClass("tab-back");
	// 设置当前按钮为选中状态
	$(this).removeClass("tab-back").addClass("tab-front");
});

// 把id=goods_desc的textarea替换在一个在线编辑器
UE.getEditor('goods_desc', {
	toolbars : btn_file,
	initialFrameWidth: "100%",
	initialFrameHeight: "500"
});

$("#btn_add_img").click(function(){
	$("#table_img").append('<tr><td><input type="file" name="goods_pics[]" /></td></tr>');
});

// AJAX获取一个类型的属性
$("select[name=type_id]").change(function(){
	// 获取类型id
	var type_id = $(this).val();
	if(type_id == "")
	{
		$("#attrTd").html("");
		return false;
	}
	// ajax获取这个类型的属性
	$.ajax({
		type : "GET",
		url : "/zj/index.php/Goods/Goods/ajaxGetAttr/type_id/"+type_id,
		dataType : "json",
		success : function(data)
		{
			// 循环每一个属性放到表单中
			var html = "";
			$(data).each(function(k,v){
				// 带+号的下拉框
				if(v.attr_type == "单选")
				{
					html += "<span>";
					html += v.attr_name+":";
					// 可选值转化成一个数组
					var _attr = v.attr_values.split(",");
					html += "<a href='#' onclick='addRow(this);'>[+]</a><select name='goods_attr["+v.id+"][]'><option>请选择</option>";
					for(var i=0; i<_attr.length; i++)
					{
						html += "<option>"+_attr[i]+"</option>";
					}
					html += "</select> ￥<input type='text' name='attr_price[]' value='0' />元<br /></span>";
				}
				else
				{
					// 如果有可选值就做一个下拉框
					// 文本框
					if(v.attr_values == "")
						html += v.attr_name+":<input type='text' name='goods_attr["+v.id+"]' /> ￥<input name='attr_price[]' type='text' value='0' />元<br />";
					else
					{
						// 不带+号的下拉框
						html += v.attr_name+":";
						// 可选值转化成一个数组
						var _attr = v.attr_values.split(",");
						html += "<select name='goods_attr["+v.id+"]'><option>请选择</option>";
						for(var i=0; i<_attr.length; i++)
						{
							html += "<option>"+_attr[i]+"</option>";
						}
						html += "</select> ￥<input name='attr_price[]' type='text' value='0' />元<br />";
					}
				}
			});
			$("#attrTd").html(html);
		}
	});
});

function addRow(o)
{
	// 先获取a标签所在的span
	var span = $(o).parent();
	if($(o).html() == "[+]")
	{
		var newspan = span.clone();
		// 克隆出来的新行名字中去掉old_
		var sel = newspan.find("select");
		// 取出原名并去掉old_
		var oldname = sel.attr('name');
		oldname = oldname.replace('old_', '');
		// 把新名字设置上
		sel.attr('name', oldname);
		newspan.find(":text").attr('name', 'attr_price[]');
		newspan.find('a').html("[-]");
		span.after(newspan);
	}
	else
	{
		if(confirm("确定要删除吗？"))
		{
			// 获取Span标签上的gaid属性，就是商品属性id
			var gaid = span.attr('gaid');
			// 调用ajax删除数据库中的属性
			$.ajax({
				type : "GET",
				url : "/zj/index.php/Goods/Goods/ajaxDelGoodsAttr/gaid/"+gaid,
				success : function(data)
				{
					// ajax成功之后，再从页面上删除
					span.remove();
				}
			});
		}
	}
}

function delLi(o)
{
	if(confirm('确定要删除吗'))
	{
		var li = $(o).parent();
		// 获取要删除的图片的id
		var pid = li.attr('pid');
		$.ajax({
			type : "GET",
			url : "/zj/index.php/Goods/Goods/ajaxDelPic/pid/"+pid,
			success : function(data)
			{
				// ajax成功之后，再从页面上删除图片
				li.remove();
			}
		});
	}
}
</script>