<?php
/**
 * 代理登陆
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/23
 * Time: 9:12
 */

namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;

class AgentLogin extends  Controller
{
    protected $type = 2;
    /**
     * 登陆
     */
    public function index()
    {
        $agentInfo['mobile'] = Lib::post('mobile');
        $agentInfo['password'] = Lib::post('password');
        $agentInfo['type'] = $this->type;

        $headerData = Lib::getAllHeaders();
        $agentInfo['device_id'] = $headerData['DEVICEID'];
        $agentInfo['device_os'] = $headerData['SYSTEMTYPE'];
//         $agentInfo['app_id']    = $headerData['APPID'];
        $agentInfo['app_id'] = Lib::request('appid');
        $AgentModelPath = "\\App\\API\\" . $headerData['VERSION'] . "\\Model\\Agent";
        $agentModel   = new $AgentModelPath();

        $data = $agentModel->login($agentInfo);
        Lib::outputJson($data);
    }
}