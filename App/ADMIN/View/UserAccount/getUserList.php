<form id="pagerForm" action="<?php echo \Core\Lib::getUrl('user','agent');?>">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" method="post" action="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" onsubmit="return dwzSearch(this, 'dialog');">
        <div class="searchBar">
            <ul class="searchContent">
                <li>
                    <label>用户名：</label>
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
        <tr>
            <th align="center" orderfield="real_name">用户姓名</th>
            <th align="center" orderfield="mobile">手机号码</th>
            <th align="center" orderfield="id_card">身份证号</th>
            <th align="center" width="80">查找带回</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr>
                <td align="center"><?=$v['real_name']?></td>
                <td align="center"><?=$v['mobile']?></td>
                <td align="center"><?=\Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['id_card']));?></td>
                <td>
                    <a class="btnSelect" href="javascript:$.bringBack({id:'<?=$v['id']?>',real_name:'<?=$v['real_name']?>'})" title="查找带回">选择</a>
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

