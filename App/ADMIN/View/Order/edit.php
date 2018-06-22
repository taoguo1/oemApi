
<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('Order','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
		<div class="pageFormContent" layoutH="56">	
			<div class="unit nowrap">
				<dl>
					<dt>订单号：</dt>
					<dd>
						<input type="text"readonly name="order_sn" placeholder="" style="width:600px;" value="<?php echo $list['order_sn']?>" />	
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>用户ID：</dt>
					<dd>
						<input type="text" readonly name="user_id" placeholder="" style="width:600px;" value="<?php echo $list['user_id']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>金额：</dt>
					<dd>
						<input type="text" class="required number"name="amount" placeholder="金额" value="<?php echo $list['amount']?>" />
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
					<dt>银行ID：</dt>
					<dd>
						<input type="text"class="required digits" name="bank_id" placeholder="银行ID" value="<?php echo $list['bank_id']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>卡号：</dt>
					<dd>
						<input type="text"class="required number creditcard" name="card_no" placeholder="卡号" value="<?php echo $list['card_no']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>商品ID：</dt>
					<dd>
						<input type="text"class="required digits" name="goods_id" placeholder="" value="<?php echo $list['goods_id']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>商品数量：</dt>
					<dd>
						<input type="text"class="required digits" name="goods_quantity" placeholder="" value="<?php echo $list['goods_quantity']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>收货人姓名：</dt>
					<dd>
						<input type="text"class="required" maxlength="20" size="30" name="receive_name" placeholder="" value="<?php echo $list['receive_name']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>收货人地址：</dt>
					<dd>
						<input type="text"class="required" name="receive_address" placeholder="" value="<?php echo $list['receive_address']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>收货人手机：</dt>
					<dd>
						<input type="text" class="required phone" name="receive_mobile" placeholder="" value="<?php echo $list['receive_mobile']?>" />
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
			
			<div class="unit">
				<dl>
					<dt>时间：</dt>
					<dd>
						<input type="text" name="add_time" dateFmt="yyyy-MM-dd HH:mm:ss" readonly class="required date" value="<?php echo  date('Y-m-d H:i:s')?>" />
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