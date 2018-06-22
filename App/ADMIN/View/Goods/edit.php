<div class="pageContent">
	<form method="post" action="<?=\Core\Lib::getUrl('goods','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
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
							<option <?php if($list['category_id']==$v['id']){echo 'selected';}?> value="<?php echo $v['id']?>"><?php echo $v['category_name']?></option>
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
						<input type="text" style="width:600px;" name="goods_name" class="required" value="<?php echo $list['goods_name']?>" placeholder="请输入标题，最大不能超过200个字符" />
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
