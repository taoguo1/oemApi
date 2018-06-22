<?php
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
class JwPay extends Controller{
    // 配置内容
    protected $appid= "";

    public function getJwpayOem()
    {
        $this->appid = Lib::request('appid');
        //获取商户配置
        $postData = [
            'appid' => $this->appid,
            'version' => 'V100'
        ];
        $ret = Lib::httpPostUrlEncode(OEM_CTRL_URL.'api/getConfig', $postData);
        $ret = json_decode($ret, true);
        if ($ret['status'] == 'fail') {
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => '获取失败'
            ];
        } else {
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '获取成功',
                'abroad_url' => $ret['abroad_url']
            ];
        }
        Lib::outputJson($data);
    }
    //商户注册支付
    public function SdkUserReg()
    {

        $datapost=[];
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = MERCHANT_ID;//大商户号
        $datapost['userName'] = trim(Lib::post('userName'));//商户名称
        $datapost['userPhone'] = trim(Lib::post('userPhone')); //法人手机
        $datapost['userAccount'] = trim(Lib::post('userAccount'));//法人姓名
        $datapost['userCert'] = trim(Lib::post('userCert'));//身份证号
        $datapost['userType'] = 'A';//类型A用户C商户
        if(empty($datapost['userName'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '商户名称不能为空');
        }
        if(empty($datapost['userPhone'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '法人手机不能为空');

        }
        if(empty($datapost['userAccount'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '法人姓名不能为空');
        }
        if(empty($datapost['userCert'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '身份证号不能为空');
        }
        $sing=md5('['.$datapost['version'].']|['.$datapost['userCert'].']|['.$datapost['userPhone'].']|['.$datapost['userType'].']|['.$datapost['merSn'].']'.ZF_SIGN);
        $datapost['Sign'] =$sing;
        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkUserReg",$datapost);
        $result = json_decode($result,true);

        if($result['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '商户信息提交成功',
                'userCode' => $result['userCode']
            ];

        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $result['error_msg']
            ];
        }

        Lib::outputJson($data);

    }
    //商户信息变更
    public function SdkUserModify()
    {

        $datapost=[];
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = MERCHANT_ID;//大商户号
        $datapost['userCode'] = trim(Lib::post('userCode'));//子商编号
        $datapost['userName'] = trim(Lib::post('userName'));//商户名称
        $datapost['userPhone'] = trim(Lib::post('userPhone')); //法人手机
        if(empty($datapost['userName'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '商户名称不能为空');
        }
        if(empty($datapost['userPhone'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '法人手机不能为空');
        }
        //签名
        $sing=md5('['.$datapost['version'].']|['.$datapost['userPhone'].']|['.$datapost['userCode'].']|['.$datapost['merSn'].']'.ZF_SIGN);
        $datapost['Sign'] =$sing;
        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkUserModify",$datapost);
        $result = json_decode($result,true);

        if($result['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '商户信息变更成功'
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $result['error_msg']
            ];
        }

        Lib::outputJson($data);

    }
    //商户费率设置
    public function SdkUserRate()
    {

        $datapost=[];
        $datapost['action'] = "SdkUserRate";
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = trim(Lib::post('merSn'));//大商户号
        $datapost['userCode'] = trim(Lib::post('userCode'));//子商编号
        $datapost['czValue'] = trim(Lib::post('czValue'));//充值手续费
        $datapost['txValue'] = trim(Lib::post('txValue'));//提现手续费
        $datapost['xfValue'] = trim(Lib::post('xfValue'));//消费手续费
        $datapost['jqValue'] = trim(Lib::post('jqValue'));//鉴权手续费
        $datapost['hkValue'] = trim(Lib::post('hkValue'));//还款手续费
        $datapost['sfValue'] = trim(Lib::post('sfValue'));//身份鉴权手续费
        $datapost['txInValue'] = trim(Lib::post('txInValue'));//套现入款手续费
        $datapost['txOutValue'] = trim(Lib::post('txOutValue'));//套现出款手续费
        $datapost['ZF_SIGN'] = trim(Lib::post('ZF_SIGN'));//商户秘钥



        if(empty($datapost['czValue'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '充值手续费不能为空');
        }
        if(empty($datapost['txValue'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '提现手续费不能为空');
        }
        if(empty($datapost['xfValue'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '消费手续费不能为空');
        }
        if(empty($datapost['jqValue'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '鉴权手续费不能为空');
        }
        if(empty($datapost['hkValue'])){
            return array('status' => 'fail', 'code' => 1000, 'msg' => '还款手续费不能为空');
        }
        $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['merSn'].']'.$datapost['ZF_SIGN']);
        $datapost['Sign'] =$sing;
        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkUserRate",$datapost);
        $result = json_decode($result,true);

        if($result['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => $result
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $result['error_msg']
            ];
        }

        Lib::outputJson($data);


    }

    //商户信息查询
    public function SdkUserQuery()
    {

        $datapost=[];
        $datapost['action'] ="SdkUserQuery";
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = trim(Lib::post('merSn'));//大商户号V3.0.1
        $datapost['userCode'] = trim(Lib::post('userCode'));//子商编号
        $datapost['ZF_SIGN'] = trim(Lib::post('ZF_SIGN'));//商户秘钥

        $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['merSn'].']'.$datapost['ZF_SIGN']);
        $datapost['Sign'] =$sing;
        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkUserQuery",$datapost);
        $result = json_decode($result,true);

        if($result['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '商户信息查询成功',
                'data'=>$result
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $result
            ];
        }

        Lib::outputJson($data);

    }
    //商户银行卡鉴卡
    public function SdkPayAuthUrl()
    {
      
        $datapost=[];
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = '140000000024';//MERCHANT_ID;//大商户号
        $datapost['userCode'] = trim(Lib::post('userCode'));//子商编号
        $datapost['cardType'] = trim(Lib::post('cardType'));//银行卡类型
        $datapost['bankNo'] = trim(Lib::post('bankNo'));//银行卡号
        $datapost['bankPhone'] = trim(Lib::post('bankPhone'));//绑定手机号码
        $datapost['bankCvn'] = trim(Lib::post('bankCvn'));//信用卡后三位
        $datapost['bankValidityDay'] = trim(Lib::post('bankValidityDay'));//信用卡有效期
        $datapost['notifyUrl'] = trim(Lib::post('notifyUrl'));//异步通知地址
        $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['bankPhone'].']|['.$datapost['cardType'].']|['.$datapost['bankNo'].']|['.$datapost['bankCvn'].']|['.$datapost['bankValidityDay'].']|['.$datapost['merSn'].']'.'54f580cb4a0708c3a4fb6ceb0e31f42d');
        $datapost['Sign'] =$sing;


        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkPayAuthUrl",$datapost);
        $result = json_decode($result,true);

        if($result['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '商户信息查询成功',
                'data'=>$result
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $datapost
            ];
        }

        Lib::outputJson($data);

    }
    //订单查询
    public function SdkPayQuery()
    {

        $datapost=[];
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = MERCHANT_ID;//大商户号
        $datapost['sysOrderSn'] = trim(Lib::post('sysOrderSn'));//平台订单号
        $datapost['userOrderSn'] = trim(Lib::post('userOrderSn'));//商户订单号
        $sing=md5('['.$datapost['version'].']|['.$datapost['sysOrderSn'].']|['.$datapost['userOrderSn'].']|['.$datapost['merSn'].']'.ZF_SIGN_IN);
        $datapost['Sign'] =$sing;

        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkPayQuery",$datapost);
        $res= json_decode($result,true);
        if($res['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '订单查询成功',
                'data'=>$res
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $result['error_msg']
            ];
        }

        Lib::outputJson($data);

    }
    //订单下单
    public function SdkPayOrder()
    {

        $datapost=[];
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = MERCHANT_ID;//大商户号
        $datapost['userCode'] = trim(Lib::post('userCode'));//子商编号
        $datapost['orderType'] = trim(Lib::post('orderType'));//订单类型
        $datapost['cardType'] = trim(Lib::post('cardType'));//银行卡类型
        $datapost['amount'] = trim(Lib::post('amount'));//金额
        $datapost['sysId'] = trim(Lib::post('sysId'));//银行卡协议号
        $datapost['userOrderSn'] = trim(Lib::post('userOrderSn'));//商户订单号
        $datapost['attach'] = trim(Lib::post('attach'));//附加参数
        $datapost['notifyUrl'] = trim(Lib::post('notifyUrl'));//异步通知地址
        $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['sysId'].']|['.$datapost['orderType'].']|['.$datapost['cardType'].']|['.$datapost['userOrderSn'].']|['.$datapost['merSn'].']'.ZF_SIGN_IN);
        $datapost['Sign'] =$sing;


        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkPayOrder",$datapost);
        $res = json_decode($result,true);
        if($res['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '下单成功',
                'data'=>$res
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $res['error_msg']
            ];
        }

        Lib::outputJson($data);

    }
    //银行卡解绑
    public function SdkCardClean()
    {

        $datapost=[];
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = MERCHANT_ID;//大商户号
        $datapost['userCode'] = trim(Lib::post('userCode'));//子商编号
        $datapost['cardType'] = trim(Lib::post('cardType'));//银行卡类型
        $datapost['sysId'] = trim(Lib::post('sysId'));//银行卡协议号
        $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['sysId'].']|['.$datapost['cardType'].']|['.$datapost['merSn'].']'.ZF_SIGN);
        $datapost['Sign'] =$sing;

        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkCardClean",$datapost);
        $res= json_decode($result,true);
        if($res['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '卡解绑成功'
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $res['error_msg']
            ];
        }

        Lib::outputJson($data);

    }

    //余额转账
    public function SdkBalanceTo()
    {

        $datapost=[];
        $datapost['action'] ='SdkBalanceTo';
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = MERCHANT_ID;//大商户号
        $datapost['userCode'] = trim(Lib::post('userCode'));//子商编号
        $datapost['amount'] = trim(Lib::post('amount'));//金额
        $datapost['remark'] = trim(Lib::post('remark'));//备注
        $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['merSn'].']'.ZF_SIGN_OUT);
        $datapost['Sign'] =$sing;

        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkBalanceTo",$datapost);
        $res= json_decode($result,true);
        if($res['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '转账成功',
                'data'=>$res
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $res['error_msg']
            ];
        }

        Lib::outputJson($data);

    }
	//子商户余额查询
    public function SdkBalanceQuery()
    {

        $datapost=[];
        $datapost['action'] ='SdkBalanceQuery';
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = trim(Lib::post('merSn'));//大商户号
        $datapost['userCode'] = trim(Lib::post('userCode'));//子商编号
        $datapost['ZF_SIGN_OUT'] = trim(Lib::post('ZF_SIGN_OUT'));//出款秘钥
        $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['merSn'].']'.$datapost['ZF_SIGN_OUT']);
        $datapost['Sign'] =$sing;

        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkBalanceQuery",$datapost);
        $res= json_decode($result,true);
        if($res['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '查询成功',
                'data'=>$res
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $res['error_msg']
            ];
        }

        Lib::outputJson($data);

    }
	//大商户余额查询
    public function SdkBalance()
    {

        $datapost=[];
        $datapost['action'] ='SdkBalance';
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = trim(Lib::post('merSn'));//大商户号
		$datapost['ZF_SIGN_OUT'] = trim(Lib::post('ZF_SIGN_OUT'));//出款秘钥
        $sing=md5('['.$datapost['version'].']|['.$datapost['merSn'].']'.$datapost['ZF_SIGN_OUT']);
        $datapost['Sign'] =$sing;

        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkBalance",$datapost);
        $res= json_decode($result,true);
        if($res['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '查询成功',
                'data'=>$res
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $res['error_msg']
            ];
        }

        Lib::outputJson($data);

    }
	//鉴权短信发送
    public function SdkPayAuthSms()
    {

        $datapost=[];
        $datapost['action'] ='SdkPayAuthSms';
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = trim(Lib::post('merSn'));//大商户号
		$datapost['userCode'] = trim(Lib::post('userCode'));//子商编号
		$datapost['cardType'] = trim(Lib::post('cardType'));//银行卡类型
		$datapost['bankNo'] = trim(Lib::post('bankNo'));//银行卡号
		$datapost['bankPhone'] = trim(Lib::post('bankPhone'));//绑定手机号码
		$datapost['bankCvn'] = trim(Lib::post('bankCvn'));//信用卡后三位
		$datapost['bankValidityDay'] = trim(Lib::post('bankValidityDay'));//信用卡有效期
		$datapost['bankCode'] = trim(Lib::post('bankCode'));//银行编码
        $datapost['ZF_SIGN_IN'] = trim(Lib::post('ZF_SIGN_IN'));//入款秘钥
        $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['bankPhone'].']|['.$datapost['cardType'].']|['.$datapost['bankNo'].']|['.$datapost['bankCvn'].']|['.$datapost['bankValidityDay'].']|['.$datapost['merSn'].']'. $datapost['ZF_SIGN_IN']);
        $datapost['Sign'] =$sing;

        $result=Lib::httpPostUrlEncode("http://pay.dizaozhe.cn/product/PAF/?action=SdkPayAuthSms",$datapost);
        $res= json_decode($result,true);
        if($res['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '发送成功',
                'data'=>$res
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $res
            ];
        }

        Lib::outputJson($data);

    }
    //鉴权查询
    public function SdkCardQuery()
    {

        $datapost=[];
        $datapost['action'] ='SdkCardQuery';
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = MERCHANT_ID;//大商户号
        $datapost['userCode'] = trim(Lib::post('userCode'));//子商编号
        $datapost['cardType'] = trim(Lib::post('cardType'));//银行卡类型
        $datapost['sysId'] = trim(Lib::post('sysId'));//银行卡协议号

        $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['sysId'].']|['.$datapost['cardType'].']|['.$datapost['merSn'].']'.ZF_SIGN);
        $datapost['Sign'] =$sing;

        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkCardQuery",$datapost);
        $res= json_decode($result,true);
        if($res['error']==0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '查询成功',
                'data'=>$res
            ];
        }else{
            $data = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $res['error_msg']
            ];
        }

        Lib::outputJson($data);

    }



}