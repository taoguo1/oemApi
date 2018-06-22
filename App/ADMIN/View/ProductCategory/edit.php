<div class="pageContent">
	<form method="post"
		action="<?php echo \Core\Lib::getUrl('ProductCategory','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">

		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>上级菜单：</dt>
					<dd>
						<select name="pid" class="required combox">
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
						<input type="text" name="name" class="required" value="<?php echo $list['name'];?>" />
					</dd>
				</dl>
			</div>

			
			
			
			<div class="unit">
				<dl>
					<dt>别名：</dt>
					<dd>
						<input type="text" name="alias" id="edit_alias" value="<?php echo $list['alias'];?>" />
					</dd>
				</dl>
			</div>
			
			<div class="unit">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<label>图标：</label> 
							<input type="text"  id="icon" name="icon" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','path=icon')?>')" value="<?php echo $list['icon'];?>"/> 
							<span style="line-height: 24px; padding-left: 10px;" onclick="$('#icon').val('');$('#icon_img').attr('src','')">清除</span></td>
						<td width="60" align="center">
						<img src="<?php echo APP_SITE_PATH.$list['icon'];?>" id="icon_img" onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" /></td>
					</tr>
				</table>
			</div>
			<div class="unit">
				<dl>
					<dt>状态：</dt>
					<dd>
						<select name="status" class="required combox">
							<option value="0" <?php if($list['status']==0){echo 'selected';}?>>正常</option>
							<option value="-1" <?php if($list['status']==-1){echo 'selected';}?>>禁用</option>
						</select>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>顺序：</dt>
					<dd>
						<input type="text" name="sort" class="required"
							value="<?php echo $list['sort'];?>" />
					</dd>
				</dl>
			</div>


		</div>
		<div class="formBar">
			<ul>
				<li><div class="button">
						<div class="buttonContent">
							<button type="submit">保存</button>
						</div>
					</div></li>
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
