<div class="pageContent">
    <form method="post" action="<?=\Core\Lib::getUrl('bill','add','act=add');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent" layoutH="56">

            <div class="unit">
                <dl>
                    <dt>还款计划ID：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="plan_id" class="required digits" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>金额：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="amount" class="required number" value=""  />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>用户名称：</dt>
                    <dd>
                        <input type="text" disabled  class="" name="users.real_name" value="" lookupGroup="users" />
                        <input type="hidden"  class="" name="users.id" value="" lookupGroup="users" />

                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('Bill','users');?>" lookupGroup="users">选择用户</a>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>代理名称：</dt>
                    <dd>
                        <input type="text" disabled  class="" name="agent.agent_name" value="" lookupGroup="agent" />
                        <input type="hidden"  class="" name="agent.agent_id" value="" lookupGroup="agent" />

                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('User','agent');?>" lookupGroup="agent">选择代理</a>
                    </dd>
                </dl>
            </div>

            <div class="unit">
                <dl>
                    <dt>类型：</dt>
                    <dd>
                        <select name="bill_type">
                        <option value="">全部</option>
                        <?php
                        foreach($dic['planlistType'] as $key => $value) {
                            ?>
                            <option value="<?php echo $key; ?>" <?php if (\Core\Lib::request('bill_type') == $key) {
                                echo 'selected';
                            } ?>><?php echo $value; ?>
                            </option>
                            <?php
                        }
                        ?>
                    </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>卡类型：</dt>
                    <dd>
                        <select name="card_type" class="required">
                            <option value="">请选择卡类型</option>
                            <option value="1">信用卡</option>
                            <option value="2">储蓄卡</option>
                        </select>
                    </dd>
                </dl>
            </div>
             <div class="unit">
                <dl>
                    <dt>所属银行：</dt>
                    <dd>
                        <select name="bank_name" class="required">
                            <option value="">请选择银行</option>
                            <?php

                            foreach ($bank as $k=>$v) {

                                    ?>
                                    <option value="<?php echo $v['name'] ?>"><?php echo $v['name'] ?></option>
                                    <?php
                            }
                            ?>
                        </select>
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>手续费：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="poundage" class="required digits" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>订单号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="order_sn" class="required digits" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>卡号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="card_no" class="required creditcard" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>任务序号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="task_no" class="required digits" value=""  />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>外部订单号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="transaction_id" class="required digits" value="" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>状态：</dt>
                    <dd>
                        <select name="status" class="required">
                            <option value="">请选择状态</option>
                            <option value="1">成功</option>
                            <option value="2">失败</option>
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
                            <option value="1">易联</option>
                            <option value="2">易宝</option>
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
