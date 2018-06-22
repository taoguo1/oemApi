<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/1/18
 * Time: 9:21
 */

namespace Core\Extend;
use Core\Lib;

class IDAuth
{
    private static $obj;
    public $testurl;
    public $url;
    public $version;
    public $accCode;
    public $accessKeyId;
    public $PRIVATEKEY;
    public $merchantId;

    private function __construct(){
        $conf = Lib::loadFile('Config/IdAuthConfig.php');
        $this->testurl = $conf['testurl'];
        $this->url = $conf['url'];
        $this->version = $conf['version'];
        $this->accCode = $conf['accCode'];
        $this->accessKeyId = $conf['accessKeyId'];
        $this->PRIVATEKEY = $conf['PRIVATEKEY'];
        $this->merchantId = $conf['merchantId'];
    }

    public static function instance(){
        if(is_null(self::$obj)){
            self::$obj = new self;
        }
        return self::$obj;
    }

    public function pGBKStr($str){
        echo $str;
        //echo iconv("UTF-8","gbk//TRANSLIT",$str)."\n";
    }

    public function generateNo(){
        return time()."000".rand(10000,99999);
    }

    public static function json($arr)
    {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', create_function('$matches', 'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'), json_encode($arr));
    }

    public function outToJson($rt){
        $obj = json_decode($rt);
        $retArr = ['status'=>'success','code'=>'0000','msg'=>$obj->msg];
        if(isset($obj->ext)){ $retArr['ext'] = $obj->ext;}
        if($obj->key !== '0000'){
            $retArr['status'] = 'fail';
            $retArr['code'] = $obj->key;
        }
        return $this->json($retArr);
    }

    public function sign($rdata){
        ksort($rdata);
        $enstr = "";
        foreach($rdata as $mk => $mv)
        {
            if($mv != null && $mv != ""){
                $enstr =  $enstr.$mk."=".$mv."&";
            }
        }
        $enstr = rtrim($enstr,"&");
        $signatur = md5($enstr.$this->PRIVATEKEY);
        return $signatur;
    }


    public function requestGet($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = curl_exec($ch);
        return $data;
    }

    public function requestPost($url, $rdata){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $rdata);
        $data = curl_exec($ch);
        return $data;
    }

    //编码图片到字符串
    public function getImgPara($path){
        $ldata = file_get_contents($path);
        $ldata = base64_encode($ldata);
        return urlencode($ldata);
    }

    public function reqApi($rdata, $blpost = false){
        $signatur = $this->sign($rdata);
        $aUrl = $this->url."/".$this->version."/".$this->accCode."/".$this->accessKeyId."/".$signatur."/".$rdata["timestamp"];
        if($blpost){
            $rt = $this->requestPost($aUrl,$rdata);
        }
        else{
            $enstr = "";
            foreach($rdata as $mk => $mv)
            {
                if($mv != null && $mv != ""){
                    $enstr =  $enstr.$mk."=".urlencode($mv)."&";
                }
            }
            $enstr = rtrim($enstr,"&");
            $aUrl = $aUrl."?".$enstr;
            $rt = $this->requestGet($aUrl);
        }
        return $rt;
    }

    //初始化身份证实名认证签名参数
    public function initParams($rdata){
        $cdata = array(
            "serviceCode"=>null,
            "name"=>null,
            "idNumber"=>null,
            "validFrom"=>null,
            "validEnd"=>null,
            "merchantId"=>$this->merchantId,
            "imageData"=>null,
            "requestId"=>$this->generateNo(),
            "accCode"=>$this->accCode,
            "accessKeyId"=>$this->accessKeyId,
            "version"=>$this->version,
            "timestamp"=>time()."000",
        );
        foreach($cdata as $key=>$value){
            if(array_key_exists($key, $rdata)){
                $cdata[$key] = $rdata[$key];
            }
        }
        return $cdata;
    }

    //初始化银行卡实名认证签名参数
    public function initBankParams($rdata){
        $cdata = array(
            "serviceCode"=>null,
            "name"=>null,
            "idNumber"=>null,
            "merchantId"=>$this->merchantId,
            "bankCard"=>null,
            "mobile"=>null,
            "requestId"=>$this->generateNo(),
            "accCode"=>$this->accCode,
            "accessKeyId"=>$this->accessKeyId,
            "version"=>$this->version,
            "timestamp"=>time()."000",
        );
        foreach($cdata as $key=>$value){
            if(array_key_exists($key, $rdata)){
                $cdata[$key] = $rdata[$key];
                $rdata[$key];
            }
        }
        return $cdata;
    }

    //初始化三大运营商实名认证签名参数
    public function sendParams($rdata){
        $cdata = array(
            "accCode"=>$this->accCode,
            "name"=>null,
            "mobile"=>null,
            "idNumber"=>null,
            "rangeCode"=>null,
            "imType"=>null,
            "imValue"=>null,
            "months"=>null,
            "serviceCode"=>null,
            "merchantId"=>$this->merchantId,
            "date"=>null,
            "accessKeyId"=>$this->accessKeyId,
            "version"=>$this->version,
            "currentPage"=>null,
            "pageSize"=>null,
            "sourcet"=>null,
            "timestamp"=>time()."000",
        );
        foreach($cdata as $key=>$value){
            if(array_key_exists($key, $rdata)){
                $cdata[$key] = $rdata[$key];
            }
        }
        return $cdata;
    }

    //1-1身份证二要素认证
    public function twoItem($name = '',$idNumber = ''){
        $mdata = array(
            "serviceCode"=>"101",
            "name"=>$name,
            "idNumber"=>$idNumber,
        );
        $rdata = $this->initParams($mdata);
        $rt = $this->reqApi($rdata);
        return  $this->outToJson($rt);
    }

    //1-2身份证四要素认证
    public function fourItem($name = '',$idNumber = '',$validFrom = '',$validEnd = ''){
        $mdata = array(
            "serviceCode"=>"102",
            "name"=>$name,
            "idNumber"=>$idNumber,
            "validFrom"=>$validFrom,
            "validEnd"=>$validEnd,
        );
        $rdata = $this->initParams($mdata);
        $rt = $this->reqApi($rdata);
        return  $this->outToJson($rt);
    }

    //1-3身份证二要素认证+人像比对 图片数据过大，建议采用POST提交
    public function twoItemAndImage($name = '',$idNumber = '',$imgurl = ''){
        $mdata = array(
            "serviceCode"=>"103",
            "name"=>$name,
            "idNumber"=>$idNumber,
            "imageData"=>$this->getImgPara($imgurl),
        );
        $rdata = $this->initParams($mdata);
        $rt = $this->reqApi($rdata,true);
        return  $this->outToJson($rt);
    }

    //1-4身份证四要素认证+人像比对
    public function fourItemAndImage($name = '',$idNumber = '',$validFrom = '',$validEnd = '',$imgurl = ''){
        $mdata = array(
            "serviceCode"=>"104",
            "name"=>$name,
            "idNumber"=>$idNumber,
            "validFrom"=>$validFrom,
            "validEnd"=>$validEnd,
            "imageData"=>$this->getImgPara($imgurl),
        );
        $rdata = $this->initParams($mdata);
        $rt = $this->reqApi($rdata,true);
        return $this->outToJson($rt);
    }

    //1-5身份证二要素认证，返回照片
    public function twoItemImage($name = '',$idNumber = ''){
        $mdata = array(
            "serviceCode"=>"105",
            "name"=>$name,
            "idNumber"=>$idNumber,
        );
        $rdata = $this->initParams($mdata);
        $rt = $this->reqApi($rdata);
        return  $this->outToJson($rt);
    }

    //1-6身份证二要素认证+SDK活体采集人像比对
    public function twoItemAndSDKImage($name = '',$idNumber = '',$imgurl = ''){
        $mdata = array(
            "serviceCode"=>"108",
            "name"=>$name,
            "idNumber"=>$idNumber,
            "imageData"=>$this->getImgPara($imgurl),
        );
        $rdata = $this->initParams($mdata);
        $rt = $this->reqApi($rdata,true);
        return  $this->outToJson($rt);
    }

    //2-1 银行卡二要素认证 （姓名+银行卡号）
    public function T301($name = '',$bankCard = ''){
        $mdata = array(
            "serviceCode"=>"301",
            "name"=>$name,
            "bankCard"=>$bankCard,
        );
        $rdata = $this->initBankParams($mdata);
        $rt = $this->reqApi($rdata);
        return  $this->outToJson($rt);
    }

    //2-2 银行卡三 要素认证 （姓名+银行卡号+身份证）
    public function T302($name = '',$bankCard = '',$idNumber = ''){
        $mdata = array(
            "serviceCode"=>"302",
            "name"=>$name,
            "bankCard"=>$bankCard,
            "idNumber"=>$idNumber,
        );
        $rdata = $this->initBankParams($mdata);
        $rt = $this->reqApi($rdata);
        return  $this->outToJson($rt);
    }

    //2-3 银行卡四要素认证 （姓名+银行卡号+身份证+手机号码）
    public function T303($name = '',$bankCard = '',$idNumber = '',$mobile = ''){
        $mdata = array(
            "serviceCode"=>"303",
            "name"=>$name,
            "bankCard"=>$bankCard,
            "idNumber"=>$idNumber,
            "mobile"=>$mobile,
        );
        $rdata = $this->initBankParams($mdata);
        $rt = $this->reqApi($rdata);
        return $this->outToJson($rt);
    }

    //2-4 银行卡三 要素认证返回精准错误 （姓名+银行卡号+身份证）
    public function T312($name = '',$bankCard = '',$idNumber = ''){
        $mdata = array(
            "serviceCode"=>"312",
            "name"=>$name,
            "bankCard"=>$bankCard,
            "idNumber"=>$idNumber,
        );
        $rdata = $this->initBankParams($mdata);
        $rt = $this->reqApi($rdata);
        return   $this->outToJson($rt);
    }

    //2-5 银行卡四要素认证返回精准错误 （姓名+银行卡号+身份证+手机号码）
    public function T313($name = '',$bankCard = '',$idNumber = '',$mobile = ''){
        $mdata = array(
            "serviceCode"=>"313",
            "name"=>$name,
            "bankCard"=>$bankCard,
            "idNumber"=>$idNumber,
            "mobile"=>$mobile,
        );
        $rdata = $this->initBankParams($mdata);
        $rt = $this->reqApi($rdata);
        return  $this->outToJson($rt);
    }




}

