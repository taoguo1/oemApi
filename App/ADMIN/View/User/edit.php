<div class="pageContent">
    <form method="post" action="<?=\Core\Lib::getUrl('user','edit','id='.$data['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>真实姓名：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="real_name" class=" textInput" maxlength="200" value="<?php echo $data['real_name']?>"  />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>手机号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="mobile" class="required phone" maxlength="11" value="<?php echo $data['mobile']?>" readonly/>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>身份证号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="id_card" class="id_card" minlength="18" maxlength="18" value="<?=\Core\Lib::aesDecrypt($data['id_card']); ?> " readonly/>
                    </dd>
                </dl>
            </div>
            <!--<div class="unit">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <label>头像：</label>
                            <input type="text" id="article_pic" placeholder="点击上传头像" style="width:300px;" name="avatar" value="<?php echo $data['avatar']?>" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','id=article_pic&path=avatar')?>')" />
                            <span style="line-height: 24px; padding-left: 10px;" onclick="$('#article_pic').val('');$('#article_pic_img').attr('src','')">清除</span>
                        </td>
                        <td width="60" align="center">
                            <img src="<?php echo APP_SITE_PATH.$data['avatar'];?>" id="article_pic_img" onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" />
                        </td>
                    </tr>
                </table>
            </div>-->
            <div class="unit">
                <dl>
                    <dt>性别：</dt>
                    <dd>
                        <select name="sex" class="" value="<?php echo $data['sex']?>">
                            <option value="">请选择</option>
                            <?php foreach($sexItems as $k=>$v){?>
                                <option value="<?php echo $k;?>" <?php if($data['sex']==$k){echo 'selected';}?>><?php echo $v;?></option>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>所属代理：</dt>
                    <dd>
                        <input type="text"  class="" name="agent.agent_name" value="<?php echo $data['agentname']?>" lookupGroup="agent" readonly/>
                        <input type="hidden"  class="" name="agent.agent_id" value="<?php echo $data['agent_id']?>" lookupGroup="agent" />

                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('user','agent');?>" lookupGroup="agent">选择代理</a>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>余额：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="balance" class="number" placeholder="0.00" value="<?php echo empty($data['balance']) ? 0 : $data['balance']?>"  />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>支付密码：</dt>
                    <dd>
                        <input type="password" style="width:300px;" name="pay_password" class="number" minlength="6" maxlength="6" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>是否实名认证：</dt>
                    <dd>
                        <select name="is_id_card_auth" class="required" value="<?php echo $data['is_id_card_auth']?>">
                            <option value="">请选择类型</option>
                            <?php foreach($userAuth as $k=>$v){?>
                                <option value="<?php echo $k;?>" <?php if($data['is_id_card_auth']==$k){echo 'selected';}?>><?php echo $v;?></option>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>是否推送：</dt>
                    <dd>
                        <select name="is_push" value="<?php echo $data['is_push']?>">
                            <option value="">请选择类型</option>
                            <?php foreach($userPush as $k=>$v){?>
                                <option value="<?php echo $k;?>" <?php if($data['is_push']==$k){echo 'selected';}?>><?php echo $v;?></option>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>状态：</dt>
                    <dd>
                        <select name="status" class="required" value="<?php echo $data['status']?>">
                            <option value="">请选择类型</option>
                            <?php foreach($userState as $k=>$v){?>
                                <option value="<?php echo $k;?>" <?php if($data['status']==$k){echo 'selected';}?>><?php echo $v;?></option>
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
