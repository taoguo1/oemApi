<?php
namespace App\ADMIN\Model;
use Core\Lib;
use Core\Base\Model;
use Core\DB\DBQ;

class ReportsCreditCardRV extends Model
{

    public function getList($type=null, $condition = null,$year=null,$month=null)
    {

        $data=[];
        $data['day']['sumday']=0;
        $datalist=DBQ::getAll("plan_list",['create_time'],$condition);
        if(empty($month)){
            for($j = 1;$j <= 12;$j++){
                if($type=="day"){

                    for ($i = 1; $i <= 31; $i++) {
                        $data['day'][$j][$i]=0;
                        $data['fday'][$i][$j]=0;
                        if($i<=Lib::getMonthLastDay($year,$j)){
                            $daysr=$year."-".$j."-".$i;
                            $daystart=strtotime($daysr." 00:00:00")."000";
                            $dayend=strtotime($daysr." 23:59:59")."999";
                            foreach ($datalist as $k=>$v) {
                                if ($v['create_time'] >= $daystart && $v['create_time'] < $dayend) {
                                    $data['day'][$j][$i]=$data['day'][$j][$i]+1;
                                    $data['fday'][$i][$j]+=1;
                                }

                            }
                        }


                    }
                    $data['day'][$j]['sum']=array_sum($data['day'][$j]);
                    $data['day']['sumday']=$data['day']['sumday']+$data['day'][$j]['sum'];


                }else{
                        for ($i = 0; $i <= 6; $i++) {
                            $data['day'][$j][$i]=0;
                            $data['fday'][$i][$j]=0;

                            for ($k = 1; $k <= Lib::getMonthLastDay($year,$j); $k++) {
                                $daysr=$year."-".$j."-".$k;
                                $timestr=strtotime($daysr);
                                $weeknum=date('w',$timestr);

                                if($weeknum==$i){
                                    $daystart=strtotime($daysr." 00:00:00")."000";
                                    $dayend=strtotime($daysr." 23:59:59")."999";
                                    foreach ($datalist as $key=>$val) {
                                        if ($val['create_time'] >= $daystart && $val['create_time'] < $dayend) {
                                            $data['day'][$j][$i]=$data['day'][$j][$i]+1;
                                            $data['fday'][$i][$j]+=1;
                                        }

                                    }
                                }

                            }

                        }
                    $data['day'][$j]['sum']=array_sum($data['day'][$j]);
                    $data['day']['sumday']=$data['day']['sumday']+$data['day'][$j]['sum'];

                }
            }
        }else{

            for ($j = 1; $j <= 31; $j++) {
                for($i = 0;$i <= 23;$i++){
                    $data['day'][$j][$i] = 0;
                    $data['fday'][$i][$j] = 0;
                    if($j<=Lib::getMonthLastDay($year,$month)) {
                        $daysr = $year . "-" . $month . "-" . $j;
                        $daystart = strtotime($daysr . " " . $i . ":00:00") . "000";
                        $dayend = strtotime($daysr . " " . $i . ":59:59") . "999";
                        foreach ($datalist as $k => $v) {
                            if ($v['create_time'] >= $daystart && $v['create_time'] < $dayend) {
                                $data['day'][$j][$i] += 1;
                                $data['fday'][$i][$j] += 1;
                            }
                        }
                    }
                }
                $data['day'][$j]['sum']=array_sum($data['day'][$j]);
                $data['day']['sumday']=$data['day']['sumday']+$data['day'][$j]['sum'];
            }
        }





        return $data;
    }


}