<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('InviteCode','create','act=create');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>生成邀请码数量：</dt>
					<dd>
						<input type="text" name="quantity" class="required digits" max="1000" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>代理选择：</dt>
                    <dd>
                        <input type="text"  class="" name="agent.agent_name"  readonly value="" lookupGroup="agent" />
                        <input type="hidden"  class="" name="agent.agent_id" value="" lookupGroup="agent" />
                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('agent','parentAgent');?>" lookupGroup="agent">选择上级代理</a>
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