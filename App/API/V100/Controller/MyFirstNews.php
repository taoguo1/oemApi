<?php
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;
class MyFirstNews extends Controller{
    public function getMyFirstNewsConfig(){
        $res=DBQ::getAll('my_first_news','*');
        $data=[
                'status' => 'success',
                'code' => 10000,
                'msg' => '获取成功',
                'data' => $res
            ];           
        Lib::outputJson($data);
    }
}