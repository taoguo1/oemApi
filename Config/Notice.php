<?php
$money=DEPOSIT_POUNDAGE/100;
return [

        'notice'=>'费率：0.55%+1元，秒结，单日限额：北京5千，邮储5千，其他5万',//收款提示
        'get_notice'=>'单笔提现金额范围100元-3000元,每笔'.WITHDRAW_POUNDAGE.'元手续费',//提现提示信息
        'put_notice'=>'每笔充值收取'.$money.'%手续费',//充值提示信息
        'add_notice'=>'添加卡片即做一笔'.VALIDATECARD_POUNDAGE.'元交易作为验证卡片准确性'//添加卡片提示信息

];