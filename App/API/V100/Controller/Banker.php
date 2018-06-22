<?php
/**
 * 银行家
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/24
 * Time: 11:25
 */

namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use App\API\V100\Model\Base;

class Banker  extends Controller
{
    public function index(){
        $bank_data = Base::getBanker();
           foreach ($bank_data  as  $v){
           	$v['img'] = OSS_ENDDOMAIN.'/'.$v['img'];
           	$bank_data1[] = $v;
           	
           }
        Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '请求成功','data'=>$bank_data1));
    }
}