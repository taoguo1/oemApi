<form id="pagerForm" action="<?php echo \Core\Lib::getUrl('agent','parentAgent');?>">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
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
            <th align="center" class="">编号</th>
            <th align="center" class="">手机号</th>
            <th align="center" class="">昵称</th>
            <th align="center" class="">身份证号</th>
            <th align="center" class="">上级代理</th>
            <th align="center" class="">代理级别</th>
            <th align="center" class="">是否实名认证</th>
            <th align="center" class="">状态</th>
            <th width="60" align="center">选择</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(!empty($agentArry)){
        foreach ($data['list'] as $k=>$v){

                if(!in_array($v['id'],$agentArry)) {

                    ?>

                    <tr target="id" rel="<?= $v['id'] ?>">
                        <td align="center"><?= $v['id'] ?></td>
                        <td align="center"><?= $v['mobile'] ?></td>
                        <td align="center"><?= $v['nickname'] ?></td>
                        <td align="center"><?= \Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['id_card'])); ?></td>
                        <td align="center"><?php
                            if ($v['pid'] === '0') {
                                echo "无";
                            } else {
                                echo $v['pname'];
                            }
                            ?></td>
                        <td align="center"><?= $v['level'] ?></td>
                        <td align="center"><?= ($v['is_id_card_auth'] == 1) ? '已通过' : '<font style="color:red">未认证' . '</font>' ?></td>
                        <td align="center"><?= ($v['status'] == 1) ? '正常' : '<font style="color:red">禁用' . '</font>' ?></td>
                        <td align="center">
                            <a href="javascript:$.bringBack({agent_id:'<?= $v['id'] ?>',agent_name:'<?= $v['nickname'] ?>',agent_mobile:'<?= $v['mobile'] ?>'})"
                               title="查找带回">选择</a>
                        </td>
                    </tr>
                    <?php
                }
           }
        }?>
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

