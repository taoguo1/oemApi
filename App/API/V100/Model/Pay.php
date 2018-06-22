<?php
namespace App\API\V100\Model;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Lib;

class Pay extends Model {

    public $Conf;
    public $merId;
    public function __construct()
    {
        //oem用常量
        $this->merId = MERCHANT_ID;
    }

    //代付
    public function payDf($data){
        $merchantId = $this->merId;
        $version = ZF_VERSION;
        $userCode = $data['userCode'];
        $sysId = $data['sysId'];
        $notifyUrl = $data['notifyUrl'];
        $cardType = $data['cardType'];
        $orderType = $data['orderType'];
        $amount = $data['amount'];
        $poundage = $data['poundage'];
        $userOrderSn = $data['userOrderSn'];

        $sdk_sign = md5('['.$version.']|['.$userCode.']|['.$sysId.']|['.$orderType.']|['.$cardType.']|['.$userOrderSn.']|['.$merchantId.']'.ZF_SIGN_OUT);
        $post = array(
            'action'   => 'SdkBalanceOrder',
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'userCode'  => $userCode,         //子商编号
            'cardType'  => $cardType,         //银行卡类型
            'orderType'  => $orderType,       //订单类型
            'sysId'  => $sysId,               //订单类型
            'amount'  => $amount*100,             //金额
            'poundage'  => $poundage*100,         //手续费 + 还款笔数费
            'userOrderSn'  => $userOrderSn,   //手续费 + 还款笔数费
            'notifyUrl' => $notifyUrl,        //异步通知地址
            'Sign'      => $sdk_sign
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL,$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
	}
    //代付查询
    public function payDfQuery($data){
        $merchantId = $this->merId;
        $version = ZF_VERSION;
        $sysOrderSn = $data['sysOrderSn'];
        $userOrderSn = $data['userOrderSn'];
        //md5,[version]|[sysOrderSn]|[userOrderSn]|[merSn]出款密钥
        $sdk_sign = md5('['.$version.']|['.$sysOrderSn.']|['.$userOrderSn.']|['.$merchantId.']'.ZF_SIGN_OUT);
        $post = array(
            'action'   => 'SdkBalanceOrderQuery',
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'sysOrderSn'  => $sysOrderSn,         //手续费 + 还款笔数费
            'userOrderSn'  => $userOrderSn,   //手续费 + 还款笔数费
            'Sign'      => $sdk_sign
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL,$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }
    //代收
    public function payDs($data){
        $merchantId = $this->merId;
        $version = ZF_VERSION;
        $userCode = $data['userCode'];
        $sysId = $data['sysId'];
        $notifyUrl = $data['notifyUrl'];
        $cardType = $data['cardType'];
        $orderType = $data['orderType'];
        $amount = $data['amount'];
        $userOrderSn = $data['userOrderSn'];
        $attach = $data['attach'];
        $notifyUrlBefore = $data['notifyUrlBefore'];
        $sdk_sign = md5('['.$version.']|['.$userCode.']|['.$sysId.']|['.$orderType.']|['.$cardType.']|['.$userOrderSn.']|['.$merchantId.']'.ZF_SIGN_IN);
        $post = array(
            'action'   => 'SdkPayOrder',
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'userCode'  => $userCode,         //子商编号
            'cardType'  => $cardType,         //银行卡类型
            'orderType'  => $orderType,       //订单类型
            'sysId'  => $sysId,               //订单类型
            'amount'  => $amount*100,             //金额
            'userOrderSn'  => $userOrderSn,   //手续费 + 还款笔数费
            'notifyUrl' => $notifyUrl,        //异步通知地址
            'attach'=>$attach,
            'notifyUrlBefore'=>$notifyUrlBefore,
            'Sign'      => $sdk_sign,
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL,$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }
    //代收查询
    public function payDsQuery($data){
        $merchantId = $this->merId;
        $version = ZF_VERSION;
        $sysOrderSn = $data['sysOrderSn'];
        $userOrderSn = $data['userOrderSn'];
        //md5,[version]|[sysOrderSn]|[userOrderSn]|[merSn]入款密钥
        $sdk_sign = md5('['.$version.']|['.$sysOrderSn.']|['.$userOrderSn.']|['.$merchantId.']'.ZF_SIGN_IN);
        $post = array(
            'action'   => 'SdkPayQuery',
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'sysOrderSn'  => $sysOrderSn,         //手续费 + 还款笔数费
            'userOrderSn'  => $userOrderSn,   //手续费 + 还款笔数费
            'Sign'      => $sdk_sign
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL,$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }
    //平衡请求平台费率  $userCode子商户号
    public function balanceUserRate($userCode)
    {
        if (empty($userCode)) return false;
        //请求费率查询
        $sign = md5("[".ZF_VERSION."]|[".$userCode."]|[".$this->merId."]".ZF_SIGN);
        $postData = array(
            'action'   => 'SdkUserQuery',
            'version'  => ZF_VERSION,
            'merSn'    => $this->merId,
            'userCode' => $userCode,
            'Sign'     => $sign
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL, $postData);
        $sdk_ret = json_decode($ret,true);
        //Lib::outputJson($sdk_ret);
        if ($sdk_ret['error'] == 0) 
        {
            //对比费率
            $manage_rata = array(
                'userCode' => $userCode,
                'czValue' => DEPOSIT_POUNDAGE,//充值手续费
                'txValue'=> WITHDRAW_POUNDAGE,//提现手续费
                'xfValue'=> CONSUME_RATE,//消费手续费
                'jqValue'=> VALIDATECARD_POUNDAGE,//鉴权手续费
                'hkValue'=> REPAYMENT_POUNDAGE,//还款手续费
                'sfValue'=> SFVALUE,//身份鉴权手续费
                'txInValue'=> TXLNVALUE,//套现入款手续费
                'txOutValue'=> TXOUTVALUE//套现出款手续费
            );
            $commArr = array_intersect_assoc($manage_rata, $sdk_ret);
            if ($commArr == $manage_rata) {
                return true;
            } else {
                //设置费率         
                $post_manage = array(
                    'action'  => 'SdkUserRate',
                    'version' => ZF_VERSION,
                    'merSn' => $this->merId,
                    'Sign' => md5('['.ZF_VERSION.']|['.$userCode.']|['.$this->merId.']'.ZF_SIGN)
                );
                $manage_ret = Lib::httpPostUrlEncode(ZF_URL,array_merge($manage_rata,$post_manage));
                $manageArr = json_decode($manage_ret,true);
                if ($manageArr['error'] == 0) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
    //商户查询
    public function payMercQuery($data){
        $merchantId = $this->merId;
        $version = ZF_VERSION;
        $userCode = $data['userCode'];
        //md5,[version]|[userCode]|[merSn]商户密钥
        $sdk_sign = md5('['.$version.']|['.$userCode.']|['.$merchantId.']'.ZF_SIGN);
        $post = array(
            'action'   => 'SdkUserQuery',
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'userCode'  => $userCode,         //手续费 + 还款笔数费
            'Sign'      => $sdk_sign
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL,$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }
    
    //银行卡查询
    public function payCardQuery($data){
        $merchantId = $this->merId;
        $version = ZF_VERSION;
        $userCode = $data['userCode'];
        $sysId = $data['sysId'];
        $cardType = $data['cardType'];
        //md5,[version]|[userCode]|[sysId]|[cardType]|[merSn]商户密钥
        $sdk_sign = md5('['.$version.']|['.$userCode.']|['.$sysId.']|['.$cardType.']|['.$merchantId.']'.ZF_SIGN);
        $post = array(
            'action'   => 'SdkCardQuery',
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'userCode'  => $userCode,         //手续费 + 还款笔数费
            'cardType'  => $cardType,         //银行卡类型
            'sysId'  => $sysId,               //订单类型
            'Sign'      => $sdk_sign
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL,$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }
    //子商户余额
    public function SdkBalanceQuery($userCode=null){
        $datapost=[];
        $datapost['action'] ='SdkBalanceQuery';
        $datapost['version'] =ZF_VERSION;
        $datapost['merSn'] = $this->merId;//大商户号
        $datapost['userCode'] = $userCode;//子商编号
        $sing=md5('['.$datapost['version'].']|['.$datapost['userCode'].']|['.$datapost['merSn'].']'.ZF_SIGN_OUT);
        $datapost['Sign'] =$sing;

        $result=Lib::httpPostUrlEncode(ZF_URL."?action=SdkBalanceQuery",$datapost);
        $res= json_decode($result,true);
        return $res;
    }
    
    //套现出款
    public function payTxOut($data){
        $merchantId = $this->merId;
        $version = ZF_VERSION;
        $userCode = $data['userCode'];
        $notifyUrl = $data['notifyUrl'];
        $bankCode = $data['bankCode'];
        $bankNo = $data['bankNo'];
        $userOrderSnIn = $data['userOrderSnIn'];
        $userOrderSn = $data['userOrderSn'];
        $attach = $data['attach'];
        //md5,[version]|[userCode]|[bankCode]|[bankNo]|[userOrderSn]|[userOrderSnIn]|[merSn]出款密钥
        $sdk_sign = md5('['.$version.']|['.$userCode.']|['.$bankCode.']|['.$bankNo.']|['.$userOrderSn.']|['.$userOrderSnIn.']|['.$merchantId.']'.ZF_SIGN_OUT);
        $post = array(
            'action'   => 'SdkTxOut',
            'version'   => $version,
            'merSn'     => $merchantId,                     //大商户号
            'userCode'  => $userCode,                       //子商编号
            'bankCode'  => $bankCode,                       //银行卡类型
            'bankNo'  => $bankNo,                        //订单类型
            'userOrderSnIn'  => $userOrderSnIn,             //金额
            'userOrderSn'  => $userOrderSn,                 //手续费 + 还款笔数费
            'notifyUrl' => $notifyUrl,                      //异步通知地址
            'attach'=>$attach,
            'Sign'      => $sdk_sign,
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL,$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }
    
    //套现入款
    public function payTxIn($data){
        $merchantId = $this->merId;
        $version = ZF_VERSION;
        $userCode = $data['userCode'];
        $sysId = $data['sysId'];
        $amount = $data['amount'];
        $userOrderSn = $data['userOrderSn'];
        $notifyUrl = $data['notifyUrl'];
        $attach = $data['attach'];
        $notifyUrlBefore = $data['notifyUrlBefore'];
        
        //return '['.$version.']|['.$userCode.']|['.$sysId.']|['.$userOrderSn.']|['.$merchantId.']'.ZF_SIGN_IN;
        //md5,[version]|[userCode]|[sysId]|[userOrderSn]|[merSn]入款密钥
        $sdk_sign = md5('['.$version.']|['.$userCode.']|['.$sysId.']|['.$userOrderSn.']|['.$merchantId.']'.ZF_SIGN_IN);
        $post = array(
            'action'   => 'SdkTxIn',
            'version'   => $version,
            'merSn'     => $merchantId,       //大商户号
            'userCode'  => $userCode,         //子商编号
            'sysId'  => $sysId,               //订单类型
            'amount'  => $amount*100,         //金额
            'userOrderSn'  => $userOrderSn,   //手续费 + 还款笔数费
            'notifyUrl' => $notifyUrl,        //异步通知地址
            'attach'=>$attach,
            'notifyUrlBefore'=>$notifyUrlBefore,
            'Sign'      => $sdk_sign,
        );
        $ret = Lib::httpPostUrlEncode(ZF_URL."?action=SdkTxIn",$post);
        $sdk_ret = json_decode($ret,true);
        return $sdk_ret;
    }

}






















