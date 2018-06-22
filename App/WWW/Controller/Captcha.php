<?php
/**
 * 图像验证码
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/23
 * Time: 17:33
 */

namespace App\WWW\Controller;
use Core\Base\Controller;
use Core\Extend\Verify;
use Core\Lib;

class Captcha extends Controller
{

    /**
        * 图形验证码
        */
    public function index(){
            $deviceId=Lib::request('deviceId');
            $appid=Lib::request('appid');
            if (empty($deviceId)) {
                Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '未获取到设备号'));
            }
            $config =    array(
                'fontSize'    =>   40,    // 验证码字体大小
                'length'      =>   4,     // 验证码位数
                //'useImgBg' => true,
                'fontttf'       =>  '5.ttf',
                'useNoise'    =>    false, // 关闭验证码杂点
            );
            $Verify = new Verify($config);
            $Verify->entry($deviceId,$appid);
    }

}