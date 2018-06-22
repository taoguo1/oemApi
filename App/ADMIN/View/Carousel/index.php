<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('Carousel')?>" method="post">
		<div class="searchBar">
			<ul class="searchContent">
				<li><label>标题：</label> <input type="text" name="title" value="<?=\Core\Lib::request('title')?>" /></li>
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
			<li><a class="add" width="800" height="600" rel="articleAdd" title="添加轮播信息" href="<?=\Core\Lib::getUrl('Carousel', 'add');?>" target="dialog"><span>添加</span></a></li>
			<li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Carousel', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			<li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="<?=\Core\Lib::getUrl('Carousel','delAll');?>" class="delete"><span>批量删除</span></a></li>
			<li><a class="edit" width="800" height="600"  title="编辑轮播信息" rel="articleEdit" href="<?=\Core\Lib::getUrl('Carousel', 'edit','id={id}');?>" target="dialog"><span>编辑</span></a></li>
		</ul>
	</div>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
				<th width="30" align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
				<th width="60" align="center" orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">编号</th>
				<th align="center" orderField="title" class="<?=($data['orderField']=='title')?$data['orderDirection']:'';?>">标题</th>
				<th align="center" orderField="ad_link" class="<?=($data['orderField']=='ad_link')?$data['orderDirection']:'';?>">图片</th>

				<th align="center" orderField="link_type" class="<?=($data['orderField']=='link_type')?$data['orderDirection']:'';?>">链接类型</th>
				<th align="center" orderField="ad_type" class="<?=($data['orderField']=='ad_type')?$data['orderDirection']:'';?>">广告类型</th>

				<th width="60"     orderField="sort" align="center" class="<?=($data['orderField']=='sort')?$data['orderDirection']:'';?>">排序</th>
				<th width="150" align="center" orderField="create_time" class="<?=($data['orderField']=='create_time')?$data['orderDirection']:'';?>">添加时间</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
				<td align="center"><?=$v['id']?></td>
				<td align="center"><?=$v['title']?></td>
				<td align="center">
                <?php if($v['ad_link']){ ?>
                <img width="60" height="60" src="<?=$v['ad_link']?>" style="margin:5px 0;">
                 <!-- <?php }else{ ?>
                  <img width="50" height="40" src="/Static/Admin/image/no_pic.png" style="margin:5px 0;">
                    <?php } ?> -->
                    </td>
                    <td align="center"><?php if($v['link_type']=='1'){echo '内部文章';}else{echo '外部链接';}?> </td>
                    <td align="center"><?php if($v['ad_type']=='1'){echo '首页顶部广告';}else{echo '首页底部部广告';}?> </td>
				<td align="center"><?=$v['sort']?></td>
                <td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s',$v['create_time']);?></td>
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
