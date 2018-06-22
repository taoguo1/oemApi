<?php $dictionary = \Core\Lib::loadFile('Config/Dictionary.php');?>
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('OemTX')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">

                <li><label>单号：</label> <input type="text" name="oem_no" value="<?=\Core\Lib::request('oem_no')?>" /></li>
                <li>
                    <label>状态：</label>
                    <select name="oem_status">
                        <option value="">全部</option>
                        <option value="1">成功</option>
                        <option value="0">进行中</option>
                        <option value="-1">失败</option>
                    </select>
                </li>

                <li><label>金额查询：</label>
                    <input type="text" class="number"  name="start_oem_amount"
                           value="<?= \Core\Lib::request('start_oem_amount') ?>"/>
                    至
                    <input type="text" class="number"  name="end_oem_amount"
                           value="<?= \Core\Lib::request('end_oem_amount') ?>"/>
                </li>
                <li><label>申请时间查询：</label>
                    <input type="text" class="date" size="10" name="start_oem_createtime"
                           value="<?= \Core\Lib::request('start_oem_createtime') ?>"/>
                    至
                    <input type="text" class="date" size="10" name="end_oem_createtime"
                           value="<?= \Core\Lib::request('end_oem_createtime') ?>"/>
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
            <li><a class="add" rel="agentAccountAdd" title="TX申请" href="<?=\Core\Lib::getUrl('OemTX', 'add');?>" target="dialog" width="560" height="350"><span>TX申请</span></a></li>
            <li><a class="edit"  title="TX编辑" rel="agentAccountEdit" href="<?=\Core\Lib::getUrl('OemTX', 'edit','id={id}');?>" target="dialog" width="560" height="350"><span>编辑</span></a></li>
            <span style="color:red">可提现金额为:<?=$maxMoney?>元</span>

            <span style="color:red">提现历史:<?=$recordMoney?>元</span>
        </ul>
    </div>
    <table class="list" width="100%" layoutH="90">
        <thead>
        <tr>
            <th align="center" orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">ID</th>
            <th align="center" orderField="oem_no" class="<?=($data['orderField']=='oem_no')?$data['orderDirection']:'';?>">申请单号</th>
            <th align="center" >申请人</th>
            <th align="center" orderField="oem_amount" class="<?=($data['orderField']=='oem_amount')?$data['orderDirection']:'';?>">金额</th>
            <th align="center" >描述</th>
            <th align="center" orderField="oem_createtime" class="<?=($data['orderField']=='oem_createtime')?$data['orderDirection']:'';?>">申请时间</th>
            <th align="center" orderField="oem_status" class="<?=($data['orderField']=='oem_status')?$data['orderDirection']:'';?>">审核状态</th>
            <th align="center" >备注</th>
            <th align="center" orderField="oem_time" class="<?=($data['orderField']=='oem_time')?$data['orderDirection']:'';?>">审核时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['oem_no']?></td>
                <td align="center"><?=$v['oem_name']?></td>
                <td align="center"><?=$v['oem_amount']?></td>
                <td align="center"><?=$v['oem_desciption']?></td>
                <td align="center"><?=\Core\Lib::uDate('Y-m-d H:i:s x',$v['oem_createtime']);?></td>
                <td align="center"><?php
                    if($v['oem_status']=='1'){
                        echo '成功';
                    }else if($v['oem_status']=='-1'){
                        echo '失败';
                    }else{
                        echo '进行中';
                    }
                    ?>
                </td>
                <td align="center"><?=$v['oem_remarks']?></td>
                <td align="center"><?php
                    if(!empty($v['oem_time'])){
                        echo \Core\Lib::uDate('Y-m-d H:i:s x',$v['oem_time']);
                    }
                    ?></td>
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
