<?php
/**
 * 忘记密码
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/22
 * Time: 17:17
 */

namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;


class UserFindPassword extends Controller
{
    /**
     * 忘记密码
     */
    public function index()
    {
        $userInfo['mobile'] = Lib::post('mobile');
        $userInfo['code'] = Lib::post('code');
        $userInfo['password'] = Lib::post('password');
        $userInfo['repassword'] = Lib::post('repassword');

        //修改
        $headerData = Lib::getAllHeaders();
        $userModelPath = "\\App\\API\\" . $headerData['VERSION'] . "\\Model\\User";
//         $userInfo['app_id'] = $headerData['APPID'];
        $userInfo['app_id'] = Lib::request('appid');
        $userModel   = new $userModelPath();
        $data = $userModel->resetPassword($userInfo);
        Lib::outputJson($data);
    }
}