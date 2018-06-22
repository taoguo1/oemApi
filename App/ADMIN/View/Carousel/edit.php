<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('Carousel','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>标题：</dt>
					<dd>
						<input type="text" style="width:220px;" name="title" class="required" value="<?php echo $list['title'];?>" placeholder="请输入标题，最大不能超过200个字符" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<label>图片地址：</label>
							<input type="text" id="ad_link" placeholder="点击上传图片" style="width:280px;" name="ad_link" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','id=ad_link&path=ad_link')?>')" value="<?php echo $list['ad_link']; ?>" />
							<span style="line-height: 24px; padding-left: 10px;" onclick="$('#ad_link').val('');$('#article_pic_img').attr('src','')">清除</span>
						</td>
						<td width="60" align="center">
							<img src="<?php echo OSS_ENDDOMAIN.'/'.$list['ad_link'];?>" id="article_pic_img" onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" />
						</td>
					</tr>
				</table>
			</div>
			<div class="unit">
                <dl>
                    <dt>链接类型：</dt>
                    <dd>  
                        <select class="required" name="link_type">
                            <option <?php if($list['link_type']==1){echo 'selected';}?> value='1' >内部文章</option>
                            <option <?php if($list['link_type']==2){echo 'selected';}?> value='2'>外部链接</option>
                        </select>
                    </dd>
                </dl>
            </div>
			<div class="unit">
                <dl>
                    <dt>外部链接：</dt>
                    <dd>  
                        <input type="text" style="width:220px;" name="herf"  value="<?php echo $list['herf'];?>" placeholder="请输入链接，选为外部时填写" />
                    </dd>
                </dl>
            </div>
			<div class="unit">
                <dl>
                    <dt>广告类型：</dt>
                    <dd>
                        <select class="required" name="ad_type">
                            <option <?php if($list['ad_type']==1){echo 'selected';}?> value='1'>首页顶部广告</option>
                            <option <?php if($list['ad_type']==2){echo 'selected';}?> value='2'>首页底部广告</option>
                        </select>
                    </dd>
                </dl>
            </div>
			<div class="unit">
				<dl>
					<dt>文章内容：</dt>
					<dd>
					<textarea class="editor" name="content" rows="40" cols="60" 
						upLinkUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorFile','path=ArticleEditorFile')?>" upLinkExt="zip,rar,txt,pdf,ppt,doc,docx,xls,xlsx,pptx" 
						upImgUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditor','path=ArticleEditor')?>" upImgExt="jpg,jpeg,gif,png,bmp" 
						upFlashUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorSwf','path=ArticleEditorSwf')?>" upFlashExt="swf"
						upMediaUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorMedia','path=ArticleEditorMedia')?>" upMediaExt:"avi,mp4"><?php echo $list['content']?></textarea>	
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