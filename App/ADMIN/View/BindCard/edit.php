<div class="pageContent">
    <form method="post" action="<?=\Core\Lib::getUrl('BindCard','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>用户ID：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="user_id" class="required digits" value="<?php echo $list['user_id'];?>" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>银行ID：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="bank_id" class="required digits" value="<?php echo $list['bank_id'];?>"  />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>身份证号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="idcard" class="required"  minlength="18" maxlength="18" value="<?php echo $list['idcard'];?>"  />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>卡类型：</dt>
                    <dd>
                        <select name="type" class="required">
                            <option value="">请选择卡类型</option>
                            <option value="1" <?php if($list['type']==1){echo 'selected';}?>>信用卡</option>
                            <option value="2" <?php if($list['type']==2){echo 'selected';}?>>储蓄卡</option>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>错误描述：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="description" class="required" value="<?php echo $list['description'];?>" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>状态：</dt>
                    <dd>
                        <select name="status" class="required">
                            <option value="">请选择状态</option>
                            <option value="1" <?php if($list['status']==1){echo 'selected';}?>>成功</option>
                            <option value="2" <?php if($list['status']==2){echo 'selected';}?>>失败</option>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>通道：</dt>
                    <dd>
                        <select name="channel" class="required">
                            <option value="">请选择通道</option>
                            <option value="1" <?php if($list['channel']==1){echo 'selected';}?>>易联</option>
                            <option value="2" <?php if($list['channel']==2){echo 'selected';}?>>易宝</option>
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
