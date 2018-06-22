<?php
/**
 * 银行卡
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/24
 * Time: 11:25
 */

namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use App\API\V100\Model\Base;

class Bank  extends Controller
{
    public function index(){
        //$bank_data = Lib::loadFile('Config/Bank.php');
        $bank_data = Lib::getBankConfig(-1);
        Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '请求成功','data'=>$bank_data));
    }
    
    public function creditbank_name(){ 
        $headerData=Lib::getallheaders();
        $s = "\\App\API\\".$headerData['VERSION']."\\Model\\Bank";
        $m=new $s;
        $datalist = $m->credit_card();
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取成功',
            'data' => $datalist
        ];

        Lib::outputJson($data);
    }
    public function depositbank_name(){
         $headerData=Lib::getallheaders();
    	$s = "\\App\API\\".$headerData['VERSION']."\\Model\\Bank";
        $m=new $s;
    	$datalist = $m->deposit_card();
    	$data = [
    			'status' => 'success',
    			'code' => 10000,
    			'msg' => '获取成功',
    			'data' => $datalist
    	];
    
    	Lib::outputJson($data);
    }
}