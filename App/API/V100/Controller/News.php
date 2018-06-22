<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/2/25
 * Time: 9:03
 */

namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;

class News extends Controller
{
	public $headers;
	public function __construct($controller, $action)
	{
		parent::__construct($controller, $action);
		$this->headers = Lib::getAllHeaders();
	}
   

    //获取最新动态分类
    public function dynamic(){


        $V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
        $id = Lib::post('category_id') ? Lib::post('category_id') : 0;
        $datalist = $m->getAritleList($id);

        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取成功',
            'data' => $datalist
        ];
        Lib::outputJson($data);
    }
	
	public function question(){
        $V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
        $datalist = $m->getList();
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取成功',
            'data' => $datalist
        ];

        Lib::outputJson($data);
    }
    //获取头条详情
     public function getMyNewShow(){
        $V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
        $id = Lib::post('id');
        $xq = $m->getMyNew($id);
        if(empty($xq)){
            $data =[
                'status' => 'fail',
                'code' => 10000,
                'msg' => '获取失败',
                'data' => ''
            ];
            Lib::outputJson($data);
        }
        $xq['create_time'] = Lib::uDate('Y-m-d',$xq['create_time']);
        $xq['img_url'] = OSS_ENDDOMAIN.'/'.$xq['img_url'];
            //$v['total']=$rss;
        $data =[
                'status' => 'success',
                'code' => 10000,
                'msg' => '获取成功',
                'data' => $xq
        ];
        Lib::outputJson($data);
    }

    public function questionShow(){
    	$V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;	
    	
    	$id = Lib::post('id');
    	$xq = $m->getone($id);
        if(empty($xq)){
            $data =[
                'status' => 'fail',
                'code' => 10000,
                'msg' => '获取失败',
                'data' => ''
            ];
            Lib::outputJson($data);
        }
		$xq['last_update_time'] = Lib::uDate('Y-m-d',$xq['last_update_time']);
        $xq['create_time'] = Lib::uDate('Y-m-d',$xq['create_time']);
        $xq['pic'] = OSS_ENDDOMAIN.'/'.$xq['pic'];
    		//$v['total']=$rss;
    	$data =[
    			'status' => 'success',
    			'code' => 10000,
    			'msg' => '获取成功',
    			'data' => $xq
    	];
    	Lib::outputJson($data);
    }

    public function userInstructions(){

        $V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
        $rs = $m->getInstuctions();
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取成功',
            'data' => $rs
        ];

        Lib::outputJson($data);
    }
    public function advertisement(){
    
    	$V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
    	$rs = $m->advertisements();
    	$arr = [];
    	foreach($rs as $v){
    	    $arr[] = OSS_ENDDOMAIN.'/'.$v['pic'];
        }
        
    	$data = [
    			'status' => 'success',
    			'code' => 10000,
    			'msg' => '获取成功',
    			'data' => $arr
    	];
    
    	Lib::outputJson($data);
    }

    //获取保险列表
    public function insure(){
    	$V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
    	$rss = $m->insures();
    	foreach ($rss  as  $v){
    		$v['pic'] = OSS_ENDDOMAIN.'/'.$v['pic'];
    		$bank_data[] = $v;
    	}
    	$data = [
    			'status' => 'success',
    			'code' => 10000,
    			'msg' => '获取成功',
    			'data' => $bank_data
    	];
    	Lib::outputJson($data);
    }

    public function usingcard(){
    
    	$V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
    	$datalist = $m->using();
    	$data = [
    			'status' => 'success',
    			'code' => 10000,
    			'msg' => '获取成功',
    			'data' => $datalist
    	];
    
    	Lib::outputJson($data);
    }
    public function loanstrategy(){
    
    	$V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
    	$datalist = $m->loan();
    	$data = [
    			'status' => 'success',
    			'code' => 10000,
    			'msg' => '获取成功',
    			'data' => $datalist
    	];
    
    	Lib::outputJson($data);
    }
    public function agentinformation(){
    
    	$V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
    	$datalist = $m->agent();
    	$data = [
    			'status' => 'success',
    			'code' => 10000,
    			'msg' => '获取成功',
    			'data' => $datalist
    	];
    
    	Lib::outputJson($data);
    }
    public function headlines(){
    
    	$V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
    	$datalist = $m->headline();
    	$data = [
    			'status' => 'success',
    			'code' => 10000,
    			'msg' => '获取成功',
    			'data' => $datalist
    	];
    
    	Lib::outputJson($data);
    }
    
    public function qrcode(){
    
    	$V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
    	$datalist = $m->getQrcode();
    	//var_dump($datalist);exit;
    	$datalist['picfull'] = OSS_ENDDOMAIN.'/'.$datalist['pic'];
  
    	$data = [
    			'status' => 'success',
    			'code' => 10000,
    			'msg' => '获取成功',
    			'data' => $datalist
    	];
    
    	Lib::outputJson($data);
    }
    public function agreement(){
    
    	$V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
    	$datalist = $m->agreementss();
    	$data = [
    			'status' => 'success',
    			'code' => 10000,
    			'msg' => '获取成功',
    			'data' => $datalist
    	];
    
    	Lib::outputJson($data);
    }
    public function attention(){
        $V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\News";



        $m = new $V;
        $datalist = $m->attention();
        if($datalist){

            $data=[
                'status' => 'success',
                'code' => 10000,
                'msg' => '获取成功',
                'data' => $datalist
            ];
        }else{
            $data=[
                'status' => 'fail',
                'code' => 1000,
                'msg' => '获取失败'
            ];
        }

        Lib::outputJson($data);
    }
}
