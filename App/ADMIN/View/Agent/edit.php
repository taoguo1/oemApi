<?php $dictionary = \Core\Lib::loadFile('Config/Dictionary.php');?>
<div class="pageContent">
    <form method="post" action="<?=\Core\Lib::getUrl('Agent','edit','id=' . $list['id'] . '&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>手机：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="mobile" class="required mobile" maxlength="11" value="<?php echo $list['mobile']?>" readonly/>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>昵称：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="nickname" class="required" minlength="2" maxlength="20" value="<?php echo $list['nickname']?>" />
                    </dd>
                </dl>
            </div>
            <!--<div class="unit">
                <dl>
                    <dt>真实姓名：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="real_name" class=" textInput" maxlength="20" value="<?php echo $list['real_name']?>" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>身份证号码：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="id_card" class="" minlength="18" maxlength="18" value="<?=\Core\Lib::aesDecrypt($list['id_card']); ?>" readonly/>
                    </dd>
                </dl>
            </div>-->
            <div class="unit">
                <dl>
                    <dt>上级代理：</dt>
                    <dd>
                        <input type="text" readonly class="" name="agent.agent_name" value="<?php echo $agentInfo?>" lookupGroup="agent" />
                        <input type="hidden"  class="" name="agent.agent_id" value="<?php echo $list['pid']?>" lookupGroup="agent" />
                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('agent','parentAgentNext','id='.$id);?>" lookupGroup="agent">选择上级代理</a>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>分润比例（万分之）：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="rate" class="required number" maxlength="2" value="<?php echo $list['rate']?>" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>收款分润比例（万分之）：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="skrate" class="number" maxlength="2" value="<?php echo $list['skrate']?>" />
                    </dd>
                </dl>
            </div>

            <!--<div class="unit">
                <dl>
                    <dt>累计佣金：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="total_commission" class="required number" onBlur="commission(this)"  value="<?php echo $list['total_commission']?>"  />
                    </dd>
                </dl>
            </div>-->
            <!-- <div class="unit">
                <dl>
                    <dt>是否实名认证：</dt>
                    <dd>
                        <select name="is_id_card_auth" class="required" value="<?php echo $list['is_id_card_auth']?>">
                            <option value="">请选择类型</option>
                            <?php
                                foreach($dictionary['userAuth'] as $k=>$v){?>
                                <option value="<?php echo $k;?>" <?php if($list['is_id_card_auth']==$k){echo 'selected';}?>><?php echo $v;?></option>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>
            </div>-->
             <div class="unit">
                <dl>
                    <dt>状态：</dt>
                    <dd>
                        <select name="status" class="required" value="<?php echo $list['status']?>">
                            <option value="">请选择类型</option>
                            <?php
                            foreach($dictionary['userState'] as $k=>$v){?>
                                <option value="<?php echo $k;?>" <?php if($list['status']==$k){echo 'selected';}?>><?php echo $v;?></option>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="formBar">
            <ul>
                <li>
                    <div class="button">
                        <div class="buttonContent">
                            <button type="submit">保存</button>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="button">
                        <div class="buttonContent">
                            <button type="button" class="close">取消</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>

<script>
    function commission(opp) {
        var arr = 0;
        var total_commission=$(opp).val();
        if(total_commission.length !=0){
            arr=parseInt(total_commission)/298;
        }
        $("#invite_code_num").val(parseInt(arr));
    }
</script>
