<?php
namespace App\API\V100\Model;
use Core\Base\Model;
use Core\DB\DBQ;

class AgentInviteCode extends Model
{


    public function getList($pageArr = null, $condition = null){
        $data = DBQ::pages($pageArr, 'invite_code (P)', [
            '[>]agent (U)' => [
                'P.agent_id' => 'id'
            ]
        ], [
            'P.id',
            'U.real_name',
            'P.code',
            'P.status',
            'P.create_time'
        ], $condition);

        return $data;
    }

    public function getSum($agent_id){
        $bought_sum = DBQ::getCount('invite_code_trade','*',['after_agent_id'=>$agent_id]);
        $sold_sum = DBQ::getCount('invite_code_trade','*',['before_agent_id'=>$agent_id]);
        return ['bought'=>$bought_sum,'sold'=>$sold_sum];
    }

    public function getBought($agent_id){
        $bought = DBQ::getAll('invite_code_trade','*',['after_agent_id'=>$agent_id]);
        return $bought;
    }

    public function getSold($agent_id){
        $sold = DBQ::getAll('invite_code_trade','*',['before_agent_id'=>$agent_id]);
        return $sold;
    }

    public function getBoughtGrp( $agent_id ){

        $bought = DBQ::getAll('invite_code_trade(A)',[
                "[>]agent(B)"=>["A.before_agent_id" => "id"]
        ],[
                "A.before_agent_id",
                "A.code",
                "A.trade_time",
                "A.volume",
                "B.nickname"
        ],['GROUP'=>['A.trade_time'],'A.after_agent_id'=>$agent_id,"ORDER"=>["A.trade_time"=>"DESC"]]);

//        $bought = DBQ::getAll('invite_code_trade','*',['GROUP'=>['trade_time'],'after_agent_id'=>$agent_id]);
        return $bought;
    }

    public function getSoldGrp($agent_id){

//        $sold = DBQ::getAll('invite_code_trade','*',['GROUP'=>['volume','trade_time'],'before_agent_id'=>$agent_id]);
        $sold = DBQ::getAll('invite_code_trade(A)',[
            "[>]agent(B)"=>["A.after_agent_id" => "id"]
        ],[
            "A.before_agent_id",
            "A.code",
            "A.trade_time",
            "A.volume",
            "B.nickname"
        ],['GROUP'=>['A.trade_time'],'A.before_agent_id'=>$agent_id,"ORDER"=>['A.trade_time'=>'DESC']]);
        return $sold;
    }

    /**
     * 判断是否为系统邀请码
     */
    public function getSys($agent_id) {
        $result = DBQ::getRow("invite_code_trade","*",[
            "after_agent_id" => $agent_id,
            'before_agent_id'=>0,
        ]);
        if($result)
            return true;
        return false;

    }

    public function getListS($pageArr = null, $condition = null){
        $data = DBQ::pages($pageArr, 'invite_code_trade (P)', [
            '[>]agent (U)' => [
                'P.after_agent_id' => 'id'
            ],

        ], [
            'P.code_id (id)',
            'U.real_name',
            'P.code',
            'P.status',
            'P.use_time'
        ], $condition);
//        var_dump(DBQ::last());die;

        return $data;
    }



}