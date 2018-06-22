<div class="pageContent">
    <form method="post" action="<?php echo \Core\Lib::getUrl('InviteCodeTrade','trade','act=trade');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>卖方代理手机号：</dt>
                    <dd>
                        <input type="text"  class="required" name="before_agent.agent_mobile"  readonly  lookupGroup="before_agent" />
                        <input type="hidden"  class="" name="before_agent.agent_id" value="" lookupGroup="before_agent" />
                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('agent','parentAgent');?>" lookupGroup="before_agent">选择代理</a>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>买方代理手机号：</dt>
                    <dd>
                        <input type="text"  class="required" name="after_agent.agent_mobile"  readonly  lookupGroup="after_agent" />
                        <input type="hidden"  class="" name="after_agent.agent_id" value="" lookupGroup="after_agent" />
                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('agent','parentAgent');?>" lookupGroup="after_agent">选择代理</a>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>交易数量：</dt>
                    <dd>
                        <input type="text" style="width:100px;"  name="volume" class="required" maxlength="20" value=""  />
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