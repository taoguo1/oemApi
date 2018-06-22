
<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('Order','add','&act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>选择用户：</dt>
                    <dd>
                        <input type="text"  class="required" style="width:280px;" name="user.real_name" value="" lookupGroup="user" disabled/>
                        <input type="hidden"  class="required" name="user.id" value="" lookupGroup="user" />
                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" lookupGroup="user">选择用户</a>
                    </dd>
                </dl>

            </div>
			<div class="unit">
                <dl>
                    <dt>金额：</dt>
                    <dd>
                        <input type="text" class="required number" name="amount" style="width:300px;"  placeholder="0.00" value="" />
                    </dd>
                </dl>
            </div>
			<div class="unit">
                <dl>
                    <dt>类型：</dt>
                    <dd>
                        <select name="type" class="required">
                            <option value="">请选择类型</option>
                            <option value="1">还款</option>
                            <option value="2">消费</option>
                            <option value="3">提现</option>
                            <option value="4">充值</option>
                            <option value="5">卡验证</option>
                            <option value="6">余额平帐</option>
                            <option value="7">强制扣款</option>
                        </select>
                    </dd>
                </dl>
            </div>
			<div class="unit">
				<dl>
					<dt>卡类型：</dt>
					<dd>
						<label style="width: auto"><input type="radio" value="1" checked name="card_type" />信用卡</label>
						<label style="width: auto"><input type="radio" value="2" name="card_type" />储蓄卡</label>
					</dd>
				</dl>
			</div>
            <div class="unit">
                <dl>
                    <dt>所属银行：</dt>
                    <dd>
                        <select name="bank_id" class="required">
                            <option value="">请选择银行</option>
                            <?php
                            foreach ($bank as $k=>$v) {
                                if($v['bank_type']==1) {
                                    ?>
                                    <option value="<?php echo $k ?>"><?php echo $v['name'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>银行卡号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="card_no" class="required creditcard" value="" placeholder="" />
                    </dd>
                </dl>
            </div>
			<div class="unit">
				<dl>
					<dt>商品ID：</dt>
					<dd>
						<input type="text" style="width:300px;"  class="required digits" name="goods_id" placeholder="" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>商品数量：</dt>
					<dd>
						<input type="text" style="width:300px;"  class="required digits" name="goods_quantity" placeholder="" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>收货人姓名：</dt>
					<dd>
						<input type="text" class="required textInput"  name="receive_name" placeholder="" value=""  style="width:300px;"/>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>收货人地址：</dt>
					<dd>
						<input type="text"class="required" name="receive_address" placeholder="" value="" style="width:300px;"/>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>收货人手机：</dt>
					<dd>
						<input type="text" class="required mobile" name="receive_mobile" placeholder="" value="" style="width:300px;"/>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>执行状态：</dt>
					<dd>
						<label style="width: auto"><input type="radio" value="1" checked name="status" />成功</label>
						<label style="width: auto"><input type="radio" value="2" name="status" />失败</label>
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