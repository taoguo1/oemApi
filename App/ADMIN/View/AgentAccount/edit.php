<?php $dictionary = \Core\Lib::loadFile('Config/Dictionary.php');?>
<div class="pageContent">
    <form method="post" action="<?php echo \Core\Lib::getUrl('AgentAccount','edit', 'id=' . $list['id'] .'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>代理选择：</dt>
                    <dd>
                        <input type="text" name="agent.agent_name" value="<?php echo $agentName?>" lookupGroup="agent" />
                        <input type="hidden"  class="" name="agent.agent_id" value="<?php echo $list['agent_id']?>" lookupGroup="agent" />
                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('Agent','parentAgent');?>" lookupGroup="agent">选择代理</a>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>金额：</dt>
                    <dd>
                        <input type="text" style="width:200px;" name="amount" class="required number" maxlength="20" value="<?php echo $list['amount']?>" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>订单号：</dt>
                    <dd>
                        <input type="text" style="width:200px;" name="order_sn" class="required digits" minlength="6" maxlength="20" value="<?php echo $list['order_sn']?>" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>描述：</dt>
                    <dd>
                        <textarea autofocus required cols="1" rows="1" maxlength="100" name="description" style="height: 18px;width: 250px;resize: none;"><?php echo $list['description']?></textarea>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>入库方式：</dt>
                    <dd>
                        <select name="in_type" class="required" value="<?php echo $list['in_type']?>">
                            <option value="">请选择类型</option>
                            <?php
                            foreach($dictionary['InStatus'] as $k=>$v){?>
                                <option value="<?php echo $k;?>" <?php if($list['in_type']==$k){echo 'selected';}?>><?php echo $v;?></option>
                            <?php } ?>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>通道：</dt>
                    <dd>
                        <select name="channel" class="required" value="<?php echo $list['channel']?>">
                            <option value="">请选择类型</option>
                            <?php
                            foreach($dictionary['channel'] as $k=>$v){?>
                                <option value="<?php echo $k;?>" <?php if($list['channel']==$k){echo 'selected';}?>><?php echo $v['name'];?></option>
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