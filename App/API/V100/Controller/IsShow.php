<?php
namespace App\API\V100\Controller;

use Core\Base\Controller;
use Core\Lib;
class IsShow extends Controller
{
    public function isshow()
    {


        //插入数据库

        //调用阿里云发送短信

            $data = [
                'status' => 'success',
                'code' => 10000,
                'data' => IS_SHOW,
                'msg' => '发送成功'
            ];
            Lib::outputJson($data);


    }
}

