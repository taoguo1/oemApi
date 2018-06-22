<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('Plan','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
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
                    <dt>当前状态：</dt>
                    <dd>
                        <select class="required" name="status">
                            <option <?php if($list['status']==1){echo 'selected';}?> value='1' >未执行</option>
                            <option <?php if($list['status']==2){echo 'selected';}?> value='2'>进行中</option>
                             <option <?php if($list['status']==3){echo 'selected';}?> value='3' >已完成</option>
                            <option <?php if($list['status']==4){echo 'selected';}?> value='4'>暂停</option>
                             <option <?php if($list['status']==5){echo 'selected';}?> value='5' >成功</option>
                            <option <?php if($list['status']==6){echo 'selected';}?> value='6'>失败</option>
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