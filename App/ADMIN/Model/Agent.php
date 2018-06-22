<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/19
 * Time: 15:03
 */

namespace App\ADMIN\Model;

use Core\DB\DBQ;
use Core\Base\Model;
use Core\Extend\Dwz;
use Core\Lib;
use Exception;

class Agent extends Model
{
    public $picary;
    public function getList($pageArr = null,$condition = null,$agent_id=null){
	    $sql = "SELECT A.*,B.total_commission,B.invite_code_num,(select nickname from dzz_agent where id = A.pid) AS pname FROM dzz_agent A LEFT JOIN dzz_agent_ext B ON A.id = B.agent_id" . $condition;

	    $data = DBQ::origPage($pageArr,$sql);
        if(!empty($agent_id)){
            $result = $this->db->query("SELECT A.*,B.total_commission,B.invite_code_num,(select nickname from dzz_agent where id = A.pid) AS pname FROM dzz_agent A LEFT JOIN dzz_agent_ext B ON A.id = B.agent_id WHERE A.pid=".$agent_id);

            $result->setFetchMode(\PDO::FETCH_ASSOC);
            $agent_last =  $result->fetchAll();

           $data['list']= array_merge($data['list'],$agent_last);
        }
        return $data;
    }


    public function add($data)
    {
        $this->db->pdo->beginTransaction();

        try {
            $this->db->insert("agent",$data);
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
                    //"total_commission" => Lib::post ( 'total_commission' ),
                    "invite_code_num" => Lib::post ( 'invite_code_num' ),
                ]);
                if ($ext === false)
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


    public function edit($data,$id)
    {
        $this->db->pdo->beginTransaction();

        try {
            $upd = $this->db->update("agent",$data,['id'=>$id]);
            if (!$upd )
            {
                $this->db->pdo->rollBack();
                return false;
            }
            else
            {
                $ext = $this->db->update("agent_ext", [
                    "total_commission" => Lib::post ( 'total_commission' ),
                    "invite_code_num" => Lib::post ( 'invite_code_num' ),
                ],['agent_id'=>$id]);
                if ($ext === false)
                {

                    $this->db->pdo->rollBack();
                    return false;
                }
                else
                {

                    $this->db->pdo->commit();
                }
            };
        }
        catch (Exception $e) {
            $this->db->pdo->rollBack();
            throw $e;
            return false;
        }
        return true;
    }



    public function del($id = 0)
    {
        if ($this->has('agent',[
            'pid' => $id
        ])) {
            Dwz::err('该代理下还有下级代理，请先删除下级代理');
            return false;
        }  else {
                $status = DBQ::getOne('agent','status',['id'=>$id]);
                if ($status === '-1')
                {
                    Dwz::err('该代理已经被删除，处于禁用状态');
                    return false;
                } else{
                    $this->update('agent',['status'=>'-1'],['id'=>$id]);
                    return true;
                }
        }
    }

    /**
     * @param $pid
     * @return bool
     * 获取自身代理级别
     */
    public function getSelfAgentLevel($pid){
        if(empty($pid) && !is_numeric($pid))return false;
        $agentInfo = DBQ::getOne('agent', '*', [
            'id' => $pid
        ]);
        if(!empty($agentInfo)){
            return $agentInfo['level'] + 1;
        }
        return false;
    }

    /**
     * @param $id
     * 查询代理信息
     */
    public function getAgentOne($pid){
        if(!empty($pid) && !is_numeric($pid))return false;
        $agentInfo = DBQ::getOne('agent', '*', [
            'id' => $pid
        ]);
        if(!empty($agentInfo)){
            return $agentInfo;
        }
        return false;
    }
    /**
     * @param $id
     * 查询代理信息
     */
    public function findAgentId($pid){
        $agentarry=DBQ::getAll('agent','*',['pid'=>$pid]);
        foreach ($agentarry as $k=>$v){
            $this->picary[]=$v['id'];
            $this->findAgentId($v['id']);
        }
    }
    public function findAgent($id){
        $this->findAgentId($id);
        return $this->picary;
    }

}