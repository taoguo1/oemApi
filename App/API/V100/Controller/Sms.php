<?php
/**
 * 发送短信
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/23
 * Time: 17:33
 */

namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;
class Sms extends Controller
{
    public $headers;
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->headers = Lib::getAllHeaders();
    }
    /**
     * 发送短信
     */
    public function index()
    {
        $oemappid     = Lib::post('appid');
        if(!$oemappid){
            Lib::outputJson(array('status' => 'fail', 'code' => 10011, 'msg' => '参数错误,appid缺失'));
        }
        $utype     = Lib::post('utype');
        $mobile     = Lib::post('mobile');
        $code_type  = Lib::post('code_type');
        $deviceId = $this->headers['DEVICEID'];
//         $app_id = $this->headers['APPID'];
        $app_id = Lib::request('appid');
        $imageCode=Lib::post( 'imageCode' );
        //接收图形验证码,判断真假
        if(empty($imageCode))
        {
            Lib::outputJson(array('status' => 'fail', 'code' => 10011, 'msg' => '图形验证码不能为空'));
        }
        $key = 'imgCode_'.$oemappid.'_'.$deviceId;
        $redis = \Core\Extend\Redis::instance('token');
        $redisRet = $redis->get($key);
        if($redisRet)
        {
            $redisRet = json_decode($redisRet,true);
            $time = $redisRet['create_time'];
            $redisCode = $redisRet['code'];
            $diffTime = (time()-$time)/60;
            if(strtoupper($imageCode)!=strtoupper($redisCode))
            {
                Lib::outputJson(array('status' => 'fail', 'code' => 10011, 'msg' => '图形验证码错误'));
            }
            if($diffTime > 15)
            {
                Lib::outputJson(array('status' => 'fail', 'code' => 10011, 'msg' => '图形验证码已过期'));
            }
            $redis->del($key);
        }
        else
        {
            Lib::outputJson(array('status' => 'fail', 'code' => 10011, 'msg' => '图形验证码错误'));
        }

        if($redisCode!=strtoupper($imageCode))
        {

            Lib::outputJson(array('status' => 'fail', 'code' => 10011, 'msg' => '图形验证码错误'));
        }
        //删除redis信息
        if (empty($mobile) || empty($code_type) || empty($utype)) {
            Lib::outputJson(array('status' => 'fail', 'code' => 10011, 'msg' => '参数错误'));
        }
        //$captcha = rand(100, 999) . rand(100, 999);

        if($utype!=1&&$utype!=2){
            Lib::outputJson(array('status' => 'fail', 'code' => 10011, 'msg' => 'utype参数有误'));
        }
        //检测手机号码是否注册
        if($utype == 1){
            $rs = DBQ::getCount('user',['id'],['mobile'=>$mobile]);
        }elseif($utype == 2){
            $rs = DBQ::getCount('agent',['id'],['mobile'=>$mobile]);
        }
        if($rs){
            //用户已注册，不能发送注册验证码
            if($code_type==1){
                Lib::outputJson(array('status' => 'fail', 'code' => 10011, 'msg' => '手机号码已经注册'));
            } 
        }else{
            //用户未注册或者用户不是绑定银行卡只能发送注册验证码
            if($code_type!=1&&$code_type!=5&&$code_type!=9){
                 Lib::outputJson(array('status' => 'fail', 'code' => 10011, 'msg' => '该手机号码未注册，请先注册'));
            } 
        }

        //Lib::outputJson(array('status' => $mobile, 'code' =>$code_type, 'msg1' => $app_id, 'msg' => $oemappid));  
        //发送短信
        $resultObj = Lib::sendSms($mobile,$code_type,$app_id,'',"SMS_129470288",$oemappid);
        $result = json_decode($resultObj,true);
        //Lib::outputJson(array('status' => 'error', 'code' => 10000, 'msg' => $result));                                                               
        //{"data":{"Message":"触发天级流控Permits:10","RequestId":"AF3B6E8B-50E6-436B-ADF0-3669AD36D0FF","Code":"isv.BUSINESS_LIMIT_CONTROL"}}
        if ($result['Code'] == 'OK') {
            Lib::outputJson(array('status' => 'success', 'code' => 10000, 'msg' => '短信发送成功'));
        } 
        if($result['Code']=='isv.BUSINESS_LIMIT_CONTROL'){
            Lib::outputJson(array('status' => 'fail', 'code' => 10000, 'msg' => '您当天获取短信条数已超限，请明天再试'));
        }
        if($result['code']=='isv.MOBILE_NUMBER_ILLEGAL'){
            Lib::outputJson(array('status' => 'fail', 'code' => 10000, 'msg' => '手机号非法'));
        }
        Lib::outputJson(array('status' => 'fail', 'code' => 10000, 'msg' => '短信发送失败'));        
    }
}