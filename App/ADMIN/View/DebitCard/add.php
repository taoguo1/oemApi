<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('debitCard','add','&act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
            <div class="unit">
                    <dl>
                        <dt>选择用户：</dt>
                        <dd>
                            <input type="text"  class="" style="width:280px;" name="user.real_name" value="" lookupGroup="user" disabled/>
                            <input type="hidden"  class="" name="user.id" value="" lookupGroup="user" />
                            <a class="btnLook" href="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" lookupGroup="user">选择用户</a>
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
                                if($v['bank_type']==2) {
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
                    <dt>手机号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="mobile" class="required mobile" value="" placeholder="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>通道类型：</dt>
                    <dd>
                        <select name="channel_type" class="required">
                            <option value="">请选择通道类型</option>
                            <?php
                            foreach ($channel as $k=>$v) {

                                    ?>
                                    <option value="<?php echo $k ?>"><?php echo $v['name'] ?></option>
                                    <?php

                            }
                            ?>
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