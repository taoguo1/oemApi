<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/3/19
 * Time: 15:53
 */

$conf = require_once(__DIR__.'/../../Config/Redis.php');

$redis = new \redis();
$redis->connect($conf['plan']['host'], $conf['plan']['port']);
if ($redis->auth($conf['plan']['password']) == false) {
    die("password:".$redis->getLastError());
}
//var_dump($redis);
for($i = 1; $i <= 5000000; $i++){

    $redis->zAdd('dzz_test_1:',$i,'{"iid":"'.$i.'","id":"14","plan_id":"2","user_id":"30","amount":"510.00","plan_type":"1","start_time":"1521583808","end_time":null,"real_name":"zjytIKyYSRZY6mMb+Dd1\/Q==","mobile":"06xtWaNjl+LwMUajkyRrZQ==","card_no":"o73ZNWxWu\/aGJvvB9OaWq\/ulbarAL+bvr0RhhimvlmQ=","id_card":"+ZdSpJgAtWE+tgOQwn7s9uv65faZ\/Eyir4mSPZmmp\/E=","cvn":"kWhulvsz7WX0NOiK3vWejw==","expiry_date":"CnR\/jWSnBbT8fswF1HZUpg==","bank_name":"\u5174\u4e1a\u94f6\u884c","code_yb":"CIB","bank_id":"16","plan_no":"1","auto_excute_count":"0","task_no":"60","transaction_id":"0","status":"2","order_sn":"H319406815023988","channel":null,"create_time":"1521440681502"}');
    echo "第".$i."\n";
}