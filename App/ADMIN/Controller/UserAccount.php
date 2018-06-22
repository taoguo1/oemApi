<?php

/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/18
 * Time: 11:37
 */
namespace App\ADMIN\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\Extend\Dwz;
use Core\DB\DBQ;

class UserAccount extends Controller {

	/**
	 * 会员账户
	 */
	public function index() {
		$pageArr 	= Lib::setPagePars();
		$user_id = Lib::request ( 'user_id' );
        $agent_id= Lib::request ( 'agent_agent_id' );
		$real_name 	= Lib::request('real_name');
		$order_sn 	= Lib::request('order_sn');
		$min_amount = Lib::request('min_amount');
		$max_amount = Lib::request('max_amount');
		$start_date = Lib::request('start_date');
		$end_date 	= Lib::request('end_date');
        $mobile 	= Lib::request('mobile');
        $id_card 	= Lib::request('id_card');
		//$in_type	= Lib::request('in_type');
		$channel	= Lib::request('channel');
		if ($pageArr['orderField']) {
			$columns['ORDER'] = [
					$pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
			];
		}
		$condition = null;
        ($agent_id) ? $condition['AND']['A.id'] = $agent_id : null;
		($user_id) ? $condition['AND']['U.id'] = $user_id : null;
		($real_name) ? $condition['AND']['U.real_name'] = $real_name : null;
        ($mobile) ? $condition['AND']['U.mobile'] = $mobile : null;
        ($id_card) ? $condition['AND']['U.id_card'] = $id_card : null;
		($order_sn) ? $condition['AND']['UA.order_sn'] = $order_sn : null;
		($min_amount) ? $condition['AND']['UA.amount[>=]'] = $min_amount : null;
		($max_amount) ? $condition['AND']['UA.amount[<=]'] = $max_amount : null;
		($start_date) ? $condition['AND']['UA.create_time[>=]'] = strtotime($start_date. " 00:00:00")*1000: null;
		($end_date) ? $condition['AND']['UA.create_time[<=]'] = strtotime($end_date. " 23:59:59") *1000: null;
		//($in_type) ? $condition ['AND'] ['UA.in_type'] = $in_type : null;
		($channel) ? $condition ['AND'] ['UA.channel'] = $channel : null;

		$condition['ORDER'] = [
				'UA.id' => 'DESC'
		];
		$dictionaryData = Lib::loadFile('Config/Dictionary.php');
		$data = $this->M()->getUserAccountList($pageArr, $condition);
	   	$this->assign('channel',$dictionaryData['channel']);
	   	$this->assign('InStatus',$dictionaryData['InStatus']);
		$this->assign('data',$data);
		$this->view ();
	}
	/**
	 * **
	 * 
	 * @name 添加
	 */
	public function add($act = null) {
		if ($act == 'add') {
			$user_id 		= Lib::post ( 'user_id' );
			$amount 		= Lib::post ( 'amount' );
			$order_sn 		= Lib::post ( 'order_sn' );
			$desciption 	= Lib::post ( 'desciption' );
			$in_type 		= Lib::post ( 'in_type' );
			$channel 		= Lib::post ( 'channel' );
			if(empty($amount) || !is_numeric($amount)){
				Dwz::err('金额不能为空且为数字');
			}
			if(empty($order_sn)){
				Dwz::err('订单号不能为空');
			}
			if(!(Lib::isLetterNum($order_sn)) || strlen($order_sn)>20 || strlen($order_sn)<6){
				Dwz::err('订单号为6到20位数字或者字母');
			}
			if(empty($in_type) || !is_numeric($in_type)){
				Dwz::err('入库方式有误');
			}
			if(empty($channel) || !is_numeric($channel)){
				Dwz::err('通道有误');
			}
			$data = [
					'user_id' 		=> $user_id,
					'amount' 		=> $amount,
					'order_sn' 		=> $order_sn,
					'desciption' 	=> $desciption,
					'in_type' 		=> $in_type,
					'channel' 		=> $channel,
					'create_time' 	=> Lib::getMs(),
			];
		
			$insertId = $this->M ()->add ( $data );
			if ($insertId) {
				Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
			} else {
				Dwz::err ();
			}
		}
		$dictionaryData = Lib::loadFile('Config/Dictionary.php');
		$this->assign('channel',$dictionaryData['channel']);
		$this->assign('InStatus',$dictionaryData['InStatus']);
		$this->view ();
	}

	/**
	 * 获取用户列表
	 */
	public function getUserList(){
		$real_name          = Lib::request ( 'real_name' );
		$mobile             = Lib::request ( 'mobile' );
		$id_card            = Lib::request ( 'id_card' );

		$condition = null;
		($real_name) ? $condition ['AND'] ['real_name'] = $real_name : null;
		($mobile) ? $condition ['AND'] ['mobile'] = $mobile : null;
		($id_card) ? $condition ['AND'] ['id_card'] = Lib::aesEncrypt($id_card): null;
		$condition ['ORDER'] = [
			'id' => 'ASC'
		];

		$pageArr = Lib::setPagePars ();
		if ($pageArr ['orderField']) {
			$columns ['ORDER'] = [
				$pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
			];
		}
		$data = $this->M()->getUserList ( $pageArr, $condition );
		$this->assign ( "data", $data );
		$this->view ();
	}
	
	/**
	 * **
	 * 
	 * @name 修改
	 */
	public function edit($id = 0, $act = null) {
		if ($act == 'edit') {
			$user_id		= Lib::post ( 'user_id' );
			$amount			= Lib::post ( 'amount' );
			$order_sn		= Lib::post ( 'order_sn' );
			$desciption		= Lib::post ( 'desciption' );
			$in_type		= Lib::post ( 'in_type' );
			$channel		= Lib::post ( 'channel' );

			if(empty($amount) || !is_numeric($amount)){
				Dwz::err('金额不能为空且为数字');
			}
			if(empty($order_sn)){
				Dwz::err('订单号不能为空');
			}
			if(!(Lib::isLetterNum($order_sn)) || strlen($order_sn)>20 || strlen($order_sn)<6){
				Dwz::err('订单号为6到20位数字或者字母');
			}
			if(!empty($desciption)){
				if(strlen($desciption)>100){
					Dwz::err('描述不能大于100个字符');
				}
			}
			if(empty($in_type) || !is_numeric($in_type)){
				Dwz::err('入库方式有误');
			}
			if(empty($channel) || !is_numeric($channel)){
				Dwz::err('通道有误');
			}
			$data = [
					'user_id' 		=> $user_id,
					'amount' 		=> $amount,
					'order_sn'		=> $order_sn,
					'desciption' 	=> $desciption,
					'in_type' 		=> $in_type,
					'channel' 		=> $channel
			];
			$upd= DBQ::upd('user_account',$data,['id'=>$id]);
			if($upd){
				 Dwz::successDialog($this->M()->modelName, '', 'closeCurrent');
			}
		}
		$list = DBQ::getRow('user_account(UA)', [
			'[>]user(U)' => [
				'UA.user_id' => 'id'
			]
		],
			[
				'UA.id',
				'UA.user_id',
				'U.real_name',
				'UA.amount',
				'UA.order_sn',
				'UA.desciption',
				'UA.in_type',
				'UA.channel',
				'UA.create_time'
			]
			,
			[
			'UA.id' => $id
		]);
		$dictionaryData = Lib::loadFile('Config/Dictionary.php');
        $this->assign('list', $list);
		$this->assign('channel',$dictionaryData['channel']);
		$this->assign('InStatus',$dictionaryData['InStatus']);
		$this->view ();
	}

	/**
	 * 删除
	 * @param int $id
	 */
	public function del($id = 0) {
		if(empty($id) || !is_numeric($id)){
			Dwz::err('删除参数错误');
		}
		$del =DBQ::upd('user_account',[
				'id'=>$id
		]);
		if($del){
			 Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
		}else{
			DWZ::err();
		}
	}
}