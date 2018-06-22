<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('SystemMessageTP')?>" method="post">
		<div class="searchBar">
			<ul class="searchContent">
				<li><label>查找标题：</label> <input type="text" name="title" value="<?=\Core\Lib::request('title')?>" /></li>			<li>
                    <label>消息类型：</label>
                    <select name="message_type">
                        <option value="">全部&nbsp;</option>
                        <?php foreach($message_type as $k=>$v){?>
                            <option value="<?php echo $k;?>" <?php if(\Core\Lib::request('message_type')==$k){echo 'selected';}?>><?php echo $v;?></option>
                        <?php } ?>
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
			<li><a class="add" rel="articleAdd" title="添加信息" href="<?=\Core\Lib::getUrl('SystemMessageTP', 'add');?>" target="dialog" width="800" height="600"><span>添加</span></a></li>
			<li><a class="edit"  title="编辑信息" rel="articleEdit" href="<?=\Core\Lib::getUrl('SystemMessageTP', 'edit','id={id}');?>" target="dialog"  width="800" height="600"><span>编辑</span></a></li>
			<li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('SystemMessageTP', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			<li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="<?=\Core\Lib::getUrl('SystemMessageTP','delAll');?>" class="delete"><span>批量删除</span></a></li>			
		</ul>
	</div>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
				<th width="30" align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
				<th width="60" align="center" orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">编号</th>
				<th width="260" align="center" orderField="title" class="<?=($data['orderField']=='title')?$data['orderDirection']:'';?>">标题</th>	
				<th align="center" orderField="content" class="<?=($data['orderField']=='content')?$data['orderDirection']:'';?>">模板描述</th>	
				<th align="center" orderField="status" class="<?=($data['orderField']=='status')?$data['orderDirection']:'';?>">状态</th>
				<th width="150" align="center" orderField="create_time" class="<?=($data['orderField']=='create_time')?$data['orderDirection']:'';?>">添加时间</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
				<td align="center"><?=$v['id']?></td>
				<td align="center"><?=$v['title']?></td>	
				<td align="center"><?=$v['describe']?></td> 
                <td align="center"><?=($v['message_type']==1)?'<span style="color:#00acec">启用</span>':'<span style="color:red">禁用'.'</span>'?></td>
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
