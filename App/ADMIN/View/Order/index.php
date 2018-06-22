
<form id="pagerForm" method="post" action="#rel#">
<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('Order')?>" method="post">
<div class="searchBar">
<ul class="searchContent">

				<li><label>订单号查询：</label> <input type="text" name="order_sn" value="<?=\Core\Lib::request('order_sn')?>" /></li>
				<li><label>金额查询：</label> 
				<input type="text"  size="10" name="minamount" value="<?=\Core\Lib::request('minamount')?>" />
				至 
				<input type="text"  size="10" name="maxamount" value="<?=\Core\Lib::request('maxamount')?>" />
				</li>
				<li><label>时间查询：</label> 
				<input type="text" class="date" size="10" name="start_date" value="<?=\Core\Lib::request('start_date')?>" />
				至 
				<input type="text"  class="date" size="10" name="end_date" value="<?=\Core\Lib::request('end_date')?>" />
				</li>
				<li>
					<label>卡类型查询：</label>
					<select name="card_type">
							<option value="">全部</option>
							<option value="1" <?php if(\Core\Lib::request('card_type')==1){echo 'selected';}?>>信用卡</option>
							<option value="2" <?php if(\Core\Lib::request('card_type')==2){echo 'selected';}?>>储蓄卡</option>
					</select>
					</li>	
					<li>
					<label>状态查询：</label>
					<select name="status">
							<option value="">全部</option>
							<option value="1" <?php if(\Core\Lib::request('status')==1){echo 'selected';}?>>成功</option>
							<option value="2" <?php if(\Core\Lib::request('status')==2){echo 'selected';}?>>失败</option>
					</select>
					</li>
					<li>
					<label>类型查询：</label>
					<select name="type">
							<option value="">全部</option>
							<option value="1" <?php if(\Core\Lib::request('type')==1){echo 'selected';}?>>还款</option>
							<option value="2" <?php if(\Core\Lib::request('type')==2){echo 'selected';}?>>消费</option>
							<option value="3" <?php if(\Core\Lib::request('type')==3){echo 'selected';}?>>提现</option>
							<option value="4" <?php if(\Core\Lib::request('type')==4){echo 'selected';}?>>充值</option>
							<option value="5" <?php if(\Core\Lib::request('type')==5){echo 'selected';}?>>卡验证</option>
							<option value="6" <?php if(\Core\Lib::request('type')==6){echo 'selected';}?>>余额平帐</option>
							<option value="7" <?php if(\Core\Lib::request('type')==7){echo 'selected';}?>>强制扣款</option>
					</select>
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
			<li><a class="add" rel="orderAdd" title="添加订单" href="<?=\Core\Lib::getUrl('Order', 'add');?>" target="dialog" width="600" height="550"><span>添加</span></a></li>
            <li class="line">line</li>
			<li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Order', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
            <li class="line">line</li>
            <li class=""><a class="icon" onclick="navTabPageBreak()" href="javascript:;"><span>刷新</span></a></li>
		</ul>
	</div>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
                <th width="20" align="center" orderField="A.id" class="<?=($data['orderField']=='A.id')?$data['orderDirection']:'';?>">编号</th>
				<th width="30"  align="center" orderField="A.order_sn" class="<?=($data['orderField']=='A.order_sn')?$data['orderDirection']:'';?>">订单号</th>
				<th width="30" align="center" orderField="A.amount" class="<?=($data['orderField']=='A.amount')?$data['orderDirection']:'';?>">金额</th>
				<th width="30"  align="center" orderField="A.type" class="<?=($data['orderField']=='A.type')?$data['orderDirection']:'';?>">类型</th>
				<th width="40"   align="center" orderField="A.card_type" class="<?=($data['orderField']=='A.card_type')?$data['orderDirection']:'';?>">卡类型</th>
				<th width="60"  align="center" orderField="bank_id" class="<?=($data['orderField']=='bank_id')?$data['orderDirection']:'';?>">银行</th>
				<th width="60"  align="center" orderField="A.card_no" class="<?=($data['orderField']=='A.card_no')?$data['orderDirection']:'';?>">卡号</th>
				<th width="60"  align="center" orderField="A.goods_id" class="<?=($data['orderField']=='A.goods_id')?$data['orderDirection']:'';?>">商品ID</th>
				<th width="60"  align="center" orderField="A.goods_quantity" class="<?=($data['orderField']=='A.goods_quantity')?$data['orderDirection']:'';?>">商品数量</th>
				<th width="60"  align="center" orderField="A.receive_name" class="<?=($data['orderField']=='A.receive_name')?$data['orderDirection']:'';?>">收货人姓名</th>
				<th width="150"  align="center" orderField="A.id" class="<?=($data['orderField']=='A.id')?$data['orderDirection']:'';?>">收货人地址</th>
				<th width="60"  align="center" orderField="A.id" class="<?=($data['orderField']=='A.id')?$data['orderDirection']:'';?>">收货人手机</th>
				<th width="60"  align="center" orderField="A.status" class="<?=($data['orderField']=='A.status')?$data['orderDirection']:'';?>">执行状态</th>
				<th width="140"  align="center" orderField="A.add_time" class="<?=($data['orderField']=='A.add_time')?$data['orderDirection']:'';?>">创建时间</th>
				
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
			    <td align="center"><?=$v['id']?></td>
				<td align="center"><?=$v['order_sn']?></td>
				<td align="center"><?=$v['amount']?></td>
				<td align="center">
				
				<?php 
				
				if($v['type']==1){
					echo '还款';
				}elseif($v['type']==2){
					echo '消费';
				}elseif($v['type']==3){
					echo '提现';
				}elseif($v['type']==4){
					echo '充值';
				}elseif($v['type']==5){
					echo '卡验证';
				}elseif($v['type']==6){
					echo '余额平帐';
				}elseif($v['type']==7){
					echo '强制扣款';
				}
				
				?>
				
				</td>
				<td align="center"><?=($v['card_type']==1)?'信用卡':'储蓄卡'?></td>
				<td align="center"><?=$bank[$v['bank_id']]['name']?></td>
				<td align="center"><?=\Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['card_no']));?></td>
				<td align="center"><?=($v['goods_id'])?></td>
				<td align="center"><?=($v['goods_quantity'])?></td>
				<td align="center"><?=\Core\Lib::starReplace($v['receive_name'])?></td>
				<td align="center"><?=($v['receive_address'])?></td>
				<td align="center"><?=\Core\Lib::strReplace($v['receive_mobile'])?></td>
				<td align="center"><?=($v['status']==1)?'成功':'<span style="color:red">失败</span>'?></td>
				<td align="center"><?= date('Y-m-d H:i:s',$v['add_time']);?></td>
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
