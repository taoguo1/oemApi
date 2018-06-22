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


class InviteCodeTrade extends Model
{
    public function getList($pageArr = null, $condition = null)
    {
        $sql = "SELECT A.*,B.nickname,C.nickname AS after_name FROM dzz_invite_code_trade A LEFT JOIN dzz_agent B ON A.before_agent_id = B.id LEFT JOIN dzz_agent C ON A.after_agent_id = C.id" . $condition;
        //.'ORDER BY A.trade_time DESC'
        $data = DBQ::origPage($pageArr,$sql);
        return $data;
    }


    public function trade()
    {
        $volume = Lib::post('volume');
        $beforeAgentID = Lib::post('before_agent_agent_id');
        $afterAgentID = Lib::post('after_agent_agent_id');

        $data = DBQ::getAll('invite_code', [
            'id',
            'code',
        ], [
            "agent_id" => $beforeAgentID,
            "status" => [1,2],
            "ORDER" => ["id" => "ASC"],
            "LIMIT" => $volume
        ]);

        $dataAll = [];
        foreach ($data as $k => $v) {
            $dataAll[] = ['code_id' => $v['id'],
                'code' => $v['code'],
                'before_agent_id' => $beforeAgentID,
                'after_agent_id' => $afterAgentID,
                'volume'=> $volume,
                'status'=> 2,
                'trade_time' => Lib::getMs()
            ];
        }

        $ids = array_column($dataAll,'code_id');
        DBQ::upd('invite_code', [
            "agent_id" => $afterAgentID,
            "status" => '2',
        ], [
            "id" => $ids
        ]);


        return DBQ::add('invite_code_trade', $dataAll);

    }

}