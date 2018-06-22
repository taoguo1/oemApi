<?php
namespace App\API\V100\Controller;

use Core\Base\Controller;
use Core\Lib;
class GetMsgCode extends Controller
{
    public function getUserRegCode()
    {
        $mobile = Lib::post('mobile');
        ///
        $code = rand(100000,999999);
        
        //插入数据库
        
        //调用阿里云发送短信
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '验证码发送成功'
        ];
        Lib::outputJson($data);
    }
}

