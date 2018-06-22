<?php
namespace App\ADMIN\Model;
use Core\DB\DBQ;
use Core\Base\Model;
use Core\Extend\Dwz;
use Core\Lib;
use Exception;

//用户各种消费金额查询model
class ReportsUserDA extends Model{
    //按小时查询
    public function selByHour($day_data,$year,$month){
        for($j=1;$j<=Lib::getMonthLastDay($year,$month);$j++){
            for($i=0;$i<=23;$i++) {
                $days=$year."-".$month."-".$j;
                $start=strtotime($days.$i.':'.'00'.':'.'00')*1000;
                $end=strtotime($days.$i.':'.'59'.':'.'59')*1000;
                $data[$j][$i]=0;
                foreach ($day_data as $k=>$v) {
                    if ($v['create_time'] >= $start && $v['create_time'] < $end) {
                        $data[$j][$i]+=$v['amount']; 
                    }
                }
            }
            $data[$j]['sum']=array_sum($data[$j]);
        }
        $total=0;
        $sum=[];
        for($m=1;$m<=count($data);$m++){
            $month_total[$m]=array_sum($data[$m]);
            //获取总金额
            $total+=$month_total[$m];
            for($a=0;$a<=23;$a++){
                @$sum[$a]+=$data[$m][$a];
            }
        }
        $allDate['total1']=$total/2;
        $allDate['sum1']=$sum;
        $allDate['day_data1']=$data;
        return $allDate;
    }

    //按月查询
    public function selByMonth($day_data,$year){
        for($j=1;$j<=12;$j++){
            for($i=1;$i<=31;$i++) {
                $days=$year."-".$j."-".$i;
                $start=strtotime($days." 00:00:00")*1000;
                $end=strtotime($days." 23:59:59")*1000;
                $data[$j][$i]=0;
                foreach ($day_data as $k=>$v) {
                    if ($v['create_time'] >= $start && $v['create_time'] < $end) {
                        $data[$j][$i]+=$v['amount']; 
                    }
                }
            }
            $data[$j]['sum']=array_sum($data[$j]);
        }
        $total=0;
        $sum=[];
        for($m=1;$m<=count($data);$m++){
            $month_total[$m]=array_sum($data[$m]);
            //获取总金额
            $total+=$month_total[$m];
            //每月当天总金额
            for($a=1;$a<=31;$a++){
                @$sum[$a]+=$data[$m][$a];
            }
        }
        $allDate['total']=$total/2;
        $allDate['sum']=$sum;
        $allDate['day_data']=$data;
        return $allDate;
    }
    //按周查询
    public function selByWeek($day_data,$year){
        for($j=1;$j<=12;$j++){
            for($i=0; $i <= 6; $i++){
                $data['day'][$j][$i]=0;
                $data['weekMoney'][$i][$j]=0;
                for($k=1;$k<=Lib::getMonthLastDay($year,$j);$k++){
                    $daysr=$year."-".$j."-".$k;
                    $timestr=strtotime($daysr);
                    $weeknum=date('w',$timestr);
                    if($weeknum==$i){
                        $daystart=strtotime($daysr." 00:00:00")*1000;
                        $dayend=strtotime($daysr." 23:59:59")*1000;
                        foreach ($day_data as $key=>$val) {
                            if ($val['create_time'] >= $daystart && $val['create_time'] < $dayend) {
                                $data['day'][$j][$i]=$data['day'][$j][$i]+$val['amount'];
                                $data['weekMoney'][$i][$j]+=$val['amount'];
                            }
                        }
                    }
                }
            }
            $data['day'][$j]['sum']=array_sum($data['day'][$j]);
            @$data['day']['sumday']=$data['day']['sumday']+$data['day'][$j]['sum'];
        }
        $total=$data['day']['sumday'];
        unset($data['day']['sumday']);
        foreach($data['day'] as $k=>$v){
            $sum[]=$v['sum'];
            //unset($v['sum']);
        }
        $allDate['total']=$total;
        $allDate['weekMoney']=$data['weekMoney'];
        $allDate['day_data']=$data['day'];
        return $allDate;
    }
}


