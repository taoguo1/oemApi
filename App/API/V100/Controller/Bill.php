<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/2/21
 * Time: 16:29
 */
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;

class Bill extends Controller
{
    public $m;
    public $headers;
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->headers = Lib::getAllHeaders();
        $model = "\\App\API\\".$this->headers['VERSION']."\\Model\\Bill";
        $this->m = new $model;
    }


    //根据订单号查询用户订单信息
    public function getOrderInfo(){
        $condition['user_id']=$this->headers['UID'];
        $condition['order_sn']=Lib::post('order_sn');
        if(empty($condition['order_sn'])){
            $data=[
                'status'=>'fail',
                'code' => 10011,
                'msg' => '订单号为必传参数',
            ];
        }else{
            
            $res=$this->m->getOrderInfo($condition);
            if(!empty($res)){
                $res['come_time']="预计2-48小时到账"; 
                $msg="查询成功";
                $code='10000';
                $status='success';     
            }else{
                $res=[];
                $msg="查询失败";
                $code='10011';
                $status='fail';
            }
            $data=[
                'status'=>$status,
                'code' => $code,
                'msg' => $msg,
                'data'=>$res,
            ];
        }
        Lib::outputJson($data);
    }
    //根据订单号查询代理订单信息
    public function getOrderInfoForAgent(){
        $condition['agent_id']=$this->headers['UID'];
        $condition['order_sn']=Lib::post('order_sn');
        if(empty($condition['order_sn'])){
            $data=[
                'status'=>'fail',
                'code' => 10011,
                'msg' => '订单号为必传参数',
            ];
        }else{
            $res=$this->m->getOrderInfoForAgent($condition);
            if(!empty($res)){
                $res['come_time']="预计2-48小时到账"; 
                $msg="查询成功";
                $code='10000';
                $status='success';     
            }else{
                $res=[];
                $msg="查询失败";
                $code='10011';
                $status='fail';
            }
            $data=[
                'status'=>$status,
                'code' => $code,
                'msg' => $msg,
                'data'=>$res,
            ];
        }
        Lib::outputJson($data);
    }

    public function index(){
        $user_id = $this->headers['UID'];
        $condition['AND']['user_id'] = $user_id;
        $condition['AND']['A.status[!]'] = -1;
        $condition ['ORDER'] = [
            'A.id' => 'DESC'
        ];
        $numPerPage = Lib::post('numPerPage');
        $pageArr = Lib::setPagePars ();
        $pageArr['numPerPage'] = $numPerPage ? $numPerPage : 10;
        $datalist = $this->m->getList($pageArr, $condition);
        //$datalist = $this->m->getList($user_id);
        $dataAll = [];
        foreach($datalist['list'] as $k => $v){
            $v['create_time_str'] = Lib::uDate('m-d H:i',$v['create_time']);
            if($v['card_no']){
                $v['card_no_last4'] = substr(Lib::aesDecrypt($v['card_no']),-4);
            }
            $dataAll[] = $v;
        }
        $datalist['list'] = $dataAll;
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取最新账单成功',
            //'data' => $dataAll,
            'data' => $datalist,
        ];
        Lib::outputJson($data);
    }

    public function editBillDay(){
        $user_id = $this->headers['UID'];
        if(!$user_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'user_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $card_id = Lib::post('card_id');
        if(!$card_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'card_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $day = Lib::post('day');
        if(!$day){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'day不能为空',
            ];
            Lib::outputJson($data);
        }

        $rs = $this->m->getCreditCard($user_id, $card_id);
        $repaymentDay = $rs['repayment_day'];

        if($repaymentDay == $day){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '账单日和最后还款日不可能是同一天',
            ];
            Lib::outputJson($data);
        }
        if($repaymentDay > $day){
            if($repaymentDay - $day < 3){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '账单日和最后还款日间隔应超过3天',
                ];
                Lib::outputJson($data);
            }
            if($repaymentDay - $day > 25){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '账单日和最后还款日间隔不应超过25天',
                ];
                Lib::outputJson($data);
            }
            $repaymentMonth = 1;
        }
        elseif($repaymentDay < $day){
            if(((31 - $day) + $repaymentDay) < 3){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '账单日和最后还款日间隔应超过3天',
                ];
                Lib::outputJson($data);
            }
            if(((31 - $day) + $repaymentDay) > 25)
            {
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '账单日和最后还款日间隔应超过3天',
                ];
                Lib::outputJson($data);
            }
            $repaymentMonth = 2;
        }



        $ret = $this->m->editCreditBillDay($card_id,$day,$user_id,$repaymentMonth);
        if($ret){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '修改账单日成功',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '修改账单日失败',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }
    }

    public function editRepaymentDay(){
        $user_id = $this->headers['UID'];
        if(!$user_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'user_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $card_id = Lib::post('card_id');
        if(!$card_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'card_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $repaymentDay = Lib::post('day');
        if(!$repaymentDay){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'day不能为空',
            ];
            Lib::outputJson($data);
        }

        $rs = $this->m->getCreditCard($user_id, $card_id);
        $day = $rs['bill_day'];

        if($repaymentDay == $day){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '账单日和最后还款日不可能是同一天',
            ];
            Lib::outputJson($data);
        }
        if($repaymentDay > $day){
            if($repaymentDay - $day < 3){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '账单日和最后还款日间隔应超过3天',
                ];
                Lib::outputJson($data);
            }
            if($repaymentDay - $day > 25){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '账单日和最后还款日间隔不应超过25天',
                ];
                Lib::outputJson($data);
            }
            $repaymentMonth = 1;
        }
        elseif($repaymentDay < $day){
            if(((31 - $day) + $repaymentDay) < 3){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '账单日和最后还款日间隔应超过3天',
                ];
                Lib::outputJson($data);
            }
            if(((31 - $day) + $repaymentDay) > 25)
            {
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '账单日和最后还款日间隔应超过3天',
                ];
                Lib::outputJson($data);
            }
            $repaymentMonth = 2;
        }

        $ret = $this->m->editCreditRepaymentDay($card_id,$repaymentDay,$user_id,$repaymentMonth);
        if($ret){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '修改最后还款日成功',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '修改最后还款日失败',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }
    }


    public function editStartDay(){
        $user_id = $this->headers['UID'];
        if(!$user_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'user_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $card_id = Lib::post('card_id');
        if(!$card_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'card_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $day = Lib::post('day');
        if(!$day){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'day不能为空',
            ];
            Lib::outputJson($data);
        }

        $vday = strtotime(date('Y-m-d',time())) + 86400;
        if(strtotime($day) < $vday){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '开始日期需要超过次日',
            ];
            Lib::outputJson($data);
        }

        $rs = $this->m->getCreditCard($user_id, $card_id);
        $endrepaymentDay = $rs['end_repayment_day'];
        if($endrepaymentDay){
            if(strtotime($endrepaymentDay) < strtotime($day)){
                $data = [
                    'status' => 'fail',
                    'code' => 10011,
                    'msg' => '开始日期不能大于结束日期',
                ];
                Lib::outputJson($data);
            }
        }

        $ret = $this->m->editCreditStartDay($card_id,$day,$user_id);
        if($ret >= 0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '修改开始还款日成功',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '修改开始还款日失败',
                'data' => $ret,
                'debug' => [$card_id,$day,$user_id]
            ];
            Lib::outputJson($data);
        }
    }

    public function editEndDay(){
        $user_id = $this->headers['UID'];
        if(!$user_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'user_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $card_id = Lib::post('card_id');
        if(!$card_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'card_id不能为空',
            ];
            Lib::outputJson($data);
        }

        $repaymentDay = Lib::post('day');
        if(!$repaymentDay){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'day不能为空',
            ];
            Lib::outputJson($data);
        }

        $rs = $this->m->getCreditCard($user_id, $card_id);
        $startrepaymentDay = $rs['start_repayment_day'];
        if(!$startrepaymentDay){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '请先设置开始日期',
            ];
            Lib::outputJson($data);
        }

        if(strtotime($repaymentDay) < (strtotime($startrepaymentDay) + 86400 * 2)){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '开始日期和结束日期间隔要大于2天',
            ];
            Lib::outputJson($data);
        }

        $ret = $this->m->editCreditEndDay($card_id,$repaymentDay,$user_id);
        if($ret >= 0){
            $data = [
                'status' => 'success',
                'code' => 10000,
                'msg' => '修改结束还款日成功',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }else{
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '修改结束还款日失败',
                'data' => $ret
            ];
            Lib::outputJson($data);
        }
    }

}