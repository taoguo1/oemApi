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


class AgentFindPassword extends Controller
{
    /**
     * 忘记密码
     */
    public function index()
    {
        $agentInfo['mobile'] = Lib::post('mobile');
        $agentInfo['code'] = Lib::post('code');
        $agentInfo['password'] = Lib::post('password');
        $agentInfo['repassword'] = Lib::post('repassword');

        //修改
        $headerData = Lib::getAllHeaders();
        $agentModelPath = "\\App\\API\\" . $headerData['VERSION'] . "\\Model\\Agent";
        $agentModel   = new $agentModelPath();
//         $agentInfo['app_id'] = $headerData['APPID'];
        $agentInfo['app_id'] = Lib::request('appid');
        $data = $agentModel->resetPasswords($agentInfo);
        Lib::outputJson($data);
    }
}