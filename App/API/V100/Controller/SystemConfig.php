<?php
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;

class SystemConfig extends Controller{
    public function getConfig(){
        $res=DBQ::getOne('system_config','*');
        $data=[
                'status' => 'success',
                'code' => 10000,
                'msg' => '获取成功',
                'data' => $res
            ];           
        Lib::outputJson($data);
    }
}