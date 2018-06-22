<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('Insure','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>标题：</dt>
					<dd>
						<input type="text" style="width:220px;" name="title" class="required" value="<?php echo $list['title'];?>" placeholder="请输入标题，最大不能超过200个字符" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>保险来源</dt>
					<dd>
						<input type="text" name="article_source" class="required" value="<?php echo $list['article_source'];?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<label>图片地址：</label>
							<input type="text" id="Insure" placeholder="点击上传图片" style="width:280px;" name="pic" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','id=Insure&path=Insure')?>')" value="<?php echo $list['pic']; ?>" />
							<span style="line-height: 24px; padding-left: 10px;" onclick="$('#Insure').val('');$('#Insure_pic_img').attr('src','')">清除</span>
						</td>
						<td width="60" align="center">
							<img src="<?php echo OSS_ENDDOMAIN.'/'.$list['pic'];?>" id="Insure_pic_img" onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" />
						</td>
					</tr>
				</table>
			</div>
			<div class="unit">
				<dl>
					<dt>状态：</dt>
					<dd>
						<select name="status" class="required combox">
							<option <?php if($list['status']==1){echo 'selected';}?> value='1'>正常</option>
							<option <?php if($list['status']==-1){echo 'selected';}?> value='-1'>禁用</option>
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
			<div class="unit">
				<dl>
					<dt>备注</dt>
					<dd>
						<input type="text" name="remarks" class="required" value="<?php echo $list['remarks'];?>" />
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