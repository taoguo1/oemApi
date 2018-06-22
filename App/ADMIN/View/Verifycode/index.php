<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('Verifycode')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">

                <li><label>手机号：</label> <input type="text" name="mobile" value="<?=\Core\Lib::request('mobile')?>" /></li>
                <li>
                    <label>状态：</label>
                    <select name="status">
                        <option value="">全部</option>
                        <option value="1" <?php if(\Core\Lib::request('status')==1){echo 'selected';}?>>未使用</option>
                        <option value="2" <?php if(\Core\Lib::request('status')==2){echo 'selected';}?>>已使用</option>
                    </select>
                </li>
                <li><label>添加时间：</label>
                    <input type="text" class="date" size="10" name="start_date" value="<?=\Core\Lib::request('start_date')?>" />
                    至
                    <input type="text" class="date" size="10" name="end_date" value="<?=\Core\Lib::request('end_date')?>" />
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
            <li><a class="add" rel="verifycodeAdd" title="添加短信发送" href="<?=\Core\Lib::getUrl('Verifycode', 'add');?>" target="dialog"><span>添加</span></a></li>
            <li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Verifycode', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="ids" postType="string" href="<?=\Core\Lib::getUrl('Verifycode','delAll');?>" class="delete"><span>批量删除</span></a></li>
            <li><a class="edit"  title="编辑短信发送" rel="verifycodeEdit" href="<?=\Core\Lib::getUrl('Verifycode', 'edit','id={id}');?>" target="dialog"><span>编辑</span></a></li>
        </ul>
    </div>
    <table class="list" width="100%" layoutH="90">
        <thead>
        <tr>
            <th align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
            <th align="center" orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">验证码ID</th>
            <th align="center" orderField="code" class="<?=($data['orderField']=='code')?$data['orderDirection']:'';?>">验证码</th>
            <th align="center" orderField="mobile" class="<?=($data['orderField']=='mobile')?$data['orderDirection']:'';?>">手机号码</th>
            <th align="center" orderField="status" class="<?=($data['orderField']=='status')?$data['orderDirection']:'';?>">状态</th>
            <th align="center" orderField="create_time" class="<?=($data['orderField']=='create_time')?$data['orderDirection']:'';?>">添加时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['code']?></td>
                <td align="center"><?=\Core\Lib::strReplace($v['mobile'],3,4)?></td>
                <td align="center"><?=($v['status']==1)?'未使用':'已使用'?></td>
                <td align="center"><?=\Core\Lib::uDate("Y-m-d H:i:s x", $v['create_time']);?></td>
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
