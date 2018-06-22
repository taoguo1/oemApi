
<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('goods','add','act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>所属分类：</dt>
					<dd>
						<select name="category_id" class="required">
							<option value="">请选择分类</option>
							<?php
							foreach ($goodsCategory as $v)
							{
							?>
							<option value="<?php echo $v['id']?>"><?php echo $v['category_name']?></option>
							<?php 
							}
							?>
						</select>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>标题：</dt>
					<dd>
						<input type="text" style="width:600px;" name="goods_name" class="required" value="" placeholder="请输入标题，最大不能超过200个字符" />
					</dd>
				</dl>
			</div>
			
			
		</div>
		<div class="formBar">
			<ul>
				<li>
					<div class="button">
						<div class="buttonContent">
							<button type="submit">保存</button>
						</div>
					</div>
				</li>
				<li>
					<div class="button">
						<div class="buttonContent">
							<button type="button" class="close">取消</button>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</form>
</div>
<script language="javascript">
    function insDelPicsBox(id)
    {
    	$("#ins_pics_box"+id).remove();
    }
	function insAddPicsBox()
	{
		var len = $('#ins_pics_box').children('div').length;
		var id = parseInt(len)+1;
		var str = '<div id="ins_pics_box'+id+'">';
			str += '<input name="pics[]" value="" id="ins_pics'+id+'" style="width: 400px;" onclick=upload_file("<?php echo \Core\Lib::getUrl('upload','index','id=ins_pics')?>'+id+'")>';
			str += '<span style="font-size: 24px; padding-left:10px; color:red; cursor:pointer" onclick="insDelPicsBox('+id+')"> - </span>';
			str += '</div>';
		$("#ins_pics_box").append(str);
	}
</script>