<form id="pagerForm" method="post" action="#rel#">
    <!--<form id="pagerForm" method="post" action="<?=\Core\lib::getUrl('KcpOrder');?>">-->
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>

<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('KcpOrder')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li><label>创建时间：</label>
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

        </ul>
    </div>
    <table class="list" width="100%" layoutH="90">
        <thead>
        <tr>
            <th align="center" orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">编号</th>
            <th align="center" orderField="order_sn" class="<?=($data['orderField']=='order_sn')?$data['orderDirection']:'';?>">订单号</th>
            <th align="center" orderField="order_wxsn" class="<?=($data['orderField']=='order_wxsn')?$data['orderDirection']:'';?>">微信订单号</th>
            <th align="center" orderField="oem_amount" class="<?=($data['orderField']=='oem_amount')?$data['orderDirection']:'';?>">商家收益</th>
            <th align="center" orderField="kcp_earnings" class="<?=($data['orderField']=='kcp_earnings')?$data['orderDirection']:'';?>">平台费用</th>
            <th align="center" orderField="amount" class="<?=($data['orderField']=='amount')?$data['orderDirection']:'';?>">总金额</th>
            <th align="center" orderField="appid" class="<?=($data['orderField']=='appid')?$data['orderDirection']:'';?>">APPID</th>
            <th align="center" orderField="app_name" class="<?=($data['orderField']=='app_name')?$data['orderDirection']:'';?>">商户名称</th>
            <th align="center" orderField="create_time" class="<?=($data['orderField']=='create_time')?$data['orderDirection']:'';?>">创建时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['order_sn']?></td>
                <td align="center"><?=$v['order_wxsn']?></td>
                <td align="center"><?=$v['oem_amount']?></td>
                <td align="center"><?=$v['kcp_earnings']?></td>
                <td align="center"><?=$v['amount']?></td>
                <td align="center"><?=$v['appid']?></td>
                <td align="center"><?=$v['app_name']?></td>
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
