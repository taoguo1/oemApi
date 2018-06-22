<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('creditCard')?>" method="post">
		<div class="searchBar" style="width:150%">
			<ul class="searchContent">
                <li>
                    <label>用户：</label>
                    <input style="width: 100px;" type="text"  class="user" size="8" style="" name="real_name" value="<?php if(isset($agentname)){echo $agentname;} else{echo '';}?>" placeholder="输入真实姓名"/>
                </li>
                <li><label>卡号：</label> <input type="text" name="card_no" size="10" value="<?=$isCard=\Core\Lib::request('card_no')?>" /></li>
                <li><label>预留手机号：</label> <input type="text" name="mobile" size="10" value="<?=$isCard=\Core\Lib::request('mobile')?>" /></li>
                <li><label>身份证号：</label> <input type="text" name="id_card" value="<?=$isCard=\Core\Lib::request('id_card')?>" /></li>
                <li style="white-space: inherit;">
                    <label>所属代理:</label>
                    <input type="text"   class="nickname" name="agent.agent_name" value="<?php echo \Core\Lib::request('agent_agent_name');?>" lookupGroup="agent" readonly/>
                    <input type="hidden"  class="nickname" name="agent.agent_mobile" value="" lookupGroup="agent" />
                    <input type="hidden"  class="nickname" name="agent.agent_id" value="" lookupGroup="agent" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('user','agent');?>" lookupGroup="agent">选择代理</a>
                    &nbsp;
                    <a title="删除"  href="javascript:void(0)" onclick="$('.nickname').val('')" class="btnDel" style="float: right">删除</a>
                </li>
                <li>
					<label>银行:</label>
                    <select name="bank_id" class="required">
                        <option value="">全部</option>
                        <?php
                        foreach ($bank as $k=>$v) {
                            //if($v['bank_type']==1) {
                                ?>
                                <option value="<?php echo $v['id'] ?>" <?php if(\Core\Lib::request('bank_id')==$v['id']){echo 'selected';}?>><?php echo $v['name'] ?></option>
                                <?php
                            //}
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
                <li><label>创建时间：</label>
                    <input type="start_create_time" class="date textInput" size="11"  name="start_create_time" value="<?=\Core\Lib::request('start_create_time')?>" />
                    至
                    <input type="end_create_time"  class="date textInput" size="11" name="end_create_time" value="<?=\Core\Lib::request('end_create_time')?>" />
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
            <li><a class="add" rel="CreditCardAdd" title="添加信用卡" href="<?=\Core\Lib::getUrl('CreditCard', 'add');?>" target="dialog" width="600" height="500"><span>添加</span></a></li>
            <li class="line">line</li>
            <li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('CreditCard', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
            <li class="line">line</li>
            <li><a class="edit"  title="编辑信用卡" rel="CreditCardEdit" href="<?=\Core\Lib::getUrl('CreditCard', 'edit','id={id}');?>" target="dialog" width="600" height="500"><span>编辑</span></a></li>
            <li class="line">line</li>
            <li class=""><a class="icon" onclick="navTabPageBreak()" href="javascript:;"><span>刷新</span></a></li>
        </ul>
    </div>
	<table class="list" width="100%" layoutH="108">
		<thead>
			<tr>
				<th width="20" align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
				<th width="20" align="center"  orderField="C.id" class="<?=($data['orderField']=='C.id')?$data['orderDirection']:'';?>">编号</th>
                <th  width="40" align="center"  orderField="C.real_name" class="<?=($data['orderField']=='C.real_name')?$data['orderDirection']:'';?>">持卡人姓名</th>
                <th  width="60" align="center"  orderField="C.lb_mobile" class="<?=($data['orderField']=='C.lb_mobile')?$data['orderDirection']:'';?>">预留手机号</th>
                <th  width="60" align="center"  orderField="C.id_card" class="<?=($data['orderField']=='C.id_card')?$data['orderDirection']:'';?>">身份证</th>
                <th  width="60" align="center"  orderField="C.bank_name" class="<?=($data['orderField']=='C.bank_name')?$data['orderDirection']:'';?>">银行</th>
                <th  width="60" align="center"  orderField="C.card_no" class="<?=($data['orderField']=='C.card_no')?$data['orderDirection']:'';?>">银行卡号</th>
                <!--<th  style="display: none;"width="40" align="center"  orderField="C.expiry_date" class="<?=($data['orderField']=='C.expiry_date')?$data['orderDirection']:'';?>">有效期</th>-->
                <th  width="60" align="center"  >所属代理(手机号)</th>
                <th  width="60" align="center" orderField="C.status" class="<?=($data['orderField']=='C.status')?$data['orderDirection']:'';?>">状态</th>
                <th  width="100" align="center" orderField="C.create_time" class="<?=($data['orderField']=='C.create_time')?$data['orderDirection']:'';?>">创建时间</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($data['list'] as $k=>$v){?>
			<tr target="id" rel="<?=$v['id']?>">
				<td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
				<td align="center"><?=$v['id']?></td>
                <td align="center">
                    <?php
                    if($isCard!=null){
                        echo \Core\Lib::aesDecrypt($v['real_name']);
                    }else{
                        echo  \Core\Lib::starReplaceName(\Core\Lib::aesDecrypt($v['real_name']));
                    }
                    ?>
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


                <td align="center" style="display: none;">
                    <?php
                    if($isCard!=null){
                        echo \Core\Lib::aesDecrypt($v['cvn']);
                    }else{
                        echo  '****';
                    }
                    ?>
                </td>
                <td align="center" style="display: none;">
                    <?php
                    if($isCard!=null){
                        echo \substr_replace(\Core\Lib::aesDecrypt($v['expiry_date']), '/', 2, 0);
                    }else{
                        echo  '****';
                    }
                    ?>
                </td>
                <td align="center"><?php echo $v['agent_name'].'&nbsp;&nbsp;('.$v['agent_mobile'].')'; ?></td>
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
