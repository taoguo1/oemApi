<?php $dictionary = \Core\Lib::loadFile('Config/Dictionary.php');?>
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('AgentAccount')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li style="white-space: inherit;">
                    <label>代理昵称：</label>
                    <input type="text"  class="agent" name="agent.agent_name" value="<?=\Core\Lib::request('agent_agent_name')?>" placeholder="全部" readonly lookupGroup="agent" />
                    <input type="hidden"  class="" name="agent.agent_id" value="" lookupGroup="agent" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('agent','parentAgent');?>" lookupGroup="agent">选择代理</a>
                    <a title="删除"  href="javascript:void(0)" onclick="$('.agent').val('')" class="btnDel" style="float: right">删除</a>
                </li>
                <li><label>订单号：</label> <input type="text" name="order_sn" value="<?=\Core\Lib::request('order_sn')?>" /></li>
                <li>
                    <label>描述：</label>
                    <select name="type">
                        <option value="">全部</option>
                        <option value="1" <?php if(\Core\Lib::request('type')==1){echo 'selected';}?>>还款分润</option>
                        <option value="2" <?php if(\Core\Lib::request('type')==2){echo 'selected';}?>>提现</option>
                        <option value="3" <?php if(\Core\Lib::request('type')==3){echo 'selected';}?>>收款分润</option>

                    </select>
                </li>
                <li><label>金额查询：</label>
                    <input type="text" class="number"  name="start_amount"
                           value="<?= \Core\Lib::request('start_amount') ?>"/>
                    至
                    <input type="text" class="number"  name="end_amount"
                           value="<?= \Core\Lib::request('end_amount') ?>"/>
                </li>
                <li><label>创建时间查询：</label>
                    <input type="text" class="date" size="10" name="start_date"
                           value="<?= \Core\Lib::request('start_date') ?>"/>
                    至
                    <input type="text" class="date" size="10" name="end_date"
                           value="<?= \Core\Lib::request('end_date') ?>"/>
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
            <li><a class="add" rel="agentAccountAdd" title="添加代理账户" href="<?=\Core\Lib::getUrl('AgentAccount', 'add');?>" target="dialog" width="560" height="350"><span>添加</span></a></li>
            <!--<li><a title="确定要删除吗？" target="ajaxTodo" href="<?/*=\Core\Lib::getUrl('AgentAccount', 'del','id={id}');*/?>" class="delete"><span>删除</span></a></li>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="<?/*=\Core\Lib::getUrl('AgentAccount','delAll');*/?>" class="delete"><span>批量删除</span></a></li>-->
            <li><a class="edit"  title="编辑代理账户" rel="agentAccountEdit" href="<?=\Core\Lib::getUrl('AgentAccount', 'edit','id={id}');?>" target="dialog" width="560" height="350"><span>编辑</span></a></li>
        </ul>
    </div>
    <table class="list" width="100%" layoutH="90">
        <thead>
        <tr>
            <th align="center" orderField="A.id" class="<?=($data['orderField']=='A.id')?$data['orderDirection']:'';?>">账户编号</th>
            <th align="center" >代理昵称</th>
            <th align="center" orderField="A.amount" class="<?=($data['orderField']=='A.amount')?$data['orderDirection']:'';?>">金额</th>
            <th align="center" orderField="A.order_sn" class="<?=($data['orderField']=='A.order_sn')?$data['orderDirection']:'';?>">订单号</th>
            <th align="center" orderField="A.description" class="<?=($data['orderField']=='A.description')?$data['orderDirection']:'';?>">描述</th>
            <th align="center" orderField="A.in_type" class="<?=($data['orderField']=='A.in_type')?$data['orderDirection']:'';?>">入库方式</th>
            <th align="center" orderField="A.create_time" class="<?=($data['orderField']=='A.create_time')?$data['orderDirection']:'';?>">创建时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['nickname']?></td>
                <td align="center"><?=$v['amount']?></td>
                <td align="center"><?=$v['order_sn']?></td>
                <td align="center"><?=$v['description']?></td>
                <td align="center"><?=($v['in_type']==1)?'自动':'手动'?></td>
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
