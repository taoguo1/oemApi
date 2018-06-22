<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/7
 * Time: 10:13
 */

namespace App\ADMIN\Controller;


use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;
use Core\Extend\Dwz;

class InviteCodeTrade extends Controller
{

    /**
     *
     * @name 邀请码交易列表
     */
    public function index()
    {
        $code = Lib::request('code');
        $before_name = Lib::request('nickname');
        $after_name = Lib::request('after_name');
        $start_date = Lib::request('start_date');
        $end_date = Lib::request('end_date');

        $condition = " WHERE 1";
        if ($code){
            $condition .= " and code = '".$code."'";
        }
        if ($before_name){
            $condition .= " and B.nickname = '".$before_name."'";
        }
        if ($after_name){
            $condition .= " and C.nickname = '".$after_name."'";
        }
        if (!empty( $start_date ) && empty( $end_date )){
            $condition .= " and A.trade_time >= " . strtotime($start_date." 00:00:00")."000";
        }
        if (empty( $start_date ) && !empty( $end_date )){
            $condition .= " and A.trade_time <= " . strtotime($end_date." 23:59:59")."999";
        }
        if (!empty( $start_date ) && !empty( $end_date )){
            $condition .= " and A.trade_time  >=".strtotime($start_date." 00:00:00")."000"." and  A.trade_time <=".strtotime($end_date." 23:59:59")."999";
        }

//$condition .= " ORDER BY `A.id` DESC";
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
     * @name 邀请码交易
     */
    public function trade($act = null)
    {
        if ($act == 'trade') {
            $volume = Lib::post('volume');
            $beforeAgentID = Lib::post('before_agent_agent_id');
            $afterAgentID = Lib::post('after_agent_agent_id');
            $beforeVol = DBQ::getOne('agent_ext','invite_code_num',['agent_id'=>$beforeAgentID]);
            $afterVol = DBQ::getOne('agent_ext','invite_code_num',['agent_id'=>$afterAgentID]);
            $beforeLevel = DBQ::getOne('agent','level',['id'=>$beforeAgentID]);
            $afterLevel = DBQ::getOne('agent','level',['id'=>$afterAgentID]);

            if ($beforeLevel === $afterLevel)
            {
                Dwz::err('同级代理之间就不用分配了啦');
                return false;
            }else{
                if ($beforeLevel > $afterLevel)
                {
                    Dwz::err('下级代理不能分配邀请码给上级代理呀');
                    return false;
                }
            }

            if ($volume > $beforeVol || $beforeVol <= '0')
            {
                Dwz::err('上级代理所剩邀请码数量不足分配，先去生成一些吧');
                return false;
            }

            if ($this->M()->trade()) {
                DBQ::upd('agent_ext',['invite_code_num'=>$beforeVol-$volume],['agent_id'=>$beforeAgentID]);
                DBQ::upd('agent_ext',['invite_code_num'=>$afterVol+$volume],['agent_id'=>$afterAgentID]);
                Dwz::successDialog ( $this->M ()->modelName, '', 'closeCurrent' );
            } else {
                Dwz::err ();
            }
        }
        $this->view();
    }
}