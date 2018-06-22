<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/2/21
 * Time: 16:29
 */
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;
use Core\Extend\Redis;
class Plan extends Controller{
    public $m;
    public $headers;
    public function __construct($controller, $action){
        parent::__construct($controller, $action);
        $this->headers = Lib::getAllHeaders();
        $this->appid = Lib::post('appid');
        $model = "\\App\API\\".$this->headers['VERSION']."\\Model\\Plan";
        $this->m = new $model;

    }
    //创建还款计划
    public function makePlan(){
        $dataErr = [
            'status' => 'fail',
            'code' => 10011,
            'msg' => '参数不足'
        ];
        $inputData = [];
        $inputData['user_id'] = $this->headers['UID'];
        $user_id = $this->headers['UID'];
        if(Lib::post ( 'amount')){
            $inputData['amount'] = Lib::post ( 'amount');
            $amount = Lib::post ( 'amount');
        }else{
            $dataErr['msg'] = '金额不能为空';
            Lib::outputJson($dataErr);
        }
        if(Lib::post ( 'card_no')){
            $inputData['card_no'] = Lib::aesEncrypt(Lib::post ( 'card_no'));
            $card_no = Lib::post ( 'card_no');
        }else{
            $dataErr['msg'] = '卡号不能为空';
            Lib::outputJson($dataErr);
        }
        //判断系统中有没有该用户当前卡片正在进行中的计划 //$card_no匹配数据库  需加密
        $doingCount = $this->m->getDoingCount($user_id,Lib::aesEncrypt($card_no));
        if($doingCount){
            $dataErr['msg'] = "当前卡在系统中有未完成的计划，暂不能创建计划！";
            Lib::outputJson($dataErr);
        }
        //获取当前信用卡信息
        $creditCard = $this->m->getCreditCard($user_id,Lib::aesEncrypt($card_no));
        //获取信用卡code
        $creditCardCode = $this->m->getCreditCardCode($creditCard['bank_id']);
        //获取用户
        $userext = $this->m->getUserExt($inputData['user_id']);
        if(!$creditCard){
            $dataErr['msg'] = "该信用卡不存在！";
            Lib::outputJson($dataErr);
        }
        $dayArr = [];
        $begin = $creditCard['start_repayment_day'];
        $end = $creditCard['end_repayment_day'];
        $begintime = strtotime($begin);
        $endtime = strtotime($end);
        $vday = strtotime(date('Y-m-d',time())) + 86400;





        
       if($begintime < $vday){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '开始日期需要超过次日',
            ];
            Lib::outputJson($data);
        }
        //检测卡片状态
        $payModel = new \App\API\V100\Model\Pay();
        $dataPost = array(
            'userCode' => $userext['userCode'],
            'sysId' => $creditCard['sysId'],
            'cardType'=> 2,
        );
        $result = $payModel->payCardQuery($dataPost);
        Lib::tempLog('plan_jq.txt',$result,'Plan');
        if ($result['isCan'] == '否') {
            $dataErr['msg'] = "该银行卡没有鉴权";
            $dataErr['isCan'] = 0;
            Lib::outputJson($dataErr);
        }
        for($start = $begintime; $start <= $endtime; $start += 24 * 3600) {
            $dayArr[] = $start;
        }
        if(count($dayArr) == 0){
            $dataErr['msg'] = "距离最后还款日没有可以执行计划的日期";
            Lib::outputJson($dataErr);
        }
        if(count($dayArr) < 5){
            $dataErr['msg'] = "距离最后还款日至少需要5个可以执行计划的日期";
            Lib::outputJson($dataErr);
        }
        if(count($dayArr) > 25){
            $dataErr['msg'] = "还款总天数不能超过25天";
            Lib::outputJson($dataErr);
        }
        //平均每天还款金额
        $avgAmount = floor(($amount / count($dayArr))*100)/100;
        //最少充值金额 目前 DEPOSIT_CO = 1.3
        $minAmountIn = floor($avgAmount * DEPOSIT_CO*100)/100;
        //获取用户余额
        $userBalance = $this->m->getUserBalance($user_id);
        //获取银行卡限额信息
        $max_money = Lib::getOneBankConfig($creditCard['bank_id']);
        //判断每日还款金额
        if($avgAmount>=$max_money['max_in_money']){
            $dataErr['msg'] = "每日还款金额超限";
            Lib::outputJson($dataErr);
        }
        //根据银行编号确定初始值
        if($max_money['code_hlb']=='CEB'){
            $bank_money=480;
            $bank_money_conf=400;
        }else{
            $bank_money=960;
            $bank_money_conf=800;
        }
        if($amount < MIN_R_AMOUNT){
            $dataErr['msg'] = "还款金额必须大于 ".MIN_R_AMOUNT;
            Lib::outputJson($dataErr);
        }
        //判断用户账号金额是否满足创建计划的要求
        if($userBalance < $minAmountIn){
            $dataErr['msg'] = "余额不足,请至少保证余额:".$minAmountIn.",充值会有".(DEPOSIT_POUNDAGE/100 )."%的手续费,请确保充值以后的余额高于".$minAmountIn."。";
            Lib::outputJson($dataErr);
        }
        //创建还款计划主记录
        $inputData['bank_name'] = $creditCard['bank_name'];
        $inputData['duration'] = count($dayArr);
        $inputData['status'] = 2;
        $inputData['start_time'] = $dayArr[0];
        $inputData['end_time'] = $dayArr[$inputData['duration']-1];
        $inputData['create_time'] = Lib::getMs();
        $insertid = $this->m->addPlan($inputData);
        if(!$insertid){
            $dataErr['msg'] = "创建还款计划失败！";
            Lib::outputJson($dataErr);
        }
        //创建任务编号
        //1000-1044
        $taskNoInArr = [];
        for($i = 1000; $i <= 1044; $i++){
            $taskNoInArr[] = $i;
        }
        //10000-10030
        $taskNoOutArr = [];
        for($i = 10000; $i <= 10030; $i++){
            $taskNoOutArr[] = $i;
        }
        $inKeys = array_rand($taskNoInArr,1);
        $outKeys = array_rand($taskNoOutArr,1);
        //计划任务编号随机生成-还款
        $taskNoIn = $taskNoInArr[$inKeys];
        //计划任务编号随机生成-消费
        $taskNoOut = $taskNoOutArr[$outKeys];
        //初始化已还金额
        $returnedAmount = 0;
        //开始生成还款计划详情
        $backDataALL = [];
        $payDataALL = [];
        foreach($dayArr as $key => $value){
            if(count($dayArr) - $key > 1){
                //还款金额浮动
                $randBack= rand(95,105);
                //本次还款金额
                $currentBackAmount = ceil($avgAmount * ($randBack / 100));
            }else{
                $currentBackAmount = $amount - $returnedAmount;
            }
            $returnedAmount += $currentBackAmount;
            //生成还款记录
            $rTime = rand(28800,72000);
            $detailStartTime = $value + $rTime;
            $backData = [
                'user_id' => $user_id,
                'plan_id' => $insertid,
                'plan_type' => 1,
                'start_time' => $detailStartTime,
                'plan_no' => $key,
                'task_no' => $taskNoOut,
                'mobile' => $creditCard['mobile'],
                'real_name' => $creditCard['real_name'],
                'card_no' => $creditCard['card_no'],
                'id_card' => $creditCard['id_card'],
                'cvn' => $creditCard['cvn'],
                'expiry_date' => $creditCard['expiry_date'],
                'bank_name' => $creditCard['bank_name'],
                //'code_yb' => $creditCardCode['code_yb'],
                'bank_id'=> $creditCardCode['id'],
                'amount' => $currentBackAmount,
                'order_sn' => Lib::createOrderNo(),
                'sysId' => $creditCard['sysId'],
                'userCode' => $userext['userCode'],
                'status' => 1,
                'create_time' => Lib::getMs()
            ];
            //为在模型中使用事务构造数据数组（还款）
            $backDataALL[] = $backData;
            //生成消费记录
            if($max_money['max_money'] > 0.00){
                if($currentBackAmount <= $bank_money){
                    $dar1 = ceil($currentBackAmount * (rand(40,55) / 100));
                    $dar2 = $currentBackAmount - $dar1;
                    //构造消费数据
                    $payData = [
                        [
                            'user_id' => $user_id,
                            'plan_id' => $insertid,
                            'plan_type' => 2,
                            'start_time' => $detailStartTime + rand(1200,1800),
                            'plan_no' => $key,
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
                    //为在模型中使用事务构造数据数组（消费）
                    $payDataALL[] = $payData[0];
                    $payDataALL[] = $payData[1];
                    $backData['re_times'] = 2;
                    $all[] = $backData;
                    $all[] = $payData[0];
                    $all[] = $payData[1];
                }else{
                    //消费次数,平均每次消费$bank_money元
                    $timearr = rand(1200,3600);
                    $randPayTimes = ceil($currentBackAmount / $bank_money);
                    $payRandAmount = 0;
                    $backData['re_times'] = $randPayTimes;
                    $all[] = $backData;
                    for($m = 0; $m < $randPayTimes; $m++){
                        if($payRandAmount < $currentBackAmount){
                            $currentResumeAmount = $bank_money_conf + ceil($bank_money * rand(15,20) / 100);
                            if($currentResumeAmount > ($currentBackAmount - $payRandAmount)){
                                $currentResumeAmount = $currentBackAmount - $payRandAmount;
                            }
                           /* $currentResumeAmount = ceil(ceil($currentBackAmount/$randPayTimes)/1.2+ceil(ceil($currentBackAmount/$randPayTimes)*rand(10,20)/100));
                            if($currentResumeAmount>($currentBackAmount-$payRandAmount)){
                                $currentResumeAmount=$currentBackAmount-$payRandAmount;
                            }*/
                            $act = rand(1200 + (600 * $m),3600);
                            //构造消费数据
                            $payDataAvg = [
                                'user_id' => $user_id,
                                'plan_id' => $insertid,
                                'plan_type' => 2,
                                'start_time' => $detailStartTime + $timearr,
                                'plan_no' => $key,
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
                            $payDataALL[] = $payDataAvg;
                            $payData[] = $payDataAvg;
                            $payDataAvg['re_times']=$randPayTimes;
                            $all[] = $payDataAvg;
                            $payRandAmount += $currentResumeAmount;
                            $timearr += $act;
                        }
                    }
                }
            }
        }
        //批量写入数据库
        $this->m->addPlanList($all);
        //写入redis
        $redis = Redis::instance('plan');
        $redisData = $this->m->getDetailListById($insertid);
        if($redisData) {
            foreach ($redisData as $k => $v) {
                $v['appid'] = $this->appid;
                $redis->zAdd('rc:' . $v['task_no'], $v['start_time'], json_encode($v));
                $redis->hSet('rc_plan_data', $this->appid.'_'.$v['id'], 1);
            }
        }
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '还款计划设置成功'
        ];
        Lib::outputJson($data);
    }

    public function myPlanRow(){
        $user_id = $this->headers['UID'];
        if(!$user_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'user_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $cardno = Lib::post('cardno');
        if(!$cardno){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '卡号不能为空',
            ];
            Lib::outputJson($data);
        }


        $rs = $this->m->getRsByCardNo($user_id, Lib::aesEncrypt($cardno));


        if($rs){
            $rsFinal = [
                'plan_id' => $rs['id'],
                'cardno' => $rs['card_no'],
                'amount' => $rs['amount'],
                'status' => $rs['status'],
                'start_time' => date('Y-m-d H:i',$rs['start_time']),
                'create_time' => Lib::uDate('Y-m-d H:i',$rs['create_time']),
            ];
            //获取已还金额  未还金额
            $payMoney=$this->m->getPayMoney($user_id,$rs['id']);
            $rsFinal['returned_amount'] =$payMoney['yhMoney'];
            $rsFinal['unreturned_amount'] =$payMoney['dhMoney'];
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '获取还款计划成功',
                'data' => $rsFinal
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'success',
                'code' => 10011,
                'msg' => '暂无还款计划',
                'data' => $rs
            ];
            Lib::outputJson($data);
        }

    }

    public function myPlanList(){
        $user_id = $this->headers['UID'];
        if(!$user_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'user_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $condition['AND']['user_id'] = $user_id;
        $pageArr = Lib::setPagePars ();
        $condition ['ORDER'] = [
            'P.id' => 'DESC'
        ];
        $datalist = $this->m->getList($pageArr, $condition);
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取我的还款计划成功',
            'data' => $datalist['list']
        ];
        Lib::outputJson($data);
    }

    public function detail(){
        $user_id = $this->headers['UID'];
        if(!$user_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'user_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $plan_id = Lib::post('plan_id');
        if(!$plan_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '计划ID不能为空',
            ];
            Lib::outputJson($data);
        }

        $condition['AND']['user_id'] = $user_id;
        $condition['AND']['plan_id'] = $plan_id;
        $pageArr = Lib::setPagePars ();
        $pageArr['orderField'] = 'P.id';
        $pageArr['orderDirection'] = 'ASC';
        $pageArr['numPerPage'] = 300;
        $datalist = $this->m->getDetailList($pageArr, $condition);
        $dataAll = [];
        $count = 0;
        foreach($datalist['list'] as $k => $val){
            $val['start_time_str'] = date('Y-m-d H:i:s',$val['start_time']);
            $val['type'] = ($val['plan_type'] == 1) ? '还款':'消费';
            if($val['plan_type'] == 1){ $pid = $val['id']; };
            if($val['plan_type'] == 2){ $val['pid'] = $pid; }
            $dataAll[] = $val;
            if($val['plan_type']==1)$count++;
        }
        $dataAllFormat = [];
        foreach($dataAll as $key1 => $value1){
            if($value1['plan_type'] == 1){
                foreach($dataAll as $key2 => $value2){
                    if($value2['plan_type'] == 2 && $value2['pid'] == $value1['id']){
                        $value1['sublist'][] = $value2;
                    }
                }
                $dataAllFormat[] = $value1;
            }
        }

        $rs = $this->m->getRsByPlanId($user_id, $plan_id);
        $rsFinal = [
            'amount' => $rs['amount'],
            'poundage' => sprintf('%.2f',$rs['amount'] * REPAYMENT_POUNDAGE/10000),
            'count' => $count,
            'card_no' => Lib::aesDecrypt($rs['card_no']),
            'bank_name' => $rs['bank_name'],
            'status' => $rs['status'],
            'create_time_str' => Lib::uDate('Y-m-d H:i:s',$rs['create_time']),
        ];

        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取计划成功详情成功',
            'data' => ['plan'=>$rsFinal,'detail_list'=>$dataAllFormat]
        ];
        Lib::outputJson($data);
    }
    //删除计划
    public function delPlan(){
        $user_id = $this->headers['UID'];
        if(!$user_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'user_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $plan_id = Lib::post('plan_id');
        if(!$plan_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'plan_id不能为空',
            ];
            Lib::outputJson($data);
        }
        //删除计划表中的数据
        $ret = $this->m->deletePlan($plan_id,$user_id);
        if($ret==-1){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '删除还款计划失败,该计划正在进行中或已完成',
                'data' => $ret
            ];
        }elseif($ret==1){
            //删除redis中数据
            $data = DBQ::getAll('plan_list', '*', ['plan_id'=>$plan_id,'user_id'=>$user_id]);
            foreach($data as $k => $v){
                $redis = Redis::instance('plan');
                $redis->hdel('rc_plan_data',$this->appid.'_'.$v['id']);
            }
            //删除计划详情表数据
            $delRes=$this->m->delePlanList($plan_id,$user_id);
            if($delRes){
                $data = [
                    'status' => 'success',
                    'code' => 10000,
                    'msg' => '删除还款计划成功',
                    'data' => $ret
                ];  
            }else{
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '删除还款计划详情失败',
                    'data' => $delRes
                ];
            }
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                //'msg' => '删除还款计划失败,有计划已经开始执行或者已完成',
                'msg' => '删除还款计划失败,有计划已经开始执行或已完成或执行失败',
                'data' => $ret
            ];
        }
        Lib::outputJson($data);
    }
    //强制完成计划
    public function finishPlan(){
        $user_id = $this->headers['UID'];
        if(!$user_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'user_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $plan_id = Lib::post('plan_id');
        if(!$plan_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'plan_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $ret = $this->m->editPlan($user_id,$plan_id,3);
        $data = DBQ::getAll('plan_list', '*', ['plan_id'=>$plan_id,'user_id'=>$user_id]);
        foreach($data as $k => $v){
            $redis = Redis::instance('plan');
            $redis->hdel('rc_plan_data',$this->appid.'_'.$v['id']);
        }
        if($ret==1){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '强制完成还款计划成功',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '强制完成还款计划失败',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }
    }

    //根据计划id  用户id  计划详情id 更改计划详情表部分数据状态
    public function changePlan(){
        $user_id = $this->headers['UID'];
        if(!$user_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'user_id不能为空',
            ];
            Lib::outputJson($data);
        }
        $plan_id = Lib::post('plan_id');
        if(!$plan_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'plan_id不能为空',
            ];
            Lib::outputJson($data);
        }
        $plan_list_id=Lib::post('plan_list_id');
        if(!$plan_list_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'plan_list_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $ret = $this->m->changePlan($user_id,$plan_id,$plan_list_id);
        if($ret==1){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '暂停成功',
                'data' => $ret
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '暂停失败',
                'data' => $ret
            ];
        }
        Lib::outputJson($data);
    }
}