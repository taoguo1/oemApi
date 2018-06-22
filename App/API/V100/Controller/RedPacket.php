<?php
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\Extend\Session;
use Core\DB\DBQ;

class RedPacket extends Controller
{
	public $model;
	public $headers;
	public function __construct($controller, $action){
		parent::__construct($controller, $action);
		$this->headers = Lib::getAllHeaders();
		$V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\RedPacket";
        $this->model = new $V;
	}
	
	public function signin(){
		$xxsj=strtotime(date('H:i:s'));
		$ks=strtotime("1:00:00");
		$jieshu=strtotime("20:00:00");
		if($ks <= $xxsj && $xxsj <= $jieshu){
			$sess = new Session();
			$total=1000;//总金额
			$i = $sess->get('i')? $sess->get('i'):1;
			$cmoney = $sess->get('cmoney')? $sess->get('cmoney'):0;
			if($cmoney >= $total){
				$data = [
						'status' => 'fail',
						'code' => 10011,
						'msg' => '红包已抢完,请明天再抢!',
				];
			
				Lib::outputJson($data);
			}
			
			$stamp = mktime(null,null,null,date('m',time()),date('d',time()),date('Y',time()));
			//var_dump($stamp);exit;
			$V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\RedPacket";
			$q= new $V;
			//var_dump($q);exit;
			$qq=$q->getalla($this->headers['UID'],$stamp);
			//echo Lib::uDate('Y-m-d H:i:s', $qq[0]['create_time']);exit;
			if($qq){
				$data = [
						'status' => 'fail',
						'code' => 10011,
						'msg' => '今天已经领过红包!',
				];
				Lib::outputJson($data);
			}
			 
			if($total-$cmoney <= 5){
				$money = $total-$cmoney;
			}else{
			
				if($i % 199 == 0){
					$money = sprintf('%.2f',$this->amountRandom(4,5));
				}elseif($i % 149 == 0){
					$money = sprintf('%.2f',$this->amountRandom(3,4));
				}elseif($i % 99 == 0){
					$money = sprintf('%.2f',$this->amountRandom(2,3));
				}elseif($i % 51 == 0){
					$money = sprintf('%.2f',$this->amountRandom(1,2));
				}else{
					$money = sprintf('%.2f',$this->amountRandom(0.5,1));
				}
			}
			$cmoney = $cmoney+$money;
			$i++;
			$sess->set('i', $i);
			$sess->set('cmoney', $cmoney);
			$this->model->inse($money,$this->headers['UID']);
			 
		
			$data = [
					'status' => 'success',
					'code' => 10000,
					'msg' => '领取成功',
					'data'=>$money,
					'cmoney'=>$cmoney,
					'i'=>$i
			];
			Lib::outputJson($data);	
		}else{
			$data = [
					'status' => 'fail',
					'code' => 10011,
					'msg' => '还没到领取时间',
					];
			Lib::outputJson($data);
		}
		

	}
		
	public function amountRandom($min=0, $max=1){
		return $min + mt_rand()/mt_getrandmax() * ($max-$min);
	}
	public function RedPacketShow(){
		$V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\RedPacket";
		$m = new $V;
		$user_id = $this->headers['UID'];
		$condition['AND']['user_id'] = $user_id;
		$condition['ORDER'] = [
				'A.create_time' =>'DESC',
		];
		$numPerPage =Lib::post('numPerPage');
		$pageArr = Lib::setPagePars ();
		$pageArr['numPerPage'] = $numPerPage ? $numPerPage :10;	
		$rss = $m->gettotol($this->headers['UID']);
		$rs = $m->getlist($pageArr, $condition);
		foreach($rs['list'] as $k => $v){
			$v['create_time_str'] = Lib::uDate('m-d H:i',$v['create_time']);
			//$v['total']=$rss;
			$dataAll['list'][] = $v;
		}
		$dataAll['total'] = $rss ? $rss:0;
		$dataAll['list'] = !empty($dataAll['list']) ? $dataAll['list']:[];
		
		$rs['list']=$dataAll['list'];
		$rs['total']=$dataAll['total'];
		$data = [
				'status' => 'success',
				'code' => 10000,
				'msg' => '获取成功',
				'data' => $rs,
				
		];
		
		Lib::outputJson($data);
	}
}
