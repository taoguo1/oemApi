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
class DebitCard extends Controller
{
    /**
     *
     * @name 查询
     */
    public function index()
    {
        $pageArr = Lib::setPagePars();
        $user_id = Lib::request ( 'user_id' );
		$real_name=Lib::request('real_name');
        $bank_id =Lib::request('bank_id');
        $card_no =Lib::request('card_no');
        $id_card =Lib::request('id_card');
        $status =Lib::request('status');
        $mobile =Lib::request('mobile');
        $lb_mobile = Lib::request('lb_mobile');
        $user_type =Lib::request('user_type');
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
			$condition['AND']['D.real_name'] = Lib::aesEncrypt($real_name);
		}
        ($id_card) ? $condition['AND']['D.id_card'] = Lib::aesEncrypt($id_card) : null;
        ($mobile) ? $condition['AND']['D.mobile'] = Lib::aesEncrypt($mobile) : null;
        ($lb_mobile) ? $condition['AND']['D.lb_mobile'] = \substr_replace($lb_mobile, '****', 3, 4) : null;
        ($bank_id) ? $condition['AND']['D.bank_id'] = $bank_id : null;
        ($user_id) ? $condition['AND']['D.user_id'] =$user_id : null;
        ($card_no) ? $condition ['AND'] ['D.card_no'] = Lib::aesEncrypt($card_no) : null;
        ($channel_type) ? $condition['AND']['D.channel_type'] = $channel_type : null;
        ($status) ? $condition['AND']['D.status'] = $status : null;
        ($user_type) ? $condition['AND']['D.user_type'] = $user_type: null;
        ($start_create_time) ? $condition['AND']['D.create_time[>=]'] = strtotime($start_create_time. " 00:00:00")*1000: null;
        ($end_create_time) ? $condition['AND']['D.create_time[<=]'] = strtotime($end_create_time. " 23:59:59")*1000 : null;
        $condition['ORDER'] = [
            'D.id' => 'DESC'
        ];
        $data = $this->M()->getList($pageArr,$condition);
//       echo "<pre>";print_r($data);
        //引入通道字典数组
        $dictionary = Lib::loadFile('Config/Dictionary.php');
        //引入银行字典
        $bank = Lib::getBankConfig(2);
        foreach ($data['list'] as $k=>$v){
            if($v['user_type']==1){
               $rowary= DBQ::getRow('user',['real_name','mobile'],['id'=>$v['user_id']]);
                $data['list'][$k]['user_name']=$rowary['real_name'];
                $data['list'][$k]['user_mobile']=$rowary['mobile'];
            }else{
                $rowary= DBQ::getRow('agent',['real_name','mobile'],['id'=>$v['user_id']]);
                $data['list'][$k]['user_name']=$rowary['real_name'];
                $data['list'][$k]['user_mobile']=$rowary['mobile'];
            }
        }
        $this->assign("data", $data);

        $this->assign("channel", $dictionary['channel']);
        $this->assign("cardStatus", $dictionary['cardStatus']);
        $this->assign("bank", $bank);
        $this->assign("user_type", $dictionary['user_type']);
        // 数据查询
        $this->view();

    }
    /**
     *
     * @name 添加
     */
    public function add($act = null)
    {
        $bank = Lib::getBankConfig(2);
        $dictionary = Lib::loadFile('Config/Dictionary.php');
        if ($act == 'add') {
            $bank_id=Lib::post('bank_id');
            $channel_type=Lib::post('channel_type');
            $user = DBQ::getRow('user', '*', ['id' => Lib::post('user_id')]);
            $mobile=Lib::post('mobile');
            $data = [
                'user_id' => $user['id'],
                'bank_id' => $bank_id,
                'bank_name' => $bank[$bank_id]['name'],
                'mobile' => Lib::aesEncrypt($mobile),
                'lb_mobile' =>\substr_replace($mobile, '****', 3, 4),
                'real_name' =>Lib::aesEncrypt($user['real_name']),
                'card_no' => Lib::aesEncrypt(Lib::post('card_no')),
                'id_card' => $user['id_card'],
                'channel_code' =>$dictionary['channel'][$channel_type]['code'],
                'channel_type' => $channel_type,
                 'create_time' =>Lib::getMs(),
            ];
            $insertId = $this->M()->add ($data);
            $bindCard = [
                'user_id' => $user['id'],
                'bank_id' => $bank_id,
                'bank_name' => $bank[$bank_id]['name'],
                'card_no' => Lib::aesEncrypt(Lib::post('card_no')),
                'id_card' => $user['id_card'],
                'card_type' =>2,
                'description'=> '无',
                'channel' => $channel_type,
                'create_time' =>Lib::getMs(),
                //'create_time' =>time(),
            ];
            if($insertId) {
                $bindCard['status']=2;
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
        $bank = Lib::getBankConfig(2);
        $dictionary = Lib::loadFile('Config/Dictionary.php');
        if ($act == 'edit' && ! empty ( $id )) {
            $bank_id=Lib::post('bank_id');
            $channel_type=Lib::post('channel_type');
            $user = DBQ::getRow('user', '*', ['id' => Lib::post('user_id')]);
            $mobile=Lib::post('mobile');
            $data = [
                'user_id' => $user['id'],
                'bank_id' => $bank_id,
                'bank_name' => $bank[$bank_id]['name'],
                'mobile' => Lib::aesEncrypt($mobile),
                'lb_mobile' =>\substr_replace($mobile, '****', 3, 4),
                'real_name' =>Lib::aesEncrypt($user['real_name']),
                'card_no' => Lib::aesEncrypt(Lib::post('card_no')),
                'id_card' => $user['id_card'],
                'channel_code' =>$dictionary['channel'][$channel_type]['code'],
                'channel_type' => $channel_type,
                 'create_time' =>Lib::getMs(),
               // 'create_time' =>time(),
            ];            
            if ($this->M ()->edit ($id, $data )) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }

        $list = DBQ::getRow('debit_card', '*', [
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
            Dwz::err('删除参数错误');
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