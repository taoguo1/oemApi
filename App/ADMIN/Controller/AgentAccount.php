<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/18
 * Time: 11:56
 */

/****
 * @name代理账户
 *
 *
 */

namespace App\ADMIN\Controller;

use\Core\DB\DBQ;
use Core\Base\Controller;
use Core\Lib;
use Core\Extend\Dwz;

class AgentAccount extends Controller
{
    /**
     *
     * @name 代理账户查询
     */
    public function index()
    {
        $agent_id = Lib::request('agent_agent_id');
        $nickname = Lib::request('agent_agent_name');
        $order_sn = Lib::request('order_sn');
        $channel = Lib::request('channel');
        $type = Lib::request('type');
        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');
        $start_amount = Lib::request('start_amount');
        $end_amount = Lib::request('end_amount');

        $condition = null;
        ($agent_id) ? $condition['AND']['A.agent_id'] = $agent_id : null;
        ($order_sn) ? $condition['AND']['A.order_sn'] = $order_sn : null;
        ($channel) ? $condition['AND']['A.channel'] = $channel : null;
        ($type) ? $condition['AND']['A.type'] = $type : null;
        ($start_date) ? $condition ['AND'] ['A.create_time[>=]'] =  (strtotime($start_date))*1000 : null;
        ($end_date) ? $condition ['AND'] ['A.create_time[<=]'] = (strtotime($end_date))*1000 : null;
        ($start_amount) ? $condition ['AND'] ['A.amount[>=]'] =  $start_amount : null;
        ($end_amount) ? $condition ['AND'] ['A.amount[<=]'] = $end_amount : null;

        $condition ['ORDER'] = [
            'A.create_time' => 'DESC'
        ];
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
     * @name 代理账户添加
     */
    public function add($act = null)
    {
        if ($act == 'add') {
            $data = [
                'agent_id' => Lib::post('agent_agent_id'),
                'amount' => Lib::post('amount'),
                'order_sn' => Lib::post('order_sn'),
                'description' => Lib::post('description'),
                'in_type' => Lib::post('in_type'),
                'channel' => Lib::post('channel'),
                'create_time' => Lib::getMs(),
                ];
            $insertId = $this->M()->add($data);
            if ($insertId) {
                Dwz::successDialog($this->M()->modelName, '', 'closeCurrent');
            }
        }
        $this->view();
    }



    /***
     * 编辑
     *
     */
    public function edit($id = 0, $act = null)
    {
        if ($act == 'edit') {
            $data = [
                'agent_id' => Lib::post('agent_agent_id'),
                'amount' => Lib::post('amount'),
                'order_sn' => Lib::post('order_sn'),
                'description' => Lib::post('description'),
                'in_type' => Lib::post('in_type'),
                'channel' => Lib::post('channel'),
            ];
            $insertId = $this->M()->edit($data,$id);
            if ($insertId) {
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }

        $list = DBQ::getRow('agent_account', '*', ['id' => $id]);
        $agentName = DBQ::getRow('agent', 'nickname',['id' => $list['agent_id']]);
        $this->assign('agentName', $agentName);
        $this->assign('list', $list);
        $this->view();
    }


    /****
     *
     * 删除
     */
    public function del($id = 0)
    {
        $del = DBQ::del('agent_account', [
            'id' => $id
        ]);
        if ($del) {
            Dwz::success(Lib::getUrl($this->M()->modelName), $this->M()->modelName);
        } else {
            DWZ::err();
        }
    }
}