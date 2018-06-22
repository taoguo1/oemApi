<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('UserAccount')?>" method="post">
		<div class="searchBar">
			<ul class="searchContent">
<li>
                    <label>用户：</label>
                    <input type="text"  size="6" class="user" style="" name="user.real_name" value="<?=\Core\Lib::request('user_real_name')?>" placeholder="全部" lookupGroup="user" readonly/>
                    <input type="hidden"  class="user" name="user.id" value="<?=\Core\Lib::request('user_id')?>" lookupGroup="user" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" lookupGroup="user">选择用户</a>
                    <a title="删除"  href="javascript:void(0)" onclick="$('.user').val('')" class="btnDel" style="float: right">删除</a>
                </li>
				<li><label>姓名：</label> <input type="text" size="5" name="real_name" value="<?=\Core\Lib::request('real_name')?>" /></li>
                <li><label>手机号：</label> <input type="text" size="9" name="mobile" value="<?=\Core\Lib::request('mobile')?>" /></li>
                <li><label>身份证：</label> <input type="text"  name="id_card" value="<?=\Core\Lib::request('id_card')?>" /></li>
                <li style="white-space: inherit;">
                    <label>所属代理:</label>
                    <input type="text"   class="nickname" name="agent.agent_name" value="<?php echo \Core\Lib::request('agent_agent_name');?>" lookupGroup="agent" readonly/>
                    <input type="hidden"  class="nickname" name="agent.agent_mobile" value="" lookupGroup="agent" />
                    <input type="hidden"  class="nickname" name="agent.agent_id" value="" lookupGroup="agent" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('user','agent');?>" lookupGroup="agent">选择代理</a>
                    &nbsp;
                    <a title="删除"  href="javascript:void(0)" onclick="$('.nickname').val('')" class="btnDel" style="float: right">删除</a>
                </li>
				<li><label>订单号：</label> <input type="text"  name="order_sn" value="<?=\Core\Lib::request('order_sn')?>" /></li>
				<li><label>金额：</label>
				<input type="text"  size="5" name="min_amount" value="<?=\Core\Lib::request('min_amount')?>" />
				至 
				<input type="text"  size="5" name="max_amount" value="<?=\Core\Lib::request('max_amount')?>" />
				</li>
				<!--<li>
					<label>入库方式：</label>
					<select name="in_type">
							<option value="">全部</option>
							<?php foreach($InStatus as $k=>$v){?>
								<option value="<?php echo $k;?>" <?php if(\Core\Lib::request('in_type')==$k){echo 'selected';}?>><?php echo $v;?></option>
							<?php } ?>
					</select>
					</li>-->

					<li><label>入库时间：</label>
						<input type="text" class="date" size="10" name="start_date" value="<?=\Core\Lib::request('start_date')?>" />
						至
						<input type="text"  class="date" size="10" name="end_date" value="<?=\Core\Lib::request('end_date')?>" />
					</li>

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
			<li><a class="add" rel="useraccountAdd" title="添加会员" href="<?=\Core\Lib::getUrl('UserAccount', 'add');?>" target="dialog" width="550" height="350"><span>添加</span></a></li>
			<!--<li><a title="确定要删除吗？" target="ajaxTodo" href="<?/*=\Core\Lib::getUrl('UserAccount', 'del','id={id}');*/?>" class="delete"><span>删除</span></a></li>-->
			<li><a class="edit"  title="修改会员" rel="useraccountEdit" href="<?=\Core\Lib::getUrl('UserAccount', 'edit','id={id}');?>" target="dialog"  width="550" height="320"><span>编辑</span></a></li>
		</ul>
	</div>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
				<th width="60" align="center"  orderField="U.id" class="<?=($data['orderField']=='U.id')?$data['orderDirection']:'';?>">编号</th>
				<th width="60" align="center"  orderField="U.real_name" class="<?=($data['orderField']=='U.real_name')?$data['orderDirection']:'';?>">用户名</th>
				<th align="center" width="150" orderField="amount" class="<?=($data['orderField']=='amount')?$data['orderDirection']:'';?>">金额</th>
				<th width="80" align="center"  orderField="order_sn" class="<?=($data['orderField']=='order_sn')?$data['orderDirection']:'';?>">订单号</th>
				<th width="60" align="center"  orderField="desciption" class="<?=($data['orderField']=='desciption')?$data['orderDirection']:'';?>">描述</th>
                <th width="80" align="center" >所属代理（手机号）</th>
				<th width="80" align="center"  orderField="in_type" class="<?=($data['orderField']=='in_type')?$data['orderDirection']:'';?>">入库方式</th>
				<th width="120" align="center" orderField="U.create_time" class="<?=($data['orderField']=='U.create_time')?$data['orderDirection']:'';?>">入库时间</th>
				
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><?=$v['id']?></td>
				<td align="center"><?=\Core\Lib::starReplaceName($v['real_name'])?></td>
				<td align="center"><?=$v['amount']?></td>
				<td align="center"><?=($v['order_sn'])?></td>
				<td align="center"><?=$v['desciption']?></td>
                <td align="center"><?php echo $v['agent_name'].'('.$v['agent_mobile'].')';?></td>
				<td align="center"><?=($v['in_type']==1)?'自动':'<font style="color:red">手动'.'</font>'?></td>
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
