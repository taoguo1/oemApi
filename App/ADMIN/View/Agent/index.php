    <?php $dictionary = \Core\Lib::loadFile('Config/Dictionary.php');?>
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>

<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('Agent')?>" method="post">
        <div class="searchBar" style="width:150%">
            <ul class="searchContent">
                <li style="white-space: inherit;">
                    <label>上级代理:</label>
                    <input type="text"  class="nickname" name="agent.agent_name" value="<?=\Core\Lib::request('agent_agent_name')?>" lookupGroup="agent" readonly/>
                    <input type="hidden"  class="nickname" name="agent.agent_id" value="" lookupGroup="agent" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('user','agent');?>" lookupGroup="agent">选择代理</a>
                    &nbsp;
                    <a title="删除"  href="javascript:void(0)" onclick="$('.nickname').val('')" class="btnDel" style="float: right">删除</a>
                </li>
                <li><label>代理昵称：</label> <input style="width: 90px;" type="text" name="nickname" value="<?=\Core\Lib::request('nickname')?>" /></li>
                <li><label>手机号：</label> <input style="width: 90px;" type="text" name="mobile" value="<?=\Core\Lib::request('mobile')?>" /></li>
                <li><label>级别：</label> <input style="width: 20px;" type="text" name="level" value="<?=\Core\Lib::request('level')?>" /></li>
                <li><label>身份证号：</label> <input type="text" name="id_card" value="<?=\Core\Lib::request('id_card')?>" /></li>
                <li><label>创建时间查询：</label>
                    <input type="text" class="date" size="10" name="start_date"
                           value="<?= \Core\Lib::request('start_date') ?>"/>
                    至
                    <input type="text" class="date" size="10" name="end_date"
                           value="<?= \Core\Lib::request('end_date') ?>"/>
                </li>
                <li>
                    <label>是否实名认证：</label>
                    <select name="is_id_card_auth">
                        <option value="">全部</option>
                        <?php
                        foreach ($dictionary['userAuth'] as $k=>$v) {
                            ?>
                            <option value="<?php echo $k ?>" <?php if(\Core\Lib::request('is_id_card_auth')==$k){echo 'selected';}?>><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </li>
                <li>
                    <label>状态：</label>
                    <select name="status">
                        <option value="">全部</option>
                        <?php
                        foreach ($dictionary['userState'] as $k=>$v) {
                            ?>
                            <option value="<?php echo $k ?>" <?php if(\Core\Lib::request('status')==$k){echo 'selected';}?>><?php echo $v ?></option>
                            <?php
                        }
                        ?>
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
            <li><a class="add" rel="agentAdd" title="添加代理" href="<?=\Core\Lib::getUrl('Agent', 'add');?>" target="dialog" width="650" height="500"><span>添加</span></a></li>
            
            
          <li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Agent', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			
            <!--<li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="<?/*=\Core\Lib::getUrl('Agent','delAll');*/?>" class="delete"><span>批量删除</span></a></li>-->
            <li><a class="edit"  title="编辑代理" rel="agentEdit" href="<?=\Core\Lib::getUrl('Agent', 'edit','id={id}');?>" target="dialog" width="650" height="420"><span>编辑</span></a></li>
        </ul>
    </div>
    <table class="list" width="100%" layoutH="105">
        <thead>
        <tr>
            <th align="center" orderField="A.id" class="<?=($data['orderField']=='A.id')?$data['orderDirection']:'';?>">编号</th>
            <th align="center" orderField="A.nickname" class="<?=($data['orderField']=='A.nickname')?$data['orderDirection']:'';?>">昵称</th>
            <th align="center" orderField="A.real_name" class="<?=($data['orderField']=='A.real_name')?$data['orderDirection']:'';?>">真实姓名</th>
            <th align="center" orderField="A.mobile" class="<?=($data['orderField']=='A.mobile')?$data['orderDirection']:'';?>">手机号</th>
            <th align="center" orderField="A.id_card" class="<?=($data['orderField']=='A.id_card')?$data['orderDirection']:'';?>">身份证号</th>
            
            <th align="center" orderField="pname" class="<?=($data['orderField']=='pname')?$data['orderDirection']:'';?>">上级代理</th>
            <th align="center" orderField="A.level" class="<?=($data['orderField']=='A.level')?$data['orderDirection']:'';?>">代理级别</th>
            <th align="center" orderField="total_commission" class="<?=($data['orderField']=='total_commission')?$data['orderDirection']:'';?>">累计佣金</th>
            <th align="center" orderField="invite_code_num" class="<?=($data['orderField']=='invite_code_num')?$data['orderDirection']:'';?>">邀请码数量</th>
            <th align="center" orderField="A.rate" class="<?=($data['orderField']=='A.rate')?$data['orderDirection']:'';?>">分润比例（万分之）</th>
            <th align="center" orderField="A.skrate" class="<?=($data['orderField']=='A.skrate')?$data['orderDirection']:'';?>">收款分润比例（万分之）</th>
            <th align="center" orderField="A.is_id_card_auth" class="<?=($data['orderField']=='A.is_id_card_auth')?$data['orderDirection']:'';?>">是否实名认证</th>
            <th align="center" orderField="A.status" class="<?=($data['orderField']=='A.status')?$data['orderDirection']:'';?>">状态</th>
            <th align="center" orderField="A.create_time" class="<?=($data['orderField']=='A.create_time')?$data['orderDirection']:'';?>">创建时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['nickname']?></td>
                <td align="center"><?=$v['real_name']?></td>
                <td align="center"><?=$v['mobile'];?></td>
                <td align="center" style="width: 127px;">
				<?php 
				if(empty($v['id_card'])){
					echo $v['id_card'];
				}else{
					?>
					<?=\Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['id_card'])); ?>
				<?php 
				}
				?>
				</td>
				<!--<td align="center"><?=$v['mobile']?></td>-->
                <td align="center"><?php
                    if ($v['pid'] === '0')
                    {echo "无";}else{echo $v['pname'];}
                    ?></td>
                
                <td align="center"><?=$v['level']?></td>
                <td align="center"><?=$v['total_commission']?></td>
                <td align="center"><?=$v['invite_code_num']?></td>
                <td align="center"><?=$v['rate']?></td>
                <td align="center"><?=$v['skrate']?></td>
                <td align="center"><?=($v['is_id_card_auth']==1)?'已通过':'<font style="color:red">未认证'.'</font>'?></td>
                <td align="center"><?=($v['status']==1)?'正常':'<font style="color:red">禁用'.'</font>'?></td>
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
