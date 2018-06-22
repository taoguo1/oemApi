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

class AgentBill extends Controller
{
    public $m;
    public $headers;
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->headers = Lib::getAllHeaders();
        $model = "\\App\API\\".$this->headers['VERSION']."\\Model\\AgentBill";
        $this->m = new $model;
    }

    public function index(){
        $agent_id = $this->headers['UID'];
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '代理UID不能为空',
            ];
            Lib::outputJson($data);
        }
        $condition['AND']['A.agent_id'] = $agent_id;
        $condition ['ORDER'] = [
            'A.id' => 'DESC'
        ];
        $numPerPage = Lib::post('numPerPage');
        $pageArr = Lib::setPagePars ();
        $pageArr['numPerPage'] = $numPerPage ? $numPerPage : 10;
        $datalist = $this->m->getList($pageArr, $condition);
        $dataAll = [];
        foreach($datalist['list'] as $k => $v){
            $v['create_time_str'] = Lib::uDate('m-d H:i',$v['create_time']);
            $v['name'] = Lib::starReplaceName($v['real_name']);
            $v['mobile'] = substr($v['mobile'],0,3).'****'.substr($v['mobile'],7);
            $dataAll[] = $v;
        }
        $datalist['list'] = $dataAll;
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取最新账单成功',
            'data' => $datalist
        ];
        Lib::outputJson($data);
    }


    public function getRepayment(){
        $agent_id = $this->headers['UID'];
        if(!$agent_id){
            $data = [
                'status' => 'fail',
                'code' => 10011,
                'msg' => '代理UID不能为空',
            ];
            Lib::outputJson($data);
        }

        $datalist = $this->m->getRepaymentList($agent_id,3);
        $dataAll = [];
        foreach($datalist as $k => $v){
            $v['create_time_str'] = Lib::uDate('Y-m-d H:i',$v['create_time']);
            $v['card_no_last4'] = substr(Lib::aesDecrypt($v['card_no']),-4);
            $dataAll[] = $v;
        }
        $data = [
            'status' => 'success',
            'code' => 10000,
            'msg' => '获取提现明细成功',
            'data' => $dataAll
        ];
        Lib::outputJson($data);
    }


}