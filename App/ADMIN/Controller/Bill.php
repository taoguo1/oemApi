<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/23
 * Time: 15:14
 */

namespace App\ADMIN\Controller;

use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;

class Bill extends Controller
{
    /**
     *
     * @name 账单查询
     */
    public function index()
    {
    	
        $plan_id = Lib::request ( 'plan_id' );
        $order_sn = Lib::request ( 'order_sn' );
        $card_no = Lib::request ( 'card_no' );
        $real_name = Lib::request ( 'real_name' );
        $transaction_id = Lib::request ( 'transaction_id' );
        $channel = Lib::request ( 'channel' );
        $bill_type = Lib::request ( 'bill_type' );
        $card_type = Lib::request ( 'card_type' );
        $status = Lib::request ( 'status' );
        $intatus =Lib::request ('intatus');
        $mobile =Lib::request ('mobile');
        $condition = null;
        ($plan_id) ? $condition ['AND'] ['plan_id'] = $plan_id : null;
        ($order_sn) ? $condition ['AND'] ['order_sn'] = $order_sn : null;
        ($transaction_id) ? $condition ['AND'] ['transaction_id'] = $transaction_id : null;
        ($real_name) ? $condition ['AND'] ['B.real_name'] = $real_name : null;
        ($mobile) ? $condition ['AND'] ['B.mobile'] = $mobile : null;
        ($card_no) ? $condition ['AND'] ['card_no'] = $card_no : null;
        ($bill_type) ? $condition ['AND'] ['bill_type'] = $bill_type : null;
        ($card_type) ? $condition ['AND'] ['card_type'] = $card_type : null;
        ($intatus) ? $condition ['AND'] ['intatus'] = $intatus : null;
        ($status) ? $condition ['AND'] ['A.status'] = $status : null;
        ($channel) ? $condition ['AND'] ['channel'] = $channel : null;
        $condition ['ORDER'] = [
            'A.id' => 'ASC'
        ];

        $pageArr = Lib::setPagePars ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];
        }
        $data = $this->M()->getList ( $pageArr, $condition );
        $this->assign ( "data", $data );
        $bank = Lib::getBankConfig(-1);
        $this->assign ( "bank", $bank );
        $this->view();
    }


    /**
     *
     * @name 会员所属代理查询
     */
    public function agent()
    {
        $realname = Lib::request ( 'realname' );
        $idcard = Lib::request ( 'idcard' );
        $mobile = Lib::request ( 'mobile' );

        $condition = null;
        ($realname) ? $condition ['AND'] ['realname[~]'] = $realname : null;
        ($idcard) ? $condition ['AND'] ['idcard[~]'] = $idcard : null;
        ($mobile) ? $condition ['AND'] ['mobile[~]'] = $mobile : null;
        $condition ['ORDER'] = [
            'id' => 'ASC'
        ];

        $pageArr = Lib::setPagePars ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];
        }
        $data = $this->M()->getAgent ( $pageArr, $condition );
        $this->assign ( "data", $data );
        $this->view ();
    }

    /**
     *
     * @name 账单添加
     */
    public function add($act = null)
    {
        if ($act == 'add') {
//            var_dump(strtotime(Lib::post('create_time')));die;

            $data = [
                'user_id' => Lib::post('users_id'),
                'agent_id' => Lib::post('agent_agent_id'),
                'plan_id' => Lib::post ( 'plan_id' ),
                'amount' => Lib::post ( 'amount' ),
                'bill_type' => Lib::post ( 'bill_type' ),
                'card_type' => Lib::post ( 'card_type' ),
                'order_sn' => Lib::post ( 'order_sn' ),
            	'poundage' => Lib::post ( 'poundage' ),
            	'bank_name' => Lib::post ( 'bank_name' ),
                'card_no' => Lib::aesEncrypt(Lib::post ( 'card_no' )),
                'task_no' => Lib::post ( 'task_no' ),
                'transaction_id' => Lib::post ( 'transaction_id' ),
                'status' => Lib::post ( 'status' ),
                'channel' => Lib::post ( 'channel' ),
            	'create_time' => Lib::getMs()
            ];
            $insertId = $this->M()->add ($data);
            if ($insertId) {
                Dwz::successDialog ( $this->M()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }
        $id = Lib::post ( 'id' );
        /**
         * 3.13 MrZhang
         */
//        $users = DBQ::getAll('user(A)',["[>]agent(B)]" => ["A.agent_id"=>"id"]],[
//                "A.real_name",
//                "A.id",
//                "B.nickname"
//        ]);

        $list = DBQ::getAll('bill', '*', [
            'id' => $id]);
        $bank = Lib::getBankConfig(-1);
        $this->assign("bank", $bank);
        $dic = Lib::loadFile('Config/Dictionary.php');
        $this->assign ( "dic", $dic );
        $this->assign('list',$list);
        $this->view ();
    }


    /**
     *
     * @name 账单编辑
     */
    public function edit($id = 0, $act = null)
    {
        if ($act == 'edit' && ! empty ( $id )) {
            $data = [
                'user_id' =>Lib::post('users_id'),
                'agent_id' => Lib::post('agent_agent_id'),
                'plan_id' => Lib::post ( 'plan_id' ),
                'amount' => Lib::post ( 'amount' ),
                'bill_type' => Lib::post ( 'type' ),
                'card_type' => Lib::post ( 'card_type' ),
                'bank_name' => Lib::post('bank_name'),
                'poundage' => Lib::post('poundage'),
                'order_sn' => Lib::post ( 'order_sn' ),
                'card_no' => Lib::aesEncrypt(Lib::post ( 'card_no' )),
                'task_no' => Lib::post ( 'task_no' ),
                'transaction_id' => Lib::post ( 'transaction_id' ),
                'status' => Lib::post ( 'status' ),
                'channel' => Lib::post ( 'channel' ),
            ];
//            p($data);
//            echo Lib::post('agent_agent_id');
            if ( $upd = DBQ::upd('bill',$data,['id'=>$id])) {
                Dwz::successDialog ( $this->M()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err();
            }
        }
        $list = DBQ::getRow('bill(A)', ["[>]user(B)"=>["A.user_id"=>"id"],"[>]agent(C)"=>['A.agent_id'=>'id']],[
            'A.id',
            'A.user_id',
            'A.agent_id',
            'A.plan_id',
            'A.amount',
            'A.bill_type',
            'A.card_type',
            'A.bank_name',
            'A.poundage',
            'A.order_sn',
            'A.card_no',
            'A.task_no',
            'A.transaction_id',
            'A.status',
            'A.channel',
            'B.real_name',
            'C.real_name(agent_name)'
        ], [
            'A.id' => $id
        ]);
        $bank = Lib::getBankConfig(-1);
        $dic = Lib::loadFile('Config/Dictionary.php');
        $this->assign ( "dic", $dic );
        $this->assign("bank", $bank);
        $this->assign('list', $list);
        $this->view();
    }

    /**
     *
     * @name 账单删除
     */
    public function del($id = 0)
    {
        $del = DBQ::del('bill', [
            'id' => $id
        ]);
        if ($del) {
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            Dwz::err();
        }
    }

    /**
     * 用户查询
     */
    public function users() {
        $real_name = Lib::request ( 'real_name' );
        $mobile = Lib::request('mobile');
        $id_card = Lib::request('id_card');

        $condition = null;
        ($real_name) ? $condition ['AND'] ['A.real_name[~]'] = $real_name : null;
        ($mobile) ? $condition ['AND'] ['A.mobile[~]'] = $mobile : null;
        ($id_card) ? $condition ['AND'] ['A.id_card[~]'] = $id_card : null;
        $condition['ORDER'] = ['A.id'=>'ASC'];
        $pageArr = Lib::setPagePars ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];
        }
        $data = DBQ::pages($pageArr,'user(A)',["[>]agent(B)]" => ["A.agent_id"=>"id"]],[
            "A.real_name",
            "A.id",
            "A.mobile",
            "A.id_card",
            "A.is_id_card_auth",
            "A.status",
            "A.agent_id",
            "B.real_name(agent_name)",
        ],
            $condition
        );
        $this->assign ( "data", $data );
        $this->view();
    }

    public function delAll() {
        $ids = explode ( ',', Lib::post ( 'ids' ) );
        if ($this->M ()->delAll ( $ids )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
        } else {
            Dwz::err ();
        }
    }
    public function upedit() {
        $vid = Lib::request ( 'vid' );
        $vname = Lib::request ( 'vname' );
        $Decrypt=array('card_no');
        if(in_array($vname,$Decrypt)){
            $vstr = Lib::aesEncrypt(Lib::request ( 'vstr' ));
        }else{
            $vstr = Lib::request ( 'vstr' );
        }

        $data[$vname]=$vstr;
        $re = DBQ::upd('bill',$data,['id'=>$vid]);
        if($re){
            echo json_encode(array('type'=>1,'msg'=>'修改成功'));
        }else{
            echo json_encode(array('type'=>0,'msg'=>'修改失败'));
        }

    }
}