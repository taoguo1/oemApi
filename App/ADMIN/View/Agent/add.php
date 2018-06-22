<?php $dictionary = \Core\Lib::loadFile('Config/Dictionary.php');?>
<style>
    #countDown{
        width: 20px;
        height: 20px;
        padding: 7px 20px;
        background-color: #689dfc;
        border-radius: 3px;
        color: #fff;
        margin-left: 10px;
        cursor: pointer;
    }
</style>
<div class="pageContent">
    <form method="post" action="<?php echo \Core\Lib::getUrl('agent','add', 'act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>手机：</dt>
                    <dd>
                        <input id="mobileNO" type="text" style="width:300px;" name="mobile" class="required mobile" maxlength="11" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>验证码：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="code" class="required code" maxlength="11" value="" />
                        <span id="countDown"  tapmode="opa-5" >获取验证码</span>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>密码：</dt>
                    <dd>
                        <input type="text" onfocus="$(this).attr('type','password')" style="width:300px;" name="password" class="required alphanumeric" minlength="6" maxlength="20" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>昵称：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="nickname" class="required" minlength="2" maxlength="20" value=""  />
                    </dd>
                </dl>
            </div>
           <!-- <div class="unit">
                <dl>
                    <dt>真实姓名：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="real_name" class="required textInput" maxlength="20"  value="" />
                    </dd>
                </dl>
            </div>-->
            <!--<div class="unit">
                <dl>
                    <dt>身份证号码：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="id_card" class="required " minlength="18" maxlength="18" value="" />
                    </dd>
                </dl>
            </div>-->

            <div class="unit">
                <dl>
                    <dt>上级代理：</dt>
                    <dd>
                        <input type="text"  class="" name="agent.agent_name"  readonly value="" lookupGroup="agent" />
                        <input type="hidden"  class="" name="agent.agent_id" value="" lookupGroup="agent" />
                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('agent','parentAgent');?>" lookupGroup="agent">选择上级代理</a>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>分润比例（万分之）：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="rate" class="required number" value=""  maxlength="2"/>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>收款分润比例（万分之）：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="skrate" class="number" maxlength="2" value="" />
                    </dd>
                </dl>
            </div>
           <!-- <div class="unit">
                <dl>
                    <dt>累计佣金：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="total_commission" class="required number" value="" onBlur="commission(this)" />
                    </dd>
                </dl>
            </div>-->
            <!--<div class="unit">
                <dl>
                    <dt>是否实名认证：</dt>
                    <dd>
                        <select name="is_id_card_auth" class="required">
                            <option value="">请选择类型</option>
                            <?php
                            foreach ($dictionary['userAuth'] as $k=>$v) {
                                ?>
                                <option value="<?php echo $k ;?>"><?php echo $v ;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </dd>
                </dl>
            </div>-->
            <div class="unit">
                <dl>
                    <dt>状态：</dt>
                    <dd>
                        <select name="status" class="required">
                            <option value="">请选择类型</option>
                            <?php
                            foreach ($dictionary['userState'] as $k=>$v) {
                                ?>
                                <option value="<?php echo $k ;?>"><?php echo $v ;?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>创建时间：</dt>
                    <dd>
                        <input type="text" name="create_time" dateFmt="yyyy-MM-dd HH:mm:ss" readonly class="required date" value="<?php echo date('Y-m-d H:i:s',time())?>"/>
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
    /*function commission(opp) {
        var arr = 0;
        var total_commission=$(opp).val();
        if(total_commission.length !=0){
            arr=parseInt(total_commission)/298;
        }
        $("#invite_code_num").val(parseInt(arr));
    }*/

    var offif=1;
   $("#countDown").click(function(){
       var mobile=$.trim($(mobileNO).val());
       var appid='<?=\Core\Lib::request("appid")?>';
       if(offif && mobile!=''){
           offif=0;
           $.post("<?php echo \Core\Lib::getUrl("Agent","sendS"); ?>",{'mobile':mobile, 'code_type':'9','appid':appid},function(ret){
               if(ret.status == 'success'){
                   var num = 60;
                   $("#countDown").text("60s");
                   var timer = setInterval(function(){
                       num--;
                       $("#countDown").text(num + "s").show();
                       if(num == 0){
                           $("#countDown").text("获取验证码");
                            offif=1;
                           clearInterval(timer);
                       }

                   },1000);

               }else{
                   offif=1;
                   alert(ret.msg);
               }
           },'json');
       }
   })

</script>