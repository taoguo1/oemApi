<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('ProductCategory','add','act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>上级分类：</dt>
					<dd>
						<select name="pid" class="required">
							<option value="0">顶级分类</option>
							<?php echo $listOptionStr;?>
						</select>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>分类名称：</dt>
					<dd>
						<input type="text" name="name" class="required" value="" />
					</dd>
				</dl>
			</div>

			<div class="unit">
				<dl>
					<dt>别名：</dt>
					<dd>
						<input type="text" name="alias" id="add_alias" value="" />
					</dd>
				</dl>
			</div>
			
			<div class="unit">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<label>图标：</label>
							<input type="text" id="icon" name="icon" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','path=icon')?>')" />
							<span style="line-height: 24px; padding-left: 10px;" onclick="$('#icon').val('');$('#icon_img').attr('src','')">清除</span>
						</td>
						<td width="60" align="center">
							<img src="" id="icon_img" onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" />
						</td>
					</tr>
				</table>
			</div>
			<div class="unit">
				<dl>
					<dt>状态：</dt>
					<dd>
						<select name="status" class="required combox">
							<option value="0">正常</option>
							<option value="-1">禁用</option>
						</select>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>顺序：</dt>
					<dd>
						<input type="text" name="sort" class="required" value="255" />
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