<form id="pagerForm" action="<?php echo \Core\Lib::getUrl('agent','parentAgent');?>">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader" style="display: none;">
    <form rel="pagerForm" method="post" action="<?php echo \Core\Lib::getUrl('agent','parentAgent');?>" onsubmit="return dwzSearch(this, 'dialog');">
        <div class="searchBar">
            <ul class="searchContent">
                <li>
                    <label>代理名称：</label>
                    <input type="text" name="nickname" value="<?=\Core\Lib::request('nickname')?>" />
                </li>
                <li>
                    <label>手机号：</label>
                    <input type="text" name="mobile" value="<?=\Core\Lib::request('mobile')?>" />
                </li>
                <li>
                    <label>身份证号：</label>
                    <input type="text" name="id_card" value="<?=\Core\Lib::request('id_card')?>" />
                </li>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">查询</button></div></div></li>
            </ul>
        </div>
    </form>
</div>
<div class="pageContent">
    <table class="list" width="100%" layoutH="62">
        <thead>
        <tr>
            <th width="60" >编号</th>
            <th width="60" >计划ID</th>
            <th  align="center" >用户姓名</th>
            <th  align="center" >金额</th>
            <th  align="center" >类型</th>
            <th  align="center" >开始时间</th>
            <th  align="center" >结束时间</th>
            <th  align="center" >订单号</th>
            <th  align="center" >计划状态</th>
            <th  align="center" >添加时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['plan_id']?></td>
                <td align="center"><?=\Core\Lib::starReplaceName($v['real_name'])?></td>
                <td align="center"><?=$v['amount']?></td>
                <td align="center" <?php if($v['plan_type'] == 1){ ?> style="color:red;"<?php } ?>><?=$planlistType[$v['plan_type']]?></td>
                <td align="center"><?=date('Y-m-d H:i:s',$v['start_time'])?></td>
                <td align="center"><?php
                    if(!empty($v['end_time'])){
                        echo date('Y-m-d H:i:s',$v['end_time']);
                    }
                    ?></td>
                <td align="center"><?=$v['order_sn']?></td>
                <td align="center"><?=$planlistStatus[$v['status']]?></td>
                <td align="center"><?=\Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time'])?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <div class="panelBar" style = "display: none;">
        <div class="pages">
            <span>显示</span>
            <select class="combox" name="numPerPage" onchange="dwzPageBreak({targetType:'dialog', numPerPage:'10'})">
                <option value="10" <?php if($data['numPerPage']=='10'){echo 'selected';}?>>10</option>
                <option value="20" <?php if($data['numPerPage']=='20'){echo 'selected';}?>>20</option>
                <option value="50" <?php if($data['numPerPage']=='50'){echo 'selected';}?>>50</option>
                <option value="100" <?php if($data['numPerPage']=='100'){echo 'selected';}?>>100</option>
            </select>
            <span>条，共<?php echo $data['totalCount']?>条</span>
        </div>
        <div class="pagination" targetType="dialog" totalCount="<?php echo $data['totalCount']?>" numPerPage="<?php echo $data['numPerPage']?>" pageNumShown="10" currentPage="<?php echo $data['pageNum']?>"></div>
    </div>
</div>

