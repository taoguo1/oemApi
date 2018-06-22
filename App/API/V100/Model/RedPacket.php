<?php
namespace App\API\V100\Model;
use App\API\V100\Model\Base;
use Core\DB\DBQ;
use Core\Lib;

class RedPacket extends Base
{
    public function inse($money,$user_id){
    	$data= DBQ::add("redpacket", [	
    			'money'=>$money,
    			'user_id' =>$user_id,
    			'create_time'=>Lib::getMs()
    	]);
    	return $data;
    }
    
    public function getlist($pageArr = null, $condition = null){
    	$data= DBQ::pages($pageArr,'redpacket (A)', [
            '[>]user (B)' => [
                'A.user_id' => 'id']
            ],[
    			"A.money",
    			"A.user_id",
    			"A.create_time"
    	],$condition);
    	return $data;
    }
    public function getalla($user_id,$starttime){
    	$data= DBQ::getAll("redpacket", [
    			'money',
    			'user_id',
    			'create_time'
    	],['user_id'=>$user_id,'create_time[>=]'=>$starttime*1000,'create_time[<=]'=>($starttime+86400)*1000]);
    	return $data;
    }
    public function gettotol($user_id){
    	$data= DBQ::getSum("redpacket", [
    			'money',
    			
    	],['user_id'=>$user_id]);
    	return $data;
    }
    
 }
    