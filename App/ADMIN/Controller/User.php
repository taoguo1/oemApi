<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/18
 * Time: 11:37
 */

namespace App\ADMIN\Controller;
use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;
use Core\Extend\Session;
class User extends Controller
{	
    /**
     *
     * @name 会员查询
     */
    public function index()
    {
    	
        $dictionaryData     = Lib::loadFile('Config/Dictionary.php');
        $real_name          = Lib::request ( 'real_name' );
        //$id                 = Lib::request('user_id');
        $id_card            = Lib::request ( 'id_card' );
        $mobile             = Lib::request ( 'mobile' );
//        $nickname           = Lib::request ( 'agent_nickname' );
        $agent_id           = Lib::request('agent_agent_id');
        $start_balance      = Lib::request ( 'start_balance' );
        $end_balance        = Lib::request ( 'end_balance' );
        $invite_code        = Lib::request ( 'invite_code' );
        $sex                = Lib::request ( 'sex' );
        $is_push            = Lib::request ( 'is_push' );
        $is_id_card_auth    = Lib::request ( 'is_id_card_auth' );
        $status             = Lib::request ( 'status' );
        $start_create_time  = Lib::request ( 'start_create_time' );
        $end_create_time    = Lib::request ( 'end_create_time' );
        $condition = "1=1";
        
        
//        ($id) ? $condition ['AND'] ['U.id'] = $id : null;
        if(isset($real_name ) && $real_name  != ""){
            $condition .= " AND `dzz_U`.`real_name` = '".$real_name ."'";
        }
//        ($id_card) ? $condition ['AND'] ['U.id_card'] = Lib::aesEncrypt($id_card) : null;
        if(isset($id_card) && $id_card != ""){
            $condition .= " AND `dzz_U`.`id_card` = '".Lib::aesEncrypt($id_card)."'";
        }
//        ($mobile) ? $condition ['AND'] ['U.mobile'] = $mobile : null;
        if(isset($mobile) && $mobile != ""){
            $condition .= " AND `dzz_U`.`mobile` = '".$mobile."'";
        }
//        ($nickname) ? $condition ['AND'] ['A.nickname'] = $nickname : null;
//        ($agent_id) ? $condition ['AND'] ['U.agent_id'] = $agent_id : null;
        if(isset($agent_id) && $agent_id != ""){
            $condition .= " AND `dzz_U`.`agent_id` = '".$agent_id."'";
        }
//        ($start_balance) ? $condition['AND']['UE.balance[>=]'] = $start_balance: null;
        if(isset($start_balance) && $start_balance != ""){
            $condition .= " AND `dzz_UC`.`amount` >= '".$start_balance."'";
        }
//        ($end_balance) ? $condition['AND']['UE.balance[<=]'] = $end_balance : null;
        if(isset($end_balance) && $end_balance != ""){
            $condition .= " AND `dzz_UC`.`amount` <= '".$end_balance."'";
        }
//        ($invite_code) ? $condition['AND']['UE.invite_code'] = $invite_code : null;
        if(isset($invite_code) && $invite_code != ""){
            $condition .= " AND `dzz_UE`.`invite_code` = '".$invite_code."'";
        }
//        ($sex) ? $condition['AND']['U.sex'] = $sex : null;
        if(isset($sex) && $sex != ""){
            $condition .= " AND `dzz_U`.`sex` = '".$sex."'";
        }
//        ($is_push) ? $condition['AND']['U.is_push'] = $is_push : null;
        if(isset($is_push) && $is_push != ""){
            $condition .= " AND `dzz_U`.`is_push` = '".$is_push."'";
        }
//        ($is_id_card_auth) ? $condition ['AND'] ['U.is_id_card_auth'] = $is_id_card_auth : null;
        if(isset($is_id_card_auth) && $is_id_card_auth != ""){
            $condition .= " AND `dzz_U`.`is_id_card_auth` = '".$is_id_card_auth."'";
        }
//        ($status) ? $condition ['AND'] ['U.status'] = $status : null;
        if(isset($status) && $status != ""){
            $condition .= " AND `dzz_U`.`status` = '".$status."'";
        }
//        ($start_create_time) ? $condition['AND']['U.create_time[>=]'] = strtotime($start_create_time. " 00:00:00")*1000: null;
        if(isset($start_create_time) && $start_create_time != ""){
            $start_create_time = strtotime($start_create_time. ' 00:00:00')*1000;
            $condition .= " AND `dzz_U`.`create_time` >= '".$start_create_time."'";
        }
//        ($end_create_time) ? $condition['AND']['U.create_time[<=]'] = strtotime($end_create_time. " 23:59:59")*1000 : null;
        if(isset($end_create_time) && $end_create_time != ""){
            $end_create_time = strtotime($end_create_time. ' 00:00:00')*1000;
            $condition .= " AND `dzz_U`.`create_time` >= '".$end_create_time."'";

        }

        //$condition .= " ORDER BY `create_time` DESC";
        $pageArr = Lib::setPagePars ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];
        }
        $data = $this->M()->getUserList ( $pageArr, $condition );


        
        	
//	  		$session = Session::instance();
//	        $account = $session->get('account');
//	        $account = isset($account) ? $account : '';  
        
        
        $this->assign('sexItems',$dictionaryData['sexItems']);
        $this->assign('userAuth',$dictionaryData['userAuth']);
        $this->assign('userPush',$dictionaryData['userPush']);
        $this->assign('userState',$dictionaryData['userState']);
        $this->assign ( "data", $data );
        $this->view ();

    }


    /**
     *
     * @name 会员所属代理查询
     */
    public function agent()
    {
		
		
		
        $real_name   = Lib::request ( 'real_name' );
        $id_card     = Lib::request ( 'id_card' );
        $mobile      = Lib::request ( 'mobile' );

        $condition = "1=1";
        if(isset($real_name) && $real_name != ""){
            $condition .= " AND A.real_name = '".$real_name."'";
        }
        if(isset($id_card) && $id_card != ""){
            $condition .= " AND A.id_card = '".$id_card."'";
        }
        if(isset($mobile) && $mobile != ""){
            $condition .= " AND A.mobile = '".$mobile."'";
        }
        $condition .= " ORDER BY A.id DESC";

        $pageArr = Lib::setPagePars ();
        if ($pageArr ['orderField']) {
            $columns ['ORDER'] = [
                $pageArr ['orderField'] => strtoupper ( $pageArr ['orderDirection'] )
            ];
        }
        $data = $this->M()->getAgentList ( $pageArr, $condition );
        $this->assign ( "data", $data );
        $this->view ();
    }

    /**
     * **
     *
     * @name 添加用户
     */
    public function add($act = null) {
        if ($act == 'add') {
            $real_name          =  Lib::post ( 'real_name' );
            $password           =  Lib::post ( 'password' );
            $repassword         =  Lib::post ( 'repassword' );
            $mobile             =  Lib::post ( 'mobile' );
            $id_card            =  Lib::post ( 'id_card' );
            $sex                =  Lib::post ( 'sex' );
            $avatar             =  OSS_ENDDOMAIN."/".Lib::post ( 'avatar' );
            $agent_agent_id     =  Lib::post ( 'agent_agent_id' );
            $pay_password       =  Lib::post ( 'pay_password' );
            $is_id_card_auth    =  Lib::post ( 'is_id_card_auth' );
            $is_push            =  Lib::post ( 'is_push' )?Lib::post ( 'is_push' ):1;
            $status             =  Lib::post ( 'status' );
            $balance            =  Lib::post ( 'balance' );
            $invite_code        =  Lib::post ( 'invite_code' );
            if (empty($real_name) || strlen($real_name) > 20) {
                Dwz::err('姓名不能为空且不能大于20个字符');
            }
            if(empty($password) || strlen($password) > 20 || strlen($password) < 6){
                Dwz::err('密码不能为空且为6到20位字符');
            }
            if(empty($repassword) || strlen($repassword) > 20 || strlen($repassword) < 6){
                Dwz::err('确认密码不能为空且为6到20位字符');
            }
            if($password != $repassword){
                Dwz::err('密码与确认密码不一致');
            }
            if(empty($mobile)){
                Dwz::err('手机号不能为空');
            }
            if(!(Lib::checkMobile($mobile))){
                Dwz::err('手机号格式错误');
            }
            if(empty($id_card)){
                Dwz::err('身份证号不能为空');
            }
            if(!(Lib::isIdCard($id_card))){
                Dwz::err('身份证格式错误');
            }
//            if(!is_numeric($balance)){
//                Dwz::err('余额只能为数字');
//            }
            //3.14MrZhang
            if (!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $balance) && !empty($balance)) {
                Dwz::err('请输入正确的金额');
            }
            if(!empty($pay_password)){
                if(!is_numeric($pay_password) || strlen($pay_password) != 6){
                    Dwz::err('支付密码为六位数字');
                }
            }
            if(empty($invite_code)){
                Dwz::err('邀请码不能为空');
            }
            if(!(Lib::isLetterNum($invite_code)) || strlen($invite_code)>32){
                Dwz::err('邀请码为小于32位数字或者字母');
            }
            $condition = null;
            $condition = ['mobile'=>$mobile];
            $mobileCount = DBQ::getCount('user',$condition);
            if($mobileCount){
                Dwz::err('手机号码已注册');
            }
            $condition = null;
            $condition = ['id_card'=>$id_card];
            $idCardCount = DBQ::getCount('user',$condition);
            if($idCardCount){
                Dwz::err('身份证号已注册');
            }
            $condition = null;
            $condition = ['code'=>$invite_code];
            $inviteCodeCount = DBQ::getCount('invite_code',$condition);
            if(empty($inviteCodeCount)){
                Dwz::err('邀请码输入错误');
            }
            $condition = null;
            $condition['code']   = $invite_code;
            $condition['status'] = 3;
            $userExtCount = DBQ::getCount('invite_code',$condition);
            if($userExtCount){
                Dwz::err('邀请码已使用');
            }

            $userData = [
                'real_name'         => $real_name,
                'password'          => Lib::compilePassword($password),
                'mobile'            => $mobile,
                'id_card'           =>  Lib::aesEncrypt($id_card),
                'sex'               => $sex,
                'avatar'            => $avatar,
                'agent_id'          => $agent_agent_id,
                'is_id_card_auth'   => $is_id_card_auth,
                'is_push'           => $is_push,
                'status'            => $status,
                'create_time'       => Lib::getMs()
            ];
            if(!empty($pay_password)){
                $userData['pay_password'] = Lib::compilePassword($pay_password);
            }
            $userDataExt = [
                'balance'          => $balance,
                'invite_code'      => $invite_code
            ];

            $result = $this->M ()->addUserData ( $userData,$userDataExt );

            //修改邀请码状态
            $InviteCodeModel = new \App\ADMIN\Model\InviteCode();
            $InviteCodeModel->updateInviteCodeStatus(3,$invite_code);
            if ($result) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            }
        }
        $dictionaryData     = Lib::loadFile('Config/Dictionary.php');
        $this->assign('sexItems',$dictionaryData['sexItems']);
        $this->assign('userAuth',$dictionaryData['userAuth']);
        $this->assign('userPush',$dictionaryData['userPush']);
        $this->assign('userState',$dictionaryData['userState']);
        $this->view ();
    }

    /**
     *
     * @name 会员修改
     */
    public function edit($id = 0, $act = null)
    {
        if ($act == 'edit' && ! empty ( $id ) && is_numeric($id)) {
            $real_name          =   Lib::post ( 'real_name' );
            $password           =   Lib::post ( 'password' );
            $mobile             =   Lib::post ( 'mobile' );
            $id_card            =   Lib::post ( 'id_card' );
            $sex                =   Lib::post ( 'sex' );
            $images = DBQ::getRow('user','avatar',['id'=>$id]);
            if($images !== Lib::post ( 'avatar' )) {
                $avatar             =   OSS_ENDDOMAIN."/".Lib::post ( 'avatar' );
            }
            $agent_agent_id     =   Lib::post ( 'agent_agent_id' );
            $pay_password       =   Lib::post ( 'pay_password' );
            $is_id_card_auth    =   Lib::post ( 'is_id_card_auth' );
            $is_push            =   Lib::post ( 'is_push' );
            $status             =   Lib::post ( 'status' );
            $balance            =   Lib::post ( 'balance' );
//          if (!empty($real_name) || strlen($real_name) > 20) {
//              Dwz::err('姓名不能大于20个字符');
//          }
            if(!empty($password)){
                if(strlen($password) > 20 || strlen($password) < 6){
                    Dwz::err('密码为6到20位字符');
                }
            }
            if(empty($mobile)){
                Dwz::err('手机号不能为空');
            }
            if(!(Lib::checkMobile($mobile))){
                Dwz::err('手机号格式错误');
            }
//            if(empty($id_card)){
//                Dwz::err('身份证号不能为空');
//            }
            if(!(Lib::isIdCard($id_card)) && !empty($id_card)){
                Dwz::err('身份证格式错误');
            }
//            if(empty($balance) || !is_numeric($balance)){
//                Dwz::err('余额不能为空且为数字');
//            }
            if (!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $balance) && !empty($balance)) {
                Dwz::err('请输入正确的金额');
            }
            if(!empty($pay_password)){
                if(!is_numeric($pay_password) || strlen($pay_password) != 6){
                    Dwz::err('支付密码为六位数字');
                }
            }
            $condition = null;
            $condition['mobile']    = $mobile;
            $condition['id[!]']     = $id;
            $mobileCount = DBQ::getCount('user',$condition);
            if($mobileCount){
                Dwz::err('手机号码已注册');
            }
            $condition = null;
            $condition['id_card']   = $id_card;
            $condition['id[!]']     = $id;
            $idCardCount = DBQ::getCount('user',$condition);
            if($idCardCount && !empty($id_card)){
                Dwz::err('身份证号已注册');
            }

            if(isset($avatar)) {
                $userData = [
                    'real_name'         => $real_name,
                    'mobile'            => $mobile,
                    'id_card'           => Lib::aesEncrypt($id_card),
                    'sex'               => $sex,
                    'avatar'            => $avatar,
                    'agent_id'          => $agent_agent_id,
                    'is_id_card_auth'   => $is_id_card_auth,
                    'is_push'           => $is_push,
                    'status'            => $status
                ];
            } else {
                $userData = [
                    'real_name'         => $real_name,
                    
                    'mobile'            => $mobile,
                    'id_card'           => Lib::aesEncrypt($id_card),
                    'sex'               => $sex,
                    'agent_id'          => $agent_agent_id,
                    'is_id_card_auth'   => $is_id_card_auth,
                    'is_push'           => $is_push,
                    'status'            => $status
                ];
            }

            if(!empty($password)){
                $userData['password'] = Lib::compilePassword($password);
            }
            if(!empty($pay_password)){
                $userData['pay_password'] = Lib::compilePassword($pay_password);
            }
            $userDataExt = [
                'balance'          => $balance
            ];

            $result = $this->M()->editUserData ( $userData,$userDataExt ,$id);
            if ( $result) {
                Dwz::successDialog ( $this->M()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err();
            }
        }
        $condition = null;
        ($id) ? $condition ['AND'] ['U.id'] = $id : null;
        $data = $this->M()->getUserInfoRow ( $condition );
        $dictionaryData     = Lib::loadFile('Config/Dictionary.php');

        $this->assign('sexItems',$dictionaryData['sexItems']);
        $this->assign('userAuth',$dictionaryData['userAuth']);
        $this->assign('userPush',$dictionaryData['userPush']);
        $this->assign('userState',$dictionaryData['userState']);
        $this->assign('data', $data);
        $this->view();
    }


    /**
     *
     * @name 会员删除
     */
    public function del($id = 0)
    {
        if(empty($id) || !is_numeric($id)){
            Dwz::err('删除参数错误');
        }
        $statusData = [
            'status'    =>  '-1'
        ];
        $result = $this->M()->delUserRelatedData ( $statusData, $id );
        if ($result !== false) {
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            Dwz::err();
        }
    }

    /**
     * @name 单个修改
     */
    public function upedit() {
        $vid = Lib::request ( 'vid' );
        $vname = Lib::request ( 'vname' );
        //需要加密的字段
//        $Decrypt=array('');
//        if(in_array($vname,$Decrypt)){
//            $vstr = Lib::aesEncrypt(Lib::request ( 'vstr' ));
//        }else{
//            $vstr = Lib::request ( 'vstr' );
//        }
        $vstr = Lib::request ( 'vstr' );
        $data[$vname]=$vstr;
        $re = DBQ::upd('user_ext',$data,['user_id'=>$vid]);
        if($re){
            echo json_encode(array('type'=>1,'msg'=>'修改成功'));
        }else{
            echo json_encode(array('type'=>0,'msg'=>'修改失败'));
        }

    }

}
