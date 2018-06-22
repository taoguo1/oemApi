<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('User')?>" method="post">

        <div class="searchBar" style="width:150%">
            <ul class="searchContent">
                <li>
                    <label>代理昵称：</label>
                    <input  type="text"  class="agent"  name="agent.agent_name" value="<?=\Core\Lib::request('agent_agent_name')?>" placeholder="全部" readonly lookupGroup="agent" />
                    <input type="hidden"  class="" name="agent.agent_id" value="" lookupGroup="agent" />
                    <a class="btnLook" href="<?php echo \Core\Lib::getUrl('agent','parentAgent');?>" lookupGroup="agent">选择代理</a>
                </li>
                <li>
                    <label>会员姓名：</label>
                    <input type="text"  size="6"  class="user" style="" name="real_name" value="<?=\Core\Lib::request('real_name')?>"   />
                </li>
                <li><label>手机：</label> <input  type="text" size="10" name="mobile" value="<?=\Core\Lib::request('mobile')?>" /></li>
                <li><label>身份证：</label> <input type="text" size="15" name="id_card" value="<?=\Core\Lib::request('id_card')?>" /></li>
                <li>
                    <label>余额：</label>
                    <input type="text" size="7" name="start_balance" value="<?=\Core\Lib::request('start_balance')?>" />
                    至
                    <input type="text" size="7" name="end_balance" value="<?=\Core\Lib::request('end_balance')?>" />
                </li>

                <li><label>注册时间：</label>
                    <input type="start_create_time" class="date textInput" size="7" name="start_create_time" value="<?=\Core\Lib::request('start_create_time')?>" />
                    至
                    <input type="end_create_time"  class="date textInput" size="7" name="end_create_time" value="<?=\Core\Lib::request('end_create_time')?>" />
                </li>
                <li>
                    <label>性别：</label>
                    <select name="sex">
                        <option value="">全部</option>
                        <?php foreach($sexItems as $k=>$v){?>
                            <option value="<?php echo $k;?>" <?php if(\Core\Lib::request('sex')==$k){echo 'selected';}?>><?php echo $v;?></option>
                        <?php } ?>
                    </select>
                </li>
                <li>
                    <label>实名认证：</label>
                    <select name="is_id_card_auth">
                        <option value="">全部</option>
                        <?php foreach($userAuth as $k=>$v){?>
                            <option value="<?php echo $k;?>" <?php if(\Core\Lib::request('is_id_card_auth')==$k){echo 'selected';}?>><?php echo $v;?></option>
                        <?php } ?>
                    </select>
                </li>

                <li>
                    <label>推送：</label>
                    <select name="is_push">
                        <option value="">全部</option>
                        <?php foreach($userPush as $k=>$v){?>
                            <option value="<?php echo $k;?>" <?php if(\Core\Lib::request('is_push')==$k){echo 'selected';}?>><?php echo $v;?></option>
                        <?php } ?>
                    </select>
                </li>
                <li>
                    <label>状态：</label>
                    <select name="status">
                        <option value="">全部</option>
                        <?php foreach($userState as $k=>$v){?>
                            <option value="<?php echo $k;?>" <?php if(\Core\Lib::request('status')==$k){echo 'selected';}?>><?php echo $v;?></option>
                        <?php } ?>
                    </select>
                </li>

                <li>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button type="submit">查询</button>
                        </div>
                    </div>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button type="reset" id="reset">重置</button>
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
            <!--<li><a class="add" rel="userAdd" title="添加用户" href="<?=\Core\Lib::getUrl('User', 'add');?>" target="dialog" width="650" height="600"><span>添加</span></a></li>
            <!--<li><a title="确定要删除吗？" target="ajaxTodo" href="<?/*=\Core\Lib::getUrl('User', 'del','id={id}');*/?>" class="delete"><span>删除/启用</span></a></li>
            <li><a class="edit"  title="编辑会员" rel="userEdit" href="<?=\Core\Lib::getUrl('User', 'edit','id={id}');?>" target="dialog"  width="700" height="550"><span>编辑</span></a></li>-->
        </ul>
    </div>
    <table class="list" width="100%" layoutH="108">
        <thead>
        <tr>
            <th align="center"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
            <th align="center" orderField="dzz_U.id" class="<?=($data['orderField']=='dzz_U.id')?$data['orderDirection']:'';?>">编号</th>
            <th align="center" orderField="dzz_U.real_name" class="<?=($data['orderField']=='dzz_U.real_name')?$data['orderDirection']:'';?>">姓名</th>
            <th align="center" orderField="dzz_U.mobile" class="<?=($data['orderField']=='dzz_U.mobile')?$data['orderDirection']:'';?>">手机</th>
            <th align="center" orderField="dzz_U.id_card" class="<?=($data['orderField']=='dzz_U.id_card')?$data['orderDirection']:'';?>">身份证号</th>
            <th align="center" orderField="dzz_U.sex" class="<?=($data['orderField']=='dzz_U.sex')?$data['orderDirection']:'';?>">性别</th>
            <th align="center" >代理昵称</th>
            <th align="center" >邀请码</th>
            <th align="center" >代理姓名</th>
            <th align="center" >代理手机</th>
            <th align="center" >余额</th>
<!--            <th align="center" >邀请码</th>-->
            <th align="center" orderField="dzz_U.is_id_card_auth" class="<?=($data['orderField']=='dzz_U.is_id_card_auth')?$data['orderDirection']:'';?>">实名认证状态</th>
            <!--<th align="center" ></th>-->
            <th align="center" orderField="dzz_U.is_push" class="<?=($data['orderField']=='dzz_U.is_push')?$data['orderDirection']:'';?>">推送状态</th>
            <th align="center" orderField="dzz_U.status" class="<?=($data['orderField']=='dzz_U.status')?$data['orderDirection']:'';?>">状态</th>
            <th align="center" orderField="dzz_U.create_time" class="<?=($data['orderField']=='dzz_U.create_time')?$data['orderDirection']:'';?>">创建时间</th>
            <th align="center" >最后登录时间</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['list'] as $k=>$v){?>
            <tr target="id" rel="<?=$v['id']?>">
                <td align="center"><input name="ids" value="<?=$v['id']?>" type="checkbox"></td>
                <td align="center"><?=$v['id']?></td>
                <td align="center"><?=($v['real_name']) ? \Core\Lib::starReplaceName($v['real_name']) : ""?></td>
                <td align="center"><?=$v['mobile']?></td>
                <td align="center">
				<?php 
				if(empty($v['id_card'])){
					echo $v['id_card'];
				}else{
					?>
					<?=\Core\Lib::idCardHide(\Core\Lib::aesDecrypt($v['id_card'])); ?>
				<?php 
				}
				?>
				
				</td>
                <td align="center"><?=$v['sex'] ? $sexItems[$v['sex']] :"未知"?></td>
                <td align="center"><?=$v['agentnickname']?></td>
                <td align="center"><?=$v['invite_code']?$v['invite_code']:'未绑定' ?></td>
                <td align="center"><?=$v['agentname']?></td>

                <td align="center"><?=$v['agentmobile']?></td>
                <td align="center"><?=$v['balance']?></td>
                <!--<td align="center" class="thisclick" vals="<?=$v['balance']?>" vid="<?=$v['id']?>" vname="balance"><?=$v['balance']?></td>-->
<!--                <td align="center">--><?//=$v['invite_code']?><!--</td>-->
                <td align="center"><?=($v['is_id_card_auth']==1)?'认证':'<font style="color:red">未认证'.'</font>'?></td>
                <td align="center"><?=($v['is_push']==1)?'推送':'<font style="color:red">不推送'.'</font>'?></td>
                <td align="center"><?=($v['status']==1)?'启用':'<font style="color:red">禁用'.'</font>'?></td>
                <td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['create_time']);?></td>
                <td align="center"><?= \Core\Lib::uDate('Y-m-d H:i:s x',$v['last_time']);?></td>

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
            var inputstr="<input class='thisinput"+vid+"' style='width:150px;' name='"+vname+"' onblur='thisfocusout("+vid+")'  value='"+str+"' />";
            $(this).html(inputstr);
            $(".thisinput"+vid).focus();
        }



    })

    function thisfocusout(vid){
        var vname=$(".thisinput"+vid).attr("name");
        var vstr=$(".thisinput"+vid).val();

        $.post("<?php echo \Core\Lib::getUrl('User', 'upedit');?>",{'vid':vid,'vname':vname,'vstr':vstr},function(data){
            offstr=1;
            navTabPageBreak();
            //$(".thisinput"+vid).parent("td").html(vstr);
        },'json')
    }
    $("#reset").click(function(){
        $('.nickname').val('');
    });
</script>
