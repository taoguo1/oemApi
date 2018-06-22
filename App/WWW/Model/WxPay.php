<?php
namespace App\WWW\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class WxPay extends Model{
    public $key="14e1b600b1fd579f47433b88e8d85291";
    //生成XML格式字符串
    public function xmlindex($data=null)
    {
        $strXml = <<<EOF
<xml>
</xml>
EOF;
        $xml = new \SimpleXMLElement($strXml);

        foreach ($data as $key => $value) {
            $xml->addChild($key, $value);
        }

        return $xml->asXML();
    }
    //将XML字符串转为数组
    public function simplexml($xmlstr)
    {
        $xmlstring = simplexml_load_string($xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = json_decode(json_encode($xmlstring),true);
        return $data;
    }
    //获取用户ip
    public function get_ip()
    {
        $cip="unknown";
        if($_SERVER['REMOTE_ADDR']){
            $cip=$_SERVER['REMOTE_ADDR'];
        }elseif(getenv("REMOTE_ADDR")){
            $cip=getenv("REMOTE_ADDR");
        }
        return $cip;
    }
    //生成签名
    public function wxsign($data)
    {
        ksort($data);
        $string='';
        foreach($data as $k=>$v){
            $string.=$k."=".$v."&";
        }

        $stringSignTemp=$string."key=".$this->key;

        return strtoupper(md5($stringSignTemp));
    }

}



?>