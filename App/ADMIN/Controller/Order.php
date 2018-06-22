<?php
namespace App\ADMIN\Controller;

use Core\Base\Controller;
use Core\Lib;
use Core\Extend\Dwz;
use Core\DB\DBQ;
/**
 * @name 订单控制器
 * @author pc
 *
 */
class Order extends  Controller
{
    /**
     * @name 订单查询
     */
    public function index(){
    	$pageArr = Lib::setPagePars();
    	$card_type = Lib::request('card_type');
    	$status = Lib::request('status');
    	$start_date = Lib::request('start_date');
        $card_no =Lib::request('card_no');
    	$end_date = Lib::request('end_date');
    	$minamount = Lib::request('minamount');
    	$maxamount = Lib::request('maxamount');
    	$order_sn = Lib::request('order_sn');
    	$type = Lib::request('type');

     	if ($pageArr['orderField']) {
    		$columns['ORDER'] = [
    				$pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
    		];
    	}
    	$condition = null;
    	($order_sn) ? $condition['AND']['order_sn[~]'] = $order_sn : null;
    	($minamount) ? $condition['AND']['amount[>=]'] = $minamount : null;
    	($maxamount) ? $condition['AND']['amount[<=]'] = $maxamount : null;
        ($card_no) ? $condition ['AND'] ['C.card_no'] = Lib::aesEncrypt($card_no) : null;
    	($card_type) ? $condition ['AND'] ['card_type'] = $card_type : null;
    	($status) ? $condition ['AND'] ['status'] = $status : null;
    	($type) ? $condition ['AND'] ['type'] = $type : null;
    	($start_date) ? $condition['AND']['add_time[>=]'] = strtotime($start_date): null;
    	($end_date) ? $condition['AND']['add_time[<=]'] = strtotime($end_date) : null;
    	
    	$condition['ORDER'] = [
    			'id' => 'DESC'
    	];

    	$data = $this->M()->getList($pageArr, $condition);
    	//p($data);die;
    	$bank = Lib::getBankConfig(2);
        $this->assign("bank", $bank);
    	$this->assign('data',$data);
        $this->view();
    }
    /**
     * @name 订单添加
     */

	public function add($act = null) {
		if ($act == 'add') {
			$data = [
					'id'  => Lib::post('id'),
					'order_sn' => Lib::createOrderNo(),
					'user_id' => Lib::post ( 'user_id' ),
					'amount' => Lib::post ( 'amount' ),
					'type' => Lib::post ( 'type' ),
					'card_type' => Lib::post ( 'card_type' ),
					'bank_id' => Lib::post ( 'bank_id' ),
					'card_no' => Lib::aesEncrypt(Lib::post('card_no')),
					'goods_id' => Lib::post ( 'goods_id' ),
					'goods_quantity' => Lib::post ( 'goods_quantity' ),
					'receive_name' => Lib::post ( 'receive_name' ),
					'receive_address' => Lib::post ( 'receive_address' ),
					'receive_mobile' => Lib::post ( 'receive_mobile' ),
					'status' => Lib::post ( 'status' ),
					'add_time' => time(),
			];

                $insertId = $this->M ()->add ( $data );


            if ($insertId) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
		}
		$bank = Lib::getBankConfig(2);
        $this->assign("bank", $bank);
		$this->view ();
	}
	/**
	 * **
	 *
	 * @name 删除
	 */
	public function del($id = 0) {
		$del =DBQ::del('order',[
				'id'=>$id
		]);
		if($del){
			Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
		}else{
			DWZ::err();
		}
	}
	/**
	 * **
	 *
	 * @name 修改
	 */
	public function edit($id = 0, $act = null) {
		if ($act == 'edit') {
			$data = [
					'order_sn' => Lib::post ( 'order_sn' ),
					'user_id' => Lib::post ( 'user_id' ),
					'amount' => Lib::post ( 'amount' ),
					'type' => Lib::post ( 'type' ),
					'card_type' => Lib::post ( 'card_type' ),
					'bank_id' => Lib::post ( 'bank_id' ),
					'card_no' => Lib::post ( 'card_no' ),
					'goods_id' => Lib::post ( 'goods_id' ),
					'goods_quantity' => Lib::post ( 'goods_quantity' ),
					'receive_name' => Lib::post ( 'receive_name' ),
					'receive_address' => Lib::post ( 'receive_address' ),
					'receive_mobile' => Lib::post ( 'receive_mobile' ),
					'status' => Lib::post ( 'status' ),
					'add_time' => strtotime(Lib::post ('add_time' )),
			];
			$upd= DBQ::upd('order',$data,['id'=>$id]);
			if($upd){
				Dwz::successDialog($this->M()->modelName, '', 'closeCurrent');
			}
		}
		$list=DBQ::getRow('order', '*', [
				'id' => $id
		]);
		$getOrderCategory = $this->M()->getOrderCategory();
		$this->assign('getOrderCategory', $getOrderCategory);
		$this->assign('list', $list);
		$this->view ();
	}

}

