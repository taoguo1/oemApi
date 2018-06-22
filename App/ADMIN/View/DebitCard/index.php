<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('debitCard')?>" method="post">
		<div class="searchBar" style="width:150%">
			<ul class="searchContent">
                <li>
                    <label>用户：</label>
                    <input style="width: 100px;" type="text"  class="user" style=""  size="8" name="real_name" value="<?php if(isset($agentname)){echo $agentname;} else{echo '';}?>" placeholder="输入真实姓名" />
                </li>
                <li>
                    <label>代理：</label>
                    <input type="text"  size="6" class="user" style="" name="user.real_name" value="<?=\Core\Lib::request('user_real_name')?>" placeholder="全部" lookupGroup="user" readonly/>
                    <input type="hidden"  class="user" name="user.id" value="<?=\Core\Lib::request('user_id')?>" lookupGroup="user" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" lookupGroup="user">选择代理</a>
                    <a title="删除"  href="javascript:void(0)" onclick="$('.user').val('')" class="btnDel" style="float: right">删除</a>
                </li>
                <li><label>卡号：</label> <input type="text" size="10" name="card_no" value="<?=$isCard=\Core\Lib::request('card_no')?>" /></li>
                <li><label>预留手机号：</label> <input type="text" size="10" name="mobile" value="<?=$isCard=\Core\Lib::request('mobile')?>" /></li>
                <li><label>手机号：</label> <input type="text"  size="10" name="lb_mobile" value="<?=$isCard=\Core\Lib::request('lb_mobile')?>" /></li>
                <li><label>身份证号：</label> <input type="text" size="10" name="id_card" value="<?=$isCard=\Core\Lib::request('id_card')?>" /></li>
                <li>
                    <label>银行:</label>
                    <select name="bank_id" class="required">
                        <option value="">全部</option>
                        <?php
                        foreach ($bank as $k=>$v) {
                            if($v['bank_type']==2) {
                                ?>
                                <option value="<?php echo $v['id'] ?>" <?php if(\Core\Lib::request('bank_id')==$v['id']){echo 'selected';}?>><?php echo $v['name'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </li>

                <li>
                    <label>状态： </label>
                    <select name="status">
                        <option value="">全部</option>
                        <?php
                        foreach ($cardStatus as $k=>$v) {
                            ?>
                            <option value="<?php echo $k ?>" <?php if(\Core\Lib::request('status')==$k){echo 'selected';}?>><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </li>

                <li>
                    <label>客户类型： </label>
                    <select name="user_type">
                        <option value="">全部</option>
                        <?php
                        foreach ($user_type as $k=>$v) {
                            ?>
                            <option value="<?php echo $k ?>" <?php if(\Core\Lib::request('user_type')==$k){echo 'selected';}?>><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </li>
                <li><label>创建时间：</label>
                    <input type="start_create_time" class="date textInput" size="8"  name="start_create_time" value="<?=\Core\Lib::request('start_create_time')?>" />
                    至
                    <input type="end_create_time"  class="date textInput" size="8" name="end_create_time" value="<?=\Core\Lib::request('end_create_time')?>" />
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
			<li><a class="add" rel="debitCardAdd" title="添加储蓄卡" href="<?=\Core\Lib::getUrl('debitCard', 'add');?>" target="dialog" width="600" height="300"><span>添加</span></a></li>
            <li class="line">line</li>
			<li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('debitCard', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
            <li class="line">line</li>
			<li><a class="edit"  title="编辑信用卡" rel="debitCardEdit" href="<?=\Core\Lib::getUrl('debitCard', 'edit','id={id}');?>" target="dialog" width="600" height="300"><span>编辑</span></a></li>
            <li class="line">line</li>
            <li class=""><a class="icon" onclick="navTabPageBreak()" href="javascript:;"><span>刷新</span></a></li>
		</ul>
	</div>
	<table class="list" width="100%" layoutH="108">
		<thead>
			<tr>
				<th width="20" align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
				<th width="20" align="center"  orderField="D.id" class="<?=($data['orderField']=='D.id')?$data['orderDirection']:'';?>">编号</th>
                <th  width="60" align="center" >注册手机号</th>
                <th  width="40" align="center"  orderField="D.real_name" class="<?=($data['orderField']=='D.real_name')?$data['orderDirection']:'';?>">持卡人姓名</th>
                <th  width="60" align="center"  orderField="D.lb_mobile" class="<?=($data['orderField']=='D.lb_mobile')?$data['orderDirection']:'';?>">预留手机号</th>
                <th  width="60" align="center"  orderField="D.id_card" class="<?=($data['orderField']=='D.id_card')?$data['orderDirection']:'';?>">身份证</th>
                <th  width="60" align="center"  orderField="D.bank_name" class="<?=($data['orderField']=='D.bank_name')?$data['orderDirection']:'';?>">银行</th>
                <th  width="60" align="center"  orderField="D.card_no" class="<?=($data['orderField']=='D.card_no')?$data['orderDirection']:'';?>">银行卡号</th>
                <th  width="60" align="center"  orderField="D.user_type" class="<?=($data['orderField']=='D.user_type')?$data['orderDirection']:'';?>">客户类型</th>

                <th  width="60" align="center" orderField="D.status" class="<?=($data['orderField']=='D.status')?$data['orderDirection']:'';?>">状态</th>

                <th  width="100" align="center" orderField="D.create_time" class="<?=($data['orderField']=='D.create_time')?$data['orderDirection']:'';?>">创建时间</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
				<td align="center"><?=$v['id']?></td>
                <td align="center">
                    <?php echo $v['user_mobile'];?>
                </td>
                <td align="center">
                    <?php echo  \Core\Lib::starReplaceName($v['user_name']);?>
                </td>
                <td align="center"><?=$v['lb_mobile']?></td>
                <td align="center"><?=\Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['id_card']));?></td>

                <td align="center"><?=$v['bank_name']?></td>
                <td align="center">
                    <?php
                    if($isCard!=null){
                        echo \Core\Lib::aesDecrypt($v['card_no']);
                    }else{
                        echo  \Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['card_no']));
                    }
                    ?>
                </td>
                <td align="center"><?=$user_type[$v['user_type']]?></td>

                <td align="center" <?=($v['status']==1)? '':'style="color:red"'?>><?=$cardStatus[$v['status']]?></td>
                <td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time']);?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
    <div class="panelBar">
        <div class="pages">
            <span>显示</span>
            <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                <option value="25" <?php if($data['numPerPage']=='25'){echo 'selected';}?>>25</option>
                <option value="50" <?php if($data['numPerPage']=='50'){echo 'selected';}?>>50</option>
                <option value="100" <?php if($data['numPerPage']=='100'){echo 'selected';}?>>100</option>
                <option value="200" <?php if($data['numPerPage']=='200'){echo 'selected';}?>>200</option>
            </select> <span>条，共<?php echo $data['totalCount']?>条</span>
        </div>
        <div class="pagination" targetType="navTab" totalCount="<?php echo $data['totalCount']?>" numPerPage="<?php echo $data['numPerPage']?>" pageNumShown="10" currentPage="<?php echo $data['pageNum']?>"></div>
    </div>
</div>
