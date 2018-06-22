<?php
namespace App\API\V100\Model;
use Core\Base\Model;
use Core\DB\DBQ;
use Exception;
use Core\Lib;

class AgentHierarchy extends Model
{
    public function getList($pageArr = null,$condition=null){
        $data = DBQ::pages($pageArr,'agent(A)',[
            '[>]agent_ext (E)' => [
                'A.id' => 'agent_id'
            ]
        ] ,[
            "A.id",
            "A.mobile",
            "A.nickname",
            "A.real_name",
            "A.rate",
            "A.skrate",
            "A.create_time",
        ],$condition);
        return $data;
    }


    public function getProfit($agent_id){
        $data = $this->sum('agent_account',['amount'],['agent_id'=>$agent_id]);
        return $data;
    }


    public function getUserList($pageArr = null,$condition=null){

        $data = DBQ::pages($pageArr,'user(U)',[
            '[>]agent (A)' => [
                'U.agent_id' => 'id'
            ]
        ], [
            "U.id",
            "U.mobile",
            "U.real_name",
            "U.create_time",
        ],$condition);
        return $data;
    }

    public function getUserExists($mobile){
        $data = DBQ::getRow('agent', [
            "id",
            "mobile",
        ],[
                "mobile" => $mobile
            ]
        );
        return $data;
    }

    public function getUser($uid){
        $data = DBQ::getRow('agent', [
            "id",
            "mobile",
            "rate",
            "skrate",
        ],[
                "id" => $uid
            ]
        );
        return $data;
    }

    /**
     * @param $insertData 添加代理的信息
     * @param $num //邀请码下发数量
     * @return bool
     * @throws Exception
     */
    public function addAgent($insertData,$num,$codesum,$inviteCodeId){
        //用事务
        //$ret = DBQ::add('agent',$insertData);
        //return $ret;
        $this->db->pdo->beginTransaction();
        try {
            $this->db->insert("agent",$insertData);
            $agent_id = $this->db->id();
            if (!$agent_id)
            {
                $this->db->pdo->rollBack();
                return false;
            }
            else
            {
                $ext = $this->db->insert("agent_ext", [
                    "agent_id" => $agent_id,
                    "invite_code_num" => $num,// 附表下发邀请码数量
                ]);

                //修改 上级代理邀请码

                $levelAgent = $this->db->update("agent_ext",[
                    "invite_code_num"=>$codesum - $num,
                ],["agent_id"=>$insertData['pid']]);

                //查找前代理 $num 条邀请码
                $beforeAgent =  DBQ::getAll("invite_code",[
                    "id(code_id)",
                    "code",
                    "agent_id(before_agent_id)",
                ],[
                    "agent_id"=>$insertData['pid'],
                    "status"=>[1,2],
                    "id"=>$inviteCodeId
                ]);

//                需要插入的字段
                //更改插入时间
                $ms = Lib::getMs();
                foreach ($beforeAgent as &$v) {
                    $v['after_agent_id'] = $agent_id;
                    $v['trade_time'] = $ms;
                    $v['volume'] = $num;
                    $v['status'] = 2;
                }



                $arr = array_column($beforeAgent, 'code_id');

                //吧查出来的邀请码 插入 invite_code_trade 库
                $result = DBQ::insert("invite_code_trade",$beforeAgent);

                //修改邀请码的状态2已下发
                $resupt = DBQ::upd("invite_code",["status"=>2,"agent_id"=>$agent_id],[
                    "id"=> $arr
                ]);

                $resupt1 = DBQ::upd("invite_code_trade",["status"=>2],[
                    "code_id"=> $arr
                ]);



                if ($ext === false && $levelAgent === false && $result === false && $resupt === false && $resupt1 === false)
                {
                    $this->db->pdo->rollBack();
                    return false;
                }
                else
                {
                    $this->db->pdo->commit();
                }
            }
        }
        catch (Exception $e) {
            $this->db->pdo->rollBack();
            throw $e;
            return false;
        }
        return true;
    }

    public function editAgentRate($rate,$agent_id){
        $count = $this->count('agent',['id'],['id'=>$agent_id]);
        if($count){
            $ret = $this->update('agent',['rate'=>$rate],['id'=>$agent_id]);
            return $ret;
        }else{
            return -1;
        }

    }

    public function editAgentSkRate($skrate,$agent_id){
        $count = $this->count('agent',['id'],['id'=>$agent_id]);
        if($count){
            $ret = $this->update('agent',['skrate'=>$skrate],['id'=>$agent_id]);
            return $ret;
        }else{
            return -1;
        }

    }

    public function editTrade($uid,$agent_id,$inviteCodeId,$num){

        //获取邀请码数量·
        $arr = DBQ::getAll('invite_code', [
            "id",
            "code",
            "status",
        ],[
            "agent_id" => $uid,
            "status[!]" => 3,
            "id" => $inviteCodeId
        ]);
//            var_dump([$uid,$agent_id,$num,$inviteCodeId]);die;
        if(count($arr) < $num){
            Lib::outputJson([
                'status' => 'fail',
                'code' => 10011,
                'msg' => '您的邀请码数量不足!',
            ]);
        }

        //print_r($arr);exit;
        //var_dump($this->db->last());
        if(count($arr) == $num){
            $this->db->pdo->beginTransaction();
            try {
                //构造需要插入的数据
                $ms = Lib::getMs();
                $multData =[];
                $ids = [];
                foreach($arr as $v){
                    $multData[] = [
                        'code_id' => $v['id'],
                        'before_agent_id' => $uid,
                        'after_agent_id' => $agent_id,
                        'code' => $v['code'],
                        'trade_time' => $ms,
                        'volume' => $num,
                        'status'=>2
                    ];
                    $ids[] = $v['id'];
                }
                $tradeInsertRet = $this->db->insert('invite_code_trade',$multData );


                if (!$tradeInsertRet)
                {
                    $this->db->pdo->rollBack();
                    return false;
                }
                else
                {
                    $arr = array_column($multData, 'code_id');
                    $updateRet = $this->db->update('invite_code',['agent_id'=>$agent_id,'status'=>2],['id'=>$arr]);

                    //修改上级代理邀请码数量数量
                    $codesum = $this->getCodeSums($uid);

                    $levelAgent = $this->db->update("agent_ext",[
                        "invite_code_num"=>$codesum - $num
                    ],["agent_id"=>$uid]);

                    $codesum1 = $this->getCodeSums($agent_id);
                    
                    $after = $this->db->update("agent_ext",[
                        "invite_code_num"=>$codesum1 + $num
                    ],["agent_id"=>$agent_id]);
//                    $data = [
//                        'status' => 'fail',
//                        'code' => 10011,
//                        'msg' => DBQ::last(),
//                    ];
//                    Lib::outputJson($data);
                    if (!$updateRet && !$levelAgent && !$after)
                    {
                        $this->db->pdo->rollBack();
                        return false;
                    }
                    else
                    {
                        $this->db->pdo->commit();
                    }
                }
            }
            catch (Exception $e) {
                $this->db->pdo->rollBack();
                throw $e;
                return false;
            }
            return true;
        }else{
            return false;
        }

    }

    public function getCodeSum($agent_id){
        $sum = DBQ::getCount('invite_code_trade','*',['after_agent_id'=>$agent_id]);
        return $sum;
    }

    public function getCodeSums($agent_id) {
        $sum = DBQ::getCount('invite_code','*',['agent_id'=>$agent_id,'status[!]'=>3]);
        return $sum;
    }

    public function getCodes($agent_id,$inviteCodeId)
    {
        $sum = DBQ::getCount('invite_code','*',['agent_id'=>$agent_id,'status[!]'=>3,'id'=>$inviteCodeId]);
        return $sum;

    }

    /**
     * 获取下级代理还款分润的最大比例
     */
    public function  maxRate($agent_id) {
        $maxRate = DBQ::getMax("agent","rate",[
            'pid'=>$agent_id
        ]);

        return $maxRate;
    }
    /**
     * 获取下级代理收款分润的最大比例
     */
    public function  maxSkRate($agent_id) {
        $maxRate = DBQ::getMax("agent","skrate",[
            'pid'=>$agent_id
        ]);

        return $maxRate;
    }
    /**
     * 获取此代理的级别
     */
    public function  getLevel($agent_id) {
        $level = DBQ::getRow("agent",'level',[
            'id'=>$agent_id
        ]);
        return $level;
    }

    /**
     * 获取前台传来的邀请码
     */

    public function getInviteCode($startId=null,$endId=null,$uid){
        $rowInviteCode = DBQ::getAll("invite_code","*",[
            "status[!]"=> 3,
            "id[<>]" => [$startId,$endId],
            "agent_id"=>$uid,
        ]);

        return $rowInviteCode;
    }

    /**
     * @param $uid
     * @param $agent_id
     * @param $num
     * @return bool
     * @throws Exception5
     * 5.1
     */
    public function editTradeNum($uid,$agent_id,$num){

        $arr = $this->db->select('invite_code', [
            "id",
            "code",
            "status",
        ],[
            "agent_id" => $uid,
            "status[!]" => 3,
            "LIMIT" => $num
        ]);
        if(count($arr) < $num){
            Lib::outputJson([
                'status' => 'fail',
                'code' => 10011,
                'msg' => '您的邀请码数量不足！',
            ]);
        }

        //print_r($arr);exit;
        //var_dump($this->db->last());
        if(count($arr) == $num){
            $this->db->pdo->beginTransaction();
            try {
                //构造需要插入的数据
                $ms = Lib::getMs();
                $multData = $ids = [];
                foreach($arr as $v){
                    $multData[] = [
                        'code_id' => $v['id'],
                        'before_agent_id' => $uid,
                        'after_agent_id' => $agent_id,
                        'code' => $v['code'],
                        'trade_time' => $ms,
                        'volume' => $num,
                        'status'=>2
                    ];
                    $ids[] = $v['id'];
                }
                $tradeInsertRet = $this->db->insert('invite_code_trade',$multData );


                if (!$tradeInsertRet)
                {
                    $this->db->pdo->rollBack();
                    return false;
                }
                else
                {
                    $arr = array_column($multData, 'code_id');
                    $updateRet = $this->db->update('invite_code',['agent_id'=>$agent_id,'status'=>2],['id'=>$arr]);

                    //修改上级代理邀请码数量数量
                    $codesum = $this->getCodeSums($uid);
                    $levelAgent = $this->db->update("agent_ext",[
                        "invite_code_num"=>$codesum - $num
                    ],["agent_id"=>$uid]);
                    $codesum1 = $this->getCodeSums($agent_id);
                    $after = $this->db->update("agent_ext",[
                        "invite_code_num"=>$codesum1 + $num
                    ],["agent_id"=>$agent_id]);
//                    $data = [
//                        'status' => 'fail',
//                        'code' => 10011,
//                        'msg' => DBQ::last(),
//                    ];
//                    Lib::outputJson($data);
                    if (!$updateRet && !$levelAgent && !$after)
                    {
                        $this->db->pdo->rollBack();
                        return false;
                    }
                    else
                    {
                        $this->db->pdo->commit();
                    }
                }
            }
            catch (Exception $e) {
                $this->db->pdo->rollBack();
                throw $e;
                return false;
            }
            return true;
        }else{
            return false;
        }

    }

    /**
     * @param $insertData
     * @param $num
     * @param $codesum
     * @return bool
     * @throws Exception
     * 5.1
     */
    public function addAgentNum($insertData,$num,$codesum){
        //用事务
        //$ret = DBQ::add('agent',$insertData);
        //return $ret;
        $this->db->pdo->beginTransaction();
        try {
            $this->db->insert("agent",$insertData);
            $agent_id = $this->db->id();
            if (!$agent_id)
            {
                $this->db->pdo->rollBack();
                return false;
            }
            else
            {
                $ext = $this->db->insert("agent_ext", [
                    "agent_id" => $agent_id,
                    "invite_code_num" => $num,// 附表下发邀请码数量
                ]);

                //修改 上级代理邀请码

                $levelAgent = $this->db->update("agent_ext",[
                    "invite_code_num"=>$codesum - $num,
                ],["agent_id"=>$insertData['pid']]);

                //查找前代理 $num 条邀请码
                $beforeAgent =  DBQ::getAll("invite_code",[
                    "id(code_id)",
                    "code",
                    "agent_id(before_agent_id)",
                ],[
                    "agent_id"=>$insertData['pid'],
                    "status"=>[1,2],
                    "LIMIT"=>$num
                ]);

//                需要插入的字段
                //更改插入时间
                $ms = Lib::getMs();
                foreach ($beforeAgent as &$v) {
                    $v['after_agent_id'] = $agent_id;
                    $v['trade_time'] = $ms;
                    $v['volume'] = $num;
                    $v['status'] = 2;
                }



                $arr = array_column($beforeAgent, 'code_id');

                //吧查出来的邀请码 插入 invite_code_trade 库
                $result = DBQ::insert("invite_code_trade",$beforeAgent);

                //修改邀请码的状态2已下发
                $resupt = DBQ::upd("invite_code",["status"=>2,"agent_id"=>$agent_id],[
                    "id"=> $arr
                ]);

                $resupt1 = DBQ::upd("invite_code_trade",["status"=>2],[
                    "code_id"=> $arr
                ]);



                if ($ext === false && $levelAgent === false && $result === false && $resupt === false && $resupt1 === false)
                {
                    $this->db->pdo->rollBack();
                    return false;
                }
                else
                {
                    $this->db->pdo->commit();
                }
            }
        }
        catch (Exception $e) {
            $this->db->pdo->rollBack();
            throw $e;
            return false;
        }
        return true;
    }
}