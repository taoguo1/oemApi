
<div class="pageContent">
	<form method="post" action="<?php echo \Core\Lib::getUrl('Product','add','cid='.intval($cid).'&act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, navTabAjaxDone);">
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
						<input type="text" style="width:600px;" name="title" class="required" value="" placeholder="请输入标题，最大不能超过200个字符" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<label>图片：</label>
							<input type="text" id="product_pic" placeholder="点击上传图片" style="width:600px;" name="pic" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','id=product_pic&path=product')?>')" />
							<span style="line-height: 24px; padding-left: 10px;" onclick="$('#product_pic').val('');$('#product_pic_img').attr('src','')">清除</span>
						</td>
						<td width="60" align="center">
							<img src="" id="product_pic_img" onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" />
						</td>
					</tr>
				</table>
			</div>
			
			<div class="unit nowrap">
				<dl>
					<dt>备注：</dt>
					<dd>
						<textarea name="remarks" placeholder="请输入备注信息" style="width:600px;" rows="3"></textarea>	
					</dd>
				</dl>
			</div>
			
			
			
			
			<div class="unit">
				<dl>
					<dt>seo标题：</dt>
					<dd>
						<input type="text" name="seo_title" placeholder="" style="width:600px;" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>seo关键字：</dt>
					<dd>
						<input type="text" name="article_keywords" placeholder="" style="width:600px;" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit nowrap">
				<dl>
					<dt>seo描述：</dt>
					<dd>
						<textarea name="seo_desc" placeholder="" style="width:600px;" rows="3"></textarea>	
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>信息来源：</dt>
					<dd>
						<input type="text" name="article_source" placeholder="请输入信息来源" style="width:600px;" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>推荐级别：</dt>
					<dd>
						<label style="width: auto"><input type="radio" value="-1" checked name="recommend_level" />不推荐</label>
						<label style="width: auto"><input type="radio" value="1" name="recommend_level" />推荐①</label>
						<label style="width: auto"><input type="radio" value="2" name="recommend_level" />推荐②</label>
						<label style="width: auto"><input type="radio" value="3" name="recommend_level" />推荐③</label>
						<label style="width: auto"><input type="radio" value="4" name="recommend_level" />推荐④</label>
						<label style="width: auto"><input type="radio" value="5" name="recommend_level" />推荐⑤</label>
						<label style="width: auto;color:red">说明：(推荐级别越高越靠前)</label>
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>作者：</dt>
					<dd>
						<input type="text" name="author" placeholder="请输入作者" value="" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>显示顺序：</dt>
					<dd>
						<input type="text" name="sort" class="required digits" placeholder="请输入整数排序" value="255" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>点击量：</dt>
					<dd>
						<input type="text" name="click_number" class="required digits" placeholder="请输入整数排序" value="0" />
					</dd>
				</dl>
			</div>
			<div class="unit">
				<dl>
					<dt>发布时间：</dt>
					<dd>
						<input type="text" name="add_time" dateFmt="yyyy-MM-dd HH:mm:ss" readonly class="required date" value="<?php echo  date('Y-m-d H:i:s')?>" />
					</dd>
				</dl>
			</div>
			
			
			<div class="unit nowrap">
				<dl>
					<dt>图集：</dt>
					<dd id="ins_pics_box">
						<div id="ins_pics_box1">
							<input name="pics[]" value="" id="ins_pics1" style="width: 400px;" onclick="upload_file('<?php echo Core\Lib::getUrl('upload','index','id=ins_pics1')?>')">
							<span style="font-size: 24px; padding-left:10px; color:green; cursor:pointer" onclick="insAddPicsBox()"> + </span>
						</div>	
					</dd>
				</dl>
			</div>
			
			<div class="unit nowrap">
				<dl>
					<dt>详细内容：</dt>
					<dd>
<!--<textarea class="kindeditor" name="content" rows="40" cols="120"></textarea>-->

					<textarea class="editor" name="content" rows="40" cols="120"
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