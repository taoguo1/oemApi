<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('InviteCode')?>" method="post">
		<div class="searchBar">
			<ul class="searchContent">
				<li><label>邀请码：</label> <input type="text" name="code" value="<?=\Core\Lib::request('code')?>" /></li>
                <li>
                    <label>代理昵称：</label>
                    <input type="text"  class="agent" name="agent.agent_name" value="<?=\Core\Lib::request('agent_agent_name')?>" placeholder="全部" readonly lookupGroup="agent" />
                    <input type="hidden"  class="" name="agent.agent_id" value="<?=\Core\Lib::request('agent_agent_id')?>" lookupGroup="agent" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('agent','parentAgent');?>" lookupGroup="agent">选择代理</a>
                    <a title="删除"  href="javascript:void(0)" onclick="$('.agent').val('')" class="btnDel" style="float: right">删除</a>
                </li>
				<li><label>时间：</label> 
				<input type="text" class="date" size="10" name="start_date" value="<?=\Core\Lib::request('start_date')?>" />
				至 
				<input type="text" class="date" size="10" name="end_date" value="<?=\Core\Lib::request('end_date')?>" />
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
			<li><a class="add" rel="InviteCodeAdd" title="生成邀请码" href="<?=\Core\Lib::getUrl('InviteCode', 'create');?>" target="dialog" width="370" height="200"><span>生成邀请码</span></a></li>
			<!--<li><a title="确定要删除吗？" target="ajaxTodo" href="<?/*=\Core\Lib::getUrl('InviteCode', 'del','id={id}');*/?>" class="delete"><span>删除</span></a></li>
			<li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="<?/*=\Core\Lib::getUrl('InviteCode','delAll');*/?>" class="delete"><span>批量删除</span></a></li>-->
            <!--<li><a title="批量选中交易" target="dialog" rel="InviteCodeTradeEdit"  href="<?=\Core\Lib::getUrl('InviteCodeTrade','trade');?>" class="edit" width="450" height="250"><span>批量分配</span></a></li>-->
        </ul>
	</div>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
				<th width="30" align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
				<th width="50" align="center" orderField="A.id" class="<?=($data['orderField']=='A.id')?$data['orderDirection']:'';?>">编号</th>
                <th width="100" align="center" orderField="A.code" class="<?=($data['orderField']=='A.code')?$data['orderDirection']:'';?>">二维码</th>
				<th width="150"  align="center"  orderField="A.code" class="<?=($data['orderField']=='A.code')?$data['orderDirection']:'';?>">邀请码</th>
				<th width="150"  align="center" orderField="B.nickname" class="<?=($data['orderField']=='B.nickname')?$data['orderDirection']:'';?>">代理昵称</th>
				<th width="150"  align="center" orderField="A.status" class="<?=($data['orderField']=='A.status')?$data['orderDirection']:'';?>">状态</th>
				<th width="250" align="center" orderField="A.create_time" class="<?=($data['orderField']=='A.create_time')?$data['orderDirection']:'';?>">生成时间</th>


				
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
				<td align="center"><?=$v['id']?></td>
                <td align="center"><a title="扫一扫获取邀请码" href="<?=\Core\Lib::getUrl('InviteCode', 'createQr','id='.$v['code'].'&appid='.\Core\Lib::request('appid'));?>" target="dialog" width="650" height="590"><img  src="/Qr/createQr/<?php echo $v['code'].'?appid='.\Core\Lib::request('appid');?>" height="50" ></a></td>
                <td align="center"><?=$v['code']?></td>
                <td align="center"><?=$v['nickname']?></td>
				<td align="center">
                    <?=$inviteCodeStatus[$v['status']]?>
                </td>
				<td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time']);?></td>
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
