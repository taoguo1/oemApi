<?php $dictionary = \Core\Lib::loadFile('Config/Dictionary.php');?>
<div class="pageContent">
    <form method="post" action="<?php echo \Core\Lib::getUrl('AgentAccount','add', 'act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent" layoutH="56">
            <div class="unit">
                <dl>
                    <dt>代理选择：</dt>
                    <dd>
                        <input type="text"  class="" readonly  name="agent.agent_name" lookupGroup="agent" />
                        <input type="hidden"  class="" name="agent.agent_id" value="" lookupGroup="agent" />
                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('Agent','parentAgent');?>" lookupGroup="agent">选择代理</a>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>金额：</dt>
                    <dd>
                        <input type="text" style="width:200px;" name="amount" class="required number" maxlength="20" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>订单号：</dt>
                    <dd>
                        <input type="text" style="width:200px;" name="order_sn" class="required digits" minlength="6" maxlength="20" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>描述：</dt>
                    <dd>
                        <textarea autofocus required cols="1" rows="1" maxlength="100" name="description" style="height: 18px;width: 250px;resize: none;"></textarea>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>入库方式：</dt>
                    <dd>
                        <select name="in_type" class="required">
                            <option value="">请选择类型</option>
                            <?php
                            foreach ($dictionary['InStatus'] as $k=>$v) {
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
                    <dt>通道：</dt>
                    <dd>
                        <select name="channel" class="required">
                            <option value="">请选择类型</option>
                            <?php
                            foreach ($dictionary['channel'] as $k=>$v) {
                                ?>
                                <option value="<?php echo $k ;?>"><?php echo $v['name'] ;?></option>
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