<?php $dictionary = \Core\Lib::loadFile('Config/Dictionary.php');?>
<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('Banker','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
		<div class="unit">
				<dl>
					<dt>银行名称：</dt>
					<dd>
						<input type="text" name="name" placeholder="" style="width:100px;" value="<?php echo $list['name']?>" />
					</dd>
				</dl>
			</div><div class="unit">
				<dl>
					<dt>编号：</dt>
					<dd>
						<input type="text" name="description" placeholder="" style="width:100px;" value="<?php echo $list['description']?>" />
					</dd>
				</dl>
			</div>
			
			
			
			
			<div class="unit">
				<dl>
					<dt>地址：</dt>
					<dd>
						<input type="text" name="url" placeholder="" style="width:100px;" value="<?php echo $list['url']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<label>图片：</label>
							<input type="text" id="bank_pic" placeholder="点击上传图片" style="width:200px;"value="<?php echo $list['img']?>" name="img" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','id=bank_pic&path=bank')?>')" />
							<span style="line-height: 24px; padding-left: 10px;" onclick="$('#bank_pic').val('');$('#bank_pic_img').attr('src','')">清除</span>
						</td>
						<td width="60" align="center">
							<img src="" id="article_pic_img" name="img" onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" />
						</td>
					</tr>
				</table>
			</div>
			  <div class="unit">
                <dl>
                    <dt>是否显示：</dt>
                    <dd>
                        <select name="isDisplay" class="required" value="<?php echo $list['isDisplay']?>">
                            <option value="">请选择类型</option>
                            <?php
                            foreach ($dictionary['userPush'] as $k=>$v) {
                                ?>
                                <option value="<?php echo $k ;?>"<?php if($list['isDisplay']==$k){echo 'selected';}?>><?php echo $v ;?></option>
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
