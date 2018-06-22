<?php
namespace App\WWW\Controller;

use Core\Base\Controller;
use Core\Lib;

class KcpOemOrder extends Controller
{
    public function addkcporder()
    {
        $appid = Lib::request('appid');
        if(empty($appid)){
            exit("参数错误");
        }else{
            $oemKcporder = [
                'order_sn' => Lib::post('order_sn'),
                'order_wxsn' => Lib::post('order_wxsn'),
                'amount' => Lib::post('amount'),
                'oem_amount' => Lib::post('oem_amount'),
                'kcp_earnings' => Lib::post('kcp_earnings'),
                'appid' =>Lib::post('appid'),
                'app_name' => Lib::post('app_name'),
                'create_time' => Lib::post('create_time')
            ];
            $this->M()->addkcporder($oemKcporder);
        }

    }
    

}