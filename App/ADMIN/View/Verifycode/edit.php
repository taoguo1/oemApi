<div class="pageContent">
    <form method="post" action="<?=\Core\Lib::getUrl('Verifycode','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>验证码：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="code" class="required digits" minlength="6" maxlength="6" value="<?php echo $list['code'];?>" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>手机号码：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="mobile" class="required phone" value="<?php echo $list['mobile'];?>"  />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>状态：</dt>
                    <dd>
                        <select name="status" class="required" <?php echo $list['status'];?>>
                            <option value="">请选择状态</option>
                            <option value="1" <?php if($list['status']==1){echo 'selected';}?>>未使用</option>
                            <option value="2" <?php if($list['status']==2){echo 'selected';}?>>已使用</option>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>添加时间：</dt>
                    <dd>
                        <input type="text" name="create_time" dateFmt="yyyy-MM-dd HH:mm:ss" readonly class="required date" value="<?=\Core\Lib::uDate("Y-m-d H:i:s", $list['create_time']);?>" />
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
