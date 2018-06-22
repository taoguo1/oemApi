<?php
namespace App\API\V100\Model;
use Core\Extend\IDAuth;
use Core\DB\DBQ;
use Core\Lib;
use Exception;
use Core\Extend\Redis;
class DebitCard extends Base
{
    public function getList($condition)
    {
        $data = DBQ:: getAll('debit_card',[
            "id",
            "status",
            "bank_id",
            "bank_name",
            "card_no",
            'is_default'
        ],$condition);

        if(!empty($data)){
         
            foreach ($data as $k => $v){
                $bank=Lib::getOneBankConfig($v['bank_id']);
                $data[$k]['logo']=$bank['logo'];
                $data[$k]['back_image']=$bank['back_image'];
                $data[$k]['card_no']=Lib::aesDecrypt($v['card_no']);

                
            }
        }

        return $data;
    }

    public function card_status($isCard,$uid)
    {
        $arr = DBQ::getRow('debit_card', '*', [
            'status' =>1,
            'user_id ' => $uid
        ]);
        ($arr) ? $is_default = 0: $is_default = 1 ;
        DBQ::upd('debit_card',[
            'status'  =>2,
            'is_default' => $is_default,
            'last_update_time'  =>  Lib::getMs()
        ], [
            'id' =>$isCard['id'],
        ]);
         return 2;
    }
    //绑定银行卡
    public function add($data)
    {
        
        if (!\ctype_digit($data['user_id'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => 'uid不合法'));
        }
        if (empty($data['bank_id']) || !(\ctype_digit($data['bank_id']))) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行id不存在或者格式错误'));
        }
        if (empty($data['appid'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '设备号不能为空'));
        }
        if (empty($data['mobile']) || !(Lib::checkMobile( $data['mobile']))){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '手机号为空或格式错误'));
        }
        if (empty($data['real_name'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '持卡人姓名不能为空'));
        }
        if (!\ctype_digit($data['card_no'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '储蓄卡号不能为空或格式错误'));
        }
        if(!(Lib::isIdCard($data['id_card']))){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '身份证号不能为空或格式错误'));
        }
        if (empty($data['code'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '验证码不能为空'));
        }
        
        $data['card_no']=\preg_replace('# #','',$data['card_no']);
/*         $cardVerification=$this->cardVerification($data['real_name'],$data['card_no'],$data['id_card'],$data['mobile']);
        if (!$cardVerification){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '身份证银行卡认证失败'));
        } */
        $authBankSta = Lib::bankAuthAli($data['real_name'],$data['id_card'],$data['card_no'],$data['mobile']);//阿里云四要素
        if (empty($authBankSta)) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行卡认证失败'));
        }
        $authBank = json_decode($authBankSta,true);
        if (!empty($authBank['respCode']) && $authBank['respCode'] != '0000') {
            $msg = !empty($authBank['respMessage']) ? $authBank['respMessage'] : '银行卡认证失败！';
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => $msg));
        }
        
        // 校样验证码
        $check_code_validity = $this->checkCodeValidity($data['mobile'], $data['code'], $data['appid'],true,$data['user_type']);
        if ($check_code_validity['status'] != 'success') {
            Lib::outputJson($check_code_validity);
        }
        $where = null;
        $where['card_no'] = Lib::aesEncrypt($data['card_no']);
        $where['user_type'] = $data['user_type'];
        $where['user_id'] = $data['user_id'];
        $isCard = DBQ::getRow('debit_card', '*', $where);
        if(!empty($isCard) && $isCard['status']==-1){
            //$isCard['status']=$this->card_status($isCard,$data['user_id']);
            $rowRet = DBQ::upd('debit_card', ['status' => 1],$where);
            if ($rowRet) Lib::outputJson(array('status'=>'success','code'=>1000,'msg'=>'绑卡成功'));
        }
        if($isCard['status']==1){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '储蓄卡已绑定'));
        }
        
        $dictionary = Lib::loadFile('Config/Dictionary.php');
        $debitCard_data = [
            'user_id' => $data['user_id'],
            'bank_id' => $data['bank_id'],
            'user_type' => $data['user_type'],
            'bank_name' => $data['bank_name'],
            'mobile' => Lib::aesEncrypt($data['mobile']),
            'lb_mobile' =>\substr_replace( $data['mobile'], '****', 3, 4),
            'real_name' =>Lib::aesEncrypt($data['real_name']),
            'card_no' => Lib::aesEncrypt($data['card_no']),
            'id_card' => Lib::aesEncrypt($data['id_card']),
            //'channel_code' =>$dictionary['channel'][2]['code'],
            //'channel_type' =>2,
            'status'       => 1,
            'create_time' =>Lib::getMs()
        ];
        //是否设置默认储蓄卡
        $default = DBQ::getRow('debit_card', '*', ['user_id'=>$data['user_id'],'user_type' => $data['user_type']]);
        if(empty($default)){
            $debitCard_data['is_default']=1;
        }
        DBQ::add('debit_card', $debitCard_data);
        $result = DBQ::insertID();
        if ($result) {
            Lib::outputJson(array('status'=>'success','code'=>1000,'msg'=>'绑卡成功'));
        } else {
            Lib::outputJson(array('status'=>'fail','code'=>1000,'msg'=>'绑卡失败'));
        }
    }
    public function addBak($data)
    {

        if (!\ctype_digit($data['user_id'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => 'uid不合法'));
        }
        if (empty($data['bank_id']) || !(\ctype_digit($data['bank_id']))) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '银行id不存在或者格式错误'));
        }
        if (empty($data['appid'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '设备号不能为空'));
        }
        if (empty($data['mobile']) || !(Lib::checkMobile( $data['mobile']))){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '手机号为空或格式错误'));
        }
        if (empty($data['real_name'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '持卡人姓名不能为空'));
        }
        if (!\ctype_digit($data['card_no'])){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '储蓄卡号不能为空或格式错误'));
        }
        if(!(Lib::isIdCard($data['id_card']))){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '身份证号不能为空或格式错误'));
        }
        if (empty($data['code'])) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '验证码不能为空'));
        }

        $data['card_no']=\preg_replace('# #','',$data['card_no']);
        $cardVerification=$this->cardVerification($data['real_name'],$data['card_no'],$data['id_card'],$data['mobile']);
        if (!$cardVerification){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '身份证银行卡认证失败'));
        }

        $where['card_no'] = Lib::aesEncrypt($data['card_no']);
        $where['user_type'] = $data['user_type'];
        $isCard = DBQ::getRow('debit_card', '*', $where);
        if($isCard['status']==-1){
            $isCard['status']=$this->card_status($isCard,$data['user_id']);
        }
        if($isCard['status']==1){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '储蓄卡已绑定'));
        }

        // 校样验证码
        $check_code_validity = $this->checkCodeValidity($data['mobile'], $data['code'], $data['appid'],true,$data['user_type']);
        if ($check_code_validity['status'] != 'success') {
            Lib::outputJson($check_code_validity);
        }
        $dictionary = Lib::loadFile('Config/Dictionary.php');


        $debitCard_data = [
            'user_id' => $data['user_id'],
            'bank_id' => $data['bank_id'],
            'user_type' => $data['user_type'],
            'bank_name' => $data['bank_name'],
            'mobile' => Lib::aesEncrypt($data['mobile']),
            'lb_mobile' =>\substr_replace( $data['mobile'], '****', 3, 4),
            'real_name' =>Lib::aesEncrypt($data['real_name']),
            'card_no' => Lib::aesEncrypt($data['card_no']),
            'id_card' => Lib::aesEncrypt($data['id_card']),
            'channel_code' =>$dictionary['channel'][2]['code'],
            'channel_type' =>2,
            'status'       => 2,
            'create_time' =>Lib::getMs()
        ];
        $default = DBQ::getRow('debit_card', '*', ['user_id'=>$data['user_id'],'user_type' => $data['user_type']]);
        if(empty($default)){
            $debitCard_data['is_default']=1;
        }


        //////////////////银行卡鉴卡
        $res=$this->SdkPayAuthUrl($data);
        if($res['status']==1) {

            $order_sn="";
            $sysOrderSn="";
            if(!empty($res['data']['sysId'])){
                $debitCard_data['sysId']=$res['data']['sysId'];
            }
            if(!empty($res['data']['sysOrderSn'])){
                $debitCard_data['sysOrderSn']=$res['data']['sysOrderSn'];
                $sysOrderSn=$res['data']['sysOrderSn'];
            }
            if(!empty($res['data']['userOrderSn'])){
                $debitCard_data['userOrderSn']=$res['data']['userOrderSn'];
                $order_sn=$res['data']['userOrderSn'];
            }
            // try{

                if($isCard['status'] != 2) {

                         DBQ::add("debit_card",$debitCard_data);
                        $debitCard=DBQ::insertID();
                        if ($debitCard) {
                            if($data['user_type']==2){
                                //添加账单表记录
                                $bill_data = array(
                                    'user_id'          => 0,
                                    'agent_id'         => $data['user_id'],
                                    'plan_id'          => 0,
                                    'amount'           => VALIDATECARD_POUNDAGE,
                                    'poundage'         => 0,
                                    'rpoundage'        => 0,
                                    'bill_type'        => 5,
                                    'card_type'        => 2,
                                    'bank_id'          => $data['bank_id'],
                                    'bank_name'        => $data['bank_name'],
                                    'card_no'          => Lib::aesEncrypt($data['card_no']),
                                    'status'           => 1,//执行状态(1成功-1失败0默认状态)
                                    'order_sn'         => $order_sn,
                                    'sysOrderSn'         => $sysOrderSn,
                                    'channel'          => 2,//1易联2易宝
                                    'is_pay'           => -1,
                                    'create_time'      => Lib::getMs()
                                );
                            }else{
                                //添加账单表记录
                                $bill_data = array(
                                    'user_id'          => $data['user_id'],
                                    'agent_id'         => 0,
                                    'plan_id'          => 0,
                                    'amount'           => VALIDATECARD_POUNDAGE,
                                    'poundage'         => 0,
                                    'rpoundage'        => 0,
                                    'bill_type'        => 5,
                                    'card_type'        => 2,
                                    'bank_id'          => $data['bank_id'],
                                    'bank_name'        => $data['bank_name'],
                                    'card_no'          => Lib::aesEncrypt($data['card_no']),
                                    'status'           => 1,//执行状态(1成功-1失败0默认状态)
                                    'order_sn'         => $order_sn,
                                    'sysOrderSn'         => $sysOrderSn,
                                    'channel'          => 2,//1易联2易宝
                                    'is_pay'           => -1,
                                    'create_time'      => Lib::getMs()
                                );
                            }
                             DBQ::add('bill', $bill_data);
                            $result_bill=DBQ::insertID();
                            //添加日志
                            $logsData = array('signMsg' => '绑定储蓄卡插入账单');
							Lib::tempLog('debitCard.txt',$bill_data,'Pay');
                            
                            if ($result_bill) {
                                //同步数据到总服务器
                                if(($data['user_type']==2)) {
                                    $bill_data = [
                                        'user_id' => 0,
                                        'amount' => VALIDATECARD_POUNDAGE,
                                        'bill_type' => 5,
                                        'card_type' => 2,
                                        'status' => 1,
                                        'order_sn' => $order_sn,
                                        'is_pay' => -1,
                                        'create_time' => Lib::getMs(),
                                        'poundage' => 0,
                                        'appid' => $data["appid"],
                                        'sysOrderSn' => $sysOrderSn,
                                        'agent_id' => $data['user_id'],
                                        'transaction_id' => 0,
                                        'version'=>OEM_CTRL_URL_VERSION
                                    ];
                                } else {
                                    $bill_data = [
                                        'user_id' => $data['user_id'],
                                        'amount' => VALIDATECARD_POUNDAGE,
                                        'bill_type' => 5,
                                        'card_type' => 2,
                                        'status' => 1,
                                        'order_sn' => $order_sn,
                                        'is_pay' => -1,
                                        'create_time' => Lib::getMs(),
                                        'poundage' => 0,
                                        'appid' => $data["appid"],
                                        'sysOrderSn' => $sysOrderSn,
                                        'agent_id' => 0,
                                        'transaction_id' => 0,
                                        'version'=>OEM_CTRL_URL_VERSION
                                    ];
                                }


                                Lib::httpPostUrlEncode(MAINURL,$bill_data);
                            } else {
                               // $this->db->pdo->rollBack();
                                Lib::outputJson(array('status'=>'fail','code'=>1000,'msg'=>'账单插入失败','data'=> ''));
                            }

                        } else {
                            //$this->db->pdo->rollBack();
                            Lib::outputJson(array('status'=>'fail','code'=>1000,'msg'=>'绑卡插入数据失败','data'=> ''));
                        }


                }elseif($isCard['status']==2 && empty($isCard['sysId'])){

                    DBQ::upd('bill',['order_sn'=>$order_sn,'sysOrderSn'=>$sysOrderSn],['order_sn'=>$isCard['userOrderSn'],'sysOrderSn'=>$isCard['sysOrderSn']]);
                    DBQ::upd('debit_card',['userOrderSn'=>$order_sn,'sysOrderSn'=>$sysOrderSn],['id'=>$isCard['id']]);

                }
                Lib::outputJson(array('status'=>'success','code'=>10000,'msg'=>'绑定成功','data' => $res['data']['payUrl']));

            /*} catch (Exception $e) {
                $this->db->pdo->rollBack();
                throw $e;
                //return false;
                Lib::outputJson(array('status'=>'fail','code'=>1000,'msg'=>'绑定失败','data'=> ''));
            }*/

        }else{

            Lib::outputJson(array('status'=>'fail','code'=>1000,'msg'=>$res['msg']));
        }


    }
    //商户银行卡鉴卡
    public function SdkPayAuthUrl($data)
    {
        $sdkreg="";
        if($data['user_type']==2){
            $rst=DBQ::getOne('agent_ext',['userCode'],['agent_id'=>$data['user_id']]);
            if(empty($rst['userCode'])){
                $sdkreg=Lib::SdkAgentReg($data['user_id']);
            }
        }else{
            $rst=DBQ::getOne('user_ext',['userCode'],['user_id'=>$data['user_id']]);
            if(empty($rst['userCode'])){
                $sdkreg= Lib::SdkUserReg($data['user_id']);

            }
        }
        $datapost=[];
        $datapost['action'] ='SdkPayAuthUrl';
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = MERCHANT_ID;//大商户号
        $datapost['userCode'] = $rst['userCode'];//子商编号
        $datapost['cardType'] = 1;//银行卡类型
        $datapost['bankNo'] = $data['card_no'];//银行卡号
        $datapost['bankPhone'] = $data['mobile'];//绑定手机号码
        $datapost['bankCvn'] = '';//信用卡后三位
        $datapost['bankValidityDay'] = '';//信用卡有效期
        $datapost['notifyUrl'] = ZF_DIFF_PATH.'notice/bkResponce/?appid='.$data['sdkappid'];//异步通知地址
        $datapost['notifyUrlBefore'] = FRAME_OPEN_URL.$data['sdkappid'];//同步跳转
        $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['bankPhone'].']|['.$datapost['cardType'].']|['.$datapost['bankNo'].']|['.$datapost['bankCvn'].']|['.$datapost['bankValidityDay'].']|['.$datapost['merSn'].']'.ZF_SIGN_IN);
        $datapost['Sign'] =$sing;


        $res =Lib::httpPostUrlEncode(ZF_URL."?action=SdkPayAuthUrl",$datapost);
        $result = json_decode($res,true);
        $payUrl=isset($result['payUrl'])?$result['payUrl']:'';
        $sysId=isset($result['sysId'])?$result['sysId']:'';
        $sysOrderSn=isset($result['sysOrderSn'])?$result['sysOrderSn']:'';
        $userOrderSn=isset($result['userOrderSn'])?$result['userOrderSn']:'';
        if($result['error']==0){
            $codedata=array(
                'payUrl'=>$payUrl,
                'sysOrderSn'=>$sysOrderSn,
                'userOrderSn'=>$userOrderSn,
                'sysId'=>$sysId
            );
            $rst =array('status'=>1,'msg'=>'鉴卡成功 '.$sdkreg,'data'=>$codedata);
        }else{
            $rst =array('status'=>0,'msg'=>$result['error_msg'].' '.$sdkreg);
        }

        return $rst;

    }
    public function deletions($where){
        $rest=$this->SdkCardClean($where);
        if($rest['status']=='success'){
            $status=DBQ::del('debit_card',$where);
            if(!$status){
                return false;
            }
            $arr = DBQ::getRow('debit_card', '*', [
                'status' =>1,
                'user_id ' => $where['user_id'],
                'user_type' =>$where['user_type']
            ]);
            if(!$arr){
                return true;
            }

            DBQ::upd('debit_card',[
                'is_default' =>1,
            ], ['id'=>$arr['id'],'user_id ' => $where['user_id']]);
            return true;
        }else{
            return false;
        }



    }
    //银行卡解绑备份
    public function SdkCardCleanBAK($where){
        $dbcard = DBQ::getRow('debit_card','*', [ 'id' =>$where['id']]);
        if($where['user_type']==1){
            $rst=DBQ::getOne('user_ext',['userCode'],['user_id'=>$where['user_id']]);
        }else{
            $rst=DBQ::getOne('agent_ext',['userCode'],['agent_id'=>$where['user_id']]);
        }

        if(!empty($dbcard['sysId'])){
            $datapost=[];
            $datapost['action'] ='SdkCardClean';
            $datapost['version'] =ZF_VERSION;
            $datapost['merSn'] = MERCHANT_ID;//大商户号
            $datapost['userCode'] = $rst['userCode'];//子商编号
            $datapost['cardType'] = 1;//1是储蓄卡
            $datapost['sysId'] = $dbcard['sysId'];//银行卡协议号

            $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['sysId'].']|['.$datapost['cardType'].']|['.$datapost['merSn'].']'.ZF_SIGN);
            $datapost['Sign'] =$sing;
            $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkCardClean",$datapost);
            $res = json_decode($result,true);

            if($res['error']==0){
                $data = [
                    'status' => 'success',
                    'code' => 10000,
                    'msg' => '银行卡解绑成功',
                    'data'=>$res
                ];
            }else{
                $data = [
                    'status' => 'fail',
                    'code' => 1000,
                    'msg' => $res['error_msg']
                ];
            }
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => '银行卡协议号不存在'
            ];
        }
        return $data;
    }
    //银行卡解绑
    public function SdkCardClean($where){
        $dbcard = DBQ::getRow('debit_card','*', [ 'id' =>$where['id']]);
        if (!empty($dbcard)) {
            $row = DBQ::upd('debit_card',['status' => -1], [ 'id' =>$where['id']]);
            if ($row) {
                Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '删除成功！'));
            } else {
                Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '删除失败！'));
            }
        } elseif ($dbcard['status'] == -1) {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '该卡已删除！'));
        } else {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '删除数据有误！'));
        }

    }

    public function edit($defaultData ,$where)
    {
        $res = DBQ::getRow('debit_card', '*', [
            'is_default' =>1,
            'user_id ' => $where['user_id']
        ]);
        if($res){
            DBQ::upd('debit_card',['is_default' => 0], [
                'id' =>$res['id'],
            ]);
        }
        return  DBQ::upd('debit_card',$defaultData, $where);
    }
}