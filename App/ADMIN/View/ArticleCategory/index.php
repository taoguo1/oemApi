<form id="pagerForm" method="post" action="<?php echo \Core\Lib::getUrl('ArticleCategory','index');?>">
	<input type="hidden" name="pageNum" value="1" /> 
	<input type="hidden" name="numPerPage" value="" /> 
	<input type="hidden" name="orderField" value="" /> 
	<input type="hidden" name="orderDirection" value="" />
</form>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" rel="ArticleCategoryAdd" href="<?php echo \Core\Lib::getUrl('ArticleCategory', 'add');?>" title="添加分类" target="dialog" width="500" height="400"><span>添加</span></a></li>
			<li><a title="确实要删除吗?" target="ajaxTodo" href="<?php echo \Core\Lib::getUrl('ArticleCategory', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			<li><a rel="ArticleCategoryEdit" class="edit" title="编辑分类" href="<?php echo \Core\Lib::getUrl('ArticleCategory', 'edit','id={id}');?>" target="dialog" width="500" height="400"><span>编辑</span></a></li>
		</ul>
	</div>
	<table class="list" width="100%" layoutH="30">
		<thead>
			<tr>
				<th width="50" align="center">编号</th>
				<th>分类名称</th>
				<th align="center">分类别名</th>


				<th width="100" align="center">图标</th>
				<th width="100" align="center">顺序</th>
				<th width="200" align="center">状态</th>
				
			</tr>
		</thead>
		<tbody>
			<?php echo $listStr;?>
		</tbody>
	</table>

</div>
