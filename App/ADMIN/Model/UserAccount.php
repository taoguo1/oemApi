<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class UserAccount extends Model
{

	/**
	 * 获取用户账户列表
	 * @param null $pageArr
	 * @param null $condition
	 * @return array
	 */
	public function getUserAccountList($pageArr = null, $condition = null)
	{
		$data = DBQ::pages($pageArr, 'user_account(UA)', [
			'[>]user(U)' => [
				'UA.user_id' => 'id'
			],
            '[>]agent(A)' => [
                'U.agent_id' => 'id'
            ]

		], [
			'UA.id',
			'U.id',
			'U.real_name',
			'UA.user_id',
			'UA.amount',
			'UA.order_sn',
			'UA.desciption',
			'UA.in_type',
			'UA.channel',
            'A.mobile(agent_mobile)',
            'A.nickname(agent_name)',
			'UA.create_time',
		], $condition);

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
		$data = DBQ::pages($pageArr, 'user','*', $condition);
		return $data;
	}

	public function getMemberCategory()
	{
		return DBQ::getAll('user_account', '*');
	}

	public function add($data)
	{
		return DBQ::add('user_account', $data);
	}



	public function del($id= 0)
	{
		return DBQ::del('user_account', [
			'id' => $id
		]);
	}


	public function edit($id = 0, $data)
	{
		return DBQ::upd('user_account', $data, [
			'id' => $id
		]);
	}
}