<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 添加新商品 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Styles/main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="/Public/Js/jquery-1.4.2.min.js"></script>

<script type="text/javascript" charset="utf-8" src="/Public/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/Public/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/ueditor/btn_config.js"></script>


</head>
<body>
<h1>
    <span class="action-span"><a href="/index.php/Goods/Goods/lst">商品列表</a>
    </span>
    <span class="action-span1"><a href="__GROUP__">ECSHOP 管理中心</a></span>
    <span id="search_id" class="action-span1"> - 添加新商品 </span>
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
        <form enctype="multipart/form-data" action="/index.php/Goods/Goods/add" method="post">
            <table width="90%" class="goods_table" align="center">
            	<tr>
                    <td class="label">商品logo：</td>
                    <td>
                        <input type="file" name="logo" size="35" />
                    </td>
                </tr>
                <tr>
                    <td class="label">商品名称：</td>
                    <td><input type="text" name="goods_name" value=""size="60" />
                    <span class="require-field">*</span></td>
                </tr>
                <tr>
                    <td class="label">商品分类：</td>
                    <td>
                        <select name="cat_id">
                            <option value="0">请选择...</option>
                            <?php foreach ($catData as $k => $v): ?>
                            <option value="<?php echo $v['id']; ?>"><?php echo str_repeat('-', $v['level']*8).$v['cat_name']; ?></option>
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
                            <?php foreach ($brandData as $k => $v): ?>
                            <option value="<?php echo $v['id']; ?>"><?php echo $v['brand_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label">市场售价：</td>
                    <td>
                        <input type="text" name="market_price" value="0.00" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">本店售价：</td>
                    <td>
                        <input type="text" name="shop_price" value="0.00" size="20"/>
                        <span class="require-field">*</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">是否上架：</td>
                    <td>
                        <input checked="checked" type="radio" name="is_on_sale" value="是" /> 是
                        <input type="radio" name="is_on_sale" value="否" /> 否
                    </td>
                </tr>
                <tr>
                    <td class="label">重量：</td>
                    <td>
                        <input type="text" name="goods_weight" />
                        <select name="weight_unit">
                        	<option value="克">克</option>
                        	<option value="千克">千克</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="label">推荐到：</td>
                    <td>
                    	<?php foreach ($recData as $k => $v): ?>
                        <input type="checkbox" name="rec[]" value="<?php echo $v['id']; ?>" /><?php echo $v['rec_name']; ?>
                    	<?php endforeach; ?>
                    </td>
                </tr>
            </table>
            <!-- 商品描述 -->
            <table class="goods_table" width="100%" style="display:none;" align="center">
            	<tr><td>
            	<textarea id="goods_desc" name="goods_desc"></textarea>
            	</td></tr>
            </table>
            <!-- 会员价格 -->
            <table class="goods_table" width="90%" style="display:none;" align="center">
            <?php foreach ($mlData as $k => $v): ?>
            	<tr>
            		<td width="80"><?php echo $v['level_name']; ?>：</td>
            		<td align="left">￥<input type="text" name="mp[<?php echo $v['id']; ?>]" />元</td>	
            	</tr>
            <?php endforeach; ?>
            </table>
            <!-- 商品属性 -->
            <table class="goods_table" width="90%" style="display:none;" align="center">
            	<tr><td><select name="type_id">
            		<option value="">选择商品类型</option>
            	<?php foreach ($typeData as $k => $v): ?>
            		<option value="<?php echo $v['id']; ?>"><?php echo $v['type_name']; ?></option>
            	<?php endforeach; ?>
            	</select></td></tr>
            	<tr><td id="attrTd"></td></tr>
            </table>
            <!-- 商品图片 -->
            <table id="table_img" class="goods_table" width="90%" style="display:none;" align="center">
            	<tr><td><input type="button" id="btn_add_img" value="再来一张" /></td></tr>
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
		url : "/index.php/Goods/Goods/ajaxGetAttr/type_id/"+type_id,
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
		newspan.find('a').html("[-]");
		span.after(newspan);
	}
	else
		span.remove();
}
</script>