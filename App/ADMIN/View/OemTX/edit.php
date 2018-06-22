<?php $dictionary = \Core\Lib::loadFile('Config/Dictionary.php');?>
<div class="pageContent">
    <form method="post" action="<?php echo \Core\Lib::getUrl('OemTX','edit', 'id=' . $list['id'] .'&act=edit&appid='.\Core\Lib::request('appid'));?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>申请人：</dt>
                    <dd>
                        <input type="text" style="width:200px;" name="oem_name" class="required" maxlength="100" value="<?=$list['oem_name']?>" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>金额：</dt>
                    <dd>
                        <input type="text" style="width:200px;" name="oem_amount" class="required number" maxlength="20" value="<?=$list['oem_amount']?>" />&nbsp;&nbsp;每次提现不能小于100
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>描述：</dt>
                    <dd>
                        <textarea autofocus required cols="1" rows="1" maxlength="100" name="oem_desciption" style="height: 18px;width: 250px;resize: none;"><?=$list['oem_desciption']?></textarea>
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