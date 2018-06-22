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


class Gather extends Controller
{

    public $m;
    public $headers;
    public $appid;
    public $pay;
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->headers = Lib::getAllHeaders();
        $payModelPath = "\\App\API\\".$this->headers['VERSION']."\\Model\\Pay";
        $model = "\\App\API\\".$this->headers['VERSION']."\\Model\\Gather";
        $this->m = new $model;
        $this->appid = Lib::post('appid');
        $this->pay = new $payModelPath;
    }

    public function index(){
        //获取用户信用卡信息
        $cardId = Lib::post('card_id');
        $incardId = Lib::post('in_card_id');
        $amount = Lib::post('amount');
        $uid = $this->headers['UID'];
        $cCardRow = $this->m->getCreditCardInfo($uid, $cardId);
        $dCardRow = $this->m->getDebitCardInfo($uid, $incardId,1);
        $userRow = $this->m->getUserExt($uid);
        if(!$cCardRow || !$dCardRow){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '卡片信息有误'
            ];
            Lib::outputJson($data);
        }
        /**/
        $h = intval(date('H'));
        if($h < 8 || $h > 17){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '收款时间为8:00至17:00'
            ];
            Lib::outputJson($data);
        }
        
        if(!$amount){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '金额不能为空'
            ];
            Lib::outputJson($data);
        }
        if($amount > 10000){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '金额不能大于10000'
            ];
            Lib::outputJson($data);
        }
        $tx_disablearray=[];
        if(!empty(TX_DISABLED) && is_array(TX_DISABLED)){
            $tx_disablearray=TX_DISABLED;
            $datebay=date("Y-m-d");
            if(in_array($datebay,$tx_disablearray)){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '非工作日不能收款！'
                ];
                Lib::outputJson($data);
            }
        }
        if(date("w")== 0 || date("w") == 6 ){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '非工作日不能收款！'
            ];
            Lib::outputJson($data);
        }
        //检测卡片状态
        
        $dataPost = array(
            'userCode' => $userRow['userCode'],
            'sysId' => $cCardRow['sysId'],
            'cardType'=> 2,
        );
        $result = $this->pay->payCardQuery($dataPost);
        Lib::tempLog('g_jq.txt',$result,'Gather');
        if ($result['isCan'] == '否') {
            $dataErr = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '该银行卡没有鉴权',
                'isCan'=> 0
            ];
            Lib::outputJson($dataErr);
        }
        
        
        //print_r($cCardRow);
        //print_r($dCardRow);
        //print_r($userRow);
        //die('pause');
        $bankNo = Lib::aesDecrypt($dCardRow['card_no']);
        $bankId = $dCardRow['bank_id'];
        $order_sn =  Lib::createOrderNo();
        //入款请求接口
        $inData = [
            'userCode'  => $userRow['userCode'],         //子商编号
            'sysId'  => $cCardRow['sysId'],               //订单类型
            'amount'  => $amount,             //金额
            'userOrderSn'  => $order_sn,   //手续费 + 还款笔数费
            'notifyUrl' => OEM_CTRL_URL.'call/GatherNotice/in',        //异步通知地址
            'attach'=>$this->appid.'|'.$bankId.'|'.$bankNo,
            'notifyUrlBefore'=>FRAME_OPEN_URL.$this->appid,
        ];
        //print_r($inData);
        //Lib::outputJson($inData);
        $inResult = $this->pay->payTxIn($inData);
        //var_dump($inResult);exit;
        if($inResult['error'] == 0){
            //记账，计入bill表
            $billData1 = [
                'user_id' => $uid,
                'amount' => $amount,
                'bill_type' => 8,
                'card_type' => 1,
                'bank_name' => $cCardRow['bank_name'],
                'card_no' => $cCardRow['card_no'],
                'bank_id' => $cCardRow['bank_id'],
                'poundage' => $amount * TXLNVALUE / 10000 + TXOUTVALUE,
                'order_sn' => $order_sn,
                'userOrderSn'=> $inResult['userOrderSn'],
                'sysOrderSn'=> $inResult['sysOrderSn'],
                'channel' => 2,
                'status' => -1,
                'intatus' => 1,
                'is_pay' => -1,
                'create_time' => Lib::getMs(),
            ];
            
            $redis = Redis::instance('msg');
            $ret1 = $redis->set('gather_data_'.$inResult['userOrderSn'],$inData['attach']);
            //$ret2 = $redis->zAdd('gather',Lib::getMs(),json_encode($billData2));
            
            $billRet1 = $this->m->addBill($billData1);
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '请求成功',
                'payUrl'=>$inResult['payUrl'],
                'result' => $inResult
            ];
            Lib::outputJson($data);
        }
    }

    public function new01(){

        $uid = $this->headers['UID'];
        $h = intval(date('H'));
        if($h < 8 || $h > 22){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '收款时间为8:00至22:00'
            ];
            Lib::outputJson($data);
        }
        if(date("w")== 0 || date("w") == 6 ){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '非工作日不能收款！'
            ];
            Lib::outputJson($data);
        }

        /*$billDataIn = [
            'user_id' => $uid,
            'amount' => 0,
            'bill_type' => 8,
            'card_type' => 1,
            'bank_name' => '',
            'card_no' => '',
            'bank_id' => 0,
            'poundage' => 0,
            'order_sn' => '',
            'userOrderSn'=> '',
            'sysOrderSn'=> '',
            'channel' => 2,
            'status' => -1,
            'intatus' => 1,
            'is_pay' => -1,
            'create_time' => Lib::getMs(),
        ];
        $inId = $this->m->addBillRid($billDataIn);
        $billDataOut = [
            'user_id' => $uid,
            'amount' => 0,
            'bill_type' => 8,
            'card_type' => 2,
            'bank_name' => '',
            'card_no' => '',
            'bank_id' => 0,
            'poundage' => 0,
            'order_sn' => '',
            'userOrderSn'=> '',
            'sysOrderSn'=> '',
            'channel' => 2,
            'status' => -1,
            'intatus' => 1,
            'is_pay' => -1,
            'create_time' => Lib::getMs(),
        ];
        $outId = $this->m->addBillRid($billDataOut);*/

        //$outId=1;
        //$inId =1;
        //请求yjf
        /*
        $post = [
            'uid'=>$uid,
            'appid'=>$this->appid,
            'inId'=> $inId,
            'outId' =>$outId
        ];
        $result = Lib::httpPostUrlEncode('https://manage.dizaozhe.cn/yjPay/index',$post);
        */
        if(1){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '请求成功',
                //'url' => 'https://manage-dev.dizaozhe.cn/yjPay/index/?appId='.$this->appid.'&userId='.$uid.'&inId='.$inId.'&outId='.$outId
                'url' => OEM_CTRL_URL.'yjPay/index/?appId='.$this->appid.'&userId='.$uid
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10000,
                'msg' => '请求失败',
                'url' => ''
            ];
        }

        Lib::outputJson($data);
    }

    public function txChannel(){
        $channelData = [
            ['name'=>'通道一','cname'=>1,'apiUrl'=>'api/Gather/index','param'=>'card_id, amount, in_card_id'],
            ['name'=>'通道二','cname'=>2,'apiUrl'=>'api/Gather/new01','param'=>''],
        ];
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '请求成功',
            'data'=>$channelData,
        ];
        Lib::outputJson($data);
    }

    public function record(){
        $user_id = $this->headers['UID'];
        $condition['AND']['user_id'] = $user_id;
        $condition['AND']['bill_type'] = 8;
        //$condition['AND']['status'] = 1;
        $condition['AND']['is_pay'] = 1;
        $condition ['ORDER'] = [
            'A.id' => 'DESC'
        ];
        $numPerPage = Lib::post('numPerPage');
        $pageArr = Lib::setPagePars ();
        $pageArr['numPerPage'] = $numPerPage ? $numPerPage : 10;
        $datalist = $this->m->getList($pageArr, $condition);
        //$datalist = $this->m->getList($user_id);
        $dataAll = [];
        foreach($datalist['list'] as $k => $v){
            $v['create_time_str'] = Lib::uDate('m-d H:i',$v['create_time']);
            if($v['card_no']){
                $v['card_no'] = Lib::aesDecrypt($v['card_no']);
                $v['card_no_last4'] = substr(Lib::aesDecrypt($v['card_no']),-4);
            }
            $dataAll[] = $v;
        }
        $datalist['list'] = $dataAll;
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取收款记录成功',
            //'data' => $dataAll,
            'data' => $datalist,
        ];
        Lib::outputJson($data);
    }

    public function creditCard(){
        $cardId = Lib::post('card_id');
        $uid = $this->headers['UID'];
        $rs = $this->m->getCreditCardInfo($uid, $cardId);
        if($rs) {
            $tRs = [
                'mobile' => Lib::aesDecrypt($rs['mobile']),
                'true_name' => Lib::aesDecrypt($rs['real_name']),
                'card_no' => Lib::aesDecrypt($rs['card_no']),
                'cvn' => Lib::aesDecrypt($rs['cvn']),
                'validity_day' => Lib::aesDecrypt($rs['expiry_date']),
                'bank_name' => $rs['bank_name'],
            ];

            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '信用卡详情获取成功',
                'ret' => $tRs
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '信用卡详情获取失败',
            ];
            Lib::outputJson($data);
        }
    }

    public function debitCard(){
        $cardId = Lib::post('card_id');
        $utype = $this->headers['UTYPE'];
        $uid = $this->headers['UID'];
        $rs = $this->m->getDebitCardInfo($uid, $cardId,$utype);
        if($rs) {
            $tRs = [
                'mobile' => Lib::aesDecrypt($rs['mobile']),
                'true_name' => Lib::aesDecrypt($rs['real_name']),
                'card_no' => Lib::aesDecrypt($rs['card_no']),
                'bank_name' => $rs['bank_name'],
            ];

            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '储蓄卡详情获取成功',
                'ret' => $tRs
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '储蓄卡详情获取失败',
            ];
            Lib::outputJson($data);
        }
    }


}