<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;
use Core\Extend\Redis;
use Core\Lib;
class PlanList extends Model{
    public function getList($pageArr = null, $condition = null){
        $data = DBQ::pages($pageArr, 'plan_list (P)', [
            '[>]user (U)' => [
                'P.user_id' => 'id'
            ],
            '[>]agent(A)' => [
                'U.agent_id' => 'id'
            ]
        ], [
            'A.mobile(aMobile)',
            'A.nickname',
            'P.id',
            'U.real_name',
            'P.amount',
            'U.mobile',
            'U.id_card',
            'P.plan_id',
            'P.plan_type',
            'P.start_time',
            'P.end_time',
            'P.order_sn',
            'P.status',
            'P.create_time'
        ], $condition);
        return $data;
    }
    public function add($data){
        return DBQ::add('plan_list', $data);
    }

    public function del($id = 0){
        return DBQ::del('plan_list', [
            'id' => $id
        ]);
    }

    public function delAll($ids){
        return DBQ::del('plan_list', [
            'id' => $ids
        ]);
    }

    public function redisAll(){
        $redis = Redis::instance();
        $dataold = $redis->zRangeByScore('plan_list_ing:start_time','-inf','inf',['withscores','limit']);
        foreach($dataold as $k => $v){
            $redis->zRem('plan_list_ing:start_time',$v);
        }
        $time = strtotime("+2 day");
        $time = date('Y-m-d',$time);
        $stime = strtotime($time." 00:00:00");
        $etime = strtotime($time." 23:59:59");
        $data = DBQ::getAll('plan_list','*',['start_time[>=]'=>$stime,'start_time[<=]'=>$etime]);
        if($data) {
            foreach($data as $k => $v){
                $redis->zAdd('plan_list_ing:start_time',$v['start_time'],json_encode($v));
            }
            return 1;
        }else{
            return 0;
        }
    }

    public function inIngAll(){
        $time = strtotime("+6 day");
        $time = date('Y-m-d',$time);
        $stime = strtotime($time." 00:00:00");
        $etime = strtotime($time." 23:59:59");
        $data = DBQ::getAll('plan_list','*',['start_time[>=]'=>$stime,'start_time[<=]'=>$etime]);
        if($data) {
            $ret = DBQ::add('plan_list_ing', $data);
        }
        return $ret;
    }

    public function edit($id=0,$data=[]){   
        $planList = DBQ::getOne('plan_list','*',['id'=>$id]);
        $planListInfo = DBQ::getAll('plan_list','*',['plan_id'=>$planList['plan_id']]);
        $return=0;
        foreach($planListInfo as $k=>$v){
            //状态修改为已暂停
            if($v['id']>=$id){
               DBQ::upd('plan_list',['status'=>$data['status']],['id'=>$v['id']]);
               $return =1;
            }
        }
        return $return;
    }

    public function second($id = 0,$data = []){   
        $planList = DBQ::getOne('plan_list','*',['id'=>$id]);
        $planListInfo = DBQ::getAll('plan_list','*',['plan_id'=>$planList['plan_id']]);
        $return=0;
        $redis = Redis::instance('plan');
        foreach($planListInfo as $k=>$v){
            //状态修改为已暂停
            if($v['id']>=$id){
               //DBQ::upd('plan_list',['status'=>$data['status']],['id'=>$v['id']]);
               //$return =1;
               //echo $v['id'].':'.Lib::request('appid');
               //echo "<br>";
               $v['appid'] = Lib::request('appid');
               $redis->zAdd('rc:' . $v['task_no'], $v['start_time'], json_encode($v));
               $redis->hSet('rc_plan_data', Lib::request('appid').'_'.$v['id'], 1);
            }
        }
        return 1;
    }


}