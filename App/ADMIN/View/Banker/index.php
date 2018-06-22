<?php $dictionary = \Core\Lib::loadFile('Config/Dictionary.php');?>
<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('Banker')?>" method="post">
        <div class="searchBar">
            <ul class="searchContent">
                <li><label>银行名字：</label> <input type="text" name="name" value="<?=\Core\Lib::request('name')?>" /></li>
                 <li><label>银行编号：</label> <input type="text" name="description"  /></li>
                <li>
                    <label>是否显示：</label>
                    <select name="isDisplay">
                        <option value="">全部</option>
                        <?php
                        foreach ($dictionary['userPush'] as $k=>$v) {
                            ?>
                            <option value="<?php echo $k ?>" <?php if(\Core\Lib::request('isDisplay')==$k){echo 'selected';}?>><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </li>

                <li><label>时间查询：</label>
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
			<li><a class="add" rel="BankAdd" title="添加银行" href="<?=\Core\Lib::getUrl('Banker', 'add');?>" target="dialog"><span>添加</span></a></li>
			<li><a title="确定要删除吗？" target="ajaxTodo" href="<?=\Core\Lib::getUrl('Banker', 'del','id={id}');?>" class="delete"><span>删除</span></a></li>
			<li><a class="edit"  title="编辑银行" rel="Bankedit" href="<?=\Core\Lib::getUrl('Banker', 'edit','id={id}');?>" target="dialog"><span>编辑</span></a></li>
		</ul>
    </div>
    <table class="list" width="100%" layoutH="90">
        <thead>
        <tr>
         <th align="center"     orderField="id" class="<?=($data['orderField']=='id')?$data['orderDirection']:'';?>">ID</th>
            <th align="center"  orderField="name" class="<?=($data['orderField']=='name')?$data['orderDirection']:'';?>">银行名字</th>
            <th align="center"  orderField="description" class="<?=($data['orderField']=='description')?$data['orderDirection']:'';?>">编号</th>
            <th align="center"  orderField="url" class="<?=($data['orderField']=='url')?$data['orderDirection']:'';?>">地址</th>
            <th align="center"  orderField="img" class="<?=($data['orderField']=='img')?$data['orderDirection']:'';?>">图片</th>
            <th align="center"  orderField="isDisplay" class="<?=($data['orderField']=='isDisplay')?$data['orderDirection']:'';?>">状态</th>
            <th align="center"  orderField="create_time" class="<?=($data['orderField']=='create_time')?$data['orderDirection']:'';?>">时间</th>
          
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=$v['name']?></td>
                <td align="center"><?=$v['description']?></td>
                <td align="center"><?=$v['url']?></td>
                <td align="center"><img width="60" height="60" src="<?=OSS_ENDDOMAIN.'/'.$v['img']?>" style="margin:5px 0;"></td>
                <td align="center"><?=($v['isDisplay']==1)?'显示':'不显示'?></td>
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
