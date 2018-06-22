<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class User extends Model
{   
    //获取用户信息
    public function getUser($condition){
        $data = DBQ::getAll('user','*',$condition);
        return $data;
    }
    /**
     * 获取用户列表数据
     * @param null $pageArr
     * @param null $condition
     * @return array
     */
    public function getUserList($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
//        $data = DBQ::pages($pageArr, 'user(U)', [
//            '[>]agent(A)' => [
//                'U.agent_id' => 'id'
//            ],
//            '[>]user_ext(UE)' => [
//                'U.id' => 'user_id'
//            ]
//
//        ], [
//            'U.id',
//            'A.nickname(agentnickname)',
//            'A.real_name(agentname)',
//            'U.real_name',
//            'U.id_card',
//            'U.sex',
//            'U.mobile',
//            'U.avatar',
//            'UE.balance',
//            'UE.invite_code',
//            'U.is_id_card_auth',
//            'U.is_push',
//            'U.status',
//            'U.create_time',
//        ], $condition);
        $sql = "SELECT
sum(`dzz_UC`.amount) AS balance,
	`dzz_U`.`id`,
	`dzz_A`.`nickname` AS `agentnickname`,
	`dzz_A`.`real_name` AS `agentname`,
	`dzz_A`.`mobile` AS `agentmobile`,
	`dzz_U`.`real_name`,
	`dzz_U`.`id_card`,
	`dzz_U`.`sex`,
	`dzz_U`.`mobile`,
	`dzz_U`.`avatar`,
	`dzz_UE`.`invite_code`,
	`dzz_U`.`is_id_card_auth`,
	`dzz_U`.`is_push`,
	`dzz_U`.`status`,
	`dzz_U`.`create_time`,
	(
		SELECT
			create_time
		FROM
			`dzz_user_login_log` AS `dzz_L` WHERE `dzz_U`.`id` = `dzz_L`.`user_id`
		ORDER BY
			`dzz_L`.`id`
		LIMIT 1
		
	) AS last_time
FROM
	`dzz_user` AS `dzz_U`
LEFT JOIN `dzz_agent` AS `dzz_A` ON `dzz_U`.`agent_id` = `dzz_A`.`id`
LEFT JOIN `dzz_user_ext` AS `dzz_UE` ON `dzz_U`.`id` = `dzz_UE`.`user_id`
LEFT  JOIN  `dzz_user_account` AS `dzz_UC` ON `dzz_U`.id = `dzz_UC`.user_id
WHERE $condition GROUP BY `dzz_U`.id";
        $data = DBQ::origPage($pageArr,$sql);
        return $data;
    }

    /**
     * @param $id
     * 删除用户相关数据
     */
    public function delUserRelatedData($statusData ,$id){
        if(empty($id) && !is_numeric($id))return false;
        $result = DBQ::upd('user',$statusData, ['id' => $id ]);
        return $result;
    }

    /**
     * @param $condition
     * @return array|bool
     * 获取用户信息
     */
    public function getUserInfoRow($condition){
        if(empty($condition))return false;
        $data = DBQ::getRow('user(U)', [
            '[>]agent(A)' => [
                'U.agent_id' => 'id'
            ],
            '[>]user_ext(UE)' => [
                'U.id' => 'user_id'
            ]

        ], [
            'U.id',
            'A.nickname(agentname)',
            'U.real_name',
            'U.id_card',
            'U.sex',
            'U.mobile',
            'U.avatar',
            'U.agent_id',
            'U.password',
            'U.pay_password',
            'UE.balance',
            'UE.invite_code',
            'U.is_id_card_auth',
            'U.is_push',
            'U.status',
            'U.create_time',
        ],$condition);

        return $data;
    }

    /**
     * @param null $pageArr
     * @param null $condition
     * @return array
     * 获取代理信息
     */
    public function getAgentList($pageArr = null, $condition = null){
            $sql = "SELECT
                        A.*,(
                            SELECT
                                real_name
                            FROM
                                dzz_agent
                            WHERE
                                id = A.pid
                        ) AS pname
                    FROM
                        dzz_agent AS A
                    WHERE $condition
                    ";
            $data = DBQ::origPage($pageArr,$sql);
            return $data;
    }

    /**
     * 添加用户数据
     * @param $userData
     * @param $userDataExt
     */
    public function addUserData($userData,$userDataExt){
        if(!empty($userData)){
            $this->db->insert('user',$userData);
            if(!empty($userDataExt)){
                $userDataExt['user_id'] = $this->db->id();
                $this->db->insert('user_ext',$userDataExt);
            }
            return $this->db->id();
        }
        return false;
    }

    /**
     * @param $userData
     * @param $userDataExt
     * 修改用户信息
     */
    public function editUserData($userData,$userDataExt,$id){
        if(empty($id) || !is_numeric($id))return false;
        if(!empty($userData)){
            $result = DBQ::upd('user',$userData,['id'=>$id]);
        }
        if(!empty($userDataExt)){
            $result1 = DBQ::upd('user_ext',$userDataExt,['user_id'=>$id]);
        }
        if($result || $result1) {

            return true;
        } else {
            return false;
        }
    }

}