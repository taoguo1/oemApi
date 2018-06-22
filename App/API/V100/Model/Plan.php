<?php
namespace App\API\V100\Model;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Lib;
class Plan extends Model
{

    public function getPlanUseBalance($uid, $cardno){
        $rs = DBQ::getOne('plan','*',['status'=>2,'user_id'=>$uid,'card_no[<>]'=>$cardno]);
        if($rs){
            $result = \ceil(($rs['amount'] / $rs['duration']) * 1.3);
            return $result;
        }else{
            return 0;
        }
    }

    public function getDoingCount($uid, $cardno){
        $data = DBQ::getCount('plan','*',['status'=>2,'user_id'=>$uid,'card_no'=>$cardno]);
        return $data;
    }

    public function getCreditCard($uid, $cardno){
        $data = DBQ::getOne('credit_card','*',['user_id'=>$uid,'user_type'=>1,'card_no'=>$cardno]);
        return $data;
    }

    public function getUserExt($user_id){
        $data = DBQ::getOne('user_ext','*',['user_id'=>$user_id]);
        return $data;
    }

    public function getCreditCardCode($bank_id){
        //$data = DBQ::getOne('bank','*',['id'=>$bank_id]);
        $data=lib::getOneBankConfig($bank_id);
        return $data;
    }
    //获取可提现金额
    public function getUserBalance($uid){
        $data=lib::getMayUseMoney($uid);
        return $data;
    }

    public function getRsByCardNo($uid, $cardno){
        $data = DBQ::getOne('plan','*',['ORDER'=>['id'=>'DESC'],'user_id'=>$uid,'card_no'=>$cardno]);
        return $data;
    }

    public function getRsByPlanId($uid, $plan_id){
        $data = DBQ::getOne('plan','*',['user_id'=>$uid,'id'=>$plan_id]);
        return $data;
    }

    public function getList($pageArr = null, $condition = null){
        $data = DBQ::pages($pageArr, 'plan (P)', [
            '[>]user (U)' => [
                'P.user_id' => 'id'
            ]
        ], [
            'P.id',
            'U.real_name',
            'P.amount',
            'P.card_no',
            'P.start_time',
            'P.end_time',
            'P.duration',
            'P.poundage',
            'P.finish_time',
            'P.finish_type',
            'P.status',
            'P.create_time'
        ], $condition);
        return $data;
    }

    public function getDetailListById($plan_id){
        $data = DBQ::getAll('plan_list', '*', ['plan_id'=>$plan_id]);
        return $data;
    }

    public function getDetailList($pageArr = null, $condition = null){
        $data = DBQ::pages($pageArr, 'plan_list (P)', [
            '[>]user (U)' => [
                'P.user_id' => 'id'
            ]
        ], [
            'P.id',
            'P.plan_id',
            'P.plan_type',
            'P.amount',
            'P.start_time',
            'P.status',
            //'P.create_time',
            'P.user_id',
        ], $condition);
        return $data;
    }
    public function addPlan($data){
        DBQ::add('plan',$data);
        $insertId = DBQ::insertID();
        return $insertId;
    }

    public function addPlanList($data){
        $ret = DBQ::add('plan_list',$data);
        return $ret;
    }
    //强制完成计划
    public function editPlan($user_id,$plan_id,$status){
        //平账数据
        //判断当前结束的计划详情数据里是否存在本期已还款但未消费状态，如存在则强制平账，将已还款期的未消费金额直接写入user_account
        $planListInfo = DBQ::getAll('plan_list','*',['plan_id'=>$plan_id,'user_id'=>$user_id]);
        //获取还款天数、期数
        $duration = DBQ::getOne('plan','*',['id'=>$plan_id,'user_id'=>$user_id]);
        $count=[];
        $payInfo=[];
        $payId=[];
        //用plan_no字段将数据归类写入数组
        foreach ($planListInfo as $key => $value) {
            for($i=0;$i<$duration['duration'];$i++){
                if($value['plan_no']==$i){
                    $count[$i][]=$value;
                }
            }
        }
        foreach($count as $k=>$v){
            if($count[$k][0]['plan_type']==1&&($count[$k][0]['status']==3||$count[$k][0]['status']==5)){
                for($j=0;$j<count($count[$k]);$j++){
                    if($count[$k][$j]['plan_type']==2 && $count[$k][$j]['status']!=3){
                        $payInfo[]=$count[$k][$j]['amount'];
                        $payId[]=$count[$k][$j]['id'];
                    }
                }
            }
        }
        if(!empty($planListInfo)){
            //更新plan数据表
            $ret = DBQ::upd('plan',['status'=>$status,'finish_type'=>2],['id'=>$plan_id]);
            //更新plan_list表状态
            foreach($planListInfo as $k=>$v){
                if($v['status']!=3&&$v['status']!=5){
                    DBQ::upd('plan_list',['status'=>6],['id'=>$v['id']]);
                }
            }
            if(!empty($payId)){
                for($s=0;$s<count($payId);$s++){
                    DBQ::upd('plan_list',['status'=>6],['id'=>$payId[$s]]);
                }
            }
            if(!empty($payInfo)){
                //将未消费数据插入user_account数据表
                //金额合计
                $allBalanced = 0;
                for($m=0;$m<count($payInfo);$m++){
                    $order_no = Lib::createOrderNo();
                    $allBalanced += $payInfo[$m];
                    $user_account = array(
                        'user_id' => $user_id,
                        'amount' => (float)($payInfo[$m])*(-1),
                        'order_sn' => $order_no,
                        'desciption' => '未消费金额平账',
                        'in_type' => 1,
                        'channel' => 1,  //1易联2易宝
                        'is_pay' => 1, //-1未支付，1已支付
                        'status' => 1, //-2锁定
                        'create_time' => Lib::getMs()
                    );
                    DBQ::add('user_account', $user_account);
                }
                //构造账单数据
                $billData1 = [
                    'user_id' => $user_id,
                    'plan_id' => $plan_id,
                    'amount' => (float)($allBalanced),
                    'bill_type' => 6,
                    'card_type' => 1,
                    'poundage' => 0,
                    'channel' => 2,
                    'card_no'=>$duration['card_no'],
                    'bank_id'=>$planListInfo[0]['bank_id'],
                    'bank_name'=>$planListInfo[0]['bank_name'],
                    'order_sn' => Lib::createOrderNo(),
                    'transaction_id' => Lib::getMs(),
                    'status' => 1,
                    'is_pay' => -1,
                    'intatus' => 1,
                    'create_time' => Lib::getMs(),
                ];
                //记账，计入bill表
                DBQ::add('bill',$billData1);

            }
            $return =1;
        }else{
            $return =0;
        }
        return $return;
    }


    //根据计划id  用户id  计划详情id 更改计划详情表部分数据状态
    public function changePlan($user_id,$plan_id,$plan_list_id){
        $planListInfo = DBQ::getAll('plan_list','*',['plan_id'=>$plan_id,'user_id'=>$user_id]);
        $return=0;
        foreach($planListInfo as $k=>$v){
            //状态修改为已暂停
            if($v['id']>=$plan_list_id){
               DBQ::upd('plan_list',['status'=>4],['id'=>$v['id']]);
               $return =1;
            }
        }
        return $return;
    }

    //删除计划表数据
    public function deletePlan($plan_id,$user_id){
        $info = DBQ::getOne('plan','*',['user_id'=>$user_id,'id'=>$plan_id]);
        if($info['status']==1){//未开始
            $ret = DBQ::del('plan',['id'=>$plan_id,'user_id'=>$user_id]);
            if($ret){
                $ret=1;
            }
        }elseif($info['status']==2){//进行中
            //判断plan_list中数据中status是否全为1,2,4（未执行）
            $all = DBQ::getAll('plan_list','*',['user_id'=>$user_id,'plan_id'=>$plan_id]);
            $status=0;
            $array=['1','2','4'];
            foreach($all as $k=>$v){
                if(!in_array($v['status'],$array)){
                    $status=1;
                }
            }
            if($status==0){
                $ret = DBQ::del('plan',['id'=>$plan_id,'user_id'=>$user_id]);//删除plan表数据
                $ret=1;
            }else{
                $ret=-2;//plan_list表中有status=3,5,6数据，不能删除
            }    


        }else{
            $ret=-1;
        }
        return $ret;
    }

    //删除还款详情表数据
    public function delePlanList($plan_id,$user_id){
        $ret = DBQ::del('plan_list',['plan_id'=>$plan_id,'user_id'=>$user_id]);
        return $ret;
    }

    //获取已还金额 未还金额
    public function getPayMoney($user_id,$plan_id){
        $all = DBQ::getAll('plan_list','*',['user_id'=>$user_id,'plan_id'=>$plan_id]);
        $yhMoney=0;
        $dhMoney=0;
        foreach($all as $k=>$v){
            if($v['plan_type']==1){
                if($v['status']==3||$v['status']==5){
                    $yhMoney+=$v['amount'];
                }else{
                    $dhMoney+=$v['amount'];
                } 
            }
        }
        $data['yhMoney']=Lib::formatMoney($yhMoney*100/100,2);
        $data['dhMoney']=Lib::formatMoney($dhMoney*100/100,2);
        return $data;
    }


    /** 构造消费数据
     *  $currentBackAmount  当期还款金额
     *  $number 次数
     *  $user_id  用户id
     *  $insertid  数据主键id
     *  $detailStartTime 开始时间
     *  $key 外层循环key值
     *  $taskNoIn 任务号
     *  $creditCard card信息
     *  $creditCardCode 
     *  $userext
     */
    public function makeData($autoExcuteCcount,$currentBackAmount,$number,$user_id,$insertid,$detailStartTime,$key,$taskNoIn,$creditCard,
        $creditCardCode,$userext,$bank_money_conf,$bank_money,$minRand,$maxRand,$timearr){
        $all=[];
        $payRandAmount = 0;
        //当期还款金额小于配置金额
        if($currentBackAmount <$bank_money){
            $rand=rand(40,55);
            $dar1 = ceil($currentBackAmount * ($rand / 100));
            $dar2 = $currentBackAmount - $dar1;
            $payData = [
                [
                    'user_id' => $user_id,
                    'plan_id' => $insertid,
                    'plan_type' => 2,
                    'start_time' => $detailStartTime + rand(1200,1800),
                    'plan_no' => $key,
                    'auto_excute_count'=> 0,
                    'task_no' => $taskNoIn,
                    'mobile' => $creditCard['mobile'],
                    'real_name' => $creditCard['real_name'],
                    'card_no' => $creditCard['card_no'],
                    'id_card' => $creditCard['id_card'],
                    'cvn' => $creditCard['cvn'],
                    'expiry_date' => $creditCard['expiry_date'],
                    'bank_name' => $creditCard['bank_name'],
                    //'code_yb' => $creditCardCode['code_yb'],
                    'bank_id'=> $creditCardCode['id'],
                    'amount' => $dar1,
                    'order_sn' => Lib::createOrderNo(),
                    'sysId' => $creditCard['sysId'],
                    'userCode' => $userext['userCode'],
                    'status' => 1,
                    're_times'=>0,
                    'create_time' => Lib::getMs()
                ],
                [
                    'user_id' => $user_id,
                    'plan_id' => $insertid,
                    'plan_type' => 2,
                    'start_time' => $detailStartTime + rand(3600,5400),
                    'plan_no' => $key,
                    'auto_excute_count'=> $autoExcuteCcount,
                    'task_no' => $taskNoIn,
                    'mobile' => $creditCard['mobile'],
                    'real_name' => $creditCard['real_name'],
                    'card_no' => $creditCard['card_no'],
                    'id_card' => $creditCard['id_card'],
                    'cvn' => $creditCard['cvn'],
                    'expiry_date' => $creditCard['expiry_date'],
                    'bank_name' => $creditCard['bank_name'],
                    //'code_yb' => $creditCardCode['code_yb'],
                    'bank_id'=> $creditCardCode['id'],
                    'amount' => $dar2,
                    'order_sn' => Lib::createOrderNo(),
                    'sysId' => $creditCard['sysId'],
                    'userCode' => $userext['userCode'],
                    'status' => 1,
                    're_times'=>0,
                    'create_time' => Lib::getMs()
                ]
            ];
            $all[] = $payData[0];
            $all[] = $payData[1];
            return $all;
        }

        if($bank_money==480&&$currentBackAmount>$bank_money){
            //极限值为1440
            $rand1=rand(31,36);
            $rand2=rand(32,34);
            $dar1=ceil($currentBackAmount*$rand1/100);
            $dar2=ceil($currentBackAmount*$rand2/100);
            if($dar1>=500){
                $dar1=1000-$dar1-rand(1,3);
            }
            if($dar2>=500){
                $dar2=1000-$dar2-rand(1,3);
            }
            $dar3= $currentBackAmount-$dar1-$dar2;
            if($dar3>500){
                if(500-$dar1>$dar3-500){
                    $dar1=$dar1+($dar3-499);
                }
                if(500-$dar2>$dar3-500){
                    $dar2=$dar2+($dar3-499);
                }
                $dar3=499;
            }
            $a=$dar3+$dar2+$dar1;
            if($a!=$currentBackAmount){
                if($a>$currentBackAmount){
                    $dar3=$dar3-($a-$currentBackAmount);
                    
                }else{
                    if(($currentBackAmount-$a)<500-$dar1){
                        $dar1=$dar1+$currentBackAmount-$a;
                    }else{
                        if(($currentBackAmount-$a)<500-$dar2){
                            $dar2=$dar2+$currentBackAmount-$a;
                        }
                    }
                }
            }
            $payData = [
                [
                    'user_id' => $user_id,
                    'plan_id' => $insertid,
                    'plan_type' => 2,
                    'start_time' => $detailStartTime + rand(1200,1800),
                    'plan_no' => $key,
                    'auto_excute_count'=> 0,
                    'task_no' => $taskNoIn,
                    'mobile' => $creditCard['mobile'],
                    'real_name' => $creditCard['real_name'],
                    'card_no' => $creditCard['card_no'],
                    'id_card' => $creditCard['id_card'],
                    'cvn' => $creditCard['cvn'],
                    'expiry_date' => $creditCard['expiry_date'],
                    'bank_name' => $creditCard['bank_name'],
                    //'code_yb' => $creditCardCode['code_yb'],
                    'bank_id'=> $creditCardCode['id'],
                    'amount' => $dar1,
                    'order_sn' => Lib::createOrderNo(),
                    'sysId' => $creditCard['sysId'],
                    'userCode' => $userext['userCode'],
                    'status' => 1,
                    're_times'=>0,
                    'create_time' => Lib::getMs()
                ],
                [
                    'user_id' => $user_id,
                    'plan_id' => $insertid,
                    'plan_type' => 2,
                    'start_time' => $detailStartTime + rand(3600,5400),
                    'plan_no' => $key,
                    'auto_excute_count'=> 0,
                    'task_no' => $taskNoIn,
                    'mobile' => $creditCard['mobile'],
                    'real_name' => $creditCard['real_name'],
                    'card_no' => $creditCard['card_no'],
                    'id_card' => $creditCard['id_card'],
                    'cvn' => $creditCard['cvn'],
                    'expiry_date' => $creditCard['expiry_date'],
                    'bank_name' => $creditCard['bank_name'],
                    //'code_yb' => $creditCardCode['code_yb'],
                    'bank_id'=> $creditCardCode['id'],
                    'amount' => $dar2,
                    'order_sn' => Lib::createOrderNo(),
                    'sysId' => $creditCard['sysId'],
                    'userCode' => $userext['userCode'],
                    'status' => 1,
                    're_times'=>0,
                    'create_time' => Lib::getMs()
                ],
                [
                    'user_id' => $user_id,
                    'plan_id' => $insertid,
                    'plan_type' => 2,
                    'start_time' => $detailStartTime + rand(7200,9000),
                    'plan_no' => $key,
                    'auto_excute_count'=> $autoExcuteCcount,
                    'task_no' => $taskNoIn,
                    'mobile' => $creditCard['mobile'],
                    'real_name' => $creditCard['real_name'],
                    'card_no' => $creditCard['card_no'],
                    'id_card' => $creditCard['id_card'],
                    'cvn' => $creditCard['cvn'],
                    'expiry_date' => $creditCard['expiry_date'],
                    'bank_name' => $creditCard['bank_name'],
                    //'code_yb' => $creditCardCode['code_yb'],
                    'bank_id'=> $creditCardCode['id'],
                    'amount' => $dar3,
                    'order_sn' => Lib::createOrderNo(),
                    'sysId' => $creditCard['sysId'],
                    'userCode' => $userext['userCode'],
                    'status' => 1,
                    're_times'=>0,
                    'create_time' => Lib::getMs()
                ]
            ];
            $all[] = $payData[0];
            $all[] = $payData[1];
            $all[] = $payData[2];
            return $all;
        }
        //当期还款金额大于配置金额
        
        for($m = 0; $m < $number; $m++){
            if($payRandAmount < $currentBackAmount){
                if($m==$number-1){
                    $currentResumeAmount=$currentBackAmount-$payRandAmount;
                    if($autoExcuteCcount == 1){ $autoExcute = 1; }else{ $autoExcute = 0; }
                }else{
                    $currentResumeAmount=$bank_money_conf-rand(1,10) + ceil($bank_money * rand($minRand,$maxRand) / 100);
                    $autoExcute = 0;
                } 
                $act = rand(1200 + (600 * $m),3600);
                //构造消费数据
                $payDataAvg = [
                    'user_id' => $user_id,
                    'plan_id' => $insertid,
                    'plan_type' => 2,
                    'start_time' => $detailStartTime + $timearr,
                    'plan_no' => $key,
                    'auto_excute_count'=> $autoExcute,
                    'task_no' => $taskNoIn,
                    'mobile' => $creditCard['mobile'],
                    'real_name' => $creditCard['real_name'],
                    'card_no' => $creditCard['card_no'],
                    'id_card' => $creditCard['id_card'],
                    'cvn' => $creditCard['cvn'],
                    'expiry_date' => $creditCard['expiry_date'],
                    'bank_name' => $creditCard['bank_name'],
                    //'code_yb' => $creditCardCode['code_yb'],
                    'bank_id'=> $creditCardCode['id'],
                    'amount' => $currentResumeAmount,
                    'order_sn' => Lib::createOrderNo(),
                    'sysId' => $creditCard['sysId'],
                    'userCode' => $userext['userCode'],
                    'status' => 1,
                    'create_time' => Lib::getMs()
                ];
                //为在模型中使用事务构造数据数组（消费）
                $payDataAvg['re_times']=$number;
                $all[] = $payDataAvg;
                $payRandAmount += $currentResumeAmount;
                $timearr += $act;
            }
        }
        return $all;     
    }


    //当前金额支持创建天数
    public function checkDay($amount,$bank_money){
        $dayArr=[1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25];
        $a=[];
        $b=[];
        for($i=0;$i<count($dayArr);$i++){
            $avgAmount=$amount/$dayArr[$i];
            $minAmountIn=$avgAmount*1.3;
            $times=ceil($avgAmount/$bank_money);
            if($times==1){
                $times=2;
            }
            $needMoney=($amount*68/10000)+(ceil($times)*$dayArr[$i])+$avgAmount;
            if($minAmountIn<$needMoney){
                 $a[]=$dayArr[$i];
            
            }else{
                 $b[]=$dayArr[$i];
            }
        }
        return $b;
    }
}