<?php
/**
 * 用户登录
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/22
 * Time: 17:04
 */

namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;

class UserLogin extends Controller
{
    //类型
    protected   $type   = 1;
    /**
     * 登陆
     */
    public function index()
    {
        $userInfo['mobile'] = Lib::post('mobile');
        $userInfo['password'] = Lib::post('password');
        $userInfo['type'] = $this->type;

        $headerData = Lib::getAllHeaders();
        $userInfo['device_id'] = $headerData['DEVICEID'];
        $userInfo['device_os'] = $headerData['SYSTEMTYPE'];
//         $userInfo['app_id'] = $headerData['APPID'];
        $userInfo['app_id'] = Lib::request('appid');
        $userModelPath = "\\App\\API\\" . $headerData['VERSION'] . "\\Model\\User";
        $userModel = new $userModelPath();
        $data = $userModel->login($userInfo);
        Lib::outputJson($data);
    }

}