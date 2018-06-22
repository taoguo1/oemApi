<div class="pageContent">

	<form method="post" action="<?=\Core\Lib::getUrl('debitCard','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this,dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dl>
                        <dt>选择用户：</dt>
                        <dd>
                            <input type="text"  class="" name="user.real_name" style="width:280px;" value="<?=\Core\Lib::aesDecrypt($list['real_name'])?>" lookupGroup="user" disabled/>
                            <input type="hidden"  class="" name="user.id" value="<?php echo $list['user_id'] ?>" lookupGroup="user" />
                            <a class="btnLook" href="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" lookupGroup="user">选择用户</a>
                        </dd>
                    </dl>
                </dl>
            </div>

            <!--<div class="unit">
                <dl>
                    <dt>所属银行：</dt>
                    <dd>
                        <select name="bank_id" class="required">
                            <option value="">请选择银行</option>
                            <?php
                            foreach ($bank as $k=>$v) {
                                if($v['bank_type']==2) {
                                    ?>
                                    <option value="<?php echo $v['id'] ?>"  <?php if($list['bank_id']==$v['id']){echo 'selected';}?>><?php echo $v['name'] ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </dd>
                </dl>
            </div>-->
            
            
             <div class="unit">
                <dl>
                    <dt>所属银行：</dt>
                    <dd>
                        <select name="bank_id" class="required">
                            <option value="">请选择银行</option>
                            <?php
                            foreach ($bank as $k => $v) {
                                if($v['bank_type']==1) {
                                    ?>
                                    <option value="<?php echo $v['id'] ?>"<?php if($list['bank_name']==$v['name']){echo 'selected';}?>><?php echo $v['name'] ?></option>
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
                        <input type="text" style="width:300px;" name="card_no" class="required" value="<?=\Core\Lib::aesDecrypt($list['card_no'])?>" placeholder="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>手机号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="mobile" class="required mobile" value="<?php echo \trim(\Core\Lib::aesDecrypt($list['mobile'])) ?>" placeholder="" />
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
                                <option value="<?php echo $k ?>" <?php if($list['channel_type']==$k){echo 'selected';}?>><?php echo $v['name'] ?></option>
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
	function delPicsBox(id)
	{
		$("#pics_box"+id).remove();
	}
	function editPicsBox()
	{
		var len = $('#pics_box').children('div').length;
		var id = parseInt(len)+1;
		var str = '<div id="pics_box'+id+'">';
			str += '<input name="pics[]" value="" id="pics'+id+'" style="width: 400px;" onclick=upload_file("<?php echo \Core\Lib::getUrl('upload','index','id=pics')?>'+id+'")>';
			str += '<span style="font-size: 24px; padding-left:10px; color:red; cursor:pointer" onclick=delPicsBox('+id+')> - </span>';
			str += '</div>';
		$("#pics_box").append(str);
	}
</script>