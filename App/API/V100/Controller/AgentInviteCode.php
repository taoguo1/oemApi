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

class AgentInviteCode extends Controller
{

    public $m;
    public $headers;
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->headers = Lib::getAllHeaders();
        $model = "\\App\API\\".$this->headers['VERSION']."\\Model\\AgentInviteCode";
        $this->m = new $model;
    }


    public function myList(){
        $agent_id = $this->headers['UID'];
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'UID不能为空',
            ];
            Lib::outputJson($data);
        }

        $condition['AND']['P.agent_id'] = $agent_id;
        $pageArr = Lib::setPagePars ();
        $datalist = $this->m->getList($pageArr, $condition);
        $tData = [];
        foreach($datalist['list'] as $k => $v){
            $v['create_time_str'] = Lib::UDate('Y-m-d H:i');
            $tData[] = $v;
        }
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取邀请码成功',
            'data' => $tData
        ];
        Lib::outputJson($data);
    }

    public function myListUnused(){
        $agent_id = $this->headers['UID'];
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'UID不能为空',
            ];
            Lib::outputJson($data);
        }
        //判断是否为系统发邀请码
//        if($this->M()->getSys($agent_id)) {
//            $condition['AND']['P.status'] = [1,2];
//
//        } else {
        $condition['AND']['P.status'] = [1,2];
//        }
        $condition['AND']['P.agent_id'] = $agent_id;
        $pageArr = Lib::setPagePars ();
        $pageArr['numPerPage'] = 10;
        $datalist = $this->m->getList($pageArr, $condition);
        $tData = [];
        foreach($datalist['list'] as $k => $v){
            $v['code'] = $v['code'].md5(time());
            $v['create_time_str'] = Lib::uDate('Y-m-d H:i',$v['create_time']);
            $tData[] = $v;
        }
        $datalist['list'] = $tData;
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取邀请码成功',
            'data' => $datalist
        ];
        Lib::outputJson($data);
    }

    public function myListUsed(){
        $agent_id = $this->headers['UID'];
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'UID不能为空',
            ];
            Lib::outputJson($data);
        }

        $condition['AND']['P.after_agent_id'] = $agent_id;
        $condition['AND']['P.status'] = 3;
        $pageArr = Lib::setPagePars ();
        $pageArr['numPerPage'] = 10;
        $datalist = $this->m->getListS($pageArr, $condition);
        $tData = [];
        foreach($datalist['list'] as $k => $v){
            $v['code'] = $v['code'].md5(time());
            $v['create_time_str'] = Lib::uDate("Y-m-d H:i",$v['use_time']);
            $tData[] = $v;
        }
        $datalist['list'] = $tData;
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取邀请码成功',
            'data' => $datalist
        ];
        Lib::outputJson($data);
    }

    public function tradeSum(){
        $agent_id = $this->headers['UID'];
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'UID不能为空',
            ];
            Lib::outputJson($data);
        }
        $sum = $this->m->getSum($agent_id);
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取邀请码交易数量成功',
            'data' => $sum
        ];
        Lib::outputJson($data);
    }

    public function getBoughtList(){
        $agent_id = $this->headers['UID'];
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'UID不能为空',
            ];
            Lib::outputJson($data);
        }
        $data = $this->m->getBought($agent_id);
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取已买邀请码成功',
            'data' => $data
        ];
        Lib::outputJson($data);
    }

    public function getSoldList(){
        $agent_id = $this->headers['UID'];
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'UID不能为空',
            ];
            Lib::outputJson($data);
        }
        $data = $this->m->getSold($agent_id);
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取已卖邀请码成功',
            'data' => $data
        ];
        Lib::outputJson($data);
    }

    public function getBoughtListGrp(){
        $agent_id = $this->headers['UID'];
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'UID不能为空',
            ];
            Lib::outputJson($data);
        }
        $datalist = $this->m->getBoughtGrp($agent_id);
        $dataAll = [];

        foreach($datalist as $k => $val){
            //$val['trade_time_str'] = date('Y-m-d H:i',$val['trade_time']);
            if( $val['before_agent_id']==0 ) {
                $dataAll[] = ['desc'=>'系统拨码','sum'=>$val['volume'],'trade_time_str'=>Lib::UDate('Y-m-d H:i',$val['trade_time'])];
            } else {
                $dataAll[] = ['desc'=>$val['nickname'].'拨码','sum'=>$val['volume'],'trade_time_str'=>Lib::UDate('Y-m-d H:i',$val['trade_time'])];
            }
        }
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取已买记录成功',
            'data' => $dataAll
        ];
        Lib::outputJson($data);
    }

    public function getSoldListGrp(){
        $agent_id = $this->headers['UID'];
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => 'UID不能为空',
            ];
            Lib::outputJson($data);
        }
        $datalist = $this->m->getSoldGrp($agent_id);
        $dataAll = [];
        foreach($datalist as $k => $val){
            //$val['trade_time_str'] = date('Y-m-d H:i',$val['trade_time']);
            if( $val['before_agent_id']==0 ) {
                $dataAll[] = ['desc'=>'系统拨码','sum'=>$val['volume'],'trade_time_str'=>Lib::UDate('Y-m-d H:i',$val['trade_time'])];
            } else {
                $dataAll[] = ['desc'=>"给".$val['nickname'].'拨码','sum'=>$val['volume'],'trade_time_str'=>Lib::UDate('Y-m-d H:i',$val['trade_time'])];
            }
        }
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取已卖记录成功',
            'data' => $dataAll
        ];
        Lib::outputJson($data);
    }

}