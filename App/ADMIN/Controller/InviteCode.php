<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/1/20
 * Time: 11:30
 */

namespace App\ADMIN\Controller;


use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;


class InviteCode extends Controller
{

    /**
     *
     * @name 邀请码列表
     */
    public function index(){
        $code = Lib::request ( 'code' );
        $nickname = Lib::request('agent_agent_name');
        $agent_id = Lib::request('agent_agent_id');
        $start_date = Lib::request ( 'start_date' );
        $end_date = Lib::request ( 'end_date' );

        $condition = null;

        ($code) ? $condition ['AND'] ['code'] = $code : null;
        ($agent_id) ? $condition ['AND'] ['A.agent_id'] = $agent_id : null;
        ($start_date) ? $condition ['AND'] ['A.create_time[>=]'] =  strtotime($start_date." 00:00:00")."000" : null;
        ($end_date) ? $condition ['AND'] ['A.create_time[<=]'] = strtotime($end_date."23:59:59")."999": null;
        $condition ['ORDER'] = [
            'A.id' => 'DESC'
        ];
        $pageArr = Lib::setPagePars();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];
        }
        $data = $this->M()->getList ( $pageArr, $condition );
        $this->assign ( "data", $data );

        $dic = Lib::loadFile('Config/Dictionary.php');
        $this->assign ( "inviteCodeStatus", $dic['inviteCodeStatus'] );
        $this->view ();
    }

    /**
     *
     * @name 创建邀请码
     */
    public function create($act = null){
        $num = Lib::post ( 'quantity' );
        if($num > 100){

            Dwz::err ();
        }
        if ($act == 'create') {
            $agent_id=Lib::post ( 'agent_agent_id');
            if(!empty($agent_id)){
                $time = Lib::getMs();
                for($i = 1; $i <= $num; $i++){
                    $data = [
                        'agent_id' =>$agent_id,
                        'code' => '',
                        'status' => 1,
                        'create_time' => $time,
                    ];
                    DBQ::add('invite_code',$data);
                    $id = DBQ::insertID();
                    $update = DBQ::upd('invite_code', ['code'=>md5(md5($id))] ,['id'=>$id]);

//              Lib::post ( 'agent_agent_id')
                    $InviteCodeTrade = DBQ::getRow('invite_code', ['code','agent_id','status','id','create_time'],['id'=>$id]);
                    DBQ::add('invite_code_trade', [
                        'code'=>$InviteCodeTrade['code'],
                        'code_id'=>$InviteCodeTrade['id'],
                        'after_agent_id'=>$InviteCodeTrade['agent_id'],
                        'status'=>$InviteCodeTrade['status'],
                        'trade_time'=>$InviteCodeTrade['create_time'],
                        'volume'=>$num,
                    ]);

                }
                if ($update) {
                    $invite_code_num = DBQ::getCount('invite_code','code',['agent_id'=>Lib::post('agent_agent_id'),'status'=>'1']);
                    $num = DBQ::upd('agent_ext', ['invite_code_num'=>$invite_code_num] ,['agent_id'=>Lib::post('agent_agent_id')]);

                    if ($num) {
                        Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
                    } else {
                        Dwz::err ();
                    }
                    Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
                } else {
                    Dwz::err ();
                }
            }else{
                Dwz::err ();
            }
        }
        $this->view ();
    }

    /**
     *
     * @name 删除邀请码
     */
    public function del($id = 0){
        if ($this->M ()->del ( $id )) {
            Dwz::success ( Lib::getUrl ( $this->M ()->modelName ), $this->M ()->modelName );
        } else {
            Dwz::err ();
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
    public function createQr($id='123456',$appid=0) {
		
        $this->assign ( "code", $id );
		$this->assign ( "appid", $appid );
        $this->view ();
    }

}