<?php
/**
 * 用户注册
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/22
 * Time: 16:52
 */

namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;

class UserReg extends Controller
{
    protected $type = 1;
    /**
     * 用户注册
     */
    public function index()
    {
        $registerInfo['mobile'] = Lib::post('mobile');
        $registerInfo['password'] = Lib::post('password');
        $registerInfo['code'] = Lib::post('code');
        $registerInfo['type'] = $this->type;
        $registerInfo['invite_code'] = Lib::post('invite_code');
        $registerInfo['device_id'] = Lib::post('device_id');
        $registerInfo['device_os'] = Lib::post('device_os');


        //注册
        $headerData = Lib::getAllHeaders();
        $userModelPath = "\\App\\API\\" . $headerData['VERSION'] . "\\Model\\User";
//         $registerInfo['app_id'] = $headerData['APPID'];
        $registerInfo['app_id'] = Lib::request('appid');
        $userModel   = new $userModelPath();
        $data = $userModel->userReg($registerInfo);
        Lib::outputJson($data);
    }


    //用户绑定邀请码
    public function bindInviteCode(){
        $headerData = Lib::getAllHeaders();
        $userModelPath = "\\App\\API\\" . $headerData['VERSION'] . "\\Model\\User";


        $registerInfo['mobile'] = Lib::post('mobile');
        $registerInfo['type'] = $this->type;
        $registerInfo['invite_code'] = Lib::post('invite_code');
        $registerInfo['user_id'] =  $headerData['UID'];
        $registerInfo['app_id'] = Lib::request('appid');
        $userModel   = new $userModelPath();
        $data = $userModel->bindInviteCode($registerInfo);
        Lib::outputJson($data);

    }
}