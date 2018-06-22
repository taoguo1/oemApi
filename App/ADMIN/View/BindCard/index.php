<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('BindCard')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li>
                    <label>用户：</label>
                    <input type="text"  class="user" style="" name="user.real_name" value="<?=\Core\Lib::request('user_real_name')?>" placeholder="全部" lookupGroup="user" readonly/>
                    <input type="hidden"  class="user" name="user.id" value="<?=\Core\Lib::request('user_id')?>" lookupGroup="user" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" lookupGroup="user">选择用户</a>
                    &nbsp;
                    <a title="删除"  href="javascript:void(0)" onclick="$('.user').val('')" class="btnDel" style="float: right">删除</a>
                </li>
                <li>
                    <label>卡类型：</label>
                    <select name="card_type" class="">
                        <option value="">全部</option>
                        <?php
                        foreach ($cardType as $k=>$v) {
                            ?>
                            <option value="<?php echo $k ?>" <?php if(\Core\Lib::request('card_type')==$k){echo 'selected';}?>><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </li>
                <li>
                    <label>银行：</label>
                    <select name="bank_id" class="">
                        <option value="">全部</option>
                        <?php
                        foreach ($bank as $k=>$v) {
                            if($v['bank_type']==1) {
                                ?>
                                <option value="<?php echo $k ?>" <?php if(\Core\Lib::request('bank_id')==$k){echo 'selected';}?>><?php echo $v['name'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </li>

                <li>
                    <label>通道类型： </label>
                    <select name="channel" class="">
                        <option value="">全部</option>
                        <?php
                        foreach ($channel as $k=>$v) {
                            ?>
                            <option value="<?php echo $k ?>" <?php if(\Core\Lib::request('channel')==$k){echo 'selected';}?>><?php echo $v['name'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </li>
                <li>
                    <label>状态： </label>
                    <select name="status">
                        <option value="">全部</option>
                        <?php
                        foreach ($bindcardStatus as $k=>$v) {
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
                    <label>&nbsp; </label>
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
            <li class=""><a class="icon" onclick="navTabPageBreak()" href="javascript:;"><span>刷新</span></a></li>
        </ul>
    </div>
    <table class="list" width="100%" layoutH="90">
        <thead>
        <tr>
            <th width="20"  align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
            <th width="30" align="center" orderField="B.id" class="<?=($data['orderField']=='B.id')?$data['orderDirection']:'';?>">编号</th>
            <th width="40" align="center" orderField="B.bank_name" class="<?=($data['orderField']=='B.bank_name')?$data['orderDirection']:'';?>">银行</th>
            <th width="60" align="center" orderField="card_no" class="<?=($data['orderField']=='card_no')?$data['orderDirection']:'';?>">卡号</th>
            <th width="60" align="center" orderField="B.id_card" class="<?=($data['orderField']=='B.id_card')?$data['orderDirection']:'';?>">身份证</th>
            <th width="60" align="center" orderField="B.card_type" class="<?=($data['orderField']=='B.card_type')?$data['orderDirection']:'';?>" >卡类型</th>
<!--            <th width="60" align="center" >错误描述</th>-->
            <th width="60" align="center" orderField="B.status" class="<?=($data['orderField']=='B.status')?$data['orderDirection']:'';?>" >状态</th>
            <th width="60" align="center" orderField="B.channel" class="<?=($data['orderField']=='B.channel')?$data['orderDirection']:'';?>">通道</th>
            <th  width="100" align="center" orderField="B.create_time" class="<?=($data['orderField']=='B.create_time')?$data['orderDirection']:'';?>">创建时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['bank_name']?></td>
                <td align="center"><?=\Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['card_no']));?></td>
                <td align="center"><?=\Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['id_card']));?></td>
                <td align="center"><?=($v['card_type']==1)?'信用卡':'储蓄卡'?></td>
<!--                <td align="center">--><?//=$v['description']?><!--</td>-->
                <td align="center" <?=($v['status']==1)? '':'style="color:red"'?>><?=$bindcardStatus[$v['status']]?></td>
                <td align="center"><?=$channel[$v['channel']]['name']?></td>
                <td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time']);?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>显示</span> <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                <option value="25" <?php if($data['numPerPage']=='25'){echo 'selected';}?>>25</option>
                <option value="50" <?php if($data['numPerPage']=='50'){echo 'selected';}?>>50</option>
                <option value="100" <?php if($data['numPerPage']=='100'){echo 'selected';}?>>100</option>
                <option value="200" <?php if($data['numPerPage']=='200'){echo 'selected';}?>>200</option>
            </select> <span>条，共<?php echo $data['totalCount']?>条</span>
        </div>
        <div class="pagination" targetType="navTab" totalCount="<?php echo $data['totalCount']?>" numPerPage="<?php echo $data['numPerPage']?>" pageNumShown="10" currentPage="<?php echo $data['pageNum']?>"></div>
    </div>
</div>

