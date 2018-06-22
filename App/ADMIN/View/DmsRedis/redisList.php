    <form id="pagerForm" method="post" action="<?=\Core\Lib::getUrl('DmsRedis', 'redisList','keys='.$keys."&type=".$type);?>">
        <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
        <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
        <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
        <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
    </form>
<div class="pageContent" style="border-left:1px #B8D0D6 solid;border-right:1px #B8D0D6 solid">
    <div class="panelBar">
        <ul class="toolBar">
            <li><a class="icon" onclick="navTabPageBreak('','<?=$type?>')" title="" rel="" href="javascript:;"  width="650" height="420"><span>刷新</span></a></li>
        </ul>
    </div>
    <table class="list" width="99%" layoutH="113" rel="<?=$type?>">
        <thead>
        <tr>
            <th align="center" width="80%">Value</th>
            <th align="center" width="20%">Score</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$keys?>">
                <td align="center" style="word-break:break-all"><?=$k?></td>
                <td align="center"><?= $v ?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>显示</span>
            <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value}, '<?=$type?>')">
                <option value="25" <?php if($data['numPerPage']=='25'){echo 'selected';}?>>25</option>
                <option value="50" <?php if($data['numPerPage']=='50'){echo 'selected';}?>>50</option>
                <option value="100" <?php if($data['numPerPage']=='100'){echo 'selected';}?>>100</option>
                <option value="200" <?php if($data['numPerPage']=='200'){echo 'selected';}?>>200</option>
            </select> <span>条，共<?php echo $data['totalCount']?>条</span>
        </div>

        <div class="pagination" targetType="navTab" rel="<?=$type?>" totalCount="<?php echo $data['totalCount']?>" numPerPage="<?php echo $data['numPerPage']?>" pageNumShown="10" currentPage="<?php echo $data['pageNum']?>"></div>

    </div>
</div>