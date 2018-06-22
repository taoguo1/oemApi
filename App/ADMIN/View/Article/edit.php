<div class="pageContent">
	<form method="post" action="<?=\Core\Lib::getUrl('Article','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<div class="unit">
				<dl>
					<dt>所属分类：</dt>
					<dd>
						<select name="category_id" class="required">
							<option value="">请选择分类</option>
							<?php echo $categoryOptionStr;?>
						</select>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>标题：</dt>
					<dd>
						<input type="text" style="width:600px;" name="title" class="required" value="<?php echo $list['title']?>" placeholder="请输入标题，最大不能超过200个字符" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<label>图片：</label>
							<input type="text" id="edit_article_pic" placeholder="点击上传图片" style="width:600px;" value="<?php echo $list['pic']?>" name="pic" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','id=edit_article_pic&path=article')?>')" />
							<span style="line-height: 24px; padding-left: 10px;" onclick="$('#edit_article_pic').val('');$('#edit_article_pic_img').attr('src','')">清除</span>
						</td>
						<td width="60" align="center">
							<img src="" id="edit_article_pic_img" onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" />
						</td>
					</tr>
				</table>
			</div>
			
			<div class="unit nowrap">
				<dl>
					<dt>备注：</dt>
					<dd>
						<textarea name="remarks" placeholder="请输入备注信息" style="width:600px;" rows="3"><?php echo $list['remarks']?></textarea>	
					</dd>
				</dl>
			</div>
			
			
			
			<div class="unit">
				<dl>
					<dt>seo标题：</dt>
					<dd>
						<input type="text" name="seo_title" placeholder="" style="width:600px;" value="<?php echo $list['seo_title']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>seo关键字：</dt>
					<dd>
						<input type="text" name="article_keywords" placeholder="" style="width:600px;" value="<?php echo $list['article_keywords']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit nowrap">
				<dl>
					<dt>seo描述：</dt>
					<dd>
						<textarea name="seo_desc" placeholder="" style="width:600px;" rows="3"><?php echo $list['seo_desc']?></textarea>	
					</dd>
				</dl>
			</div>
			
			
			
			<div class="unit">
				<dl>
					<dt>信息来源：</dt>
					<dd>
						<input type="text" name="article_source" placeholder="请输入信息来源" style="width:600px;" value="<?php echo $list['article_source']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>推荐级别：</dt>
					<dd>
						<label style="width: auto"><input type="radio" value="-1" <?php if($list['recommend_level']=='-1'){echo 'checked';}?> name="recommend_level" />不推荐</label>
						<label style="width: auto"><input type="radio" value="1" <?php if($list['recommend_level']=='1'){echo 'checked';}?> name="recommend_level" />推荐①</label>
						<label style="width: auto"><input type="radio" value="2" <?php if($list['recommend_level']=='2'){echo 'checked';}?> name="recommend_level" />推荐②</label>
						<label style="width: auto"><input type="radio" value="3" <?php if($list['recommend_level']=='3'){echo 'checked';}?> name="recommend_level" />推荐③</label>
						<label style="width: auto"><input type="radio" value="4" <?php if($list['recommend_level']=='4'){echo 'checked';}?> name="recommend_level" />推荐④</label>
						<label style="width: auto"><input type="radio" value="5" <?php if($list['recommend_level']=='5'){echo 'checked';}?> name="recommend_level" />推荐⑤</label>
						<label style="width: auto;color:red">说明：(推荐级别越高越靠前)</label>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>作者：</dt>
					<dd>
						<input type="text" name="author" placeholder="请输入作者" value="<?php echo $list['author']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>显示顺序：</dt>
					<dd>
						<input type="text" name="sort" class="required digits" placeholder="请输入整数排序" value="<?php echo $list['sort']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>点击量：</dt>
					<dd>
						<input type="text" name="click_number" class="required digits" placeholder="请输入整数排序" value="<?php echo $list['click_number']?>" />
					</dd>
				</dl>
			</div>
			<div class="unit nowrap">
				<dl>
					<dt>图集：</dt>
					<dd id="pics_box">
						<?php 
						  if(!empty($list['pics']))
						  {
						      $picsArr = explode(',', $list['pics']);
						      
						      foreach($picsArr as $k=>$v)
						      {
						          
						?>
						<div id="pics_box<?php echo ($k+1)?>">
							<input name="pics[]" value="<?php echo $v;?>" id="pics<?php echo ($k+1)?>" style="width: 400px;" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','id=pics'.($k+1))?>')">
							<?php 
							if($k==0)
							{
							   echo '<span style="font-size: 24px; padding-left:10px; color:green; cursor:pointer" onclick="editPicsBox()"> + </span>'; 
							}
							else
							{
							    echo '<span style="font-size: 24px; padding-left:10px; color:red; cursor:pointer" onclick=delPicsBox("'.($k+1).'")> - </span>';
							}
							?>
						</div>
						<?php                   
						      }
						      
						?>
						
						<?php       
						  }
						  else 
						  {
						?>
						<div id="pics_box1">
							<input name="pics[]" value="" id="pics1" style="width: 400px;" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','id=pics1')?>')">
							<span style="font-size: 24px; padding-left:10px; color:green; cursor:pointer" onclick="editPicsBox()"> + </span>
						</div>
						<?php       
						  }
						?>
						
							
					</dd>
				</dl>
			</div>
			<div class="unit nowrap">
				<dl>
					<dt>详细内容：</dt>
					<dd>
						<!-- <textarea class="kindeditor"   name="content" rows="40" cols="120"></textarea> -->
						<textarea class="editor" name="content" rows="40" cols="120"
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
			str += '<input name="pics[]" value="" id="ins_pics'+id+'" style="width: 400px;" onclick=upload_file("/admin/upload/index/ins_pics'+id+'?appid=1feb30526e31e188")>';
			str += '<span style="font-size: 24px; padding-left:10px; color:red; cursor:pointer" onclick=delPicsBox('+id+')> - </span>';
			str += '</div>';
		$("#pics_box").append(str);
	}
</script>