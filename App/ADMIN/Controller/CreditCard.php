<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/18
 * Time: 11:37
 */
namespace App\ADMIN\Controller;
use Core\Lib;
use Core\Base\Controller;
use Core\Extend\Dwz;
use Core\DB\DBQ;
class CreditCard extends Controller
{
    /**
     *
     * @name 查询
     */
    public function index()
    {

        $pageArr = Lib::setPagePars();
        $user_id = Lib::request ( 'user_id' );
        $agent_id= Lib::request ( 'agent_agent_id' );
		$real_name=Lib::request('real_name');
        $bank_id =Lib::request('bank_id');
        $card_no =Lib::request('card_no');
        $id_card =Lib::request('id_card');
        $status =Lib::request('status');
        $mobile =Lib::request('mobile');
        $channel_type=Lib::request('channel_type');
        $start_create_time  = Lib::request ( 'start_create_time' );
        $end_create_time    = Lib::request ( 'end_create_time' );
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }
        $condition = null;
		
		
		if($real_name!=''){
			$this->assign('agentname',$real_name);
			$condition['AND']['C.real_name'] = Lib::aesEncrypt($real_name);
		}
        ($agent_id) ? $condition['AND']['A.id'] = $agent_id: null;
        ($id_card) ? $condition['AND']['C.id_card'] = Lib::aesEncrypt($id_card) : null;
        ($mobile) ? $condition['AND']['C.mobile'] = Lib::aesEncrypt($mobile) : null;
        ($bank_id) ? $condition['AND']['C.bank_id'] = $bank_id : null;
        ($user_id) ? $condition['AND']['C.user_id'] =$user_id : null;
        ($card_no) ? $condition ['AND'] ['C.card_no'] = Lib::aesEncrypt($card_no) : null;
        ($channel_type) ? $condition['AND']['C.channel_type'] = $channel_type : null;
        ($status) ? $condition['AND']['C.status'] = $status : null;
        ($start_create_time) ? $condition['AND']['C.create_time[>=]'] = strtotime($start_create_time. " 00:00:00")*1000: null;
        ($end_create_time) ? $condition['AND']['C.create_time[<=]'] = strtotime($end_create_time. " 23:59:59")*1000 : null;
        $condition['ORDER'] = [
            'C.id' => 'DESC'
        ];
        $data = $this->M()->getList($pageArr,$condition);
        //引入字典数组
        $dictionary = Lib::loadFile('Config/Dictionary.php');
        $bank = Lib::getBankConfig(1);
        //p($data);die;
        $this->assign("data", $data);
        $this->assign("channel", $dictionary['channel']);
        $this->assign("cardStatus", $dictionary['cardStatus']);
        $this->assign("bank", $bank);

        // 数据查询
        $this->view();

    }
    /**
     *
     * @name 添加
     */
    public function add($act = null)
    {
        $bank = Lib::getBankConfig(1);
        $dictionary = Lib::loadFile('Config/Dictionary.php');
        if ($act == 'add') {
            $expiry_date=Lib::post('expiry_date');
            $bank_name=Lib::post('bank_name');
            $channel_type=Lib::post('channel_type');
            $user = DBQ::getRow('user', '*', ['id' => Lib::post('user_id')]);
            $mobile=Lib::post('mobile');
//          $bill_day=Lib::post('bill_day');
//          $repayment_day=Lib::post('repayment_day');
//          if($bill_day <  $repayment_day){
//              //本月
//              $repayment_month = 1;
//          }elseif($bill_day >  $repayment_day){
//              //下月
//              $repayment_month = 2;
//          }else{
//              Dwz::err ('账单日和还款日不能同一天');
//          }
            $data = [
                'user_id' => $user['id'],
                'bank_name' => $bank_name,
//                'bank_name' => $bank[$bank_id]['name'],
                'mobile' => Lib::aesEncrypt($mobile),
                'lb_mobile' =>\substr_replace($mobile, '****', 3, 4),
                'real_name' =>Lib::aesEncrypt($user['real_name']),
                'card_no' => Lib::aesEncrypt(Lib::post('card_no')),
                'cvn' => Lib::aesEncrypt(Lib::post('cvn')),
                'id_card' => $user['id_card'],
                'expiry_date' =>Lib::aesEncrypt(str_replace('/','',$expiry_date)),
//              'bill_day' => $bill_day,
//              'repayment_day' =>$repayment_day,
//              'repayment_month' => $repayment_month,
                'channel_code' =>$dictionary['channel'][$channel_type]['code'],
                'channel_type' => $channel_type,
                'create_time' =>Lib::getMs()
            ];
            $bindCard = [
                'user_id' => $user['id'],
                'bank_name' => $bank_name,
//                'bank_name' => $bank[$bank_id]['name'],
                'card_no' => Lib::aesEncrypt(Lib::post('card_no')),
                'id_card' => $user['id_card'],
                'card_type' => 1,
                'description'=> '无',
                'channel' => $channel_type,
                'create_time' =>Lib::getMs()
            ];

            $insertId = $this->M()->add ($data);

            if ($insertId) {
                $bindCard['status']=1;
                DBQ::add('bind_card', $bindCard);
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }
        $this->assign("bank", $bank);
        $this->assign("channel", $dictionary['channel']);
        $this->view();
    }
    /**
     *
     * @name 编辑
     * @param number $id
     * @param $act
     */
    public function edit($id = 0, $act = null)
    {
        $bank = Lib::getBankConfig(1);
        $dictionary = Lib::loadFile('Config/Dictionary.php');
        if ($act == 'edit' && ! empty ( $id )) {
            $expiry_date=Lib::post('expiry_date');
            $bank_id=Lib::post('bank_id');
            if( $bank_id ) {
            	foreach($bank as $v) {
            		if($v['id'] == $bank_id) {
            			$bank_name = $v['name'];
            		}
            	}
            } 
            $channel_type=Lib::post('channel_type');
            $user = DBQ::getRow('user', '*', ['id' => Lib::post('user_id')]);
            $mobile=Lib::post('mobile');
//          $bill_day=Lib::post('bill_day');
//          $repayment_day=Lib::post('repayment_day');
//          if($bill_day <  $repayment_day){
//              //本月
//              $repayment_month = 1;
//          }elseif($bill_day >  $repayment_day){
//              //下月
//              $repayment_month = 2;
//          }else{
//              Dwz::err ('账单日和还款日不能同一天');
//          }
            $data = [
                'user_id' => $user['id'],
                'bank_id' => $bank_id,
                'bank_name' => $bank_name,
                'mobile' => Lib::aesEncrypt($mobile),
                'lb_mobile' =>\substr_replace($mobile, '****', 3, 4),
                'real_name' =>Lib::aesEncrypt($user['real_name']),
                'card_no' => Lib::aesEncrypt(Lib::post('card_no')),
                'cvn' => Lib::aesEncrypt(Lib::post('cvn')),
                'id_card' => $user['id_card'],
                'expiry_date' =>Lib::aesEncrypt(str_replace('/','',$expiry_date)),
//              'bill_day' => $bill_day,
//              'repayment_day' =>$repayment_day,
//              'repayment_month' => $repayment_month,
                'channel_code' =>$dictionary['channel'][$channel_type]['code'],
                'channel_type' => $channel_type,
                'create_time' =>Lib::getMs()
            ];
            //p($data);die;
            if ($this->M ()->edit ($id, $data )) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }

        $list = DBQ::getRow('credit_card', '*', [
            'id' => $id
        ]);

        $this->assign("bank", $bank);
        $this->assign("channel", $dictionary['channel']);
        $this->assign('list', $list);
        $this->view();
    }


    /**
     *
     * @name 逻辑删除
     * @param number $id
     */
    public function del($id = 0) {
        if(empty($id) || !is_numeric($id)){
            Dwz::err( '删除参数错误' );
        }
        $statusData = [
            'status'    =>  '-1'
        ];
        $result = $this->M()->logicalDeletion( $statusData, $id );
        if ($result !== false){
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            Dwz::err();
        }
    }
    /**
     *
     * @name 批量删除
     */
    public function delAll() {
        $ids = explode ( ',', Lib::post ( 'ids' ) );
        if ($this->M ()->delAll ( $ids )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
        } else {
            Dwz::err ();
        }
    }
}