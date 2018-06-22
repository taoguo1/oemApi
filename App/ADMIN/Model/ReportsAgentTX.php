<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/7
 * Time: 10:14
 */
namespace App\ADMIN\Model;
use Core\Base\Model;
use Core\DB\DBQ;
use Core\Lib;

class ReportsAgentTX extends Model
{
    public function getList($type=null, $condition = null,$year=null,$month=null)
    {
        if(empty($month)) {
 
               $sql = "SELECT
                    sum(amount) AS regnum,
                    FROM_UNIXTIME(
                        `create_time` / 1000,
                        '%Y-%m'
                    ) AS shijian
                FROM
                    dzz_agent_account
                WHERE
                   ".$condition."
                GROUP BY
                    FROM_UNIXTIME(
                        `create_time` / 1000,
                        '%Y-%m'
                    )";


        } else {
             $sql = "SELECT
                     sum(amount) AS regnum,
                    FROM_UNIXTIME(
                        `create_time` / 1000,
                        '%m-%d'
                    ) AS shijian
                FROM
                    dzz_agent_account
                WHERE
                   ".$condition."
                GROUP BY
                    FROM_UNIXTIME(
                        `create_time` / 1000,
                        '%Y-%m-%d %H'
                    )";
        }

        $result = $this->db->query($sql);
		
        $result->setFetchMode(\PDO::FETCH_ASSOC);
        $dataall =  $result->fetchAll();

		$data=[];
		
		if(empty($month)) {
			for($j = 1;$j <= 12;$j++){
				$data[$j]=0;
				foreach($dataall as $k=>$v){
					$dateary=explode('-',$v['shijian']);

						if($j==intval($dateary[1])){
							$data[$j]+=$v['regnum'];
						}	
				}
				
			}
				
			
		}else{

			for($j = 1;$j <= Lib::getMonthLastDay($year,$month);$j++){
				$data[$j]=0;
				foreach($dataall as $k=>$v){
					$dateary=explode('-',$v['shijian']);
					if($j==intval($dateary[1])){
						$data[$j]+=$v['regnum'];
					}
				}					
					
			}
				
				
		}
		$data=array_values($data);
        return $data;
    }



}
