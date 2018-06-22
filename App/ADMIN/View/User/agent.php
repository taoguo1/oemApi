<form id="pagerForm" action="<?php echo \Core\Lib::getUrl('user','agent');?>">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" method="post" action="<?php echo \Core\Lib::getUrl('user','agent');?>" onsubmit="return dwzSearch(this, 'dialog');">
        <div class="searchBar">
            <ul class="searchContent">
                <li>
                    <label>姓名：</label>
                    <input type="text" name="real_name" value="<?=\Core\Lib::request('real_name')?>" />
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
            <!--<div class="subBar">
                <ul>
                    
                </ul>
            </div>-->
        </div>
    </form>
</div>
<div class="pageContent">
    <table class="table" layoutH="118" targetType="dialog" width="100%">
        <thead>
        <tr align="center">
            <th >编号</th>
            <th >手机号码</th>
            <th >姓名</th>
            <th >昵称</th>
            <th>身份证号</th>
            <!--<th >上级代理</th>
            <th >代理级别</th>-->
            <th >实名认证</th>
            <th >状态</th>
            <th width="80">查找带回</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr align="center">
                <td><?=$v['id']?></td>
                <td><?=$v['mobile']?></td>
                <td><?=$v['real_name']?></td>
                <td><?=$v['nickname']?></td>
                <td><?=\Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['id_card']))?></td>
                <!--<td><?=$v['pname']?></td>
                <td><?=$v['level']?></td>-->
                <td><?=($v['is_id_card_auth']==1)?'已通过':'<font style="color:red">未认证'.'</font>'?></td>
                <td><?=($v['status']==1)?'正常':'<font style="color:red">禁用'.'</font>'?></td>
                <td align="center">
                    <a style="margin-left: 25px;" class="btnSelect" href="javascript:$.bringBack({agent_id:'<?=$v['id']?>',agent_name:'<?=$v['nickname']?>(<?=$v['mobile']?>)',agent_mobile:'<?=$v['mobile']?>'})" title="查找带回">选择</a>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <div class="panelBar">
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

