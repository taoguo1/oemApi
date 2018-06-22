<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/19
 * Time: 14:27
 */

namespace App\ADMIN\Controller;
use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Extend\Redis;
use Core\Lib;
use Core\Extend\Dwz;

class Agent extends Controller
{
    /**
     *
     * @name 代理查询
     */
    public function index()
    {

        $agent_id = Lib::request('agent_agent_id');
        $nickname = Lib::request('nickname');
        $mobile = Lib::request('mobile');
        $level = Lib::request('level');
        $status = Lib::request('status');
        $is_id_card_auth = Lib::request('is_id_card_auth');
        $id_card = Lib::request('id_card');
        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');

        $condition = " WHERE 1";
        if($nickname){
			$this->assign('agentname',$nickname);
            $condition .= " and A.nickname  LIKE '%".$nickname."%'";
        }
        if ($agent_id){
            $condition .= " and A.id = '".$agent_id."'";
        }
        if ($mobile){
            $condition .= " and A.mobile = '".$mobile."'";
        }
        if ($level){
            $condition .= " and A.level = '".$level."'";
        }
        if ($status){
            $condition .= " and A.status = '".$status."'";
        }
        if ($is_id_card_auth){
            $condition .= " and is_id_card_auth = '".$is_id_card_auth."'";
        }
        if ($id_card){
            $condition .= " and A.id_card = '".Lib::aesEncrypt($id_card)."'";
        }
        if ($start_date || $end_date){
            $condition .= " and A.create_time between " . (strtotime($start_date))*1000 . " and " . (strtotime($end_date))*1000 ;
        }
        //$condition .= " ORDER BY A.create_time DESC";
        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }
        $data = $this->M()->getList($pageArr, $condition,$agent_id);
        $this->assign("data", $data);

        $this->view();
    }

    /**
     *
     * @name 上级代理回显
     */
    public function parentAgentNext($id=null)
    {
        $nickname = Lib::request('nickname');
        $mobile = Lib::request('mobile');
        $id_card = Lib::request('id_card');
        $level = Lib::request('level');
        $status = Lib::request('status');
        $is_id_card_auth = Lib::request('is_id_card_auth');

        $condition = " WHERE 1";
        if($nickname){
            $condition .= " and nickname = '".$nickname."'";
        }
        if ($mobile){
            $condition .= " and mobile = '".$mobile."'";
        }
        if ($level){
            $condition .= " and level = '".$level."'";
        }
        if ($status){
            $condition .= " and status = '".$status."'";
        }
        if ($is_id_card_auth){
            $condition .= " and is_id_card_auth = '".$is_id_card_auth."'";
        }
        if ($id_card){
            $condition .= " and id_card = '".Lib::aesEncrypt($id_card)."'";
        }
        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }

        $agentArry =  $this->M()->findAgent($id);

        $this->assign("agentArry", $agentArry);
        $data = $this->M()->getList($pageArr, $condition);
        $this->assign("data", $data);
        $this->view();
    }
    /**
     *
     * @name 上级代理回显
     */
    public function parentAgent()
    {
        $nickname = Lib::request('nickname');
        $mobile = Lib::request('mobile');
        $id_card = Lib::request('id_card');
        $level = Lib::request('level');
        $status = Lib::request('status');
        $is_id_card_auth = Lib::request('is_id_card_auth');

        $condition = " WHERE 1";
        if($nickname){
            $condition .= " and nickname = '".$nickname."'";
        }
        if ($mobile){
            $condition .= " and mobile = '".$mobile."'";
        }
        if ($level){
            $condition .= " and level = '".$level."'";
        }
        if ($status){
            $condition .= " and status = '".$status."'";
        }
        if ($is_id_card_auth){
            $condition .= " and is_id_card_auth = '".$is_id_card_auth."'";
        }
        if ($id_card){
            $condition .= " and id_card = '".Lib::aesEncrypt($id_card)."'";
        }
        $pageArr = Lib::setPagePars();
        if ($pageArr['orderField']) {
            $columns['ORDER'] = [
                $pageArr['orderField'] => strtoupper($pageArr['orderDirection'])
            ];
        }

        $data = $this->M()->getList($pageArr, $condition);
        $this->assign("data", $data);
        $this->view();
    }

    /**
     *
     * @name 代理添加
     */
    public function add($act = null) {

        if ($act == 'add')
        {
            $rate=Lib::post('rate');
            $skrate=Lib::post('skrate');
            if ($this->M()->has('agent',[
                'mobile' => Lib::post('mobile')
            ])){
                Dwz::err('手机号已经存在');
                return false;
            }

            if(empty(Lib::post('code'))) {
                DWZ::err("验证码不能为空");
                return false;
            }

            $verifyCode = Lib::post('code');
            $check_code_validity = $this->checkCodeValidity( Lib::post('mobile'),$verifyCode,Lib::request('appid'),true,9);
            if ($check_code_validity['status'] != 'success') {
                //Lib::outputJson($check_code_validity);
                Dwz::err($check_code_validity['msg']);
            }

            if(intval($rate)!=0){
                if (!empty(Lib::post('agent_agent_id')) && DBQ::getOne('agent','rate',['id'=>Lib::post('agent_agent_id')]) < $rate)
                {
                    Dwz::err('下级代理分润比例不能大于上级代理');
                    return false;
                }
                if($rate > REPAYMENT_RATE) {
                    Dwz::err('分润比例不能大于'.REPAYMENT_RATE);
                    return false;
                }
            }
            if(intval($skrate)!=0){
                if (!empty(Lib::post('agent_agent_id')) && DBQ::getOne('agent','skrate',['id'=>Lib::post('agent_agent_id')]) < $skrate)
                {
                    Dwz::err('下级代理收款分润比例不能大于上级代理');
                    return false;
                }
                if($skrate > TX_AGENT_RATE) {
                    Dwz::err('分润比例不能大于'.TX_AGENT_RATE);
                    return false;
                }

            }

//            if (DBQ::getCount('invite_code','code',['agent_id'=>Lib::post('agent_agent_id')]))
//            {
//                Dwz::err('下级代理邀请码数量不能大于上级代理');
//                return false;
//            }
            /**
             * 代码增加
             * 2018.3.13 Zhang
             */
//            var_dump(Lib::post('rate'));


            $data = [
                'mobile' => Lib::post('mobile'),
                'password' => Lib::post('password'),
                'nickname' => Lib::post('nickname'),
                'real_name' => '',
                'id_card' => '',
                'pid' => Lib::post('agent_agent_id'),
                'rate' => Lib::post('rate'),
                'skrate' => Lib::post('skrate'),
                'is_id_card_auth' => '-1',
                'status' => Lib::post('status'),
                'create_time' => Lib::getMs(),
            ];
            //密码加密
            $data['password'] = Lib::compilePassword($data['password']);
            //代理级别
            if (!empty($data['pid'])) {
                $level = $this->M()->getSelfAgentLevel($data['pid']);
                if (!empty($level)) {
                    $data['level'] = $level;
                }
            }

            $insert = $this->M ()->add ( $data );
            if ($insert) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }

        $id = Lib::post ( 'id' );
        $list = DBQ::getAll('agent', '*', [
            'id' => $id]);
        $this->assign('list',$list);
        $this->view ();
    }



    /**
     *
     * @name 代理编辑
     */
    public function edit($id = 0, $act = null)
    {
        if ($act == 'edit') {
            $rate=Lib::post('rate');
            $skrate=Lib::post('skrate');
            $agent_id=Lib::post('agent_agent_id');
            if(intval($rate)!=0){
                if (!empty(Lib::post('agent_agent_id')) && DBQ::getOne('agent','rate',['id'=>Lib::post('agent_agent_id')]) < Lib::post('rate'))
                {
                    Dwz::err('不能大于上级代理分润');
                    return false;
                }
                $rateNaxt=DBQ::getMax('agent','rate',['pid'=>$id]);
                if($rateNaxt>Lib::post('rate')){
                    Dwz::err('不能小于下级代理分润比例');
                    return false;
                }
            }
            if(intval($skrate)!=0){
                if (!empty(Lib::post('agent_agent_id')) && DBQ::getOne('agent','skrate',['id'=>Lib::post('agent_agent_id')]) < Lib::post('skrate'))
                {
                    Dwz::err('不能大于上级代理分润');
                    return false;
                }

                $skrateNaxt=DBQ::getMax('agent','skrate',['pid'=>$id]);
                if($skrateNaxt>Lib::post('skrate')){
                    Dwz::err('不能小于下级代理分润比例');
                    return false;
                }
            }


//            if (DBQ::getCount('invite_code','code',['agent_id'=>Lib::post('agent_agent_id')]))
//            {
//                Dwz::err('下级代理邀请码数量不能大于上级代理');
//                return false;
//            }
            if(Lib::post('rate') > REPAYMENT_RATE) {
                Dwz::err('分润比例不能大于'.REPAYMENT_RATE);
                return false;
            }
            if(Lib::post('skrate') > TX_AGENT_RATE) {
                Dwz::err('分润比例不能大于'.TX_AGENT_RATE);
                return false;
            }
            $data = [
                'mobile' => Lib::post('mobile'),
                'nickname' => Lib::post('nickname'),
                'real_name' => Lib::post('real_name'),
                'id_card' => Lib::aesEncrypt(Lib::post('id_card')),
                'pid' => Lib::post('agent_agent_id'),
                'rate' => Lib::post('rate'),
                'skrate' => Lib::post('skrate'),
                'is_id_card_auth' => -1,
                'status' => Lib::post('status'),
               // 'is_id_card_auth' => Lib::post('is_id_card_auth'),
               // 'status' => Lib::post('status'),
            ];

            //代理级别
            if (!empty($data['pid'])) {
                $level = $this->M()->getSelfAgentLevel($data['pid']);
                if (!empty($level)) {
                    $data['level'] = $level;
                }
            }
            $upd = $this->M()->edit($data,$id);
            if ($upd) {
                Dwz::successDialog($this->M()->modelName, '', 'closeCurrent');
            } else {
                Dwz::err();
            }
        }
        //$table, $join = null, $columns = null, $where = null
        $list = DBQ::getRow('agent (A)', [
            '[>]agent_ext (B)' => [
                'A.id' => 'agent_id'
            ]
        ], [
            'A.id',
            'A.mobile',
            'A.nickname',
            'A.real_name',
            'A.id_card',
            'A.pid',
            'A.level',
            'A.rate',
            'A.skrate',
            'A.status',
            'A.is_id_card_auth',
            'A.create_time',
            'B.total_commission',
            'B.invite_code_num',
        ],[
            'A.id' => $id
        ]);
        //代理
        $agentInfo = DBQ::getRow('agent', 'nickname',['id' => $list['pid']]);
        $this->assign('agentInfo', $agentInfo);
        $this->assign('list', $list);
        $this->assign('id', $id);
        $this->view();

    }

    /**
     *
     * @name 代理删除
     */
    public function del($id = 0)
    {
        $del = $this->M ()->del ($id);
        if ($del) {
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            Dwz::err();
        }
    }

    //发送短信验证码
    public function sendS() {

        $mobile =  Lib::post('mobile');
        $oemappid = Lib::request("appid");
        $code_type = Lib::post('code_type');
        $rs = DBQ::getCount('agent',['id'],['mobile'=>$mobile]);
        if($rs) {
            if($code_type = 9) {
                Lib::outputJson(array('status' => 'fail', 'code' => 10011, 'msg' => '手机号码已经注册'));
            }
        }

        $resultObj = Lib::sendSms($mobile,$code_type,$oemappid,'',"SMS_129470288",$oemappid);
        $result = json_decode($resultObj,true);
        if ($result['Code'] == 'OK') {
            Lib::outputJson(array('status' => 'success', 'code' => 10000, 'msg' => '短信发送成功'));
        }
        if($result['Code']=='isv.BUSINESS_LIMIT_CONTROL'){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '您当天获取短信条数已超限，请明天再试'));
        }
        if($result['code']=='isv.MOBILE_NUMBER_ILLEGAL'){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '手机号非法'));
        }
        Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '短信发送失败'));
    }
    public function  checkCodeValidity($mobile, $code,$app_id, $check_reg = true, $type = 1) {
        if (empty ( $mobile ) || empty ( $code )) {
            return array (
                'status' => 'fail',
                'code' => 1000,
                'msg' => '验证码错误'
            );
        }

        // 手机号码是否注册
// 		if ($check_reg) {
// 			$table = $type == 1 ? 'user' : 'agent';
// 			$condition ['AND'] ['mobile'] = $mobile;
// 			$user_info_ext = DBQ::getRow ( $table, '*', $condition );
// 			if (empty ( $user_info_ext )) {
// 				return array (
// 						'status' => 'fail',
// 						'code' => 1000,
// 						'msg' => '手机号尚未注册'
// 				);
// 			}
// 		}

        // 验证码是否过期
        /*		$condition ['AND'] ['mobile'] = $mobile;
                $condition ['AND'] ['code'] = $code;
                $condition ['ORDER'] = [
                        'create_time' => 'DESC'
                ];*/
        $redis = Redis::instance('msg');
        $mobile_redis = $redis->zRangeByScore($app_id.'_code',$mobile,$mobile,['withscores'=>true,'limit'=>[0,100000]]);
        $verifyCode = null;
        if (!empty($mobile_redis)) {
            foreach ($mobile_redis as $k => $v) {
                $row_redis = null;
                $row_redis = json_decode($k,true);
                if ($row_redis['code'] == $code &&  $row_redis['mobile'] == $mobile) {
                    $verifyCode = $row_redis;
                    break;
                }
            }
        }
        if (empty ( $verifyCode )) {
            return array (
                'status' => 'fail',
                'code' => 1000,
                'msg' => '无效的验证码'
            );
        }
        // 已使用
        if ($verifyCode ['status'] != 1) {
            return array (
                'status' => 'fail',
                'code' => 1000,
                'msg' => '无效的验证码'
            );
        }

        $compareTime = Lib::getMs () - $verifyCode ['create_time'] - 900000;
        if ($compareTime > 0) {
            return array (
                'status' => 'fail',
                'code' => 1000,
                'msg' => '验证码已过期'
            );
        }
        return array (
            'status' => 'success',
            'code' => 1000,
            'msg' => '验证码状态正常',
            'verifycode' => $verifyCode
        );
    }


}