<form id="pagerForm" action="<?php echo \Core\Lib::getUrl('Bill','users');?>">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" method="post" action="<?php echo \Core\Lib::getUrl('Bill','users');?>" onsubmit="return dwzSearch(this, 'dialog');">
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
            </ul>
            <div class="subBar">
                <ul>
                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">查询</button></div></div></li>
                </ul>
            </div>
        </div>
    </form>
</div>
<div class="pageContent">
    <table class="table" layoutH="118" targetType="dialog" width="100%">
        <thead>
        <tr align="center">
            <th orderfield="A.id" class="<?=($data['orderField']=='A.id')?$data['orderDirection']:'';?>">编号</th>
            <th orderfield="A.real_name" class="<?=($data['orderField']=='A.real_name')?$data['orderDirection']:'';?>">姓名</th>
            <th orderfield="B.real_name" class="<?=($data['orderField']=='B.real_name')?$data['orderDirection']:'';?>">代理姓名</th>
            <th orderfield="A.mobile" class="<?=($data['orderField']=='A.mobile')?$data['orderDirection']:'';?>">手机号</th>
            <th orderfield="A.is_id_card_auth" class="<?=($data['orderField']=='A.is_id_card_auth')?$data['orderDirection']:'';?>">实名认证</th>
            <th orderfield="A.status" class="<?=($data['orderField']=='A.status')?$data['orderDirection']:'';?>">状态</th>
            <th width="80">查找带回</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr align="center">
                <td><?=$v['id']?></td>
                <td><?=$v['real_name']?></td>
                <td><?=$v['agent_name']?></td>
                <td><?=\Core\Lib::strReplace($v['mobile'])?></td>
                <td><?=($v['is_id_card_auth']==1)?'已通过':'<font style="color:red">未认证'.'</font>'?></td>
                <td><?=($v['status']==1)?'正常':'<font style="color:red">禁用'.'</font>'?></td>
                <td>
                    <a class="btnSelect" href="javascript:$.bringBack({id:'<?=$v['id']?>',real_name:'<?=$v['real_name']?>',agent_id:'<?=$v['agent_id']?>'})" title="查找带回">选择</a>
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

