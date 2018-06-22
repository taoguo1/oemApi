<div class="pageContent">
    <form method="post" action="<?=\Core\Lib::getUrl('bill','edit','id='.$list['id'].'&act=edit');?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
        <div class="pageFormContent" layoutH="56">

            <div class="unit">
                <dl>
                    <dt>还款计划ID：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="plan_id" class="required digits" value="<?php echo $list['plan_id'];?>" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>金额：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="amount" class="required number" value="<?php echo $list['amount'];?>"  />
                    </dd>
                </dl>
            </div>
<!--            <div class="unit">-->
<!--                <dl>-->
<!--                    <dt>用户名称：</dt>-->
<!--                    <dd>-->
<!--                        <input type="text" style="width:300px;" name="username" class="required" value="--><?php //echo $list['username'];?><!--" />-->
<!--                    </dd>-->
<!--                </dl>-->
<!--            </div>-->
<!--            <div class="unit">-->
<!--                <dl>-->
<!--                    <dt>代理名称：</dt>-->
<!--                    <dd>-->
<!--                        <input type="text" style="width:300px;" name="agentname" class="required" value="--><?php //echo $list['agentname'];?><!--" />-->
<!--                    </dd>-->
<!--                </dl>-->
<!--            </div>-->

            <div class="unit">
                <dl>
                    <dt>用户名称：</dt>
                    <dd>
                        <input type="text" disabled  class="" name="users.real_name" value="<?php echo $list['real_name'] ?>" lookupGroup="users" />
                        <input type="hidden"  class="" name="users.id" value="<?= $list['user_id']?>" lookupGroup="users" />

                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('Bill','users');?>" lookupGroup="users">选择用户</a>
                    </dd>
                </dl>
            </div>
              <div class="unit">
                <dl>
                    <dt>代理名称：</dt>
                    <dd>
                        <input type="text" disabled  class="" name="agent.agent_name" value="<?=$list['agent_name']?>" lookupGroup="agent" />
                        <input type="hidden"  class="" name="agent.agent_id" value="<?=$list['agent_id']?>" lookupGroup="agent" />

                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('User','agent');?>" lookupGroup="agent">选择代理</a>
                    </dd>
                </dl>
            </div>
            <div class="unit">

                <dl>
                    <dt>类型：</dt>
                    <dd>
                        <select name="type" class="required">
                            <option value="">请选择类型</option>
                            <?php

                            foreach ($dic['planlistType'] as $k=>$v) {
                                $selected = $k==$list['bill_type'] ? "selected":"";
                                ?>
                                <option  <?= $selected ?> value="<?= $k ?>"> <?=$v ?></option>
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
                            <option value="1" <?php if($list['card_type']==1){echo 'selected';}?>>信用卡</option>
                            <option value="2" <?php if($list['card_type']==2){echo 'selected';}?>>储蓄卡</option>
                        </select>
                    </dd>
                </dl>
            </div>
             <div class="unit">
                <dl>
                    <dt>所属银行：</dt>
                    <dd>
                        <select name="bank_name" class="">
                            <option value="">请选择银行</option>
                            <?php
                            foreach ($bank as $k=>$v) {
                                $selected = $v['name'] == $list['bank_name'] ? "selected" : "";
                                    ?>
                                    <option   <?=$selected ?> value="<?php echo $v['name'] ?>"><?php echo $v['name'] ?></option>
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
                        <input type="text" style="width:300px;" name="poundage" class="required " value="<?= $list['poundage']?>" />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>订单号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="order_sn" class="required " value="<?php echo $list['order_sn'];?>" />
                    </dd>
                </dl>
            </div>

            <div class="unit">
                <dl>
                    <dt>卡号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="card_no" class="creditcard" value="<?php echo \Core\Lib::aesDecrypt($list['card_no']);?>" />
                    </dd>
                </dl>
            </div>
<!--            <div class="unit">-->
<!--                <dl>-->
<!--                    <dt>产品编号：</dt>-->
<!--                    <dd>-->
<!--                        <input type="text" style="width:300px;" name="goods_id" class="required digits" value="--><?php //echo $list['goods_id'];?><!--"  />-->
<!--                    </dd>-->
<!--                </dl>-->
<!--            </div>-->
            <div class="unit">
                <dl>
                    <dt>任务序号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="task_no" class="required digits" value="<?php echo $list['task_no'];?>"  />
                    </dd>
                </dl>
            </div>
            <div class="unit">
                <dl>
                    <dt>外部订单号：</dt>
                    <dd>
                        <input type="text" style="width:300px;" name="transaction_id" class="required digits" value="<?php echo $list['transaction_id'];?>" />
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
