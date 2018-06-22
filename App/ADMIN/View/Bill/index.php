<?php $dic = \Core\Lib::loadFile('Config/Dictionary.php');?>
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>

<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('Bill')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">

                <li><label>还款计划ID：</label> <input type="text" name="plan_id" value="<?=\Core\Lib::request('plan_id')?>" /></li>
                <li><label>用户姓名：</label> <input type="text" name="real_name" value="<?=\Core\Lib::request('real_name')?>" /></li>
                <li><label>订单号：</label> <input type="text" name="order_sn" value="<?=\Core\Lib::request('order_sn')?>" /></li>
                <li><label>外部订单号：</label> <input type="text" name="transaction_id" value="<?=\Core\Lib::request('transaction_id')?>" /></li>
                <li><label>手机号查询：</label> <input type="text" name="mobile" value="<?=\Core\Lib::request('mobile')?>" /></li>
                <li><label>卡号：</label> <input type="text" name="card_no" value="<?=\Core\Lib::request('card_no')?>" /></li>
                <li>
                    <label>账单类型：</label>
                    <select name="bill_type">
                        <option value="">全部</option>
                        <?php
                        foreach($dic['planlistType'] as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php if (\Core\Lib::request('bill_type') == $key) {
                                echo 'selected';
                            } ?>><?php echo $value; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </li>
                <li>
                    <label>卡类型：</label>
                    <select name="card_type">
                        <option value="">全部</option>
                        <?php
                        foreach($dic['cardType'] as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php if (\Core\Lib::request('card_type') == $key) {
                                echo 'selected';
                            } ?>><?php echo $value; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </li>
                <li>
                    <label>入库方式：</label>
                    <select name="intatus">
                        <option value="">全部</option>
                        <?php
                        foreach($dic['InStatus'] as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php if (\Core\Lib::request('intatus') == $key) {
                                echo 'selected';
                            } ?>><?php echo $value; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                </li>
                <li>
                    <label>通道：</label>
                    <select name="channel">
                        <option value="">全部</option>
                        <?php
                        foreach($dic['channel'] as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php if (\Core\Lib::request('channel') == $key) {
                                echo 'selected';
                            } ?>><?php echo $value['name']; ?>
                            </option>
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
                        foreach($dic['bindcardStatus'] as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php if (\Core\Lib::request('status') == $key) {
                                echo 'selected';
                            } ?>><?php echo $value; ?>
                            </option>
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

            <li><a class="add" rel="billAdd" title="添加账单" href="<?=\Core\Lib::getUrl('Bill', 'add');?>" target="dialog" width="650" height="500"><span>添加</span></a></li>
            <li><a class="edit" rel="billedit" title="修改账单" href="<?=\Core\Lib::getUrl('Bill', 'edit','id={id}');?>" target="dialog" width="650" height="500"><span>编辑</span></a></li>
            <li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Bill', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="<?=\Core\Lib::getUrl('Bill','delAll');?>" class="delete"><span>批量删除</span></a></li>

        </ul>
    </div>
    <table class="list" width="100%" layoutH="120">
        <thead>
        <tr>
            <th align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
            <th align="center" orderField="A.id" class="<?=($data['orderField']=='A.id')?$data['orderDirection']:'';?>">账单ID</th>
            <th align="center" orderField="plan_id" width="100" class="<?=($data['orderField']=='plan_id')?$data['orderDirection']:'';?>">还款计划ID</th>
            <th align="center" orderField="B.real_name" class="<?=($data['orderField']=='B.real_name')?$data['orderDirection']:'';?>">用户名称</th>
            <th align="center" orderField="C.real_name" class="<?=($data['orderField']=='C.real_name')?$data['orderDirection']:'';?>">代理名称</th>
            <th align="center" orderField="amount" class="<?=($data['orderField']=='amount')?$data['orderDirection']:'';?>">金额</th>
            <!--            <th align="center" orderField="bank_id" class="--><?//=($data['orderField']=='bank_id')?$data['orderDirection']:'';?><!--">银行ID</th>-->
            <th align="center" orderField="bank_id" class="<?=($data['orderField']=='bank_id')?$data['orderDirection']:'';?>">银行名称</th>
            <th align="center" orderField="card_no" class="<?=($data['orderField']=='card_no')?$data['orderDirection']:'';?>">卡号</th>
            <th align="center" orderField="bill_type" class="<?=($data['orderField']=='bill_type')?$data['orderDirection']:'';?>">类型</th>
            <th align="center" orderField="poundage" class="<?=($data['orderField']=='poundage')?$data['orderDirection']:'';?>">手续费</th>
            <th align="center" orderField="card_type" class="<?=($data['orderField']=='card_type')?$data['orderDirection']:'';?>" >卡类型</th>
            <th align="center" orderField="order_sn" class="<?=($data['orderField']=='order_sn')?$data['orderDirection']:'';?>">订单号</th>

            <th align="center" orderField="task_no" class="<?=($data['orderField']=='task_no')?$data['orderDirection']:'';?>">任务序号</th>
            <th align="center" orderField="transaction_id" class="<?=($data['orderField']=='transaction_id')?$data['orderDirection']:'';?>">外部订单号</th>
            <th align="center" orderField="B.status" class="<?=($data['orderField']=='B.status')?$data['orderDirection']:'';?>">状态</th>
            <th align="center" orderField="channel" class="<?=($data['orderField']=='channel')?$data['orderDirection']:'';?>"" class="<?=($data['orderField']=='B.status')?$data['orderDirection']:'';?>">通道</th>
            <th align="center" orderField="A.create_time" class="<?=($data['orderField']=='A.create_time')?$data['orderDirection']:'';?>">创建时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
                <td align="center"><?=$v['id']?></td>
                <td align="center" class="thisclick" vals="<?=$v['plan_id']?>" vid="<?=$v['id']?>" vname="plan_id" ><?=$v['plan_id']?></td>
                <td align="center">
                    <?php
                    if($v['real_name']!=null){
                        echo  \Core\Lib::starReplace($v['real_name']);
                    }else{
                        echo  $v['real_name'];
                    }
                    ?>

                </td>
                <td align="center"><?=\Core\Lib::starReplace($v['agent_real_name'])?></td>
                <td align="center" class="thisclick" vals="<?=$v['amount']?>" vid="<?=$v['id']?>" vname="amount" ><?=$v['amount']?></td>

                <!--                <td align="center">--><?//=$v['bank_id']?><!--</td>-->
                <td align="center"><?=$v['bank_name']?></td>
                <td align="center" class="thisclick" vals="<?=\Core\Lib::aesDecrypt($v['card_no'])?>" vid="<?=$v['id']?>" vname="card_no" ><?php echo \Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['card_no']))?></td>
                <td align="center"><?php echo $dic['planlistType'][$v['bill_type']];?></td>
                <td align="center" class="thisclick" vals="<?=$v['poundage']?>" vid="<?=$v['id']?>" vname="poundage" ><?=$v['poundage']?></td>
                <td align="center"><?php echo $dic['cardType'][$v['card_type']];?></td>
                <td align="center" class="thisclick" vals="<?=$v['order_sn']?>" vid="<?=$v['id']?>" vname="order_sn" ><?=$v['order_sn']?></td>

                <td align="center" class="thisclick" vals="<?=$v['task_no']?>" vid="<?=$v['id']?>" vname="task_no" ><?=$v['task_no']?></td>
                <td align="center" class="thisclick" vals="<?=$v['transaction_id']?>" vid="<?=$v['id']?>" vname="transaction_id" ><?=$v['transaction_id']?></td>
                <td align="center"><?php echo $dic['bindcardStatus'][$v['status']];?></td>
                <td align="center"><?php echo $dic['channel'][$v['channel']]['name'];?></td>
                <td align="center"><?=\Core\Lib::uDate("Y-m-d H:i:s", $v['create_time']);?></td>
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
<script>
    var offstr=1;
    $(".thisclick").dblclick(function(){

        if(offstr){
            offstr=0;
            var str=$(this).attr("vals");
            var vid=$(this).attr("vid");
            var vname=$(this).attr("vname");
            $(this).html("");
            var inputstr="<input class='thisinput"+vid+"' style='width:150px;' name='"+vname+"'   onblur='thisfocusout("+vid+")'  value='"+str+"' />";
            $(this).html(inputstr);
            $(".thisinput"+vid).focus();
        }



    })
    function thisfocusout(vid){
        var vname=$(".thisinput"+vid).attr("name");
        var vstr=$(".thisinput"+vid).val();

        $.post("<?php echo \Core\Lib::getUrl('bill', 'upedit');?>",{'vid':vid,'vname':vname,'vstr':vstr},function(data){
            offstr=1;
            navTabPageBreak();
            //$(".thisinput"+vid).parent("td").html(vstr);
        },'json')
    }
</script>
