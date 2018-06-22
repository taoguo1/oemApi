<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
<title>信用卡智能管家</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body class="bg-wh">
<div style="width: 100%; margin: 0 auto; position: relative;" id="box_all">
    <table border="1" style="width: 90%;margin: 0px auto;">
      <tr>
      	<th style="text-align: center;color:#ff0000;">异常差额</th>
        <th style="text-align: center;">用户id</th>
        <th style="text-align: center;">账户姓名</th>
        <th style="text-align: center;">账户金额</th>
        <th style="text-align: center;">可提现金额</th>
        <th style="text-align: center;">子商户余额</th>
        <th style="text-align: center;">子商户余额(通道)</th>
          <th style="text-align: center;">操作</th>
      </tr>
      <?php foreach ($data as $k=>$v){?>
      <tr>
        <td style="text-align: center;color:#ff0000;"><?=bcsub($v['total_account'], $v['amountByGuo'],2)?></td>
        <td style="text-align: center;"><?=$v['user_id']?></td>
        <td style="text-align: center;"><?=$v['real_name']?></td>
        <td style="text-align: center;"><?=$v['total_account']?></td>
        <td style="text-align: center;"><?=$v['canTxMoney']?></td>
        <td style="text-align: center;"><?=$v['amountByGuo']?></td>
        <td style="text-align: center;"><?=$v['balanceChannel']?></td>
          <td style="text-align: center;"><?php if($v['balanceChannel'] > 0){ ?><a target="_blank" href="trans/<?=$v['userCode']?>/1/<?=$v['balanceChannel']?>/<?=$v['user_id']?>/?appid=<?=\Core\Lib::request('appid')?>">提现</a><?php } ?> </td>
      </tr>
      <?php }?>
    </table>
</div>
</body>
</html>