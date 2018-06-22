<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('PlanList','second','id='.$list['id'].'&act=second');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>编号：</dt>
					<dd>
						<input type="text" style="width:220px;" name="id"  value="<?php echo $list['id'];?>" readonly/>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>计划id：</dt>
					<dd>
						<input type="text" style="width:220px;" name="plan_id"  value="<?php echo $list['plan_id'];?>" readonly />
					</dd>
				</dl>
			</div>
			
			<div class="unit">
                <dl>
                    <dt>金额：</dt>
                    <dd>
<input type="text" style="width:220px;" name="amount"  value="<?php echo $list['amount'];?>" readonly />
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