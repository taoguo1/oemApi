<?php
namespace App\ADMIN\Model;

use Core\Base\Model;
use Core\DB\DBQ;

class OemTX extends Model
{
    public function getList($pageArr = null, $condition = null)
    {
        // $pageArr, $table, $join, $columns = null, $where = null
        $data = DBQ::pages($pageArr, 'oemtx','*', $condition);

        return $data;
    }



    public function add($data)
    {
        return DBQ::add('oemtx', $data);
    }

    /**
     * @param $data
     * @param $id
     * @return int
     *
     */


    public function edit($data,$id)
    {
        return DBQ::upd('oemtx', $data, [
            'id' => $id
        ]);
    }
/**
 * oe商提现额度
 *
 */

    public function withAmount()
    {
        //OE商所有还款的金额
       $oRepayment = DBQ::sum("bill",['amount'],[
            'bill_type'=>1,
            'is_pay'=>1,
            'status'=>1
        ]);
        //OE商所有收款的金额
        $oGathering = DBQ::sum("bill",['amount'],[
            'bill_type'=>8,
            'is_pay'=>1,
            'status'=>1
        ]);

       //代理商所有的金额
       $DMoney = DBQ::sum("agent_account",['amount'],[
            'description'=>"还款分润"
       ]);
       //提现记录
        $recordMoney = $this->recordMoney();

       //计算提现的最大额度
        $maxMoney = ($oRepayment * (REPAYMENT_RATE/10000) + $oGathering * (TX_AGENT_RATE/10000)) - $DMoney-$recordMoney;

        if( $maxMoney > 0 )
             return $maxMoney;
        return 0;

    }

    /**
     * 提现记录
     */

    public function recordMoney()
    {
        $recordMoney = DBQ::sum('oemtx',['oem_amount'],[
            'oem_status'=>1
        ]);
        if($recordMoney)
            return $recordMoney;
            return 0;

    }

	    
}
