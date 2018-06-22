
<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('UserAccount','add','&act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dl>
						<dt>用户：</dt>
						<dd>
							<input type="text"  class="required" name="user.real_name" value="" lookupGroup="user" readonly/>
							<input type="hidden"  class="" name="user.id" value="" lookupGroup="user" />

							<a class="btnLook" href="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" lookupGroup="user">选择用户</a>
						</dd>
					</dl>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>金额：</dt>
					<dd>
						<input type="text" name="amount" class="required number" placeholder="0.00" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>订单号：</dt>
					<dd>
						<input type="text" name="order_sn" class="required alphanumeric" minlength="6" maxlength="20" placeholder="订单号" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>描述：</dt>
					<dd>
						<textarea autofocus required cols="1" rows="1" maxlength="100" name="desciption" style="height: 18px;width: 250px;resize: none;"></textarea>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>入库方式：</dt>
					<dd>
						<select name="in_type" class="required">
							<option value="">请选择类型</option>
							<?php foreach($InStatus as $k=>$v){?>
								<option value="<?php echo $k;?>"><?php echo $v;?></option>
							<?php } ?>
						</select>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>通道：</dt>
					<dd>
						<select name="channel" class="required">
							<option value="">请选择类型</option>
							<?php foreach($channel as $k=>$v){?>
								<option value="<?php echo $k;?>" ><?php echo $v['name'];?></option>
							<?php } ?>
						</select>
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