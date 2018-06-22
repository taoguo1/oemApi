<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/3/16
 * Time: 14:59
 */
namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\Lib;
class WxPay extends Controller
{
    public $appid="wxd3ae8cc5ab3d162d";
    public $mch_id="1250189001";
    public $notify_url="http://oem.dizaozhe.cn/WxPay/notify_url";
    public $wxURL="https://api.mch.weixin.qq.com/pay/unifiedorder";
    public function wxpay()
    {
       $strrand=rand(1,99).time()."oem";
        $nonce_str=md5($strrand);
        $scene_info=[
            "h5_info"=>[
                "type"=>"Wap",
                "wap_url"=>"https://www.baidu.com",
                "wap_name"=>"11111"
            ]
        ];
        $data=[
            "appid"=>$this->appid,      //公众账号ID
            "mch_id"=>$this->mch_id,     //商户号
            "nonce_str"=>$nonce_str,  //随机字符串
            "body"=>"H5test",        //商品描述
            "out_trade_no"=>Lib::createOrderNo(),        //商户订单号
            "total_fee"=>1,        //总金额  单位为分
            "spbill_create_ip"=>$this->M()->get_ip(),        //终端IP
            "notify_url"=>$this->notify_url,        //通知地址
            "trade_type"=>"MWEB",        //交易类型
            "product_id"=>"1",        //商品ID
            "scene_info"=>json_encode($scene_info)        //场景信息
        ];

        $sign=$this->M()->wxsign($data);

        $data['sign']=$sign;      //签名
        //'detail'=>'test',        //商品详情
        // 'attach'=>'支付测试',        //附加数据
        //  'fee_type'=>'CNY',        //货币类型
        // 'time_start'=>time(),        //交易起始时间

        $res=$this->M()->xmlindex($data);


        $result=Lib::httpPost($this->wxURL,$res);
        $resarray=$this->M()->simplexml($result);
        var_dump($resarray);
        $mweb_url="";
        if($resarray['return_code']!='SUCCESS'){
            $msgdata = [
                'status' => 'fail',
                'code' => 1000,
                'msg' => $resarray['return_msg']
            ];
        }else{
           if($resarray['sign']==$sign){
               $mweb_url=$resarray['mweb_url'];
               header("Location:".$mweb_url);
           }else{
               $msgdata = [
                   'status' => 'fail',
                   'code' => 1000,
                   'msg' => "非法访问"
               ];
           }
        }

    }
    public function notify_url()
    {


    }


}