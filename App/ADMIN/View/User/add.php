<div class="pageContent">
    <form method="post" action="<?=\Core\Lib::getUrl('user','add','act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>真实姓名：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="real_name" class="required textInput" maxlength="20" value=""  />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>密码：</dt>
                    <dd>
                        <input type="password" id="w_validation_pwd" style="width:300px;" name="password" class="required alphanumeric" minlength="6" maxlength="20" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>确认密码：</dt>
                    <dd>
                        <input type="password" style="width:300px;" name="repassword" class="required" equalto="#w_validation_pwd" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>手机号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="mobile" class="required phone" maxlength="11" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>身份证号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="id_card" class="required id_card" minlength="18" maxlength="18" value=""/>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>性别：</dt>
                    <dd>
                        <select name="sex" class="required" value="">
                            <option value="">请选择</option>
                            <?php foreach($sexItems as $k=>$v){?>
                                <option value="<?php echo $k;?>"><?php echo $v;?></option>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>
            </div>
            <!--<div class="unit">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <label>图片：</label>
                            <input type="text" id="user_pic" placeholder="点击上传图片" style="width:300px;" name="avatar" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','id=user_pic&path=avatar')?>')" />
                            <span style="line-height: 24px; padding-left: 10px;" onclick="$('#user_pic').val('');$('#user_pic_img').attr('src','')">清除</span>
                        </td>
                        <td width="60" align="center">
                            <img src="" id="user_pic_img" onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" />
                        </td>
                    </tr>
                </table>
            </div>-->
            <div class="unit">
                <dl>
                    <dt>所属代理：</dt>
                    <dd>
                        <input type="text"  class="" name="agent.agent_name" value="" lookupGroup="agent" readonly/>
                        <input type="hidden"  class="" name="agent.agent_id" value="" lookupGroup="agent" />

                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('user','agent');?>" lookupGroup="agent">选择代理</a>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>余额：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="balance" class="number" placeholder="0.00" value=""/>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>支付密码：</dt>
                    <dd>
                        <input type="password" style="width:300px;" name="pay_password" class="digits" minlength="6" maxlength="6" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>邀请码：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="invite_code" class="required alphanumeric" maxlength="32" value=""  />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>是否实名认证：</dt>
                    <dd>
                        <select name="is_id_card_auth" class="required" value="">
                            <option value="">请选择类型</option>
                            <?php foreach($userAuth as $k=>$v){?>
                                <option value="<?php echo $k;?>"><?php echo $v;?></option>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>是否推送：</dt>
                    <dd>
                        <select name="is_push"  value="">
                            <option value="">请选择类型</option>
                            <?php foreach($userPush as $k=>$v){?>
                                <option value="<?php echo $k;?>"><?php echo $v;?></option>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>状态：</dt>
                    <dd>
                        <select name="status" class="required" value="">
                            <option value="">请选择类型</option>
                            <?php foreach($userState as $k=>$v){?>
                                <option value="<?php echo $k;?>"><?php echo $v;?></option>
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
