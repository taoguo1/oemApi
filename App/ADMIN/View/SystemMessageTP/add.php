
<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('SystemMessageTP','add','&act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>标题：</dt>
					<dd>
						<input type="text" style="width:350px;" name="title" class="required" value="" placeholder="请输入标题，最大不能超过200个字符" />
					</dd>
				</dl>
			</div>        
            <div class="unit nowrap">
				<dl>
					<dt>描述：</dt>
					<dd>
						<textarea name="describe" placeholder="请输入描述" style="width:580px;" rows="3"></textarea>	
					</dd>
				</dl>
			</div>
			<div class="unit">
                <dl>
                    <dt>状态：</dt>
                    <dd>
                        <select name="message_type" class="required"style="width: 80px;">
                            <option value="1">启用</option>
                            <option value="2">禁用</option>                            
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit nowrap">
				<dl>
					<dt>内容：</dt>
					<dd>
					<textarea class="editor" name="content" rows="20" cols="70"
						upLinkUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorFile','path=ArticleEditorFile')?>" upLinkExt="zip,rar,txt,pdf,ppt,doc,docx,xls,xlsx,pptx" 
						upImgUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditor','path=ArticleEditor')?>" upImgExt="jpg,jpeg,gif,png,bmp" 
						upFlashUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorSwf','path=ArticleEditorSwf')?>" upFlashExt="swf"
						upMediaUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorMedia','path=ArticleEditorMedia')?>" upMediaExt:"avi,mp4"></textarea>	
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