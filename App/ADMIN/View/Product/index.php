<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('product')?>" method="post">
		<div class="searchBar">
			<ul class="searchContent">
				<li>
					<label>分类：</label>
					<select name="category_id">
							<option value="">全部分类</option>
							<?php echo $listOptionStr;?>
						</select>
				</li>
				<li><label>标题：</label> <input type="text" name="title" value="<?=\Core\Lib::request('title')?>" /></li>
				
				<li><label>时间：</label> 
				<input type="text" class="date" size="10" name="start_date" value="<?=\Core\Lib::request('start_date')?>" />
				至 
				<input type="text" class="date" size="10" name="end_date" value="<?=\Core\Lib::request('end_date')?>" />
				</li>
				
				<li>
					<label>推荐：</label>
					<select name="recommend_level">
							<option value="">全部</option>
							<option value="-1" <?php if(\Core\Lib::request('recommend_level')==-1){echo 'selected';}?>>不推荐</option>
							<option value="1" <?php if(\Core\Lib::request('recommend_level')==1){echo 'selected';}?>>推荐①</option>
							<option value="2" <?php if(\Core\Lib::request('recommend_level')==2){echo 'selected';}?>>推荐②</option>
							<option value="3" <?php if(\Core\Lib::request('recommend_level')==3){echo 'selected';}?>>推荐③</option>
							<option value="4" <?php if(\Core\Lib::request('recommend_level')==4){echo 'selected';}?>>推荐④</option>
							<option value="5" <?php if(\Core\Lib::request('recommend_level')==5){echo 'selected';}?>>推荐⑤</option>
						</select>
				</li>
				<li>
					<div class="buttonActive">
						<div class="buttonContent">
							<button type="submit">查询</button>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</form>
</div>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" rel="productAdd" title="添加产品" href="<?=\Core\Lib::getUrl('Product', 'add','cid='.$cid.'');?>" target="navTab"><span>添加</span></a></li>
			<li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Product', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			<li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="<?=\Core\Lib::getUrl('Product','delAll');?>" class="delete"><span>批量删除</span></a></li>
			<li><a class="edit"  title="编辑产品" rel="productEdit" href="<?=\Core\Lib::getUrl('Product', 'edit','id={id}');?>" target="navTab"><span>编辑</span></a></li>
		</ul>
	</div>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
				<th width="30" align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
				<th width="60" align="center" orderField="A.id" class="<?=($data['orderField']=='A.id')?$data['orderDirection']:'';?>">编号</th>
                <th width="70" align="center" orderField="A.pic" class="<?=($data['orderField']=='A.pic')?$data['orderDirection']:'';?>">缩略图</th>
				<th  align="left" width="100" orderField="title"  class="<?=($data['orderField']=='title')?$data['orderDirection']:'';?>">标题</th>
				<th align="center" width="100" orderField="category_id"  class="<?=($data['orderField']=='category_id')?$data['orderDirection']:'';?>">所属分类</th>
				<th width="80" orderField="recommend_level" align="center" class="<?=($data['orderField']=='recommend_level')?$data['orderDirection']:'';?>">推荐级别</th>
				<th width="60" orderField="A.sort" align="center" class="<?=($data['orderField']=='A.sort')?$data['orderDirection']:'';?>">排序</th>
				<th width="60" orderField="A.click_number" align="center" class="<?=($data['orderField']=='A.click_number')?$data['orderDirection']:'';?>">点击量</th>
				<th width="120" align="center" orderField="last_update_time" class="<?=($data['orderField']=='last_update_time')?$data['orderDirection']:'';?>">最后更新</th>
				
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
				<td align="center"><?=$v['id']?></td>
				<td align="center"><img width="60" height="60" src="<?=OSS_ENDDOMAIN.'/'.$v['pic']?>" style="margin:5px 0;"></td>
                <td align="left"><?=$v['title']?></td>
				<td align="center"><?=$v['category_name']?></td>
				<td align="center"><?=($v['recommend_level']==-1)?'无':'<font style="color:red">推荐'.$v['recommend_level'].'</font>'?></td>
				<td align="center"><?=$v['sort']?></td>
				<td align="center"><?=$v['click_number']?></td>
				<td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['last_update_time']);?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<div class="panelBar">
		<div class="pages">
			<span>显示</span> <select class="combox" name="numPerPage"
				onchange="navTabPageBreak({numPerPage:this.value})">
				<option value="25" <?php if($data['numPerPage']=='25'){echo 'selected';}?>>25</option>
				<option value="50" <?php if($data['numPerPage']=='50'){echo 'selected';}?>>50</option>
				<option value="100" <?php if($data['numPerPage']=='100'){echo 'selected';}?>>100</option>
				<option value="200" <?php if($data['numPerPage']=='200'){echo 'selected';}?>>200</option>
			</select> <span>条，共<?php echo $data['totalCount']?>条</span>
		</div>
		<div class="pagination" targetType="navTab" totalCount="<?php echo $data['totalCount']?>" numPerPage="<?php echo $data['numPerPage']?>" pageNumShown="10" currentPage="<?php echo $data['pageNum']?>"></div>
	</div>
</div>
